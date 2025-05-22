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
        // Captura o ID do produto que o frontend está enviando
        $produtoId = $request->input('produto_id');
    
        // Busca o produto diretamente pelo seu ID (chave primária)
        $produto = DB::table('produtos')->find($produtoId); // O método find() busca pela chave primária
    
        if ($produto) {
            // Insere a venda na tabela 'vendas' usando o ID do produto
            DB::table('vendas')->insert([
                'produto_id' => $produto->id,
                'loja' => $loja,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            // Retorna o nome do produto para o frontend, confirmando a venda
            return response()->json(['status' => 'ok', 'produto' => $produto->nome]);
        } else {
            // Mensagem de erro se o produto com o ID fornecido não for encontrado
            return response()->json(['status' => 'erro', 'mensagem' => 'Produto com o ID especificado não encontrado.']);
        }
    }


    public function buscarProduto($codigo)
    {
        $produtos = DB::table('produtos')->where('codigo_barras', $codigo)->get();

        if ($produtos->isNotEmpty()) {
            $produtosFormatados = $produtos->map(function ($produto) {
                return [
                    'id' => $produto->id, // <-- CRUCIAL: Retorne o ID aqui!
                    'nome' => $produto->nome,
                    'tamanho' => $produto->tamanho,
                    'cor' => $produto->cor
                ];
            });

            return response()->json([
                'status' => 'ok',
                'produtos' => $produtosFormatados
            ]);
        } else {
            return response()->json(['status' => 'erro', 'mensagem' => 'Nenhum produto encontrado com este código de barras.']);
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
