<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Escolher Loja</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dad5d5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 12px;
            width: 100%;
            font-size: 1rem;
            margin-bottom: 20px;
        }

        button {
            padding: 12px 20px;
            background-color: #3490dc;
            color: white;
            border: none;
            font-size: 1rem;
            cursor: pointer;
        }

        button:hover {
            background-color: #2779bd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Informe o nome da loja</h1>
        <form id="lojaForm">
            <input type="text" id="nomeLoja" placeholder="Ex: Malhada, Iuiu..." required>
            <button type="submit">Entrar</button>
        </form>
    </div>

    <script>
        document.getElementById('lojaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const loja = document.getElementById('nomeLoja').value.trim();
            if (loja) {
                window.location.href = `/loja/${loja}`;
            }
        });
    </script>
</body>
</html>
