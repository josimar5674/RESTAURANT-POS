<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrustedDevice;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginWithPin(Request $request)
{
    $data = $request->validate([
        'device_id' => ['required', 'uuid'],
        'pin' => ['required', 'digits:6'],
    ]);

    $device = TrustedDevice::where('device_id', $data['device_id'])
        ->where('active', true)
        ->first();

    if (! $device) {
        return response()->json([
            'message' => 'Dispositivo no autorizado.',
        ], 403);
    }

    $user = \App\Models\User::where('active', true)
        ->whereNotNull('pin')
        ->get()
        ->first(fn ($user) => Hash::check($data['pin'], $user->pin));

    if (! $user) {
        return response()->json([
            'message' => 'PIN incorrecto.',
        ], 401);
    }

    $device->update([
        'last_seen_at' => now(),
    ]);

    $token = $user->createToken('pos-app')->plainTextToken;

   return response()->json([
    'success' => true,
    'message' => 'Acceso autorizado.',
    'token' => $token,
    'user' => [
        'id' => $user->id,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'email' => $user->email,
        'role' => $user->roles->first()?->name,
    ],
]);
}
}
