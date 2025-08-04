<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\Usuario;


class LoginController extends Controller
{
  public function index()
  {
    return view('pages.login');
  }

  public function login(Request $request)
  {
    // Validar datos de entrada
    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    // Obtener usuario por email
    $usuario = Usuario::where('email', $request->email)->first();

    // Verificar existencia y contraseña
    if (!$usuario || !Hash::check($request->password, $usuario->password)) {
      return back()->withErrors([
        'email' => 'Las credenciales ingresadas no son válidas.',
      ])->onlyInput('email');
    }

    // Autenticar usuario
    Auth::login($usuario, $request->has('remember'));


    // Redirigir al dashboard o donde quieras
    return redirect()->intended('/profile');
  }

  public function logout(Request $request)
  {
    Auth::logout(); // Cierra sesión del usuario

    // Invalida la sesión actual y regenera el token CSRF
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/')->with('status', 'Sesión cerrada correctamente.');
  }

  public function sendResetLinkEmail(Request $request)
  {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
      $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
      ? back()->with(['status' => __($status)])
      : back()->withErrors(['email' => __($status)]);
  }

  public function reset(Request $request)
  {
    $request->validate([
      'token' => 'required',
      'email' => 'required|email',
      'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
      $request->only('email', 'password', 'password_confirmation', 'token'),
      function ($user, $password) {
        $user->forceFill([
          'password' => Hash::make($password),
        ])->save();
      }
    );

    return $status === Password::PASSWORD_RESET
      ? redirect()->route('login')->with('status', __($status))
      : back()->withErrors(['email' => [__($status)]]);
  }
}
