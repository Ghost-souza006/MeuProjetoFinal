<?php
session_start();

// Verificar se está logado e é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/conexao.php';

$mensagem = '';
$tipo_mensagem = '';

// Buscar todas as notícias com informações do autor
$stmt = $pdo->query('
    SELECT n.*, u.nome as autor_nome 
    FROM noticias n 
    INNER JOIN usuarios u ON n.autor = u.id 
    ORDER BY n.data DESC
');
$noticias = $stmt->fetchAll();

$total_noticias = count($noticias);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Notícias - EcoFinanças</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css?v=<?php echo time(); ?>">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <img src="../imagens/logo-ecofinancas.png" alt="Logo" style="height: 45px; margin-right: 0.5rem;">
            EcoFinanças
        </div>
        <div class="navbar-info">
            <a href="dashboard.php" class="btn btn-ghost btn-sm"><i class="fas fa-arrow-left"></i> Voltar</a>
            <span class="usuario-nome"><i class="fas fa-user-shield"></i> <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
            <a href="../logout.php" class="btn btn-ghost btn-sm"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </div>
    </nav>

    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="dashboard-welcome">
                <div class="welcome-text">
                    <h1><i class="fas fa-newspaper"></i> Gerenciar Notícias</h1>
                    <p>Visualize, edite e exclua todas as notícias do portal</p>
                </div>
                <div class="welcome-actions">
                    <a href="nova_noticia.php" class="btn btn-primary"><i class="fas fa-plus"></i> Nova Notícia</a>
                </div>
            </div>
        </div>

        <?php if ($mensagem): ?>
            <div class="alert alert-<?= $tipo_mensagem === 'sucesso' ? 'success' : 'error' ?>">
                <i class="fas fa-<?= $tipo_mensagem === 'sucesso' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                <span><?= htmlspecialchars($mensagem) ?></span>
            </div>
        <?php endif; ?>

        <!-- Lista de Notícias -->
        <div class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-list"></i> Todas as Notícias</h2>
                <span class="badge"><?= $total_noticias ?> notícias</span>
            </div>
            <div class="card-body">
                <?php if (empty($noticias)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h3>Nenhuma notícia publicada</h3>
                        <p>Ainda não há notícias no portal.</p>
                    </div>
                <?php else: ?>
                    <div class="noticias-grid">
                        <?php foreach ($noticias as $noticia): ?>
                            <div class="noticia-card">
                                <?php if ($noticia['imagem']): ?>
                                    <div class="noticia-imagem">
                                        <img src="<?= htmlspecialchars($noticia['imagem']) ?>" alt="<?= htmlspecialchars($noticia['titulo']) ?>" class="noticia-imagem-erro">
                                    </div>
                                <?php endif; ?>
                                <div class="noticia-conteudo">
                                    <div class="noticia-header">
                                        <h3 class="noticia-titulo"><?= htmlspecialchars($noticia['titulo']) ?></h3>
                                        <div class="noticia-meta">
                                            <span class="noticia-autor">
                                                <i class="fas fa-user"></i> <?= htmlspecialchars($noticia['autor_nome']) ?>
                                            </span>
                                            <span class="noticia-data">
                                                <i class="fas fa-calendar"></i> <?= date('d/m/Y H:i', strtotime($noticia['data'])) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <p class="noticia-texto"><?= nl2br(htmlspecialchars(mb_strimwidth($noticia['noticia'], 0, 150, '...'))) ?></p>
                                    <div class="noticia-footer">
                                        <a href="../noticia.php?id=<?= $noticia['id'] ?>" class="btn btn-ghost btn-sm" target="_blank">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        <a href="editar_noticia.php?id=<?= $noticia['id'] ?>" class="btn btn-ghost btn-sm">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form method="POST" action="excluir_noticia.php" class="inline-form" 
                                              onsubmit="return confirm('⚠️ TEM CERTEZA que deseja excluir a notícia:\n\n&quot;<?= htmlspecialchars($noticia['titulo']) ?>&quot;\n\nEsta ação não pode ser desfeita!')">
                                            <input type="hidden" name="id" value="<?= $noticia['id'] ?>">
                                            <button type="submit" class="btn btn-ghost btn-sm text-error">
                                                <i class="fas fa-trash"></i> Excluir
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.noticia-imagem-erro').forEach(img => {
            img.addEventListener('error', function() {
                this.classList.add('imagem-erro');
            });
        });
    </script>
</body>
</html>
