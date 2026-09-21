<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('products.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('products.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'active' => ['nullable', 'boolean'],
        ]);

        ProductCategory::create([
            'uuid' => (string) Str::uuid(),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'] ?? 1,
            'active' => $request->boolean('active'),
        ]);

        return redirect()
            ->route('product-categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(ProductCategory $productCategory)
    {
        return view(
            'products.categories.edit',
            compact('productCategory')
        );
    }

    public function update(
        Request $request,
        ProductCategory $productCategory
    ) {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'active' => ['nullable', 'boolean'],
        ]);

        $productCategory->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'] ?? 1,
            'active' => $request->boolean('active'),
        ]);

        return redirect()
            ->route('product-categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();

        return redirect()
            ->route('product-categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}