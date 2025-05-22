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
            color: black; /* Cor padrão para mensagens */
        }

        #produtos-encontrados {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 8px;
            display: none; /* Escondido por padrão */
            text-align: left;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .produto-item {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
        }

        .produto-item label {
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .produto-item input[type="radio"] {
            margin-right: 10px;
        }

        #confirmarBtn {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: not-allowed; /* Inicialmente desabilitado */
            opacity: 0.6; /* Indicador visual de desabilitado */
        }

        #confirmarBtn.active { /* Classe para quando o botão estiver ativo */
            cursor: pointer;
            opacity: 1;
        }

        #confirmarBtn.active:hover {
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

    <div id="produtos-encontrados">
        <h3>Selecione o produto vendido:</h3>
        <div id="lista-produtos">
            </div>
    </div>

    <button id="confirmarBtn">Confirmar Venda</button>

    <script>
        const loja = @json($loja);
        const csrfToken = '{{ csrf_token() }}';
    </script>

    <script>
        const input = document.getElementById('codigo');
        const mensagem = document.getElementById('mensagem');
        const produtosEncontradosDiv = document.getElementById('produtos-encontrados');
        const listaProdutosDiv = document.getElementById('lista-produtos');
        const confirmarBtn = document.getElementById('confirmarBtn');

        let produtosDisponiveis = []; // Armazenará os produtos retornados pela API
        let produtoSelecionado = null; // Armazenará o produto selecionado pela vendedora

        // Função para resetar o estado da interface
        function resetInterface() {
            mensagem.textContent = '';
            mensagem.style.color = 'black';
            produtosEncontradosDiv.style.display = 'none';
            listaProdutosDiv.innerHTML = '';
            confirmarBtn.classList.remove('active');
            confirmarBtn.disabled = true; // Desabilita o botão
            produtoSelecionado = null;
            produtosDisponiveis = [];
        }

        input.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                const codigo = input.value.trim();
                if (!codigo) return;

                input.value = '';
                resetInterface();
                mensagem.textContent = 'Buscando produto(s)...';

                fetch(`/produto/${codigo}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'ok' && data.produtos && data.produtos.length > 0) {
                            produtosDisponiveis = data.produtos;
                            mensagem.textContent = `Produto(s) encontrado(s). Selecione o item vendido:`;
                            mensagem.style.color = 'green';
                            
                            listaProdutosDiv.innerHTML = '';
                            produtosDisponiveis.forEach((p, index) => {
                                // MUDANÇA AQUI: Usamos o ID do produto para o ID do rádio e seu valor
                                const radioId = `produto-${p.id}`;
                                listaProdutosDiv.innerHTML += `
                                    <div class="produto-item">
                                        <label for="${radioId}">
                                            <input type="radio" id="${radioId}" name="produto_selecionado" value="${p.id}"> ${p.nome} - Tamanho: ${p.tamanho} - Cor: ${p.cor}
                                        </label>
                                    </div>
                                `;
                            });
                            produtosEncontradosDiv.style.display = 'block';

                            document.querySelectorAll('input[name="produto_selecionado"]').forEach(radio => {
                                radio.addEventListener('change', function() {
                                    // MUDANÇA AQUI: Encontra o produto completo usando o ID selecionado
                                    produtoSelecionado = produtosDisponiveis.find(p => p.id == this.value);
                                    confirmarBtn.classList.add('active');
                                    confirmarBtn.disabled = false;
                                });
                            });

                        } else if (data.status === 'ok' && (!data.produtos || data.produtos.length === 0)) {
                            mensagem.textContent = `✘ Nenhum produto encontrado com este código de barras.`;
                            mensagem.style.color = 'red';
                        }
                        else {
                            mensagem.textContent = `✘ ${data.mensagem}`;
                            mensagem.style.color = 'red';
                        }
                    })
                    .catch(() => {
                        mensagem.textContent = 'Erro de conexão com o servidor.';
                        mensagem.style.color = 'red';
                        resetInterface();
                    });
            }
        });

        confirmarBtn.addEventListener('click', function () {
            if (!produtoSelecionado || confirmarBtn.disabled) {
                mensagem.textContent = 'Por favor, selecione um produto antes de confirmar.';
                mensagem.style.color = 'orange';
                return;
            }

            mensagem.textContent = 'Registrando venda...';
            mensagem.style.color = 'black';
            confirmarBtn.classList.remove('active');
            confirmarBtn.disabled = true;

            // MUDANÇA PRINCIPAL AQUI: Enviando APENAS o ID único do produto
            fetch(`/registrar/${loja}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    produto_id: produtoSelecionado.id // <-- Enviando APENAS o ID único do produto!
                })
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
                resetInterface();
            })
            .catch(() => {
                mensagem.textContent = 'Erro ao registrar venda.';
                mensagem.style.color = 'red';
                resetInterface();
            });
        });
    </script>
</html>