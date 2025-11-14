<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();

        $finduser = User::where('google_id', $user->id)->first();

        if($finduser){
            Auth::login($finduser);
            return redirect()->intended('home');
        }else{
            $finduser = User::where('email', $user->email)->first();

            if($finduser){
                $finduser->google_id = $user->id;
                $finduser->save();

                Auth::login($finduser);
                return redirect()->intended('home');
            }else{
                return redirect()->route('login')->withErrors(['email' => 'No tienes permiso para acceder con esta cuenta de Google.']);
            }
        }
    }
}
