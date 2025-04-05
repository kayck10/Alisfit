<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tabela_medidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained()->onDelete('cascade');
            $table->string('tamanho');
            $table->decimal('torax', 8, 2)->nullable();
            $table->decimal('cintura', 8, 2)->nullable();
            $table->decimal('quadril', 8, 2)->nullable();
            $table->decimal('comprimento', 8, 2)->nullable();
            $table->decimal('altura', 8, 2)->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabela_medidas');
    }
};
