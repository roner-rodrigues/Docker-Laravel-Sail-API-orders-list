<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\ListaDeComprasRepository;
use App\Repositories\ProdutoRepository;
use Carbon\Carbon;
use Exception;

class ListaDeComprasService
{
    protected $listaDeComprasRepository;
    protected $produtoRepository;

    public function __construct(
        ListaDeComprasRepository $listaDeComprasRepository, 
        ProdutoRepository        $produtoRepository
    ) {
        $this->listaDeComprasRepository = $listaDeComprasRepository;
        $this->produtoRepository        = $produtoRepository;
    }

    public function create(array $data)
    {
        // Inicia uma transação
        DB::beginTransaction();

        try {
            // Cria a lista de compras
            $listaDeCompras = $this->listaDeComprasRepository->create($data);

            // Cria os produtos e associa-os à lista de compras
            foreach ($data['produtos'] as $produtoData) 
            {
                $produto = $this->produtoRepository->findOrFail($produtoData['id']);

                $listaDeCompras->produtos()->attach($produto->id, ['quantidade' => $produtoData['quantidade']]);
            }

            // Se tudo ocorrer bem, confirma a transação
            DB::commit();

            return $listaDeCompras;

        } catch (\Exception $e) {
            // Se algo der errado, reverta a transação
            DB::rollBack();

            throw $e;
        }
    }

    public function duplicarLista($id)
    {
        // Inicia uma transação
        DB::beginTransaction();
        
        try {
            // Verifica se a lista de compras especificada existe
            $listaDeCompras  = $this->listaDeComprasRepository->findOrFail($id);
            
            // Duplica a lista de compras
            $novaListaDeCompras = $listaDeCompras->replicate();
            $novaListaDeCompras->created_at = Carbon::now();
            $novaListaDeCompras->updated_at = Carbon::now();
            $novaListaDeCompras->save();
            
            // Replica as entradas relacionadas em lista_de_compras_produto
            $produtos = $listaDeCompras->produtos()->get();

            // Cria os produtos e associa-os à lista de compras
            foreach ($produtos as $produtoData) 
            {
                $produtoId  = $produtoData->pivot->produto_id;
                $produtoQtd = $produtoData->pivot->quantidade;

                $novaListaDeCompras->produtos()->attach($produtoId, ['quantidade' => $produtoQtd]);
            }

            // Se tudo ocorrer bem, confirma a transação
            DB::commit();
            
            return $novaListaDeCompras;

        } catch (\Exception $e) {
            // Se algo der errado, reverta a transação
            DB::rollBack();
            
            throw $e;
        }
    }

    public function removeProdutoFromLista($id, $id_produto)
    {
        $listaDeCompras = $this->listaDeComprasRepository->findOrFail($id);
        $produto        = $this->produtoRepository->findOrFail($id_produto);

        $listaDeCompras->produtos()->detach($produto);
        
        return true;
    }

    public function addProdutoToLista(array $data, $id)
    {
        $listaDeCompras = $this->listaDeComprasRepository->getById($id);

        // Cria os produtos e associa-os à lista de compras
        foreach ($data['produtos'] as $produtoData) 
        {
            $produto = $this->produtoRepository->findOrFail($produtoData['id']);

            if($listaDeCompras->produtos()->where('produto_id', $produto->id)->exists()){
                // Atualiza a quantidade do produto na lista de compras
                $listaDeCompras->produtos()->updateExistingPivot($produto->id, ['quantidade' => $produtoData['quantidade']]);
            } else {
                // Adiciona o produto na lista de compras
                $listaDeCompras->produtos()->attach($produto->id, ['quantidade' => $produtoData['quantidade']]);
            }
        }

        // Recarrega a relação de produtos na lista de compras após a adição
        $listaDeCompras->load('produtos');

        return $listaDeCompras;
    }

    public function increaseProdutoQtd($id, $id_produto, $qtd)
    {
        $listaDeCompras = $this->listaDeComprasRepository->findOrFail($id);
        $produto        = $this->produtoRepository->findOrFail($id_produto);

        $this->listaDeComprasRepository->increaseProdutoQtd($id, $id_produto, $qtd);

        return true;
    }

    public function decreaseProdutoQtd($id, $id_produto, $qtd)
    {
        $listaDeCompras = $this->listaDeComprasRepository->findOrFail($id);
        $produto        = $this->produtoRepository->findOrFail($id_produto);

        $this->listaDeComprasRepository->decreaseProdutoQtd($id, $id_produto, $qtd);

        return true;
    }



}
