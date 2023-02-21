<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Exeptions;

class LoginWithGoogleController extends Controller
{
    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback() {
        try {
            $user = Socialite::driver('google')->user();

            $finduser = User::where('google_id', $user->id)->first();

            if ($finduser) {
                Auth:login($finduser);
                return redirect()->intended('dashboard');
            } else {
                $newuser = User::create([
                    'google_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => encrypt('123'),
                    'level' => 'pelanggan',
                ]);

                Auth::login($newuser);
                return redirect()->intended('dashboard');
            }
        } catch(Exeption $e) {
            dd($e->getMassage());
        }
    }
}
