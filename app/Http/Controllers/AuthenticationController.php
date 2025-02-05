<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthenticationController extends Controller
{
    public function index()
    {
        return view('authentication.index');
    }

    public function userSignIn()
    {
        return view('authentication.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        $credentials = $request->validate([
            'email'     => ['required', 'email'],
            'password'  => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();

                return back()
                    ->withErrors([
                        'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                    ])
                    ->onlyInput('email');
            }

            $request->session()->regenerate();

            $clockNow = Carbon::now();
            $lastLogged = User::where('id', $user->id)->update(['last_logged' => $clockNow]);

            return redirect()->intended('beranda');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/sign_in');
    }

}
