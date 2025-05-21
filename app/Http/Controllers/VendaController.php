<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendaController extends Controller
{
    public function index($loja)//loja está vindo da url
    {
        return view('venda', compact('loja'));
    }

    public function registrar(Request $request, $loja)
    {
        $codigo = $request->input('codigo');

        // Buscar o produto
        $produto = DB::table('produtos')->where('codigo_barras', $codigo)->first();

        if ($produto) {
            DB::table('vendas')->insert([
                'produto_id' => $produto->id,
                'loja' => $loja,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['status' => 'ok', 'produto' => $produto->nome]);
        } else {
            return response()->json(['status' => 'erro', 'mensagem' => 'Produto não encontrado']);
        }
    }

    public function buscarProduto($codigo)
    {
        $produto = DB::table('produtos')->where('codigo_barras', $codigo)->first();

        if ($produto) {
            return response()->json([
                'status' => 'ok',
                'produto' => [
                    'nome' => $produto->nome,
                    'tamanho' => $produto->tamanho,
                    'cor' => $produto->cor
                ]
            ]);
        } else {
            return response()->json(['status' => 'erro', 'mensagem' => 'Produto não encontrado']);
        }
    }


    

    public function listarVendas()
    {
        $vendas = DB::table('vendas')
            ->join('produtos', 'vendas.produto_id', '=', 'produtos.id')
            ->select('produtos.nome', 'produtos.codigo_barras', 'produtos.referencia', 'produtos.cor', 'produtos.tamanho', 'vendas.loja', 'vendas.created_at')
            ->orderByDesc('vendas.created_at')
            ->get();
    
        return view('vendas', compact('vendas'));
    }
    

}
