<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    
    // Excluir notícias do usuário antes de excluir usuário (ON DELETE CASCADE pode resolver, mas forçamos por segurança)
    $stmt = $pdo->prepare('DELETE FROM noticias WHERE autor = ?');
    $stmt->execute([$usuario_id]);

    $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id = ?');
    if ($stmt->execute([$usuario_id])) {
        session_unset();
        session_destroy();
    }
}

header('Location: ../login.php');
exit;
?>
