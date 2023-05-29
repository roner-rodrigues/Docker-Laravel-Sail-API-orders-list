<?php

namespace App\Repositories;

use App\Models\Produto;

class ProdutoRepository
{
    protected $model;

    public function __construct(Produto $produto)
    {
        $this->model = $produto;
    }

    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }
}
