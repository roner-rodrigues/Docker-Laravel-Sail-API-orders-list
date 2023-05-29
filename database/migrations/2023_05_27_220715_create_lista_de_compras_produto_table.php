<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListaDeComprasProdutoTable extends Migration
{
    public function up()
    {
        Schema::create('lista_de_compras_produto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lista_de_compras_id')->constrained()->onDelete('cascade');
            $table->foreignId('produto_id')->constrained()->onDelete('cascade');
            $table->integer('quantidade')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lista_de_compras_produto');
    }
}
