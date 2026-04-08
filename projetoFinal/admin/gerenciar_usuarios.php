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

// Buscar todos os usuários
$stmt = $pdo->query('SELECT * FROM usuarios ORDER BY criado_em DESC');
$usuarios = $stmt->fetchAll();

$total_usuarios = count($usuarios);
$total_admins = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE tipo = 'admin'")->fetchColumn();
$total_reporters = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE tipo = 'reporter'")->fetchColumn();
$total_leitores = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE tipo = 'leitor'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários - EcoFinanças</title>
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
                    <h1><i class="fas fa-users-cog"></i> Gerenciar Usuários</h1>
                    <p>Administre todas as contas do sistema</p>
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
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <h3><?= $total_usuarios ?></h3>
                    <p>Total de Usuários</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-user-shield"></i></div>
                <div class="stat-info">
                    <h3><?= $total_admins ?></h3>
                    <p>Administradores</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-user-edit"></i></div>
                <div class="stat-info">
                    <h3><?= $total_reporters ?></h3>
                    <p>Repórteres</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-user"></i></div>
                <div class="stat-info">
                    <h3><?= $total_leitores ?></h3>
                    <p>Leitores</p>
                </div>
            </div>
        </div>

        <!-- Tabela de Usuários -->
        <div class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-list"></i> Lista de Usuários</h2>
                <span class="badge"><?= $total_usuarios ?> usuários</span>
            </div>
            <div class="card-body">
                <?php if (empty($usuarios)): ?>
                    <div class="empty-state">
                        <i class="fas fa-users-slash"></i>
                        <h3>Nenhum usuário encontrado</h3>
                        <p>Ainda não há usuários cadastrados no sistema.</p>
                    </div>
                <?php else: ?>
                    <div style="overflow-x: auto;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Tipo</th>
                                    <th>Cadastro</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td><?= $usuario['id'] ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($usuario['nome']) ?></strong>
                                            <?php if ($usuario['tipo'] === 'admin'): ?>
                                                <span style="color: var(--primary-light); margin-left: 5px;">
                                                    <i class="fas fa-shield-alt"></i>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                                        <td>
                                            <span class="badge" style="background: <?= 
                                                $usuario['tipo'] === 'admin' ? 'linear-gradient(135deg, #ef4444, #dc2626)' : 
                                                ($usuario['tipo'] === 'reporter' ? 'linear-gradient(135deg, #3b82f6, #2563eb)' : 
                                                'linear-gradient(135deg, #6b7280, #4b5563)') 
                                            ?>;">
                                                <?= ucfirst($usuario['tipo']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($usuario['criado_em'])) ?></td>
                                        <td>
                                            <div style="display: flex; gap: 8px;">
                                                <a href="editar_usuario.php?id=<?= $usuario['id'] ?>" 
                                                   class="btn btn-ghost btn-sm" 
                                                   title="Editar usuário">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($usuario['id'] != $_SESSION['usuario_id']): ?>
                                                    <form method="POST" action="excluir_usuario.php" 
                                                          style="display: inline;" 
                                                          onsubmit="return confirm('⚠️ TEM CERTEZA que deseja excluir o usuário <?= htmlspecialchars($usuario['nome']) ?>?\n\nEsta ação não pode ser desfeita!')">
                                                        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                                                        <button type="submit" class="btn btn-ghost btn-sm text-error" title="Excluir usuário">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span style="color: var(--text-muted); font-size: 0.85rem; padding: 8px;">
                                                        <i class="fas fa-lock"></i> Você
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
