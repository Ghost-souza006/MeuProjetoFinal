<?php
session_start();

// Quando acessa index como usuário logado através do botão "Início"
// o parâmetro ?view=portal permite ver a página inicial em vez de dashboard.
if (isset($_SESSION['usuario_id']) && (!isset($_GET['view']) || $_GET['view'] !== 'portal')) {
    header('Location: dashboard.php');
    exit;
}

$isLogado = isset($_SESSION['usuario_id']);
$nomeUsuario = $isLogado ? htmlspecialchars($_SESSION['usuario_nome']) : '';
$inicialUsuario = $isLogado ? mb_strtoupper(mb_substr($nomeUsuario, 0, 1, 'UTF-8'), 'UTF-8') : '';

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoFinanças - Portal de Notícias Financeiras</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Header -->
    <header class="hero-header">
        <nav class="navbar">
            <div class="navbar-brand">
                <i class="fas fa-money-bill"></i>
                <span>EcoFinanças</span>
            </div>
            <div class="navbar-info">
                <?php if ($isLogado): ?>
                    <a href="noticias.php" class="btn btn-ghost btn-sm"><i class="fas fa-newspaper"></i> Notícias</a>
                    <a href="dashboard.php" class="btn btn-ghost btn-sm profile-link"><span class="profile-badge"><?= $inicialUsuario ?></span> <?= $nomeUsuario ?></a>
                    <a href="logout.php" class="btn btn-ghost btn-sm"><i class="fas fa-sign-out-alt"></i> Sair</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-ghost btn-sm"><i class="fas fa-sign-in-alt"></i> Entrar</a>
                    <a href="cadastro.php" class="btn btn-ghost btn-sm"><i class="fas fa-user-plus"></i> Cadastrar</a>
                <?php endif; ?>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="hero-content">
            <div class="hero-text">
                <h1>Seu Portal de Notícias Financeiras</h1>
                <p>Informações atualizadas sobre economia, investimentos e finanças pessoais. Gerencie e compartilhe conhecimento financeiro com nossa comunidade.</p>
                <div class="hero-actions">
                </div>
            </div>
            <div class="hero-image">
                <div class="hero-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2>Por que escolher o EcoFinanças?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h3>Notícias Atualizadas</h3>
                    <p>Conteúdo financeiro fresco e relevante, atualizado diariamente por nossa equipe especializada.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Comunidade Ativa</h3>
                    <p>Conecte-se com outros entusiastas das finanças e compartilhe experiências e conhecimentos.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Análises Detalhadas</h3>
                    <p>Relatórios completos sobre mercado financeiro, investimentos e tendências econômicas.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Conteúdo Seguro</h3>
                    <p>Informações verificadas e confiáveis para suas decisões financeiras mais importantes.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Notícias Publicadas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Usuários Ativos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Atualização Contínua</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Pronto para começar?</h2>
            <p>Junte-se à nossa comunidade e tenha acesso a conteúdo financeiro exclusivo.</p>
            <div class="cta-actions">
                <a href="cadastro.php" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Criar Conta Grátis
                </a>
                <a href="login.php" class="btn btn-secondary">
                    <i class="fas fa-sign-in-alt"></i> Já tenho conta
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <i class="fas fa-wallet"></i>
                    <span>EcoFinanças</span>
                </div>
                <div class="footer-links">
                    <a href="noticias.php">Notícias</a>
                    <a href="login.php">Login</a>
                    <a href="cadastro.php">Cadastro</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 EcoFinanças. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="landing.js" defer></script>
</body>

</html>