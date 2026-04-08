<?php
session_start();

// Verificar se está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/conexao.php';

$mensagem = '';
$tipo_mensagem = '';

// Verificar mensagens da sessão
if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    $tipo_mensagem = $_SESSION['tipo_mensagem'];
    unset($_SESSION['mensagem']);
    unset($_SESSION['tipo_mensagem']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    $titulo = trim($_POST['titulo'] ?? '');
    $conteudo = trim($_POST['noticia'] ?? '');
    $imagem = trim($_POST['imagem'] ?? '');
    $autor = $_SESSION['usuario_id'];

    if (empty($titulo) || empty($conteudo)) {
        $mensagem = 'Preencha todos os campos obrigatórios.';
        $tipo_mensagem = 'erro';
    } else {
        $stmt = $pdo->prepare('INSERT INTO noticias (titulo, noticia, imagem, autor, data) VALUES (?, ?, ?, ?, NOW())');
        if ($stmt->execute([$titulo, $conteudo, $imagem, $autor])) {
            $mensagem = 'Notícia publicada com sucesso!';
            $tipo_mensagem = 'sucesso';
            header('refresh:1');
        } else {
            $mensagem = 'Erro ao publicar notícia.';
            $tipo_mensagem = 'erro';
        }
    }
}



// Buscar notícias conforme o tipo de usuário
if ($_SESSION['usuario_tipo'] === 'admin') {
    // Admin vê todas as notícias
    $stmt = $pdo->prepare('SELECT n.*, u.nome as autor_nome FROM noticias n INNER JOIN usuarios u ON n.autor = u.id ORDER BY n.data DESC');
    $stmt->execute();
} else {
    // Reporter vê apenas suas notícias
    $stmt = $pdo->prepare('SELECT n.*, u.nome as autor_nome FROM noticias n INNER JOIN usuarios u ON n.autor = u.id WHERE n.autor = ? ORDER BY n.data DESC');
    $stmt->execute([$_SESSION['usuario_id']]);
}
$noticias = $stmt->fetchAll();

