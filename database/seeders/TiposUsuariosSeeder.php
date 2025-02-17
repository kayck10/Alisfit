<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposUsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipos_usuarios')->insert([
            ['desc' => 'Administrador', 'created_at' => now(), 'updated_at' => now()],
            ['desc' => 'Gerente', 'created_at' => now(), 'updated_at' => now()],
            ['desc' => 'Funcionário', 'created_at' => now(), 'updated_at' => now()],
            ['desc' => 'Cliente', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
