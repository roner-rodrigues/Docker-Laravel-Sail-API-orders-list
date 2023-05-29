<?php

namespace App\Repositories;

use App\Models\ListaDeCompras;
use Exception;

class ListaDeComprasRepository
{
    protected $model;

    public function __construct(ListaDeCompras $listaDeCompras)
    {
        $this->model = $listaDeCompras;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function get()
    {
        return $this->model->get();
    }

    public function getById($id)
    {
        $listaDeCompras = $this->model->findOrFail($id);

        foreach ($listaDeCompras->produtos as $produto) {
            $produto->quantidade = $produto->pivot->quantidade;
            unset($produto->pivot);
        }

        return $listaDeCompras;
    }

    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    public function increaseProdutoQtd($id, $id_produto, $qtd)
    {
        $listaDeCompras = $this->model->findOrFail($id);

        // Recupera o produto com o ID especificado da lista de compras
        $produto = $listaDeCompras->produtos()->where('produto_id', $id_produto)->first();

        // Se o produto não for encontrado, retorne um erro
        if (!$produto) {
            throw new Exception('Produto não encontrado');
        }

        // Incrementa a quantidade na tabela pivot
        $produto->pivot->quantidade += $qtd;
        $produto->pivot->save();

        return true;
    }

    public function decreaseProdutoQtd($id, $id_produto, $qtd)
    {
        $listaDeCompras = $this->model->findOrFail($id);

        // Recupera o produto com o ID especificado da lista de compras
        $produto = $listaDeCompras->produtos()->where('produto_id', $id_produto)->first();

        // Se o produto não for encontrado, retorne um erro
        if (!$produto) {
            throw new Exception('Produto não encontrado');
        }
        
        // Verifica se é possível decrementar a quantidade especificada na tabela pivot
        if($qtd > $produto->pivot->quantidade) {
            throw new Exception('Não é possível decrementar na lista de compras a quantidade especificada do produto');
        } else {
            // Decrementa a quantidade na tabela pivot
            $produto->pivot->quantidade -= $qtd;
            $produto->pivot->save();
        }

        return true;
    }

}
