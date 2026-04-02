<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_noticia = $_POST['id'] ?? 0;
    
    // Verifica se a noticia existe e pertence ao usuario logado
    $stmt = $pdo->prepare('SELECT autor FROM noticias WHERE id = ?');
    $stmt->execute([$id_noticia]);
    $noticia = $stmt->fetch();

    if ($noticia && $noticia['autor'] == $_SESSION['usuario_id']) {
        $stmt = $pdo->prepare('DELETE FROM noticias WHERE id = ?');
        $stmt->execute([$id_noticia]);
    }
}

// Redireciona de volta ao dashboard
header('Location: dashboard.php');
exit;
?>
