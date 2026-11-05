<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Services\OrderItemService;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(Request $request, OrderService $orderService)
    {
        $data = $request->validate([
            'table_id' => ['required', 'integer', 'exists:restaurant_objects,id'],
        ]);

        $order = $orderService->createOrder(
            tableId: $data['table_id'],
            userId: $request->user()?->id
        );

        return response()->json([
            'message' => 'Orden creada correctamente.',
            'order' => $order,
        ], 201);
    }

    public function showForTable(
    int $tableId,
    OrderService $orderService
) {
    $order = $orderService->getOpenOrderForTable($tableId);

    if (!$order) {
        return response()->json([
            'order' => null,
        ]);
    }

    return response()->json([
        'order' => $order,
    ]);
}

public function showActiveForTable(
    int $tableId,
    OrderService $orderService
) {
    $orders = $orderService->getActiveOrdersForTable($tableId);

    return response()->json([
        'orders' => $orders,
    ]);
}

public function show(
    int $orderId
) {
    $order = Order::with([
        'table',
        'user',
        'items.modifiers',
    ])->findOrFail($orderId);

    return response()->json([
        'order' => $order,
    ]);
}
public function addItem(
    Request $request,
    int $orderId,
    OrderItemService $orderItemService
) {
    $data = $request->validate([
        'variant_id' => ['required', 'integer', 'exists:product_variants,id'],

        'quantity' => [
            'required',
            'numeric',
            'min:1',
        ],

        'modifiers' => [
            'nullable',
            'array',
        ],

        'modifiers.*' => [
            'integer',
            'exists:modifier_options,id',
        ],

        'notes' => [
            'nullable',
            'string',
        ],
    ]);

    $order = \App\Models\Order::findOrFail($orderId);

    $item = $orderItemService->addItem(
        order: $order,
        variantId: (int) $data['variant_id'],
        quantity: (float) $data['quantity'],
        modifierOptionIds: $data['modifiers'] ?? [],
        notes: $data['notes'] ?? null,
    );

    return response()->json([
        'message' => 'Producto agregado correctamente.',
        'item' => $item,
    ], 201);
}
public function updateItem(
    Request $request,
    int $orderId,
    int $itemId,
    OrderItemService $orderItemService
) {
    $data = $request->validate([
        'variant_id' => [
            'required',
            'integer',
            'exists:product_variants,id',
        ],

        'quantity' => [
            'required',
            'numeric',
            'min:1',
        ],

        'modifiers' => [
            'nullable',
            'array',
        ],

        'modifiers.*' => [
            'integer',
            'exists:modifier_options,id',
        ],

        'notes' => [
            'nullable',
            'string',
        ],
    ]);

    $order = \App\Models\Order::findOrFail($orderId);

    $item = $orderItemService->updateItem(
        order: $order,
        itemId: $itemId,
        variantId: (int) $data['variant_id'],
        quantity: (float) $data['quantity'],
        modifierOptionIds: $data['modifiers'] ?? [],
        notes: $data['notes'] ?? null,
    );

    return response()->json([
        'message' => 'Producto actualizado correctamente.',
        'item' => $item,
    ]);
}
public function deleteItem(
    int $orderId,
    int $itemId,
    OrderItemService $orderItemService
) {
    $order = \App\Models\Order::findOrFail($orderId);

    $orderItemService->deleteItem(
        order: $order,
        itemId: $itemId,
    );

    return response()->json([
        'message' => 'Producto eliminado correctamente.',
    ]);
}

public function sendToKitchen(
    int $orderId,
    OrderService $orderService
) {
    $order = Order::with('items')->findOrFail($orderId);

    if ($order->status !== 'open') {
        return response()->json([
            'message' => 'La orden ya fue enviada a cocina.',
        ], 422);
    }

    if ($order->items->isEmpty()) {
        return response()->json([
            'message' => 'No puedes enviar una orden sin productos a cocina.',
        ], 422);
    }

    $sentAt = now();

    foreach ($order->items as $item) {
        $item->update([
            'sent_to_kitchen_at' => $sentAt,
            'received_at' => null,
        ]);
    }

    $order->update([
        'status' => 'sent',
    ]);

    return response()->json([
        'message' => 'Orden enviada a cocina correctamente.',
        'order' => $order->fresh([
            'table',
            'user',
            'items.modifiers',
        ]),
    ]);
}

public function markItemReceived(
    int $orderId,
    int $itemId
) {
    $order = Order::findOrFail($orderId);

    if ($order->status !== 'sent') {
        return response()->json([
            'message' => 'La orden no está en cocina.',
        ], 422);
    }

    $item = $order->items()
        ->whereKey($itemId)
        ->firstOrFail();

    if (!$item->sent_to_kitchen_at) {
        return response()->json([
            'message' => 'El platillo no ha sido enviado a cocina.',
        ], 422);
    }

    if ($item->received_at) {
        return response()->json([
            'message' => 'El platillo ya fue marcado como recibido.',
            'item' => $item,
        ]);
    }

    $item->update([
        'received_at' => now(),
    ]);

    return response()->json([
        'message' => 'Platillo marcado como recibido.',
        'item' => $item->fresh(),
    ]);
}

public function checkout(
    int $tableId,
    \App\Services\TableCheckoutService $checkoutService
) {
    $checkout = $checkoutService->getCheckout($tableId);

    if ($checkout['orders']->isEmpty()) {
        return response()->json([
            'message' => 'La mesa no tiene órdenes activas.',
        ], 422);
    }

    return response()->json([
        'checkout' => $checkout,
    ]);
}

public function createCheckoutAccount(
    \Illuminate\Http\Request $request,
    int $tableId,
    \App\Services\TableCheckoutService $checkoutService
) {
    $account = $checkoutService->createAccount(
        tableId: $tableId,
        userId: $request->user()?->id
    );

    return response()->json([
        'message' => 'Cuenta de cobro creada correctamente.',
        'account' => $account,
    ], 201);
}

public function showCheckoutAccount(
    \Illuminate\Http\Request $request,
    int $accountId,
    \App\Services\TableCheckoutService $checkoutService
) {
    $data = $request->validate([
        'table_id' => ['required', 'integer', 'exists:restaurant_objects,id'],
    ]);

    $account = $checkoutService->getAccount($accountId);

    if ($account->table_id !== (int) $data['table_id']) {
        return response()->json([
            'message' => 'La cuenta no pertenece a esta mesa.',
        ], 403);
    }

    return response()->json([
        'account' => $account->load([
            'orders.items.modifiers',
            'payments',
        ]),
    ]);
}
}