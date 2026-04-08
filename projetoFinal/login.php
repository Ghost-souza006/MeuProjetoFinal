<?php
session_start();

// Se já estiver logado, redireciona para o dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: admin/dashboard.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    
    if (empty($email) || empty($senha) || empty($tipo)) {
        $erro = 'Preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } elseif (!in_array($tipo, ['admin', 'reporter', 'leitor'])) {
        $erro = 'Tipo de usuário inválido.';
    } else {
        require_once 'includes/conexao.php';
        
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND tipo = ?");
        $stmt->execute([$email, $tipo]);
        $usuario = $stmt->fetch();
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];
            
            header('Location: admin/dashboard.php');
            exit;
        } else {
            $erro = 'E-mail, senha ou tipo de usuário incorretos.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EcoFinanças</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="cadastro-container">
        <div class="cadastro-card">
            <div class="cadastro-header">
                <div style="text-align: center; margin-bottom: 25px;">
                    <img src="imagens/logo-ecofinancas.png" alt="Logo" style="width: 100px; height: 100px; object-fit: contain;">
                </div>
                <h1>Bem-vindo de volta!</h1>
                <p>Faça login para acessar sua conta</p>
            </div>

            <?php if ($erro): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= htmlspecialchars($erro) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="cadastro-form">
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        E-mail
                    </label>
                    <input type="email" id="email" name="email" class="form-control" 
                        placeholder="seu@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="senha" class="form-label">
                        <i class="fas fa-lock"></i>
                        Senha
                    </label>
                    <div class="password-input">
                        <input type="password" id="senha" name="senha" class="form-control" 
                            placeholder="Sua senha" required autocomplete="current-password">
                        <button type="button" class="toggle-password" onclick="toggleSenha()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="tipo" class="form-label">
                        <i class="fas fa-user-tag"></i>
                        Tipo de usuário
                    </label>
                    <select id="tipo" name="tipo" class="form-control" required>
                        <option value="">Selecione o tipo de usuário</option>
                        <option value="admin">Administrador</option>
                        <option value="reporter">Repórter</option>
                        <option value="leitor">Leitor</option>
                    </select>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="lembrar">
                        <span>Lembrar-me</span>
                    </label>
                    <a href="#" class="forgot-password">Esqueceu a senha?</a>
                </div>

                <button type="submit" class="btn btn-success btn-cadastro">
                    <i class="fas fa-sign-in-alt"></i> Entrar
                </button>
            </form>

            <div class="cadastro-footer">
                <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
            </div>
        </div>
    </div>

    <script>
        function toggleSenha() {
            const senhaInput = document.getElementById('senha');
            const toggleIcon = document.getElementById('toggleIcon');
            if (senhaInput.type === 'password') {
                senhaInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                senhaInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        document.querySelector('form').addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Entrando...';
        });
    </script>
</body>
</html>