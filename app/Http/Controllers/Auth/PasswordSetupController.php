<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Migración del viejo login por pin de 4 dígitos a contraseña.
 * Solo permite crear la contraseña de cuentas que todavía no tienen una (password null);
 * una vez creada, esta ruta deja de aceptar esa cuenta.
 */
class PasswordSetupController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.password-setup', [
            'name' => old('name', $request->query('name', '')),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = User::where('name', $validated['name'])->whereNull('password')->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'name' => 'No encontramos una cuenta pendiente de migración con ese nombre.',
            ]);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        Auth::login($user);

        if ($user->role === 'admin') {
            return redirect('/dashboard');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
