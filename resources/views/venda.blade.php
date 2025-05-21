<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registro de Venda - Loja {{ $loja }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #dad5d5;
            text-align: center;
        }

        h1 {
            color: #242323;
        }

        input[type="text"] {
            font-size: 1.2rem;
            padding: 10px;
            width: 300px;
            margin-top: 30px;
        }

        #mensagem {
            margin-top: 20px;
            font-weight: bold;
        }

        #confirmarBtn {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            display: none;
        }

        #confirmarBtn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h1>Loja {{ $loja }} - Registro de Venda</h1>

    <a href="/lista/vendas">
        <button style="margin-bottom: 20px; padding: 10px 15px; background-color: #3490dc; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Ver produtos vendidos
        </button>
    </a>
    
    <input 
        type="text" 
        id="codigo" 
        placeholder="Escaneie o código de barras" 
        autofocus 
        autocomplete="off"
    >

    <p id="mensagem"></p>
    <button id="confirmarBtn">Confirmar Venda</button>

    <!-- DEFINIÇÕES GLOBAIS -->
    <script>
        const loja = @json($loja);
        const csrfToken = '{{ csrf_token() }}';
    </script>

    <script>
        const input = document.getElementById('codigo');
        const mensagem = document.getElementById('mensagem');
        const confirmarBtn = document.getElementById('confirmarBtn');
        let codigoProduto = null;

        input.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                const codigo = input.value.trim();
                if (!codigo) return;

                input.value = '';
                mensagem.textContent = 'Buscando produto...';
                mensagem.style.color = 'black';
                confirmarBtn.style.display = 'none';
                codigoProduto = null;

                fetch(`/produto/${codigo}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'ok') {
                            codigoProduto = codigo;
                            const p = data.produto;
                            mensagem.textContent = `Produto encontrado: ${p.nome} - Tamanho: ${p.tamanho} - Cor: ${p.cor}`;
                            mensagem.style.color = 'green';
                            confirmarBtn.style.display = 'inline-block';
                        } else {
                            mensagem.textContent = `✘ ${data.mensagem}`;
                            mensagem.style.color = 'red';
                        }
                    })
                    .catch(() => {
                        mensagem.textContent = 'Erro de conexão';
                        mensagem.style.color = 'red';
                    });
            }
        });

        confirmarBtn.addEventListener('click', function () {
            if (!codigoProduto) return;

            fetch(`/registrar/${loja}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ codigo: codigoProduto })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'ok') {
                    mensagem.textContent = `✔ Venda registrada: ${data.produto}`;
                    mensagem.style.color = 'blue';
                } else {
                    mensagem.textContent = `✘ ${data.mensagem}`;
                    mensagem.style.color = 'red';
                }
                confirmarBtn.style.display = 'none';
                codigoProduto = null;
            })
            .catch(() => {
                mensagem.textContent = 'Erro ao registrar venda';
                mensagem.style.color = 'red';
            });
        });
    </script>    
</body>
</html>
