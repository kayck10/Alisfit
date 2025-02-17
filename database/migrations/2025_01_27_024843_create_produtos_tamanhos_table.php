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
    Schema::create('produtos_tamanhos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
        $table->foreignId('tamanho_id')->constrained('tamanhos')->onDelete('cascade');
        $table->integer('quantidade')->default(1);
        $table->string('cor');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos_tamanhos');
    }
};
