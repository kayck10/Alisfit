<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusPedidosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('status_pedidos')->insert([
            ['desc' => 'Pagamento pedente'],
            ['desc' => 'Pagamento Aprovado'],
            ['desc' => 'Pedido Separado'],
            ['desc' => 'Pedido Enviado'],
            ['desc' => 'Concluído'],
        ]);
    }
}
