<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus dulu user dengan email yang sama (biar gak duplicate)
        DB::table('users')->where('email', 'test@example.com')->delete();

        // Masukin lagi user default
        DB::table('users')->insert([
            'name' => 'Lisdawati',
            'email' => 'test@example.com',
            'password' => Hash::make('20202'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('users')->where('email', 'test@example.com')->delete();
    }
};