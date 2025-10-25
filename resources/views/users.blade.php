<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuários</title>
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
            max-width: 600px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .user-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 400px;
            overflow-y: auto;
        }

        .user-card {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px;
            text-align: left;
            transition: transform 0.2s ease, background-color 0.2s;
        }

        .user-card:hover {
            background-color: #e9ecef;
            transform: scale(1.01);
        }

        .user-name {
            font-weight: bold;
            color: #333;
        }

        .user-email {
            color: #555;
            font-size: 0.95rem;
        }

        .back-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 15px;
            font-size: 0.95rem;
            color: red;
        }

        .loading {
            font-size: 1rem;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Lista de Usuários</h2>
        <div id="loading" class="loading">Carregando usuários...</div>
        <div class="user-list" id="userList"></div>
        <div id="message" class="message"></div>
        <button class="back-btn" id="backBtn">Voltar</button>
    </div>

    <script>
        const token = localStorage.getItem('token');
        if (!token) {
            window.location.href = '/login';
        }

        const userList = document.getElementById('userList');
        const messageDiv = document.getElementById('message');
        const loadingDiv = document.getElementById('loading');

        async function carregarUsuarios() {
            try {
                const response = await fetch('/api/usuarios', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                const data = await response.json();

                loadingDiv.style.display = 'none';

                if (response.ok && data.user && data.user.length > 0) {
                    data.user.forEach(u => {
                        const card = document.createElement('div');
                        card.classList.add('user-card');

                        card.innerHTML = `
                            <div class="user-name">${u.name}</div>
                            <div class="user-email">${u.email}</div>
                        `;

                        userList.appendChild(card);
                    });
                } else {
                    messageDiv.textContent = 'Nenhum usuário encontrado.';
                }

            } catch (error) {
                console.error('Erro ao carregar usuários:', error);
                loadingDiv.style.display = 'none';
                messageDiv.textContent = 'Erro ao carregar usuários.';
            }
        }

        carregarUsuarios();

        document.getElementById('backBtn').addEventListener('click', () => {
            window.location.href = '/dashboard';
        });
    </script>
</body>
</html>
