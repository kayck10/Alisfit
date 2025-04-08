<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('produtos', function (Blueprint $table) {
            // Adiciona colunas booleanas com valor padrão 'false'
            $table->boolean('lancamento')->default(false);;
            $table->boolean('oferta')->default(false)->after('lancamento');
        });
    }

    public function down()
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn(['lancamento', 'oferta']);
        });
    }
};
