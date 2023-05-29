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
        Schema::create('lista_de_compras', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lista_de_compras', function (Blueprint $table) {
            $table->dropIndex(['titulo']); // Remove o índice aqui
        });
        
        Schema::dropIfExists('lista_de_compras');
    }
};
