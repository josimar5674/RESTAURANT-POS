<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index()
    {
        $taxes = Tax::orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('taxes.index', compact('taxes'));
    }

    public function create()
    {
        return view('taxes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['nullable', 'string', 'max:50', 'unique:taxes,code'],
            'rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'active' => ['nullable', 'boolean'],
        ]);

        Tax::create([
            'name' => $data['name'],
            'code' => $data['code'] ?? null,
            'rate' => $data['rate'],
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'] ?? 1,
            'active' => $request->boolean('active'),
        ]);

        return redirect()
            ->route('taxes.index')
            ->with('success', 'Impuesto creado correctamente.');
    }

    public function edit(Tax $tax)
    {
        return view('taxes.edit', compact('tax'));
    }

    public function update(Request $request, Tax $tax)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => [
                'nullable',
                'string',
                'max:50',
                'unique:taxes,code,' . $tax->id,
            ],
            'rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'active' => ['nullable', 'boolean'],
        ]);

        $tax->update([
            'name' => $data['name'],
            'code' => $data['code'] ?? null,
            'rate' => $data['rate'],
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'] ?? 1,
            'active' => $request->boolean('active'),
        ]);

        return redirect()
            ->route('taxes.index')
            ->with('success', 'Impuesto actualizado correctamente.');
    }

    public function destroy(Tax $tax)
    {
        $tax->delete();

        return redirect()
            ->route('taxes.index')
            ->with('success', 'Impuesto eliminado correctamente.');
    }
}