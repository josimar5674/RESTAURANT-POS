<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RestaurantArea;

class FloorPlanController extends Controller
{
    public function index()
    {
        $areas = RestaurantArea::where('active', true)
            ->orderBy('sort_order')
            ->with([
                'objects' => function ($query) {
                    $query->orderBy('id');
                }
            ])
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'areas' => $areas
            ]
        ]);
    }
}