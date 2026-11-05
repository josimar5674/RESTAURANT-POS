<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    /**
     * Mostrar los métodos de pago.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10);

        return view('payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Mostrar el formulario para crear un método.
     */
    public function create()
    {
        return view('payment-methods.create');
    }

    /**
     * Guardar un nuevo método de pago.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'code' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9_-]+$/',
                'unique:payment_methods,code',
            ],
            'requires_reference' => [
                'nullable',
                'boolean',
            ],
            'active' => [
                'nullable',
                'boolean',
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        $name = trim($validated['name']);

        $code = $validated['code'] ?? Str::slug($name, '_');

        if (strlen($code) > 50) {
            return back()
                ->withInput()
                ->withErrors([
                    'code' => 'El código generado supera los 50 caracteres. Introduce uno más corto.',
                ]);
        }

        if (PaymentMethod::where('code', $code)->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'code' => 'Ya existe un método de pago con ese código.',
                ]);
        }

        PaymentMethod::create([
            'uuid' => (string) Str::uuid(),
            'name' => $name,
            'code' => $code,
            'requires_reference' => $request->boolean('requires_reference'),
            'active' => $request->boolean('active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('payment-methods.index')
            ->with('success', 'Método de pago creado correctamente.');
    }

    /**
     * Mostrar un método de pago.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        return redirect()->route('payment-methods.edit', $paymentMethod);
    }

    /**
     * Mostrar el formulario de edición.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Actualizar un método de pago.
     */
    public function update(
        Request $request,
        PaymentMethod $paymentMethod
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'code' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Z0-9_-]+$/',
                Rule::unique('payment_methods', 'code')
                    ->ignore($paymentMethod->id),
            ],
            'requires_reference' => [
                'nullable',
                'boolean',
            ],
            'active' => [
                'nullable',
                'boolean',
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        $paymentMethod->update([
            'name' => trim($validated['name']),
            'code' => $validated['code'],
            'requires_reference' => $request->boolean('requires_reference'),
            'active' => $request->boolean('active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('payment-methods.index')
            ->with('success', 'Método de pago actualizado correctamente.');
    }

    /**
     * Activar o inactivar un método de pago.
     */
    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update([
            'active' => !$paymentMethod->active,
        ]);

        $message = $paymentMethod->active
            ? 'Método de pago activado correctamente.'
            : 'Método de pago inactivado correctamente.';

        return redirect()
            ->route('payment-methods.index')
            ->with('success', $message);
    }

    /**
     * Eliminar un método de pago.
     *
     * Por seguridad, preferimos inactivarlo para conservar
     * el historial de pagos.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        return redirect()
            ->route('payment-methods.index')
            ->with(
                'error',
                'Los métodos de pago no se eliminan. Puedes inactivarlos para impedir que se utilicen en nuevos cobros.'
            );
    }


 /**
        * Obtener los métodos de pago activos.
     */
    public function activeMethods()
{
    $methods = \App\Models\PaymentMethod::query()
        ->where('active', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get([
            'id',
            'name',
            'code',
            'requires_reference',
        ]);

    return response()->json([
        'methods' => $methods,
    ]);
}
}