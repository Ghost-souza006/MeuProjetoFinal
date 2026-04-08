<?php
require_once 'includes/conexao.php';
$noticias = $pdo->query('SELECT n.*, u.nome as autor_nome FROM noticias n JOIN usuarios u ON n.autor = u.id ORDER BY n.data DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notícias - EcoFinanças</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand"><img src="imagens/logo-ecofinancas.png" alt="Logo" style="height: 45px; margin-right: 0.5rem;"> EcoFinanças</div>
        <div class="navbar-info">
            <a href="index.php?view=portal" class="btn btn-ghost btn-sm"><i class="fas fa-home"></i> Início</a>
            <a href="admin/dashboard.php" class="btn btn-ghost btn-sm"><i class="fas fa-tachometer-alt"></i> Meu perfil</a>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-newspaper"></i> Notícias Públicas</h2>
                <span class="badge"><?= count($noticias) ?> notícias</span>
            </div>
            <div class="card-body">
                <?php if (empty($noticias)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h3>Nenhuma notícia publicada</h3>
                        <p>Volte em breve para ver as últimas notícias.</p>
                    </div>
                <?php else: ?>
                    <div class="noticias-grid">
                        <?php foreach ($noticias as $n): ?>
                            <a href="noticia.php?id=<?= $n['id'] ?>" class="noticia-card noticia-card-link">
                                <?php if ($n['imagem']): ?>
                                    <div class="noticia-imagem">
                                        <img src="<?= htmlspecialchars($n['imagem']) ?>" alt="<?= htmlspecialchars($n['titulo']) ?>" class="noticia-imagem-erro">
                                    </div>
                                <?php endif; ?>
                                <div class="noticia-conteudo">
                                    <div class="noticia-header">
                                        <h3 class="noticia-titulo"><?= htmlspecialchars($n['titulo']) ?></h3>
                                        <div class="noticia-meta">
                                            <span class="noticia-autor"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($n['autor_nome']) ?></span>
                                            <span class="noticia-data"><i class="fas fa-calendar"></i> <?= date('d/m/Y H:i', strtotime($n['data'])) ?></span>
                                        </div>
                                    </div>
                                    <p class="noticia-texto"><?= nl2br(htmlspecialchars(mb_strimwidth($n['noticia'], 0, 200, '...'))) ?></p>
                                    <div class="noticia-leitura">
                                        <span class="btn btn-sm btn-primary"><i class="fas fa-book-open"></i> Ler Mais</span>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
