<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}

// Verificar se o usuário é reporter
if ($_SESSION['usuario_tipo'] !== 'reporter') {
    header('Location: dashboard.php');
    exit;
}

require_once '../includes/conexao.php';

$id_noticia = $_GET['id'] ?? 0;
$mensagem = '';
$tipo_mensagem = '';

// Buscar a notícia
$stmt = $pdo->prepare('SELECT * FROM noticias WHERE id = ? AND autor = ?');
$stmt->execute([$id_noticia, $_SESSION['usuario_id']]);
$noticia = $stmt->fetch();

if (!$noticia) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $conteudo = trim($_POST['noticia'] ?? '');
    $imagem = trim($_POST['imagem'] ?? '');

    if ($titulo === '' || $conteudo === '') {
        $mensagem = 'Título e conteúdo são obrigatórios.';
        $tipo_mensagem = 'erro';
    } else {
        $stmt = $pdo->prepare('UPDATE noticias SET titulo = ?, noticia = ?, imagem = ? WHERE id = ? AND autor = ?');
        if ($stmt->execute([$titulo, $conteudo, $imagem, $id_noticia, $_SESSION['usuario_id']])) {
            $mensagem = 'Notícia atualizada com sucesso!';
            $tipo_mensagem = 'sucesso';
            
            // Recarregar notícia
            $stmt = $pdo->prepare('SELECT * FROM noticias WHERE id = ? AND autor = ?');
            $stmt->execute([$id_noticia, $_SESSION['usuario_id']]);
            $noticia = $stmt->fetch();
            
            header('refresh:2;url=dashboard.php');
        } else {
            $mensagem = 'Erro ao atualizar notícia.';
            $tipo_mensagem = 'erro';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Notícia - EcoFinanças</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand"><img src="../imagens/Semfundo.png" alt="Logo" class="navbar-logo"> EcoFinanças</div>
        <div class="navbar-info">
            <a href="dashboard.php" class="btn btn-ghost btn-sm"><i class="fas fa-arrow-left"></i> Voltar</a>
            <a href="../logout.php" class="btn btn-ghost btn-sm"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-edit"></i> Editar Notícia</h2>
            </div>
            <div class="card-body">
                <?php if ($mensagem): ?>
                    <div class="alert alert-<?= $tipo_mensagem === 'sucesso' ? 'success' : 'error' ?>">
                        <i class="fas fa-<?= $tipo_mensagem === 'sucesso' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                        <?= htmlspecialchars($mensagem) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="noticia-form">
                    <div class="form-group">
                        <label for="titulo" class="form-label"><i class="fas fa-heading"></i> Título *</label>
                        <input type="text" id="titulo" name="titulo" class="form-control" value="<?= htmlspecialchars($noticia['titulo']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="imagem" class="form-label"><i class="fas fa-image"></i> URL da Imagem</label>
                        <input type="url" id="imagem" name="imagem" class="form-control" value="<?= htmlspecialchars($noticia['imagem'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="noticia" class="form-label"><i class="fas fa-align-left"></i> Conteúdo *</label>
                        <textarea id="noticia" name="noticia" class="form-control auto-resize-textarea" rows="6" required><?= htmlspecialchars($noticia['noticia']) ?></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Atualizar</button>
                        <a href="dashboard.php" class="btn btn-ghost"><i class="fas fa-times"></i> Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const textarea = document.getElementById('noticia');
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        }
    </script>
</body>
</html>