$total_noticias_usuario = count($noticias);
$total_noticias_geral = $pdo->query('SELECT COUNT(*) as total FROM noticias')->fetch()['total'];
$total_usuarios = $pdo->query('SELECT COUNT(*) as total FROM usuarios')->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - EcoFinanças</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css?v=<?php echo time(); ?>">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand"><img src="../imagens/logo-ecofinancas.png" alt="Logo" style="height: 45px; margin-right: 0.5rem;"> EcoFinanças</div>
        <div class="navbar-info">
            <a href="../index.php?view=portal" class="btn btn-ghost btn-sm"><i class="fas fa-home"></i> Início</a>
            <span class="usuario-nome"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
            <a href="../logout.php" class="btn btn-ghost btn-sm"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </div>
    </nav>

    <div class="dashboard-container">
        <!-- Header do Dashboard -->
        <div class="dashboard-header">
            <div class="dashboard-welcome">
                <div class="welcome-text">
                    <h1><i class="fas fa-tachometer-alt"></i> Meu perfil</h1>
                    <p>Bem-vindo de volta, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>! Seu tipo: <strong><?= ucfirst($_SESSION['usuario_tipo'] ?? 'leitor') ?></strong></p>
                </div>
                <div class="welcome-actions">
                    <?php if ($_SESSION['usuario_tipo'] === 'admin'): ?>
                        <!-- Botões exclusivos para Admin -->
                        <a href="gerenciar_usuarios.php" class="btn btn-primary"><i class="fas fa-users-cog"></i> Gerenciar Usuários</a>
                        <a href="gerenciar_noticias.php" class="btn btn-secondary"><i class="fas fa-newspaper"></i> Todas as Notícias</a>
                    <?php elseif ($_SESSION['usuario_tipo'] === 'reporter'): ?>
                        <a href="nova_noticia.php" class="btn btn-primary"><i class="fas fa-plus"></i> Nova Notícia</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if ($mensagem): ?>
            <div class="alert alert-<?= $tipo_mensagem === 'sucesso' ? 'success' : 'error' ?>">
                <i class="fas fa-<?= $tipo_mensagem === 'sucesso' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                <span><?= htmlspecialchars($mensagem) ?></span>
            </div>
        <?php endif; ?>

        <!-- Cards de Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-newspaper"></i></div>
                <div class="stat-info">
                    <h3><?= $total_noticias_usuario ?></h3>
                    <p>Minhas Notícias</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-globe"></i></div>
                <div class="stat-info">
                    <h3><?= $total_noticias_geral ?></h3>
                    <p>Total no Portal</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <h3><?= $total_usuarios ?></h3>
                    <p>Usuários</p>
                </div>
            </div>
        </div>

        <!-- Painel de Perfil do Usuário -->
        <div class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-user-circle"></i> Perfil do Usuário</h2>
                <a href="editar_usuario.php" class="btn btn-primary btn-sm"><i class="fas fa-user-edit"></i> Editar Perfil</a>
            </div>
            <div class="card-body">
                <div class="profile-card-top">
                    <div class="profile-avatar-lg"><i class="fas fa-user"></i></div>
                    <div class="profile-details">
                        <h3><?= htmlspecialchars($_SESSION['usuario_nome']) ?></h3>
                        <p><strong>E-mail:</strong> <?= htmlspecialchars($_SESSION['usuario_email'] ?? 'N/A') ?></p>
                        <p><strong>Entrou em:</strong> <?= isset($_SESSION['usuario_criacao']) ? date('d/m/Y H:i', strtotime($_SESSION['usuario_criacao'])) : 'Não definido' ?></p>
                    </div>
                </div>
                <div class="profile-overview">
                    <div class="profile-field"><strong>Total de notícias:</strong> <?= $total_noticias_usuario ?></div>
                    <div class="profile-field"><strong>Total no portal:</strong> <?= $total_noticias_geral ?></div>
                    <div class="profile-field"><strong>Usuários:</strong> <?= $total_usuarios ?></div>
                </div>
                <p class="profile-mensagem">Use os botões abaixo em cada notícia para <strong>editar</strong> ou <strong>excluir</strong>. Para publicar novo conteúdo, vá em <a href="nova_noticia.php">Nova Notícia</a>.</p>
            </div>
        </div>

        <!-- Lista de Minhas Notícias (para reporter e admin) -->
        <?php if ($_SESSION['usuario_tipo'] === 'reporter' || $_SESSION['usuario_tipo'] === 'admin'): ?>
        <div class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-list"></i> Minhas Publicações</h2>
                <span class="badge"><?= count($noticias) ?> notícias</span>
            </div>
            <div class="card-body">
                <?php if (empty($noticias)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h3>Nenhuma notícia publicada</h3>
                        <p>Seja o primeiro a compartilhar uma notícia!</p>
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
                                            <span class="noticia-data"><i class="fas fa-calendar"></i> <?= date('d/m/Y H:i', strtotime($noticia['data'])) ?></span>
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
                                        <form method="POST" action="excluir_noticia.php" class="inline-form" onsubmit="return confirm('Tem certeza que deseja excluir esta notícia?')">
                                            <input type="hidden" name="id" value="<?= $noticia['id'] ?>">
                                            <button type="submit" class="btn btn-ghost btn-sm text-error"><i class="fas fa-trash"></i> Excluir</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-eye"></i> Modo Leitor</h2>
            </div>
            <div class="card-body">
                <div class="empty-state">
                    <i class="fas fa-book"></i>
                    <h3>Bem-vindo ao modo de leitura</h3>
                    <p>Seu tipo de usuário é <strong><?= ucfirst($_SESSION['usuario_tipo']) ?></strong>. Você pode visualizar notícias públicas no portal.</p>
                    <a href="../noticias.php" class="btn btn-primary" style="margin-top: 1rem;"><i class="fas fa-newspaper"></i> Ir para Notícias</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script>
        // Handle image load errors
        document.querySelectorAll('.noticia-imagem-erro').forEach(img => {
            img.addEventListener('error', function() {
                this.classList.add('imagem-erro');
            });
        });
    </script>
</body>
</html>
