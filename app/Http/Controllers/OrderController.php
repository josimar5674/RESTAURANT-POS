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
    $order = \App\Models\Order::findOrFail($orderId);

    if ($order->status !== 'open') {
        return response()->json([
            'message' => 'La orden ya fue enviada a cocina.',
        ], 422);
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
}