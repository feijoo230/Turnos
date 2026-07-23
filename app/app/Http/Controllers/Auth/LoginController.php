<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->activo) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Tu cuenta está inactiva. Por favor contacta al administrador.']);
        }

        if ($user->hasRole('ADMINISTRADOR') || $user->hasRole('OPERADOR')) {
            return redirect()->intended($this->redirectPath());
        }

        return redirect('/');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Error al autenticar con Google. Por favor intenta de nuevo.']);
        }

        $user = User::where('email', $googleUser->email)->first();

        if ($user) {
            if (!$user->google_id) {
                $user->google_id = $googleUser->id;
                $user->save();
            }
        } else {
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => bcrypt(str_random(16)),
                'activo' => 1
            ]);

            $rol = Rol::where('name', 'USUARIO')->first();
            if ($rol) {
                $user->assignRole($rol->name);
            }
        }

        if (!$user->activo) {
            return redirect()->route('login')->withErrors(['email' => 'Tu cuenta está inactiva. Por favor contacta al administrador.']);
        }

        Auth::login($user);

        if ($user->hasRole('ADMINISTRADOR') || $user->hasRole('OPERADOR')) {
            return redirect()->intended($this->redirectPath());
        }

        return redirect('/');
    }
}