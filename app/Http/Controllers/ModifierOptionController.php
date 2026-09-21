<?php

namespace App\Http\Controllers;

use App\Models\ModifierGroup;
use App\Models\ModifierOption;
use Illuminate\Http\Request;

class ModifierOptionController extends Controller
{
    public function index(ModifierGroup $modifierGroup)
    {
        $options = $modifierGroup->options()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view(
            'modifiers.options.index',
            compact('modifierGroup', 'options')
        );
    }


    public function store(
        Request $request,
        ModifierGroup $modifierGroup
    ) {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'price_adjustment' => ['nullable', 'numeric'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'active' => ['nullable', 'boolean'],
        ]);

        ModifierOption::create([
            'modifier_group_id' => $modifierGroup->id,
            'name' => $data['name'],
            'price_adjustment' => $data['price_adjustment'] ?? 0,
            'sort_order' => $data['sort_order'] ?? 1,
            'active' => $request->boolean('active'),
        ]);

        return redirect()
            ->route('modifier-options.index', $modifierGroup)
            ->with('success', 'Opción creada correctamente.');
    }


    public function update(
        Request $request,
        ModifierGroup $modifierGroup,
        ModifierOption $modifierOption
    ) {
        abort_unless(
            $modifierOption->modifier_group_id === $modifierGroup->id,
            404
        );

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'price_adjustment' => ['nullable', 'numeric'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'active' => ['nullable', 'boolean'],
        ]);

        $modifierOption->update([
            'name' => $data['name'],
            'price_adjustment' => $data['price_adjustment'] ?? 0,
            'sort_order' => $data['sort_order'] ?? 1,
            'active' => $request->boolean('active'),
        ]);

        return redirect()
            ->route('modifier-options.index', $modifierGroup)
            ->with('success', 'Opción actualizada correctamente.');
    }


    public function destroy(
        ModifierGroup $modifierGroup,
        ModifierOption $modifierOption
    ) {
        abort_unless(
            $modifierOption->modifier_group_id === $modifierGroup->id,
            404
        );

        $modifierOption->delete();

        return redirect()
            ->route('modifier-options.index', $modifierGroup)
            ->with('success', 'Opción eliminada correctamente.');
    }
}