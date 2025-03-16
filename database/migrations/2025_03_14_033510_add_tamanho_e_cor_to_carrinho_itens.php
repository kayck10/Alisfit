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
        Schema::table('carrinho_iten', function (Blueprint $table) {
            $table->unsignedBigInteger('tamanho_id')->nullable();
            $table->string('cor')->nullable();
            $table->foreign('tamanho_id')->references('id')->on('tamanhos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('carrinho_iten', function (Blueprint $table) {
            $table->dropForeign(['tamanho_id']);
            $table->dropColumn(['tamanho_id', 'cor']);
        });
    }
};
