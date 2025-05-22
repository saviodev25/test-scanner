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
        $request->validate([
            'produto_id' => [
                'required',          // O campo 'produto_id' é obrigatório
                'integer',           // Deve ser um número inteiro
                'exists:produtos,id' // Deve existir na coluna 'id' da tabela 'produtos'
            ],
        ], [
            // Mensagens de erro personalizadas
            'produto_id.required' => 'O ID do produto é obrigatório para registrar a venda.',
            'produto_id.integer' => 'O ID do produto deve ser um número válido.',
            'produto_id.exists' => 'O produto selecionado não foi encontrado no sistema.',
        ]);

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
    // Exemplo: No seu controller que lida com a rota /lista/vendas

    public function listarVendas()
    {
        $vendas = DB::table('vendas')
                    ->join('produtos', 'vendas.produto_id', '=', 'produtos.id')
                    ->select(
                        'vendas.id', // <-- ESSENCIAL: Certifique-se de selecionar o ID da venda aqui!
                        'produtos.nome',
                        'produtos.codigo_barras',
                        'produtos.referencia', // Se você tiver essa coluna em produtos
                        'produtos.tamanho',
                        'produtos.cor',
                        'vendas.loja',
                        'vendas.created_at'
                    )
                    ->orderBy('vendas.created_at', 'desc') // Opcional: ordenar por data
                    ->get();

        return view('vendas', compact('vendas'));
    }
    public function removerVenda($id)
    {
        // Busca a venda pelo ID. O método find() é otimizado para chaves primárias.
        $venda = DB::table('vendas')->find($id);

        if ($venda) {
            // Se a venda for encontrada, a remove do banco de dados.
            DB::table('vendas')->where('id', $id)->delete();

            // Retorna uma resposta JSON de sucesso.
            return response()->json(['status' => 'ok', 'mensagem' => 'Venda removida com sucesso.']);
        } else {
            // Se a venda não for encontrada, retorna um erro com status HTTP 404.
            return response()->json(['status' => 'erro', 'mensagem' => 'Venda não encontrada.'], 404);
        }
    }
    

}
