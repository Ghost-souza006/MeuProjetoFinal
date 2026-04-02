<?php
require_once 'includes/conexao.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare('SELECT n.*, u.nome as autor_nome, u.email as autor_email FROM noticias n JOIN usuarios u ON n.autor = u.id WHERE n.id = ?');
$stmt->execute([$id]);
$noticia = $stmt->fetch();

if (!$noticia) {
    header('Location: noticias.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($noticia['titulo']) ?> - EcoFinanças</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar">
        <div class="navbar-brand"><img src="imagens/Semfundo.png" alt="Logo" class="navbar-logo"> EcoFinanças</div>
        <div class="navbar-info">
            <a href="index.php?view=portal" class="btn btn-ghost btn-sm"><i class="fas fa-home"></i> Início</a>
            <a href="noticias.php" class="btn btn-ghost btn-sm"><i class="fas fa-newspaper"></i> Todas as Notícias</a>
            <a href="admin/dashboard.php" class="btn btn-ghost btn-sm"><i class="fas fa-tachometer-alt"></i>Meu perfil </a>
        </div>
    </nav>

    <article class="noticia-container">
        <div class="noticia-wrapper">
            <!-- Breadcrumb -->
            <div class="breadcrumb">
               
                <span><?= htmlspecialchars(mb_strimwidth($noticia['titulo'], 0, 50, '...')) ?></span>
            </div>

            <!-- Cabeçalho da Notícia -->
            <div class="noticia-header-full">
                <?php if ($noticia['imagem']): ?>
                    <div class="noticia-imagem-full">
                        <img src="<?= htmlspecialchars($noticia['imagem']) ?>"
                            alt="<?= htmlspecialchars($noticia['titulo']) ?>" class="noticia-imagem-erro">
                    </div>
                <?php endif; ?>

                <div class="noticia-titulo-full">
                    <h1><?= htmlspecialchars($noticia['titulo']) ?></h1>
                </div>

                <div class="noticia-meta-full">
                    <div class="meta-item">
                        <i class="fas fa-user-circle"></i>
                        <strong>Autor:</strong>
                        <span><?= htmlspecialchars($noticia['autor_nome']) ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <strong>Data:</strong>
                        <span><?= date('d/m/Y \à\s H:i', strtotime($noticia['data'])) ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-file-word"></i>
                        <strong>Categoria:</strong>
                        <span>Finanças Pessoais</span>
                    </div>
                </div>
            </div>

            <!-- Conteúdo da Notícia -->
            <div class="noticia-conteudo-full">
                <?= nl2br(htmlspecialchars($noticia['noticia'])) ?>
            </div>

            <!-- Informações do Autor -->
            <div class="autor-info">
                <div class="autor-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="autor-details">
                    <h3><?= htmlspecialchars($noticia['autor_nome']) ?></h3>
                    <p>Autor de notícias sobre economia e finanças pessoais no portal EcoFinanças.</p>
                </div>
            </div>

            <!-- Ações -->
            <div class="noticia-actions">
                <a href="noticias.php" class="btn btn-success"><i class="fas fa-arrow-left"></i> Voltar para
                    Notícias</a>
                <a href="index.php?view=portal" class="btn btn-primary"><i class="fas fa-home"></i> Ir para Página Inicial</a>
            </div>

            <!-- Notícias Relacionadas -->
            <div class="noticias-relacionadas">
                <h3><i class="fas fa-link"></i> Notícias Relacionadas</h3>
                <?php
                $stmt = $pdo->prepare('SELECT n.*, u.nome as autor_nome FROM noticias n JOIN usuarios u ON n.autor = u.id WHERE n.id != ? ORDER BY n.data DESC LIMIT 3');
                $stmt->execute([$id]);
                $relacionadas = $stmt->fetchAll();

                if ($relacionadas):
                    ?>
                    <div class="noticias-grid-pequeno">
                        <?php foreach ($relacionadas as $rel): ?>
                            <a href="noticia.php?id=<?= $rel['id'] ?>" class="noticia-card-pequeno">
                                <div class="noticia-card-titulo">
                                    <?= htmlspecialchars(mb_strimwidth($rel['titulo'], 0, 60, '...')) ?>
                                </div>
                                <div class="noticia-card-meta">
                                    <span><i class="fas fa-user"></i> <?= htmlspecialchars($rel['autor_nome']) ?></span>
                                    <span><i class="fas fa-calendar"></i>
                                        <?= date('d/m/Y', strtotime($rel['data'])) ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="texto-vazio">Nenhuma outra notícia disponível no momento.</p>
                <?php endif; ?>
            </div>
        </div>
    </article>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <img src="imagens/Semfundo.png" alt="Logo" class="navbar-logo">
                    <span>EcoFinanças</span>
                </div>
                <div class="footer-links">
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 EcoFinanças. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
</body>

</html>