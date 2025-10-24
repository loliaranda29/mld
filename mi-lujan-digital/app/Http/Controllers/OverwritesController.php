<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OverwritesController extends Controller
{
  // Método para enviar el link de recuperación por CUIT
  public function sendResetLink(Request $request)
  {
    $request->validate([
      'cuit' => ['required', 'regex:/^[0-9]{11}$/'],
    ], [
      'cuit.required' => 'Debe ingresar un CUIT.',
      'cuit.regex' => 'El CUIT debe tener 11 dígitos numéricos.',
    ]);

    $user = User::where('cuit', $request->cuit)->first();

    if (! $user) {
      return Inertia::render('Auth/ForgotPassword', [
        'errors' => ['cuit' => 'No se encontró un usuario con ese CUIT.'],
      ]);
    }

    $status = Password::sendResetLink(['email' => $user->email]);

    $maskEmail = function ($email) {
      [$name, $domain] = explode('@', $email);
      $nameMasked = substr($name, 0, 2) . str_repeat('*', max(1, strlen($name) - 2));
      $domainParts = explode('.', $domain);
      $domainMasked = substr($domainParts[0], 0, 1) . str_repeat('*', max(1, strlen($domainParts[0]) - 1)) . '.' . end($domainParts);
      return "{$nameMasked}@{$domainMasked}";
    };

    $maskedEmail = $maskEmail($user->email);

    if ($status === Password::RESET_LINK_SENT) {
      return Inertia::render('Auth/ForgotPassword', [
        'flash' => [
          'status' => __($status),
          'maskedEmail' => $maskedEmail,
        ],
      ]);
    }

    return Inertia::render('Auth/ForgotPassword', [
      'flash' => [
        'error' => __($status),
      ],
    ]);
  }


  public function login(Request $request)
  {
    $request->validate([
      'cuit' => ['required', 'string'],
      'password' => ['required', 'string'],
    ]);

    $user = User::where('cuit', $request->cuit)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
      return back()->withErrors([
        'cuit' => 'Las credenciales no coinciden con nuestros registros.',
      ]);
    }

    Auth::login($user, $request->boolean('remember'));

    $request->session()->regenerate();

    return redirect()->intended(route('ciudadano.index'));
  }

  public function logout(Request $request)
  {
    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
  }
}
