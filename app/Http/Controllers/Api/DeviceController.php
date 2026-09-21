<?php



namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrustedDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DeviceController extends Controller
{
    public function register(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
        'device_id' => ['required', 'uuid'],
        'name' => ['nullable', 'string', 'max:100'],
    ]);

    if (! Auth::attempt([
        'email' => $credentials['email'],
        'password' => $credentials['password'],
        'active' => true,
    ])) {
        return response()->json([
            'message' => 'Las credenciales son incorrectas.',
        ], 401);
    }

    $admin = Auth::user();

    if (! $admin->hasRole('Administrador')) {
        Auth::logout();

        return response()->json([
            'message' => 'El usuario no tiene permisos para registrar dispositivos.',
        ], 403);
    }

    $device = TrustedDevice::updateOrCreate(
        ['device_id' => $credentials['device_id']],
        [
            'name' => $credentials['name'] ?? null,
            'registered_by' => $admin->id,
            'active' => true,
            'last_seen_at' => now(),
        ]
    );

    return response()->json([
        'message' => 'Dispositivo registrado correctamente.',
        'device' => $device,
    ]);
}


public function check(Request $request)
{
    $data = $request->validate([
        'device_id' => ['required', 'uuid'],
    ]);

    $device = TrustedDevice::where('device_id', $data['device_id'])
        ->where('active', true)
        ->first();

    if (! $device) {
        return response()->json([
            'trusted' => false,
            'message' => 'Dispositivo no autorizado.',
        ], 403);
    }

    $device->update([
        'last_seen_at' => now(),
    ]);

    return response()->json([
        'trusted' => true,
        'message' => 'Dispositivo autorizado.',
    ]);
}
}