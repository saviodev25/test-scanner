<!DOCTYPE html>
<html>
<head>
    <title>Lista de Vendas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #dad5d5;
        }
        h1 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 12px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #ffffff;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Vendas Registradas</h1>

    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Código de Barras</th>
                <th>Referencia</th>
                <th>Tamanho</th>
                <th>Cor</th>
                <th>Loja</th>
                <th>Data/Hora</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vendas as $venda)
                <tr>
                    <td>{{ $venda->nome }}</td>
                    <td>{{ $venda->codigo_barras }}</td>
                    <td>{{ $venda->referencia }}</td>
                    <td>{{ $venda->tamanho }}</td>
                    <td>{{ $venda->cor }}</td>
                    <td>{{ $venda->loja }}</td>
                    <td>{{ \Carbon\Carbon::parse($venda->created_at)->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Nenhuma venda registrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
