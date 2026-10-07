<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;

class ProductCatalogController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::where('active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->with([
                'products' => function ($query) {
                    $query
                        ->where('active', true)
                        ->orderBy('sort_order')
                        ->orderBy('name')
                        ->with([
                            'variants' => function ($query) {
                                $query
                                    ->where('active', true)
                                    ->orderBy('sort_order');
                            },
                            'tax',
                            'modifierGroups' => function ($query) {
                                $query
                                    ->where('active', true)
                                    ->orderBy('sort_order')
                                    ->with([
                                        'tax',
                                        'options' => function ($query) {
                                            $query
                                                ->where('active', true)
                                                ->orderBy('sort_order');
                                        },
                                    ]);
                            },
                        ]);
                },
            ])
            ->get();

        return response()->json([
            'data' => [
                'categories' => $categories,
            ],
        ]);
    }
    public function show(int $productId)
{
    $product = \App\Models\Product::where('id', $productId)
        ->where('active', true)
        ->with([
            'variants' => function ($query) {
                $query
                    ->where('active', true)
                    ->orderBy('sort_order');
            },
            'tax',
            'modifierGroups' => function ($query) {
                $query
                    ->where('active', true)
                    ->orderBy('sort_order')
                    ->with([
                        'tax',
                        'options' => function ($query) {
                            $query
                                ->where('active', true)
                                ->orderBy('sort_order');
                        },
                    ]);
            },
        ])
        ->firstOrFail();

    return response()->json([
        'data' => $product,
    ]);
}
}