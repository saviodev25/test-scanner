<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produtos = [
            ['nome' => 'Tênis Nike Air Max', 'codigo_barras' => '7894561230001', 'referencia' => 'NIKE-AMX-01', 'tamanho' => '42', 'cor' => 'Preto/Vermelho'],
            ['nome' => 'Sandália Beira Rio', 'codigo_barras' => '7894561230002', 'referencia' => 'BR-SDL-02', 'tamanho' => '37', 'cor' => 'Bege'],
            ['nome' => 'Chinelo Havaianas Preto', 'codigo_barras' => '7894561230003', 'referencia' => 'HVN-CHN-03', 'tamanho' => '39', 'cor' => 'Preto'],
            ['nome' => 'Sapatênis Masculino Pegada', 'codigo_barras' => '7894561230004', 'referencia' => 'PGD-SPT-04', 'tamanho' => '41', 'cor' => 'Marrom'],
            ['nome' => 'Tênis Adidas Run', 'codigo_barras' => '7894561230005', 'referencia' => 'AD-RUN-05', 'tamanho' => '40', 'cor' => 'Branco/Azul'],
            ['nome' => 'Bota Feminina Via Marte', 'codigo_barras' => '7894561230006', 'referencia' => 'VM-BTF-06', 'tamanho' => '36', 'cor' => 'Caramelo'],
            ['nome' => 'Tênis Infantil Molekinha', 'codigo_barras' => '7894561230007', 'referencia' => 'MLK-INF-07', 'tamanho' => '28', 'cor' => 'Rosa'],
            ['nome' => 'Sandália Rasteira Dakota', 'codigo_barras' => '7894561230008', 'referencia' => 'DKD-RAS-08', 'tamanho' => '38', 'cor' => 'Dourado'],
            ['nome' => 'Chinelo Slide Nike', 'codigo_barras' => '7894561230009', 'referencia' => 'NK-SLD-09', 'tamanho' => '43', 'cor' => 'Branco/Preto'],
            ['nome' => 'Bota Coturno Masculina', 'codigo_barras' => '7894561230010', 'referencia' => 'CTN-MAS-10', 'tamanho' => '44', 'cor' => 'Preto'],
        ];
        
        // DB::table('produtos')->insert($produtos);
        foreach ($produtos as $produto) {
            DB::table('produtos')->insert([
                'codigo_barras' => $produto['codigo_barras'],
                'nome' => $produto['nome'],
                'referencia' => $produto['referencia'],
                'tamanho' => $produto['tamanho'],
                'cor' => $produto['cor'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
