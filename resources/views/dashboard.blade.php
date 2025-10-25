<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        header {
            width: 100%;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            display: flex;
            justify-content: flex-end;
        }

        #logoutBtn {
            background-color: #ff4b5c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #logoutBtn:hover {
            background-color: #e03e4f;
        }

        main {
            width: 90%;
            max-width: 600px;
            margin-top: 30px;
            text-align: center;
        }

        .user-info {
            margin-bottom: 25px;
        }

        .user-info h2 {
            margin: 0;
            font-size: 1.6rem;
            color: #333;
        }

        .user-info p {
            margin: 5px 0 0;
            font-size: 1.2rem;
            color: #555;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .actions button {
            flex: 1;
            margin: 0 5px;
            padding: 10px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .actions button:hover {
            background-color: #0062cc;
        }

        .extrato {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            padding: 15px;
            text-align: left;
        }

        .extrato h3 {
            text-align: center;
            margin-top: 0;
        }

        .transacao {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }

        .transacao:last-child {
            border-bottom: none;
        }

        .transacao-info {
            display: flex;
            flex-direction: column;
        }

        .transacao-info span {
            font-size: 14px;
        }

        .valor {
            font-weight: bold;
        }

        .deposit {
            color: green;
        }

        .transfer {
            color: red;
        }

        .reversal {
            color: #007bff;
        }

        .estornar-btn {
            background-color: #ff4b5c;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 6px 10px;
            cursor: pointer;
        }

        .estornar-btn:hover {
            background-color: #e03e4f;
        }

        .loading {
            text-align: center;
            margin-top: 20px;
            color: #555;
        }
    </style>
</head>
<body>
    <header>
        <button id="logoutBtn">Logout</button>
    </header>

    <main>
        <div class="user-info">
            <h2 id="userName">Carregando...</h2>
            <p id="userBalance">Saldo: R$ 0,00</p>
        </div>

        <div class="actions">
            <button id="btnTransfer">Transferências</button>
            <button id="btnUsuarios">Listagem de Usuários</button>
        </div>

        <div class="extrato">
            <h3>Extrato</h3>
            <div id="extratoContainer" class="loading">Carregando extrato...</div>
        </div>
    </main>

    <script>
        const token = localStorage.getItem('token');
        if (!token) {
            window.location.href = '/login';
        }

        async function carregarUsuario() {
            try {
                const res = await fetch('/api/usuario', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (!res.ok) throw new Error('Erro ao buscar usuário');

                const data = await res.json();
                const user = data.user;

                document.getElementById('userName').textContent = user.name;
                document.getElementById('userBalance').textContent = `Saldo: R$ ${user.wallet.balance}`;
            } catch (err) {
                console.error(err);
                alert('Erro ao carregar informações do usuário.');
            }
        }

        async function carregarExtrato() {
            try {
                const res = await fetch('/api/extrato', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (!res.ok) throw new Error('Erro ao buscar extrato');

                const data = await res.json();
                const container = document.getElementById('extratoContainer');
                container.innerHTML = '';

                if (data.transactions.length === 0) {
                    container.innerHTML = '<p>Nenhuma transação encontrada.</p>';
                    return;
                }

                data.transactions.forEach(tx => {
                    const div = document.createElement('div');
                    div.classList.add('transacao');

                    let tipoClasse = '';
                    let tipoTexto = '';

                    if (tx.type === 'deposit') {
                        tipoClasse = 'deposit';
                        tipoTexto = 'Depósito';
                    } else if (tx.type === 'transfer') {
                        tipoClasse = 'transfer';
                        tipoTexto = 'Transferência';
                    } else if (tx.type === 'reversal') {
                        tipoClasse = 'reversal';
                        tipoTexto = 'Estorno';
                    }

                    div.innerHTML = `
                        <div class="transacao-info">
                            <span>${tipoTexto}</span>
                            <span class="valor ${tipoClasse}">R$ ${tx.amount}</span>
                            <span>Status: ${tx.status}</span>
                        </div>
                        ${tx.type === 'transfer' ? `<button class="estornar-btn" onclick="estornar('${tx.id}')">Estornar</button>` : ''}
                    `;

                    container.appendChild(div);
                });
            } catch (err) {
                console.error(err);
                alert('Erro ao carregar extrato.');
            }
        }

        async function estornar(id) {
            if (!confirm('Deseja realmente estornar essa transferência?')) return;

            try {
                const res = await fetch(`/api/estorno/${id}`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    }
                });

                const data = await res.json();

                if (res.ok) {
                    alert('Transferência estornada com sucesso!');
                    carregarExtrato();
                } else {
                    alert(data.message || 'Erro ao estornar.');
                }
            } catch (err) {
                console.error(err);
                alert('Erro ao conectar com o servidor.');
            }
        }

        document.getElementById('logoutBtn').addEventListener('click', async () => {
            try {
                const response = await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                });

                if (response.ok) {
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

        document.getElementById('btnTransfer').addEventListener('click', () => {
            window.location.href = '/transfer';
        });

        document.getElementById('btnUsuarios').addEventListener('click', () => {
            window.location.href = '/usuarios';
        });

        // Inicializa ao abrir a tela
        carregarUsuario();
        carregarExtrato();
    </script>
</body>
</html>
