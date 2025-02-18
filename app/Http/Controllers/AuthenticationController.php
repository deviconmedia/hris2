<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAuthenticateRequest;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class AuthenticationController extends Controller
{
    public function index()
    {
        return view('authentication.index');
    }

    public function userSignIn()
    {
        return view('authentication.signin');
    }

    public function authenticate(UserAuthenticateRequest $request)
    {
        try {
            $email = $request->input('email');
            $cacheKey = 'login_attempts_' . $email;
            $blockKey = 'login_blocked_' . $email;

            if (Cache::has($blockKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda telah mencoba login terlalu banyak. Silakan coba lagi dalam 1 menit.',
                ], 403);
            }

            $credentials = $request->validated();

            if (Auth::attempt($credentials)) {
                Cache::forget($cacheKey);

                $user = Auth::user();

                if (!$user->is_active) {
                    Auth::logout();
                    return response()->json([
                        'success' => false,
                        'message' => 'Akun Anda Tidak Aktif. Silahkan hubungi Administrator.',
                    ], 403);
                }

                $request->session()->regenerate();
                $clockNow = Carbon::now();
                User::where('id', $user->id)->update(['last_logged' => $clockNow]);

                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil',
                ], 200);
            }

            $attempts = Cache::get($cacheKey, 0) + 1;
            Cache::put($cacheKey, $attempts, now()->addMinutes(1));

            if ($attempts >= 5) {
                Cache::put($blockKey, true, now()->addMinutes(1));
                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu banyak percobaan login. Coba lagi dalam 1 menit.',
                ], 403);
            }

            return response()->json([
                'success' => false,
                'message' => 'Email atau kata sandi salah.',
            ], 403);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim data: ' . $th->getMessage(),
            ], 500);
        }
    }


    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/sign_in');
    }

}
