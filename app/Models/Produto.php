<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];


    public function listasDeCompras(): BelongsToMany
    {
        $listaDeCompras = $this->belongsToMany(ListaDeCompras::class, 'lista_de_compras_produto')
                               ->withTimestamps();

        return $listaDeCompras;
    }
}
