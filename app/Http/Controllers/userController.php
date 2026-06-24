<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // 🚨 Tambahin Log biar gampang di-debug

class userController extends Controller
{
    public function getUserById($id){
        try{
            $user = User::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data user',
                'data' => $user
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        } catch( \Exception $e){
            Log::error('Error get user by id: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function getAllUser(){
       try{
            $data = User::all();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data user',
                'data' => $data
            ], 200);
       } catch( \Exception $e){
            Log::error('Error get all user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
       }
    }

    public function createUsers(Request $request){
        try{
            $request->validate([
                'nama_panjang' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'nomor_telepon' => 'required|string',
                'role' => 'sometimes|in:admin,user,petugas',
                'id_location' => 'nullable|string|exists:locations,id' 
            ]);
            
            $user = User::create([
                'nama_panjang' => $request->nama_panjang,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nomor_telepon' => $request->nomor_telepon,
                'role' => $request->role ?? 'user',
                'id_location' => $request->id_location 
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil membuat user',
                'data' => $user
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
          
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch( \Exception $e){
            Log::error('Error create user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function updateUsers(Request $request, $id){
        try{
            $request->validate([
                'nama_panjang' => 'sometimes|string',
                'email'=> 'sometimes|email|unique:users,email,' . $id,
                'password'=> 'nullable|string|min:8',
                'nomor_telepon' => 'sometimes|string',
                'role' => 'sometimes|in:admin,user,petugas', 
                'id_location' => 'nullable|string|exists:locations,id'
            ]);

            $user = User::findOrFail($id);
            $data = array_filter($request->only([
                'nama_panjang',
                'email',
                'nomor_telepon',
                'role',
                'id_location'
            ]), function ($value) {
                return !is_null($value);
            });

            if($request->filled('password')){
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);
            $user->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengupdate user',
                'data' => $user
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        } catch( \Exception $e){
            Log::error('Error update user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function deleteUsers($id){
        try{
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus user',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        } catch( \Exception $e){
            Log::error('Error delete user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function getDeletedUsers()
    {
        try {
            $users = User::onlyTrashed()->get();
            return response()->json([
                'success' => true,
                'data' => $users
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error get deleted users: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function restoreDeletedUser($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();
            return response()->json([
                'success' => true,
                'message' => 'User berhasil dikembalikan',
                'data' => $user
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User sampah tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error restore deleted user: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }
}