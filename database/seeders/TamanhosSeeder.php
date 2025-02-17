<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TamanhosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tamanhos')->insert([
            ['desc' => 'PP', 'created_at' => now(), 'updated_at' => now()],
            ['desc' => 'P', 'created_at' => now(), 'updated_at' => now()],
            ['desc' => 'M', 'created_at' => now(), 'updated_at' => now()],
            ['desc' => 'G', 'created_at' => now(), 'updated_at' => now()],
            ['desc' => 'GG', 'created_at' => now(), 'updated_at' => now()],
            ['desc' => 'XG', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
