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
            $table->foreignId('pedido_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('carrinho_iten', function (Blueprint $table) {
            $table->dropForeign(['pedido_id']);
            $table->dropColumn('pedido_id');
        });
    }
};
