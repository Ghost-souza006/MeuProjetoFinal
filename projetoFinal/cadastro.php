<?php
session_start();

// Se já estiver logado, redireciona para o dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: admin/dashboard.php');
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    $tipo = $_POST['tipo'] ?? 'leitor';
    
    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = 'Todos os campos são obrigatórios.';
    } elseif (strlen($nome) < 3) {
        $erro = 'O nome deve ter pelo menos 3 caracteres.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($senha !== $confirmar_senha) {
        $erro = 'As senhas não coincidem.';
    } elseif (!in_array($tipo, ['leitor', 'reporter'])) {
        $erro = 'Tipo de usuário inválido.';
    } else {
        require_once 'includes/conexao.php';
        
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $erro = 'Este e-mail já está cadastrado.';
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
            
            if ($stmt->execute([$nome, $email, $senha_hash, $tipo])) {
                $sucesso = 'Cadastro realizado com sucesso! Redirecionando...';
                header('refresh:2;url=login.php');
            } else {
                $erro = 'Erro ao cadastrar. Tente novamente.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - EcoFinanças</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="cadastro-container">
        <div class="cadastro-card">
            <div class="cadastro-header">
                <div style="text-align: center; margin-bottom: 25px;">
                    <img src="imagens/Semfundo.png" alt="Logo" class="login-logo">
                </div>
                <h1>Criar Conta</h1>
                <p>Junte-se ao EcoFinanças e organize suas finanças</p>
            </div>

            <?php if ($erro): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= htmlspecialchars($erro) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($sucesso): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?= htmlspecialchars($sucesso) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="cadastro-form">
                <div class="form-group">
                    <label for="nome" class="form-label">
                        <i class="fas fa-user"></i>
                        Nome Completo
                    </label>
                    <input type="text" id="nome" name="nome" class="form-control" 
                        placeholder="Seu nome completo" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required minlength="3">
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        E-mail
                    </label>
                    <input type="email" id="email" name="email" class="form-control" 
                        placeholder="seu@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="senha" class="form-label">
                        <i class="fas fa-lock"></i>
                        Senha
                    </label>
                    <div class="password-input">
                        <input type="password" id="senha" name="senha" class="form-control" 
                            placeholder="Mínimo 6 caracteres" required minlength="6" onkeyup="verificarForcaSenha()">
                        <button type="button" class="toggle-password" onclick="toggleSenha('senha', 'olho1')">
                            <i class="fas fa-eye" id="olho1"></i>
                        </button>
                    </div>
                    <div class="senha-forte">
                        <span id="texto-forca">Força da senha</span>
                        <div class="barra"><div class="progresso" id="progresso-senha"></div></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirmar_senha" class="form-label">
                        <i class="fas fa-lock"></i>
                        Confirmar Senha
                    </label>
                    <div class="password-input">
                        <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-control" 
                            placeholder="Repita a senha" required>
                        <button type="button" class="toggle-password" onclick="toggleSenha('confirmar_senha', 'olho2')">
                            <i class="fas fa-eye" id="olho2"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="tipo" class="form-label">
                        <i class="fas fa-user-tag"></i>
                        Tipo de Usuário
                    </label>
                    <select id="tipo" name="tipo" class="form-control">
                        <option value="leitor">Leitor</option>
                        <option value="reporter">Repórter (Publicar Notícias)</option>
                    </select>
                </div>

                <div class="termos">
                    <input type="checkbox" id="termos" name="termos" required>
                    <label for="termos">
                        Li e concordo com os <a href="#">Termos de Uso</a> e <a href="#">Política de Privacidade</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-success btn-cadastro">
                    <i class="fas fa-user-plus"></i> Criar Conta
                </button>
            </form>

            <div class="cadastro-footer">
                <p>Já tem uma conta? <a href="login.php">Fazer Login</a></p>
            </div>
        </div>
    </div>

    <script>
        function toggleSenha(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function verificarForcaSenha() {
            const senha = document.getElementById('senha').value;
            const progresso = document.getElementById('progresso-senha');
            const texto = document.getElementById('texto-forca');
            let forca = 0;
            if (senha.length >= 6) forca++;
            if (senha.length >= 10) forca++;
            if (/[A-Z]/.test(senha)) forca++;
            if (/[0-9]/.test(senha)) forca++;
            if (/[^A-Za-z0-9]/.test(senha)) forca++;
            progresso.className = 'progresso';
            if (forca <= 2) {
                progresso.classList.add('fraca');
                texto.textContent = 'Senha fraca';
                texto.style.color = '#e53935';
            } else if (forca <= 4) {
                progresso.classList.add('media');
                texto.textContent = 'Senha média';
                texto.style.color = '#fb8c00';
            } else {
                progresso.classList.add('forte');
                texto.textContent = 'Senha forte';
                texto.style.color = '#43a047';
            }
        }
    </script>
</body>
</html>