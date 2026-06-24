<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class authController extends Controller
{
    public function Register(Request $request)
    {
        $request->validate([
            'nama_panjang' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'nomor_telepon' => 'required|string',
            'role' => 'sometimes|in:admin,user',
            'id_location' => 'required|string',
        ]);
        try {
            $user = User::create([
                'nama_panjang' => $request->nama_panjang,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'nomor_telepon' => $request->nomor_telepon,
                'role' => $request->role ?? 'user',
                'id_location'=> $request->id_location,
            ]);
            $user->sendEmailVerificationNotification();
            return response()->json([
                'message' => "Pendaftaran berrhasil silahkan check email untuk verifikasi",

            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Terjadi kesalahan saat mendaftar"
            ], 500);
        }
    }

  public function Login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message'=> "Login gagal, Pastikan email dan password benar"
            ], 401);
        }

        // if (!$user->hasVerifiedEmail()) {
        //     return response()->json([
        //         'message'=>'Email belum diverifikasi, silakan check email anda'
        //     ], 403);
        // }

        try {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login berhasil',
                'token' => $token,
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal generate token, pastikan table personal_access_tokens sudah di-migrate di TiDB Cloud.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}