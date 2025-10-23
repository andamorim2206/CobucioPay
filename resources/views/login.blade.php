<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        .links {
            display: flex;
            justify-content: space-between;
            margin-top: 12px;
            font-size: 14px;
        }

        .links a {
            color: #4e73df;
            text-decoration: none;
            transition: 0.2s;
        }

        .links a:hover {
            text-decoration: underline;
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
        <h2>Bem-vindo</h2>
        <form id="loginForm">
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>

        <div class="links">
            <a href="/register">Criar conta</a>
            <a href="#">Esqueci minha senha</a>
        </div>

        <div class="message" id="message"></div>
    </div>

    <script>
        const form = document.getElementById('loginForm');
        const messageDiv = document.getElementById('message');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            messageDiv.textContent = '';
            messageDiv.className = 'message';

            const formData = new FormData(form);
            const data = {
                email: formData.get('email'),
                password: formData.get('password')
            };

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    messageDiv.textContent = 'Login realizado com sucesso!';
                    messageDiv.classList.add('success');

                    // Armazena o token localmente (opcional)
                    localStorage.setItem('token', result.access_token);

                    // Redireciona para o dashboard
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 800);
                } else {
                    let errors = result.error || 'Credenciais inválidas.';
                    messageDiv.textContent = errors;
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
