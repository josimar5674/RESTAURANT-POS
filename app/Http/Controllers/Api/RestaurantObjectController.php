<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RestaurantObject;
use Illuminate\Http\Request;

class RestaurantObjectController extends Controller
{
    /**
     * Devuelve todos los objetos del plano.
     */
 public function index(Request $request)
{
    return RestaurantObject::where('area_id', $request->area_id)
        ->orderBy('id')
        ->get();
}

    /**
     * Guarda un nuevo objeto.
     */
    public function store(Request $request)
    {
        $data = $request->validate([

            'type' => ['required', 'string'],

            'name' => ['nullable', 'string', 'max:100'],

            'area_id' => ['required', 'exists:restaurant_areas,id'],

            'x' => ['required', 'numeric'],

            'y' => ['required', 'numeric'],

            'rotation' => ['nullable', 'numeric'],

            'width' => ['nullable', 'numeric'],

            'height' => ['nullable', 'numeric'],

            'shape' => ['nullable', 'string'],

            'style' => ['nullable', 'string'],

            'properties' => ['nullable', 'array'],

        ]);

        $object = RestaurantObject::create($data);

        return response()->json([
            'success' => true,
            'data' => $object
        ], 201);
    }

    /**
     * Actualiza un objeto.
     */
    public function update(Request $request, RestaurantObject $restaurantObject)
    {
        $data = $request->validate([

            'name' => ['nullable', 'string', 'max:100'],

            'x' => ['nullable', 'numeric'],

            'y' => ['nullable', 'numeric'],

            'rotation' => ['nullable', 'numeric'],

            'width' => ['nullable', 'numeric'],

            'height' => ['nullable', 'numeric'],

            'shape' => ['nullable', 'string'],

            'style' => ['nullable', 'string'],

            'properties' => ['nullable', 'array'],

        ]);

        $restaurantObject->update($data);

        return response()->json([
            'success' => true,
            'data' => $restaurantObject
        ]);
    }

    /**
     * Elimina un objeto.
     */
    public function destroy(RestaurantObject $restaurantObject)
    {
        $restaurantObject->delete();

        return response()->json([
            'success' => true
        ]);
    }
}