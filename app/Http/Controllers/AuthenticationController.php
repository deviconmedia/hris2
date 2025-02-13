<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAuthenticateRequest;
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
        return view('authentication.signin');
    }

    public function authenticate(UserAuthenticateRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            $credentials =$request->validated();

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                if (!$user->is_active) {
                    Auth::logout();
                    return response()->json([
                        'success' => false,
                        'message' => 'Akun Anda Tidak Aktif. Silahkan hubungi Administrator.',
                    ],  403);
                }

                $request->session()->regenerate();

                $clockNow = Carbon::now();
                $lastLogged = User::where('id', $user->id)->update(['last_logged' => $clockNow]);

                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil',
                ],  200);

            }

            return response()->json([
                'success' => false,
                'message' => 'Email atau kata sandi salah.',
            ],  403);

        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengirim data: ' . $th->getMessage(),
                ],
                500,
            );
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
