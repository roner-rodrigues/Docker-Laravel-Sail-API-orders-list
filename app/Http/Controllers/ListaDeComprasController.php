<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Services\ListaDeComprasService;
use App\Repositories\ListaDeComprasRepository;
use Exception;

class ListaDeComprasController extends Controller
{
    protected $listaDeComprasService;
    protected $listaDeComprasRepository;

    public function __construct(ListaDeComprasService $listaDeComprasService, ListaDeComprasRepository $listaDeComprasRepository)
    {
        $this->listaDeComprasService    = $listaDeComprasService;
        $this->listaDeComprasRepository = $listaDeComprasRepository;
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'titulo' => 'required',
                'produtos' => 'required|array',
                'produtos.*.id' => 'required|exists:produtos,id',
                'produtos.*.quantidade' => 'required|integer|min:1'
            ]);
    
            $listaDeCompras = $this->listaDeComprasService->create($request->all());
    
            return response()->json([
                'message' => 'Lista de compras criada com sucesso',
                'lista_de_compras' => $listaDeCompras
            ], Response::HTTP_OK);
        }
        catch(Exception $exception) {
            $messageError = $exception->getMessage();
            return response()->json([
                'message' => $messageError
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function duplicarLista($id)
    {
        try {
            $listaDeCompras = $this->listaDeComprasService->duplicarLista($id);
    
            return response()->json([
                'message' => 'Lista de compras duplicada com sucesso',
                'lista_de_compras' => $listaDeCompras
            ], Response::HTTP_OK);
        }
        catch(Exception $exception) {
            $messageError = $exception->getMessage();
            return response()->json([
                'message' => $messageError
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getById($id)
    {
        try {
            $listaDeCompras = $this->listaDeComprasRepository->getById($id);

            return response()->json([
                'listaDeCompras' => $listaDeCompras
            ], Response::HTTP_OK);
        }
        catch(ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Lista de compras não encontrada'
            ], Response::HTTP_NOT_FOUND);
        }
        catch(Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function removeProdutoFromLista($id, $id_produto)
    {
        try {
            $removed = $this->listaDeComprasService->removeProdutoFromLista($id, $id_produto);

            if ($removed) {
                return response()->json([
                    'message' => 'Produto removido com sucesso da lista de compras'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'O produto não estava na lista de compras ou não foi possível removê-lo'
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Erro ao remover produto da lista de compras: ' . $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function addProdutoToLista(Request $request, $id)
    {
        try {
            $request->validate([
                'produtos' => 'required|array',
                'produtos.*.id' => 'required|exists:produtos,id',
                'produtos.*.quantidade' => 'required|integer|min:1'
            ]);
    
            $listaDeCompras = $this->listaDeComprasService->addProdutoToLista($request->all(), $id);

            if ($listaDeCompras) {
                return response()->json([
                    'message' => 'Lista de compras atualizada com sucesso',
                    'lista_de_compras' => $listaDeCompras
                    ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'A Lista de compras ou o Id do(s) Produto(s) especificado(s) é inválida'
                ], Response::HTTP_BAD_REQUEST);
            }
        }
        catch(Exception $exception) {
            return response()->json([
                'message' => 'Erro ao atualizar a lista de compras: ' . $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function increaseProdutoQtd(Request $request, $id, $id_produto)
    {
        try {
            $request->validate([
                'quantidade' => 'required|integer|min:1'
            ]);
            
            $qtd = $request->quantidade;

            $updated = $this->listaDeComprasService->increaseProdutoQtd($id, $id_produto, $qtd);
            
            if ($updated) {
                return response()->json([
                    'message' => 'Produto(s) adicionado(s) com sucesso na lista de compras'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'O produto ou a lista especificada é inválida'
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Erro ao incrementar o produto na lista de compras: ' . $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function decreaseProdutoQtd(Request $request, $id, $id_produto)
    {
        try {
            $request->validate([
                'quantidade' => 'required|integer|min:1'
            ]);
            
            $qtd = $request->quantidade;

            $updated = $this->listaDeComprasService->decreaseProdutoQtd($id, $id_produto, $qtd);
            
            if ($updated) {
                return response()->json([
                    'message' => 'Produto(s) removido(s) com sucesso na lista de compras'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'O produto ou a lista especificada é inválida'
                ], Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Erro ao decrementar o produto na lista de compras: ' . $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }







}
