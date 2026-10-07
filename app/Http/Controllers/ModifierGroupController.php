<?php

namespace App\Http\Controllers;

use App\Models\ModifierGroup;
use Illuminate\Http\Request;
use App\Models\Tax;

class ModifierGroupController extends Controller
{
    public function index()
    {
                $groups = ModifierGroup::with('tax')
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();

                $taxes = Tax::where('active', true)
                    ->orderBy('name')
                    ->get();

                return view('modifiers.groups.index', compact('groups', 'taxes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'min_selections' => ['required', 'integer', 'min:0'],
            'max_selections' => ['required', 'integer', 'min:1'],
            'tax_id' => ['nullable', 'exists:taxes,id'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'active' => ['nullable', 'boolean'],
            
        ]);

        if ($data['max_selections'] < $data['min_selections']) {
            return back()
                ->withErrors([
                    'max_selections' =>
                        'El máximo de selecciones no puede ser menor que el mínimo.'
                ])
                ->withInput();
        }

        ModifierGroup::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'min_selections' => $data['min_selections'],
            'tax_id' => $data['tax_id'] ?? null,
            'max_selections' => $data['max_selections'],
            'sort_order' => $data['sort_order'] ?? 1,
            'active' => $request->boolean('active'),
        ]);

        return redirect()
            ->route('modifier-groups.index')
            ->with('success', 'Grupo de modificadores creado correctamente.');
    }

    public function update(Request $request, ModifierGroup $modifierGroup)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'min_selections' => ['required', 'integer', 'min:0'],
            'max_selections' => ['required', 'integer', 'min:1'],
            'tax_id' => ['nullable', 'integer', 'exists:taxes,id'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'active' => ['nullable', 'boolean'],
        ]);

        if ($data['max_selections'] < $data['min_selections']) {
            return back()
                ->withErrors([
                    'max_selections' =>
                        'El máximo de selecciones no puede ser menor que el mínimo.'
                ])
                ->withInput();
        }

        $modifierGroup->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'min_selections' => $data['min_selections'],
            'max_selections' => $data['max_selections'],
            'tax_id' => $data['tax_id'] ?? null,
            'sort_order' => $data['sort_order'] ?? 1,
            'active' => $request->boolean('active'),
        ]);

        return redirect()
            ->route('modifier-groups.index')
            ->with('success', 'Grupo de modificadores actualizado correctamente.');
    }

    public function destroy(ModifierGroup $modifierGroup)
    {
        $modifierGroup->delete();

        return redirect()
            ->route('modifier-groups.index')
            ->with('success', 'Grupo de modificadores eliminado correctamente.');
    }
}