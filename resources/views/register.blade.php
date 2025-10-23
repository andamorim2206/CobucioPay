<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #4e73df, #1cc88a);
        }

        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            padding: 40px 35px;
            width: 100%;
            max-width: 400px;
            transition: 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input {
            padding: 12px 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            transition: 0.2s;
        }

        input:focus {
            border-color: #4e73df;
            outline: none;
            box-shadow: 0 0 4px rgba(78, 115, 223, 0.3);
        }

        button {
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #4e73df;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.2s;
        }

        button:hover {
            background: #3752c5;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            font-weight: 500;
        }

        .error {
            color: #e74c3c;
        }

        .success {
            color: #1cc88a;
        }

        @media (max-width: 480px) {
            .card {
                padding: 30px 25px;
            }
        }
    </style>
</head>
<body>

    <div class="card">
        <h2>Crie sua conta</h2>
        <form id="registerForm">
            <input type="text" name="name" placeholder="Nome completo" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="password" placeholder="Senha" required>
            <input type="password" name="password_confirmation" placeholder="Confirmar senha" required>
            <button type="submit">Cadastrar</button>
        </form>
        <div class="message" id="message"></div>
    </div>

    <script>
        const form = document.getElementById('registerForm');
        const messageDiv = document.getElementById('message');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            messageDiv.textContent = '';
            messageDiv.className = 'message';

            const formData = new FormData(form);
            const data = {
                name: formData.get('name'),
                email: formData.get('email'),
                password: formData.get('password'),
                password_confirmation: formData.get('password_confirmation')
            };

            try {
                const response = await fetch('/api/cadastro', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    messageDiv.textContent = 'Usuário cadastrado com sucesso! Redirecionando...';
                    messageDiv.classList.add('success');
                    form.reset();

                    // redireciona após 1.5s
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 1500);
                } else {
                    let errors = '';
                    if (result.errors) {
                        errors = Object.values(result.errors).flat().join(', ');
                    } else if (result.error) {
                        errors = result.error;
                    }
                    messageDiv.textContent = errors || 'Erro ao cadastrar.';
                    messageDiv.classList.add('error');
                }
            } catch (err) {
                messageDiv.textContent = 'Falha na conexão com o servidor.';
                messageDiv.classList.add('error');
            }
        });
    </script>
</body>
</html>
