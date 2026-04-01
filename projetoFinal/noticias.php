<?php
require_once 'conexao.php';
$noticias = $pdo->query('SELECT n.*, u.nome as autor_nome FROM noticias n JOIN usuarios u ON n.autor_id = u.id ORDER BY n.data_publicacao DESC')->fetchAll();
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar"><a href="index.php">Início</a> | <a href="dashboard.php">Dashboard</a> | <a href="login.php">Login</a></nav>
    <main class="page-container">
        <h1>Notícias Públicas</h1>
        <?php if (empty($noticias)): ?>
            <p>Nenhuma notícia publicada ainda.</p>
        <?php else: ?>
            <?php foreach ($noticias as $n): ?>
                <article class="noticia-card">
                    <h2><?= htmlspecialchars($n['titulo']) ?></h2>
                    <p><strong>Por:</strong> <?= htmlspecialchars($n['autor_nome']) ?> | <?= date('d/m/Y H:i', strtotime($n['data_publicacao'])) ?></p>
                    <p><?= nl2br(htmlspecialchars($n['conteudo'])) ?></p>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>