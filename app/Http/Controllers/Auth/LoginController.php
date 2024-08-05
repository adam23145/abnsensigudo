<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Menampilkan form login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Menangani proses login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'nisn' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('nisn', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if the user has the 'admin' role
            if ($user->role == 'admin') {
                $request->session()->regenerate();

                return redirect()->intended('/absence-schedule')
                    ->with('success', 'Login berhasil.');
            } else {
                Auth::logout();
                throw ValidationException::withMessages([
                    'nisn' => [trans('auth.failed')],
                ]);
            }
        }

        throw ValidationException::withMessages([
            'nisn' => [trans('auth.failed')],
        ]);
    }


    /**
     * Menangani logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('success', 'Logout berhasil.');
    }
}
