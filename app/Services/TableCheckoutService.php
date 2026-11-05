<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Collection;
use App\Models\CheckoutAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TableCheckoutService
{
   public function getCheckout(int $tableId): array
{
    $orders = Order::query()
        ->where('table_id', $tableId)
        ->whereIn('status', ['open', 'sent', 'received'])
        ->whereDoesntHave('checkoutAccounts')
        ->with(['items.modifiers'])
        ->orderBy('id')
        ->get();

    $items = $orders->flatMap(function (Order $order) {
        return $order->items->map(function ($item) use ($order) {
            return [
                'id' => $item->id,
                'order_id' => $order->id,
                'product_name' => $item->product_name,
                'variant_name' => $item->variant_name,
                'quantity' => (float) $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'subtotal' => (float) $item->subtotal,
                'discount' => (float) $item->discount,
                'tax_amount' => (float) $item->tax_amount,
                'total' => (float) $item->total,
                'modifiers' => $item->modifiers,
            ];
        });
    })->values();

    return [
        'table_id' => $tableId,
        'orders' => $orders,
        'items' => $items,
        'subtotal' => round(
            $orders->sum(fn ($order) => (float) $order->subtotal),
            2
        ),
        'discount' => round(
            $orders->sum(fn ($order) => (float) $order->discount),
            2
        ),
        'tax' => round(
            $orders->sum(fn ($order) => (float) $order->tax),
            2
        ),
        'total' => round(
            $orders->sum(fn ($order) => (float) $order->total),
            2
        ),
    ];
}

public function createAccount(int $tableId, ?int $userId = null): CheckoutAccount
{
    return DB::transaction(function () use ($tableId, $userId) {

        // Bloquear las órdenes disponibles para evitar procesarlas simultáneamente.
        $orders = Order::query()
            ->where('table_id', $tableId)
            ->whereIn('status', ['open', 'sent', 'received'])
            ->whereDoesntHave('checkoutAccounts')
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        if ($orders->isEmpty()) {
            throw ValidationException::withMessages([
                'table' => 'La mesa no tiene órdenes pendientes de cobro.',
            ]);
        }

        // Crear una cuenta consolidada.
        $account = CheckoutAccount::create([
            'table_id' => $tableId,
            'user_id' => $userId,
            'name' => 'Cuenta de mesa',
            'status' => 'open',
            'subtotal' => round(
                $orders->sum(fn ($order) => (float) $order->subtotal),
                2
            ),
            'discount' => round(
                $orders->sum(fn ($order) => (float) $order->discount),
                2
            ),
            'tax' => round(
                $orders->sum(fn ($order) => (float) $order->tax),
                2
            ),
            'total' => round(
                $orders->sum(fn ($order) => (float) $order->total),
                2
            ),
        ]);

        // Asociar todas las órdenes a la cuenta.
        $account->orders()->attach($orders->modelKeys());

        return $account->load('orders');
    });
}

public function getAccount(int $accountId): CheckoutAccount
{
    return CheckoutAccount::query()
        ->with([
            'orders.items.modifiers',
            'payments',
        ])
        ->findOrFail($accountId);
}
}