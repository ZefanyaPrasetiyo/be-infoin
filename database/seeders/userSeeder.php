<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
          [
                'id' => (string) Str::ulid(),
                'nama_panjang' => 'jepanhuahua',
                'email' => 'huahua@infoin.com',
                'password' => Hash::make('huahua2903'),
                'nomor_telepon' => '08123456789',
                'role' => 'admin',
                'id_location' => null, 
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
