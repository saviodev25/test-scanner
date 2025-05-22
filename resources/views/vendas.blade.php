<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
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
        .remove-btn {
            background-color: #dc3545; /* Vermelho para remover */
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
        }
        .remove-btn:hover {
            background-color: #c82333;
        }
        /* Estilos para a message box customizada */
        .message-box {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            display: none; /* Escondido por padrão */
            text-align: center;
        }
        .message-box button {
            margin-top: 15px;
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .message-box button:hover {
            background-color: #0056b3;
        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none; /* Escondido por padrão */
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
                <th>Ações</th> </tr>
        </thead>
        <tbody>
            @forelse($vendas as $venda)
                <tr id="venda-{{ $venda->id }}"> <td>{{ $venda->nome }}</td>
                    <td>{{ $venda->codigo_barras }}</td>
                    <td>{{ $venda->referencia }}</td>
                    <td>{{ $venda->tamanho }}</td>
                    <td>{{ $venda->cor }}</td>
                    <td>{{ $venda->loja }}</td>
                    <td>{{ \Carbon\Carbon::parse($venda->created_at)->format('d/m/Y H:i') }}</td>
                    <td>
                        <button class="remove-btn" data-id="{{ $venda->id }}">Remover</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Nenhuma venda registrada.</td> </tr>
            @endforelse
        </tbody>
    </table>

    <div class="overlay" id="overlay"></div>
    <div class="message-box" id="messageBox">
        <p id="messageBoxContent"></p>
        <button id="messageBoxClose">OK</button>
    </div>

    <script>
        // Obtém o token CSRF do Laravel para segurança das requisições POST/DELETE
        const csrfToken = '{{ csrf_token() }}';

        // Função para exibir a message box customizada
        function showMessageBox(message) {
            const messageBox = document.getElementById('messageBox');
            const messageBoxContent = document.getElementById('messageBoxContent');
            const overlay = document.getElementById('overlay');

            messageBoxContent.textContent = message;
            messageBox.style.display = 'block';
            overlay.style.display = 'block';
        }

        // Event listener para fechar a message box
        document.getElementById('messageBoxClose').addEventListener('click', function() {
            document.getElementById('messageBox').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        });

        // Adiciona event listeners aos botões de remover quando o DOM estiver carregado
        document.addEventListener('DOMContentLoaded', function () {
            const removeButtons = document.querySelectorAll('.remove-btn');

            removeButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const vendaId = this.dataset.id; // Pega o ID da venda do atributo data-id do botão

                    // Confirmação antes de remover (você pode substituir por uma modal mais elaborada)
                    if (!confirm('Tem certeza que deseja remover esta venda? Esta ação é irreversível.')) {
                        return; // Cancela a operação se o usuário não confirmar
                    }

                    // Faz a requisição DELETE para o backend
                    fetch(`/venda/${vendaId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken // Envia o token CSRF para segurança
                        }
                    })
                    .then(response => {
                        // Verifica se a resposta não foi bem-sucedida (ex: status 404, 500)
                        if (!response.ok) {
                            // Tenta ler a mensagem de erro do JSON retornado pelo backend
                            return response.json().then(errorData => {
                                throw new Error(errorData.mensagem || 'Erro desconhecido ao remover a venda.');
                            });
                        }
                        return response.json(); // Se OK, parseia a resposta JSON
                    })
                    .then(data => {
                        if (data.status === 'ok') {
                            // Se a remoção foi bem-sucedida, remove a linha da tabela do DOM
                            const rowToRemove = document.getElementById(`venda-${vendaId}`);
                            if (rowToRemove) {
                                rowToRemove.remove();
                                showMessageBox('Venda removida com sucesso!');
                            }
                        } else {
                            // Exibe a mensagem de erro do backend
                            showMessageBox(`Erro: ${data.mensagem}`);
                        }
                    })
                    .catch(error => {
                        // Captura erros de rede ou erros lançados nos blocos .then()
                        showMessageBox(`Erro na requisição: ${error.message}`);
                    });
                });
            });
        });
    </script>
</body>
</html>
