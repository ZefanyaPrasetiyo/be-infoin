<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class authController extends Controller
{
    public function Register(Request $request)
    {
        $request->validate([
            'nama_panjang' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'nomor_telepon' => 'required|string',
            'role' => 'sometimes|in:admin,user'
        ]);
        try {
            $user = User::create([
                'nama_panjang' => $request->nama_panjang,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'nomor_telepon' => $request->nomor_telepon,
                'role' => $request->role ?? 'user'
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

        $token = auth()->guard('api')->attempt($request->only('email', 'password'));

        if(!$token){
            return response()->json([
                'message'=> "Login gagal, Pastikan email dan password benar"
            ],401);
        }
        $user = auth()->guard('api')->user();

        if(!$user->hasVerifiedEmail()) {
            auth()->guard('api')->logout();
            return response()->json([
                'message'=>'Email belum diverifikasi, silakan check email anda'
            ], 403);
        }
        return response()->json([
        'message' => 'Login berhasil',
        'token' => $token,
        'user' => $user
    ], 200);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60 // Default 1 jam
        ]);
    }
}
