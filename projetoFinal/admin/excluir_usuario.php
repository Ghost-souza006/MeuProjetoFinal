<?php
session_start();

// Apenas admins podem excluir usuários
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id'] ?? 0;
    $admin_id = $_SESSION['usuario_id'];

    // Impedir que o admin exclua a si mesmo
    if ($id_usuario == $admin_id) {
        $_SESSION['mensagem'] = 'Você não pode excluir sua própria conta!';
        $_SESSION['tipo_mensagem'] = 'erro';
        header('Location: gerenciar_usuarios.php');
        exit;
    }

    // Verificar se o usuário existe
    $stmt = $pdo->prepare('SELECT nome, tipo FROM usuarios WHERE id = ?');
    $stmt->execute([$id_usuario]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        // Excluir notícias do usuário primeiro
        $stmt = $pdo->prepare('DELETE FROM noticias WHERE autor = ?');
        $stmt->execute([$id_usuario]);

        // Excluir o usuário
        $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id = ?');
        if ($stmt->execute([$id_usuario])) {
            $_SESSION['mensagem'] = 'Usuário "' . htmlspecialchars($usuario['nome']) . '" excluído com sucesso!';
            $_SESSION['tipo_mensagem'] = 'sucesso';
        } else {
            $_SESSION['mensagem'] = 'Erro ao excluir usuário.';
            $_SESSION['tipo_mensagem'] = 'erro';
        }
    } else {
        $_SESSION['mensagem'] = 'Usuário não encontrado.';
        $_SESSION['tipo_mensagem'] = 'erro';
    }
}

header('Location: gerenciar_usuarios.php');
exit;
?>
