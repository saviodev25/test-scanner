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
