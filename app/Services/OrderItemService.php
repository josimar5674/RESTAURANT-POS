<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemModifier;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Services\OrderService;

class OrderItemService
{
    public function addItem(
        Order $order,
        int $variantId,
        float $quantity,
        array $modifierOptionIds = [],
        ?string $notes = null
    ): OrderItem {
        return DB::transaction(function () use (
            $order,
            $variantId,
            $quantity,
            $modifierOptionIds,
            $notes
        ) {

            if ($order->status !== 'open') {
                throw ValidationException::withMessages([
                    'order' => 'La orden no está abierta.',
                ]);
            }

            /*
             * Obtener producto, variante,
             * impuesto y modificadores
             */

            $product = Product::with([
                'variants',
                'tax',
                'modifierGroups' => function ($query) {
                    $query->with([
                        'tax',
                        'options',
                    ]);
                },
            ])
                ->whereHas('variants', function ($query) use ($variantId) {
                    $query->where('id', $variantId);
                })
                ->firstOrFail();

            $variant = $product->variants
                ->firstWhere('id', $variantId);

            if (!$variant || !$variant->active) {
                throw ValidationException::withMessages([
                    'variant_id' =>
                        'La variante seleccionada no está disponible.',
                ]);
            }

            /*
             * Validar modificadores
             */

            $selectedOptions = collect($modifierOptionIds)
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            foreach ($product->modifierGroups as $group) {

                $groupOptionIds = $group->options
                    ->where('active', true)
                    ->pluck('id');

                $selectedForGroup = $selectedOptions
                    ->intersect($groupOptionIds);

                $min = (int) $group->min_selections;
                $max = (int) $group->max_selections;

                if ($selectedForGroup->count() < $min) {
                    throw ValidationException::withMessages([
                        'modifiers' =>
                            "Debes seleccionar al menos {$min} opción(es) en {$group->name}.",
                    ]);
                }

                if ($selectedForGroup->count() > $max) {
                    throw ValidationException::withMessages([
                        'modifiers' =>
                            "No puedes seleccionar más de {$max} opción(es) en {$group->name}.",
                    ]);
                }
            }

            /*
             * Precio base del producto
             */

            $unitPrice = (float) $variant->price;

            /*
             * Impuesto del producto
             */

            $productTaxRate =
                (float) ($product->tax?->rate ?? 0);

            $productTaxPerUnit =
                $unitPrice * ($productTaxRate / 100);

            /*
             * Crear OrderItem inicialmente.
             *
             * Los valores definitivos se recalculan
             * después de crear los modificadores.
             */

            $orderItem = OrderItem::create([
                'order_id' => $order->id,

                'product_id' => $product->id,
                'variant_id' => $variant->id,

                'product_name' => $product->name,
                'variant_name' => $variant->name,

                'quantity' => $quantity,

                'unit_price' => $unitPrice,

                'tax_rate' => $productTaxRate,

                'tax_amount' => 0,

                'discount' => 0,

                'subtotal' => 0,

                'total' => 0,

                'notes' => $notes,
            ]);

            /*
             * Crear modificadores
             */

            foreach ($product->modifierGroups as $group) {

                $groupTaxRate =
                    (float) ($group->tax?->rate ?? 0);

                $groupOptionIds = $group->options
                    ->where('active', true)
                    ->pluck('id');

                $selectedForGroup = $selectedOptions
                    ->intersect($groupOptionIds);

                foreach ($selectedForGroup as $optionId) {

                    $option = $group->options
                        ->firstWhere('id', $optionId);

                    if (!$option) {
                        continue;
                    }

                    /*
                     * Precio base del modificador
                     */

                    $basePrice =
                        (float) $option->price_adjustment;

                    /*
                     * Impuesto del grupo
                     */

                    $taxAmount =
                        $basePrice * ($groupTaxRate / 100);

                    /*
                     * Precio final del modificador
                     */

                    $finalPrice =
                        $basePrice + $taxAmount;

                    OrderItemModifier::create([
                        'order_item_id' => $orderItem->id,

                        'modifier_option_id' => $option->id,

                        'modifier_name' =>
                            "{$group->name}: {$option->name}",

                        /*
                         * Precio SIN impuesto
                         */
                        'price_adjustment' => $basePrice,

                        /*
                         * Snapshot del impuesto
                         */
                        'tax_rate' => $groupTaxRate,

                        'tax_amount' => $taxAmount,

                        /*
                         * Cantidad del modificador
                         */
                        'quantity' => 1,

                        /*
                         * Precio FINAL del modificador
                         * incluyendo impuesto
                         */
                        'total' => $finalPrice,
                    ]);
                }
            }

            /*
             * Recalcular el OrderItem
             */

            $orderItem->load('modifiers');

            /*
             * Modificadores antes de impuesto
             */

            $modifierSubtotal =
                $orderItem->modifiers->sum(
                    fn ($modifier) =>
                        (float) $modifier->price_adjustment *
                        (float) $modifier->quantity
                );

            /*
             * Impuesto de los modificadores
             */

            $modifierTax =
                $orderItem->modifiers->sum(
                    fn ($modifier) =>
                        (float) $modifier->tax_amount *
                        (float) $modifier->quantity
                );

            /*
             * Subtotal del producto
             * + modificadores
             */

            $itemSubtotal =
                ($unitPrice * $quantity)
                +
                ($modifierSubtotal * $quantity);

            /*
             * Impuesto del producto
             * + modificadores
             */

            $itemTax =
                ($productTaxPerUnit * $quantity)
                +
                ($modifierTax * $quantity);

            /*
             * Total
             */

            $itemTotal =
                $itemSubtotal
                +
                $itemTax
                -
                (float) $orderItem->discount;

            $orderItem->update([
                'tax_amount' => $itemTax,

                'subtotal' => $itemSubtotal,

                'total' => $itemTotal,
            ]);

            /*
             * Recalcular toda la orden
             */

            app(OrderService::class)
                ->recalculateOrder($order);

            return $orderItem->fresh([
                'product',
                'variant',
                'modifiers',
            ]);
        });
    }

    public function deleteItem(
    Order $order,
    int $itemId
): Order {
    return DB::transaction(function () use ($order, $itemId) {

        if ($order->status !== 'open') {
            throw ValidationException::withMessages([
                'order' => 'La orden ya fue enviada y no puede modificarse.',
            ]);
        }

        $item = $order->items()
            ->where('id', $itemId)
            ->firstOrFail();

        $item->delete();

        app(OrderService::class)
            ->recalculateOrder($order);

        return $order->fresh([
            'table',
            'user',
            'items.modifiers',
        ]);
    });
}

public function updateItem(
    Order $order,
    int $itemId,
    int $variantId,
    float $quantity,
    array $modifierOptionIds = [],
    ?string $notes = null
): OrderItem {
    return DB::transaction(function () use (
        $order,
        $itemId,
        $variantId,
        $quantity,
        $modifierOptionIds,
        $notes
    ) {

        if ($order->status !== 'open') {
            throw ValidationException::withMessages([
                'order' => 'La orden ya fue enviada y no puede modificarse.',
            ]);
        }

        $item = $order->items()
            ->where('id', $itemId)
            ->firstOrFail();

        /*
         * Eliminamos la configuración anterior.
         */

        $item->delete();

        /*
         * Creamos nuevamente el item
         * con la nueva configuración.
         */

        $newItem = $this->addItem(
            order: $order,
            variantId: $variantId,
            quantity: $quantity,
            modifierOptionIds: $modifierOptionIds,
            notes: $notes,
        );

        return $newItem;
    });
}
}