<?php

use App\Http\Controllers\VendaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});
Route::get('/loja/{loja}', [VendaController::class, 'index']);
Route::get('/produto/{codigo}', [VendaController::class, 'buscarProduto']);
Route::post('/registrar/{loja}', [VendaController::class, 'registrar']);
Route::get('/lista/vendas', [VendaController::class, 'listarVendas']);
Route::delete('/venda/{id}', [VendaController::class, 'removerVenda'])->name('vendas.remover');
