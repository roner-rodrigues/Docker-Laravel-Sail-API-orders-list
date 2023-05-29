<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Produto;

class ListaDeCompras extends Model
{
    use HasFactory;

    protected $fillable = ['titulo'];

    /**
     * O(s) Produto(s) que pertencem a esta ListaDeCompras.
     */
    public function produtos(): BelongsToMany
    {
        return $this->belongsToMany(Produto::class, 'lista_de_compras_produto')
                    ->withPivot('quantidade')
                    ->withTimestamps();
    }
}
