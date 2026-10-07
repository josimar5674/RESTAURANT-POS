<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(int $tableId, ?int $userId = null): Order
    {
        return DB::transaction(function () use ($tableId, $userId) {

         

            $order = Order::create([
                'table_id' => $tableId,
                'user_id' => $userId,
                'status' => 'open',
                'subtotal' => 0,
                'discount' => 0,
                'tax' => 0,
                'total' => 0,
            ]);

            return $order->load([
                'table',
                'user',
                'items.modifiers',
            ]);
        });
    }

 public function getOpenOrderForTable(int $tableId): ?Order
{
    return Order::where('table_id', $tableId)
        ->whereIn('status', [
            'open',
            'sent',
        ])
        ->with([
            'table',
            'user',
            'items.modifiers',
        ])
        ->latest('id')
        ->first();
}
 public function recalculateOrder(Order $order): Order
{
    return DB::transaction(function () use ($order) {

        $order->load('items');

        $subtotal = $order->items->sum(
            fn ($item) => (float) $item->subtotal
        );

        $tax = $order->items->sum(
            fn ($item) => (float) $item->tax_amount
        );

        $discount = (float) $order->discount;

        $total =
            $subtotal +
            $tax -
            $discount;

        $order->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ]);

        return $order->fresh([
            'table',
            'user',
            'items.modifiers',
        ]);
    });
}

public function getActiveOrdersForTable(int $tableId)
{
    return Order::where('table_id', $tableId)
        ->whereIn('status', ['open', 'sent', 'received'])
        ->with([
            'table',
            'user',
            'items.modifiers',
        ])
        ->latest('id')
        ->get();
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
}