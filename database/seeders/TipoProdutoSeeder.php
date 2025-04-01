<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipos_produtos')->insert([
            ['desc' => 'Camisa'],
            ['desc' => 'Calça Legging'],
            ['desc' => 'Shorts'],
            ['desc' => 'Tops'],
            ['desc' => 'Conjuntos'],
        ]);
    }
}
