<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $nisn = $request->input('nisn');
        $password = $request->input('password');

        // Verifikasi kredensial pengguna
        $user = DB::table('users')->where('nisn', $nisn)->first();

        if ($user && password_verify($password, $user->password)) {
            // Cari pkl_place_id di tabel pkl_places
            $pklPlace = DB::table('absence_schedules')->where('user_id', $user->id)->first();
            $pklPlaceId = $pklPlace ? $pklPlace->pkl_place_id : null;

            $guruPembimbing = DB::table('pkl_places')->where('id', $pklPlaceId)->first();
            $guruPembimbingId = $guruPembimbing ? $guruPembimbing->guru_pembimbing_id : null;

            // Kembalikan ID pengguna dan pkl_place_id
            return response()->json([
                'message' => 'Login successful',
                'user_id' => $user->id,
                'nama' => $user->nama,
                'pkl_place_id' => $pklPlaceId,
                'guru_pembimbing_id' => $guruPembimbingId
            ], 200);
        } else {
            // Kredensial tidak valid
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }


    // public function register(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'nisn' => ['required', 'string', 'max:255'],
    //         'password' => ['required', 'string', 'min:5'],
    //         'role' => ['required', 'string', 'in:admin,siswa'], // Validasi role
    //     ]);

    //     // Cek apakah NISN sudah digunakan
    //     $existingUser = User::where('nisn', $request->nisn)->first();
    //     if ($existingUser) {
    //         return response()->json(['message' => 'NISN already in use'], 400);
    //     }

    //     // Buat pengguna baru
    //     User::create([
    //         'nisn' => $request->nisn,
    //         'password' => Hash::make($request->password),
    //         'role' => $request->role, // Menambahkan role pengguna baru
    //     ]);

    //     return response()->json(['message' => 'Registration successful'], 200);
    // }
}
