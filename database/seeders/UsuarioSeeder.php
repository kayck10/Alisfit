<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => '$2y$12$K8u5Fc/V8EIIOyh9iyFAvu3MTPYhrlTu7bRfSkGeLIZl4y8ZpRwqG',
            'id_tipos_usuarios' => 1,
        ]);
    }

}
