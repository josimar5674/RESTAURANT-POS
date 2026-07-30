<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RestaurantArea;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RestaurantAreaController extends Controller
{
    public function index()
    {
        return response()->json(
            RestaurantArea::where('active', true)
                ->orderBy('sort_order')
                ->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([

            'name' => ['required', 'string', 'max:100']

        ]);

        $area = RestaurantArea::create([

            'uuid' => Str::uuid(),

            'name' => $data['name'],

            'description' => null,

            'sort_order' => RestaurantArea::max('sort_order') + 1,

            'active' => true

        ]);

        return response()->json([

            'success' => true,

            'data' => $area

        ], 201);
    }
}