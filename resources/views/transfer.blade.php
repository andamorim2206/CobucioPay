<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transferência</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            text-align: left;
            font-weight: bold;
            color: #555;
        }

        input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
        }

        button {
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #218838;
        }

        .back-btn {
            margin-top: 15px;
            background-color: #007bff;
        }

        .back-btn:hover {
            background-color: #0062cc;
        }

        .message {
            margin-top: 15px;
            font-size: 0.95rem;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Transferência</h2>
        <form id="transferForm">
            <label for="email">Email do destinatário</label>
            <input type="email" id="email" name="email" placeholder="ex: aegon@targeryan.com" required>

            <label for="amount">Valor</label>
            <input type="number" id="amount" name="amount" placeholder="50" min="1" required>

            <button type="submit">Enviar Transferência</button>
        </form>

        <button class="back-btn" id="backBtn">Voltar</button>
        <div class="message" id="message"></div>
    </div>

    <script>
        const token = localStorage.getItem('token');
        if (!token) {
            window.location.href = '/login';
        }

        const form = document.getElementById('transferForm');
        const messageDiv = document.getElementById('message');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            messageDiv.textContent = '';
            messageDiv.className = 'message';

            const email = document.getElementById('email').value.trim();
            const amount = parseFloat(document.getElementById('amount').value);

            if (!email || !amount || amount <= 0) {
                messageDiv.textContent = 'Preencha todos os campos corretamente.';
                messageDiv.classList.add('error');
                return;
            }

            try {
                const response = await fetch('/api/transferencia', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({ email, amount })
                });

                const data = await response.json();

                if (response.ok) {
                    messageDiv.textContent = 'Transferência realizada com sucesso!';
                    messageDiv.classList.add('success');
                    form.reset();
                } else {
                    messageDiv.textContent = data.message || 'Erro ao realizar transferência.';
                    messageDiv.classList.add('error');
                }
            } catch (err) {
                console.error(err);
                messageDiv.textContent = 'Erro de conexão ao servidor.';
                messageDiv.classList.add('error');
            }
        });

        document.getElementById('backBtn').addEventListener('click', () => {
            window.location.href = '/dashboard'; // volta para o dashboard
        });
    </script>
</body>
</html>
