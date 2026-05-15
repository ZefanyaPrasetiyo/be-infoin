<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash; 

class userController extends Controller
{
    public function getUserById($id){
        try{
            $user = User::findOrFail($id);
            return response()->json([
                'message' => 'Berhasil mengambil data user',
                'data' => $user
            ], 200);
        }catch( \Exception $e){
            return response()->json([
                'message' => 'Gagal mengambil data user',
                'error' => $e->getMessage()
            ], 500);
        }

    }
    public function getAllUser(){
       try{
        $data = User::all();
        return response()->json([
            'message' => 'Berhasil mengambil data user',
            'data' => $data
        ]);
       }catch( \Exception $e){
        return response()->json([
            'message' => 'Gagal mengambil data user',
            'error' => $e->getMessage()
        ], 500);

       }
    }

    public function createUsers(Request $request){
        $request->validate([
            'nama_panjang' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'nomor_telepon' => 'required|string',
            'role' => 'sometimes|in:admin,user'
        ]);
        try{
        $user = User::create([
        'nama_panjang' => $request->nama_panjang,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'nomor_telepon' => $request->nomor_telepon,
        'role' => $request->role ?? 'user'
        ]);
        return response()->json([
            'message' => 'Berhasil membuat user',
        ], 201);
        }catch( \Exception $e){
            return response()->json([
                'message' => 'Gagal membuat user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function updateUsers(Request $request, $id){
   $request->validate([
            'nama_panjang' => 'sometimes|string',
            'email'=> 'sometimes|email|unique:users,email,' . $id,
            'password'=> 'nullable|string|min:8',
            'nomor_telepon' => 'sometimes|string',
            'role' => 'sometimes|in:admin,user'
        ]);
        try{
            $user = User::findOrFail($id);
             $data = array_filter($request->only([
            'nama_panjang',
            'email',
            'nomor_telepon',
            'role'
        ]), function ($value) {
            return !is_null($value);
        });

        if($request->filled('password')){
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->refresh();

        return response()->json([
            'message' => 'Berhasil mengupdate user',
            'data' => $user
        ], 200);
        }catch( \Exception $e){
            return response()->json([
                'message' => 'Gagal mengupdate user',
                'error' => $e->getMessage()
            ], 500);
        }
    
}
    public function deleteUsers($id){
        try{
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'message' => 'Berhasil menghapus user',
            ], 200);
        }catch( \Exception $e){
            return response()->json([
                'message' => 'Gagal menghapus user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

}
