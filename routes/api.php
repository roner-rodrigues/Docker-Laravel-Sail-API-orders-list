<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListaDeComprasController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/lista-de-compras/{id}', [ListaDeComprasController::class, 'getById']);

Route::post('/lista-de-compras',     [ListaDeComprasController::class, 'create']);
Route::post('/lista-de-compras/{id}/adicionar-produto', [ListaDeComprasController::class, 'addProdutoToLista']);
Route::post('/lista-de-compras/{id}/duplicar', [ListaDeComprasController::class, 'duplicarLista']);

Route::patch('/lista-de-compras/{id}/adicionar-qtd-produto/{id_produto}', [ListaDeComprasController::class, 'increaseProdutoQtd']);
Route::patch('/lista-de-compras/{id}/diminuir-qtd-produto/{id_produto}', [ListaDeComprasController::class, 'decreaseProdutoQtd']);

Route::delete('/lista-de-compras/{id}/remover-produto/{id_produto}', [ListaDeComprasController::class, 'removeProdutoFromLista']);




// Route::controller(ListaDeComprasController::class)->group(function() {
//     Route::post('/lista-de-compras',     'create');
//     Route::get('/lista-de-compras/{id}', 'getById');
// });