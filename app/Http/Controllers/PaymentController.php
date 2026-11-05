<?php

namespace App\Http\Controllers;

use App\Models\CheckoutAccount;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function store(Request $request, int $accountId)
    {
        $data = $request->validate([
            'payments' => ['required', 'array', 'min:1'],
            'payments.*.payment_method_id' => [
                'required',
                'integer',
                'distinct',
                'exists:payment_methods,id',
            ],
            'payments.*.amount' => [
                'required',
                'numeric',
                'gt:0',
            ],
            'payments.*.reference' => [
                'nullable',
                'string',
                'max:100',
            ],
            'payments.*.notes' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        return DB::transaction(function () use ($data, $accountId, $request) {
            $account = CheckoutAccount::query()
                ->lockForUpdate()
                ->findOrFail($accountId);

            if ($account->status !== 'open') {
                throw ValidationException::withMessages([
                    'account' => 'La cuenta no está abierta para recibir pagos.',
                ]);
            }

            $methods = PaymentMethod::query()
                ->whereIn(
                    'id',
                    collect($data['payments'])
                        ->pluck('payment_method_id')
                        ->unique()
                )
                ->where('active', true)
                ->get()
                ->keyBy('id');

            if ($methods->count() !== collect($data['payments'])
                ->pluck('payment_method_id')
                ->unique()
                ->count()) {
                throw ValidationException::withMessages([
                    'payments' => 'Uno o más métodos de pago no están activos.',
                ]);
            }

            $newTotal = collect($data['payments'])->sum(
                fn ($payment) => round((float) $payment['amount'], 2)
            );

            $existingTotal = (float) $account->payments()->sum('amount');

            $remaining = round(
                (float) $account->total - $existingTotal,
                2
            );

            if (round($newTotal, 2) > $remaining) {
                throw ValidationException::withMessages([
                    'payments' => 'El total de los pagos supera el saldo pendiente.',
                ]);
            }

            foreach ($data['payments'] as $paymentData) {
                $method = $methods->get(
                    (int) $paymentData['payment_method_id']
                );

                if (
                    $method->requires_reference &&
                    empty(trim($paymentData['reference'] ?? ''))
                ) {
                    throw ValidationException::withMessages([
                        'payments' => "El método {$method->name} requiere una referencia.",
                    ]);
                }

                Payment::create([
                    'checkout_account_id' => $account->id,
                    'order_id' => null,
                    'user_id' => $request->user()?->id,
                    'method' => $method->code,
                    'amount' => round((float) $paymentData['amount'], 2),
                    'reference' => $paymentData['reference'] ?? null,
                    'notes' => $paymentData['notes'] ?? null,
                    'paid_at' => now(),
                ]);
            }

            $paidTotal = (float) $account->payments()->sum('amount');

            $remaining = round(
                (float) $account->total - $paidTotal,
                2
            );

            if ($remaining === 0.0) {
                $account->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }

            return response()->json([
                'message' => $remaining === 0.0
                    ? 'Cuenta pagada correctamente.'
                    : 'Pagos registrados correctamente.',
                'account_id' => $account->id,
                'total' => (float) $account->total,
                'paid' => round($paidTotal, 2),
                'remaining' => $remaining,
                'status' => $account->fresh()->status,
                'payments' => $account->payments()->get(),
            ]);
        });
    }
}