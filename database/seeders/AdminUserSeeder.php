<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Eliminar cualquier usuario con id 1 para evitar conflicto
        DB::table('users')->where('id', 1)->delete();

        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@dulceconmaria.com',
            'password' => Hash::make('AdminDCM-2026'), // cámbiala después en producción
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
