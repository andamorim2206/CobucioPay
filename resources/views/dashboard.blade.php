<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            height: 100vh;
            justify-content: center;
            align-items: center;
            background-color: #f0f2f5;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        button {
            padding: 10px 20px;
            background-color: #ff4b5c;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #e03e4f;
        }
    </style>
</head>
<body>
    <h1>Bem-vindo ao Dashboard!</h1>

    <button id="logoutBtn">Logout</button>

    <script>
        document.getElementById('logoutBtn').addEventListener('click', async () => {
            try {
                const response = await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`, // se estiver usando token
                    },
                });

                if (response.ok) {
                    // Remove o token e redireciona para login
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                } else {
                    const data = await response.json();
                    alert(data.message || 'Erro ao fazer logout');
                }
            } catch (error) {
                console.error('Erro ao fazer logout:', error);
                alert('Erro de conexão ao tentar sair.');
            }
        });
    </script>
</body>
</html>
