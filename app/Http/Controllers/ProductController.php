<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use App\Models\ModifierGroup;

class ProductController extends Controller
{
    public function index()
    {
       $products = Product::with([
    'category',
    'variants',
     'modifierGroups',
])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $categories = ProductCategory::where('active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $modifierGroups = ModifierGroup::where('active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

       return view('products.index', compact(
            'products',
            'categories',
            'modifierGroups'
            ));

    }

    public function create()
{
    $categories = ProductCategory::where('active', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();

    $modifierGroups = ModifierGroup::where('active', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();

    return view(
        'products.create',
        compact('categories', 'modifierGroups')
    );
}

public function store(Request $request)
{
    $data = $request->validate([
        'category_id' => [
            'required',
            'exists:product_categories,id'
        ],

        'name' => [
            'required',
            'string',
            'max:150'
        ],

        'description' => [
            'nullable',
            'string'
        ],

        'image' => [
            'nullable',
            'string',
            'max:255'
        ],

        'sort_order' => [
            'nullable',
            'integer',
            'min:1'
        ],

        'active' => [
            'nullable',
            'boolean'
        ],

        'has_variants' => [
            'nullable',
            'boolean'
        ],

        'price' => [
            'nullable',
            'numeric',
            'min:0'
        ],

        'variants' => [
            'nullable',
            'array'
        ],

        'variants.*.name' => [
            'required',
            'string',
            'max:100'
        ],

        'variants.*.price' => [
            'required',
            'numeric',
            'min:0'
        ],

                    'modifier_groups' => [
                'nullable',
                'array',
            ],

            'modifier_groups.*' => [
                'integer',
                'exists:modifier_groups,id',
            ],
    ]);

    DB::transaction(function () use ($request, $data) {

        $product = Product::create([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
            'sort_order' => $data['sort_order'] ?? 1,
            'active' => $request->boolean('active'),
        ]);

        if ($request->boolean('has_variants')) {

            foreach ($data['variants'] ?? [] as $index => $variant) {

                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $variant['name'],
                    'price' => $variant['price'],
                    'sort_order' => $index + 1,
                    'active' => true,
                ]);
            }

        } else {

            ProductVariant::create([
                'product_id' => $product->id,
                'name' => 'Único',
                'price' => $data['price'] ?? 0,
                'sort_order' => 1,
                'active' => true,
            ]);
        }
        $product->modifierGroups()->sync(
            $data['modifier_groups'] ?? []
            );
    });

    return redirect()
        ->route('products.index')
        ->with('success', 'Producto creado correctamente.');
}

   public function edit(Product $product)
{
    $categories = ProductCategory::where('active', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();

    $modifierGroups = ModifierGroup::where('active', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();

    $product->load('modifierGroups');

    return view(
        'products.edit',
        compact(
            'product',
            'categories',
            'modifierGroups'
        )
    );
}

   public function update(Request $request, Product $product)
{
    $data = $request->validate([
        'category_id' => [
            'required',
            'exists:product_categories,id'
        ],

        'name' => [
            'required',
            'string',
            'max:150'
        ],

        'description' => [
            'nullable',
            'string'
        ],

        'image' => [
            'nullable',
            'string',
            'max:255'
        ],

        'sort_order' => [
            'nullable',
            'integer',
            'min:1'
        ],

        'active' => [
            'nullable',
            'boolean'
        ],

        'has_variants' => [
            'nullable',
            'boolean'
        ],

        'price' => [
            'nullable',
            'numeric',
            'min:0'
        ],

        'variants' => [
            'nullable',
            'array'
        ],

        'variants.*.id' => [
            'nullable',
            'integer'
        ],

        'variants.*.name' => [
            'required',
            'string',
            'max:100'
        ],

        'variants.*.price' => [
            'required',
            'numeric',
            'min:0'
        ],

        'modifier_groups' => [
            'nullable',
            'array',
        ],

        'modifier_groups.*' => [
            'integer',
            'exists:modifier_groups,id',
        ],
    ]);

    DB::transaction(function () use ($request, $data, $product) {

        $product->update([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
            'sort_order' => $data['sort_order'] ?? 1,
            'active' => $request->boolean('active'),
        ]);


        /*
        |--------------------------------------------------------------------------
        | Producto con variantes
        |--------------------------------------------------------------------------
        */

        if ($request->boolean('has_variants')) {

            $variants = $data['variants'] ?? [];

            $variantIds = collect($variants)
                ->pluck('id')
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->values()
                ->all();


            // Elimina variantes que el usuario quitó del formulario
            $product->variants()
                ->whereNotIn('id', $variantIds)
                ->delete();


            foreach ($variants as $index => $variant) {

                if (!empty($variant['id'])) {

                    $productVariant = $product->variants()
                        ->where('id', $variant['id'])
                        ->first();

                    if ($productVariant) {

                        $productVariant->update([
                            'name' => $variant['name'],
                            'price' => $variant['price'],
                            'sort_order' => $index + 1,
                            'active' => true,
                        ]);
                    }

                } else {

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'name' => $variant['name'],
                        'price' => $variant['price'],
                        'sort_order' => $index + 1,
                        'active' => true,
                    ]);
                }
            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | Producto con precio único
            |--------------------------------------------------------------------------
            */

            $variant = $product->variants()->first();

            if ($variant) {

                $variant->update([
                    'name' => 'Único',
                    'price' => $data['price'] ?? 0,
                    'sort_order' => 1,
                    'active' => true,
                ]);

                $product->variants()
                    ->where('id', '!=', $variant->id)
                    ->delete();

            } else {

                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => 'Único',
                    'price' => $data['price'] ?? 0,
                    'sort_order' => 1,
                    'active' => true,
                ]);
            }
        }
        $product->modifierGroups()->sync(
                $data['modifier_groups'] ?? []
            );
    });

    return redirect()
        ->route('products.index')
        ->with('success', 'Producto actualizado correctamente.');
}

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}