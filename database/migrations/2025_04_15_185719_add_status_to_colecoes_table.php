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
        Schema::table('colecoes', function (Blueprint $table) {
            $table->enum('status', ['rascunho', 'publicado'])->default('rascunho');
            $table->timestamp('publicado_em')->nullable();
        });
    }

    public function down()
    {
        Schema::table('colecoes', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('publicado_em');
        });
    }
};
