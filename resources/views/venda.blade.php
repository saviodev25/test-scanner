<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registro de Venda - Loja {{ $loja }}</title>
    <link rel="stylesheet" href="{{ asset('css/vendas.css') }}"></head>
<body>
    <h1>Loja {{ $loja }} - Registro de Venda</h1>

    <a href="/lista/vendas">
        <button class="ver-vendidos">Ver produtos vendidos</button>
    </a>

    <input type="text" id="codigo" placeholder="Escaneie o código de barras" autofocus autocomplete="off">
    
    <p id="mensagem"></p>
    <button id="confirmarBtn">Confirmar Venda</button>

    <script>
        const loja = "{{ $loja }}";
        const csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('js/vendas.js') }}"></script>
</body>
</html>
