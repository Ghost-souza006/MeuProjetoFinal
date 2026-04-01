<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'conexao.php';

$usuario_id = $_SESSION['usuario_id'];

$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao']) && $_POST['acao'] === 'excluir') {
        // Excluir notícias do usuário antes de excluir usuário (opcional)
        $stmt = $pdo->prepare('DELETE FROM noticias WHERE autor_id = ?');
        $stmt->execute([$usuario_id]);

        $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id = ?');
        if ($stmt->execute([$usuario_id])) {
            session_unset();
            session_destroy();
            header('Location: login.php');
            exit;
        } else {
            $mensagem = 'Erro ao excluir o perfil. Tente novamente.';
            $tipo_mensagem = 'erro';
        }
    }

    if (isset($_POST['acao']) && $_POST['acao'] === 'atualizar') {
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        if ($nome === '' || $email === '') {
            $mensagem = 'Nome e e-mail são obrigatórios.';
            $tipo_mensagem = 'erro';
        } else {
            if ($senha !== '') {
                $hashSenha = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?');
                $atualizou = $stmt->execute([$nome, $email, $hashSenha, $usuario_id]);
            } else {
                $stmt = $pdo->prepare('UPDATE usuarios SET nome = ?, email = ? WHERE id = ?');
                $atualizou = $stmt->execute([$nome, $email, $usuario_id]);
            }

            if ($atualizou) {
                $_SESSION['usuario_nome'] = $nome;
                $_SESSION['usuario_email'] = $email;
                $mensagem = 'Perfil atualizado com sucesso!';
                $tipo_mensagem = 'sucesso';
            } else {
                $mensagem = 'Erro ao atualizar perfil. Tente novamente.';
                $tipo_mensagem = 'erro';
            }
        }
    }
}

// Buscar dados atuais do usuário
$stmt = $pdo->prepare('SELECT nome, email FROM usuarios WHERE id = ?');
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

$nome = $usuario['nome'];
$email = $usuario['email'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - EcoFinanças</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand"><i class="fas fa-wallet"></i> EcoFinanças</div>
        <div class="navbar-info">
            <a href="dashboard.php" class="btn btn-ghost btn-sm"><i class="fas fa-arrow-left"></i> Voltar</a>
            <a href="logout.php" class="btn btn-ghost btn-sm"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </div>
    </nav>

    <main class="page-container">
        <h1>Editar Perfil</h1>

        <?php if ($mensagem): ?>
            <div class="alert alert-<?= $tipo_mensagem === 'sucesso' ? 'success' : 'error' ?>">
                <i class="fas fa-<?= $tipo_mensagem === 'sucesso' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="cadastro-form" style="max-width: 500px;">
            <div class="form-group">
                <label for="nome" class="form-label"><i class="fas fa-user"></i> Nome</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nome) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email" class="form-label"><i class="fas fa-envelope"></i> E-mail</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="senha" class="form-label"><i class="fas fa-lock"></i> Nova senha (deixe em branco para manter)</label>
                <input type="password" id="senha" name="senha" class="form-control">
            </div>
            <div class="form-actions">
                <button type="submit" name="acao" value="atualizar" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Alterações</button>
            </div>
        </form>

        <div style="margin-top: 1.5rem;">
            <form method="POST" action="" onsubmit="return confirm('Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.');">
                <input type="hidden" name="acao" value="excluir">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Excluir Perfil</button>
            </form>
        </div>
    </main>
</body>
</html>
