<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}

require_once '../includes/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_noticia = $_POST['id'] ?? 0;
    $usuario_tipo = $_SESSION['usuario_tipo'];
    $usuario_id = $_SESSION['usuario_id'];

    // Verifica se a noticia existe
    $stmt = $pdo->prepare('SELECT autor FROM noticias WHERE id = ?');
    $stmt->execute([$id_noticia]);
    $noticia = $stmt->fetch();

    if ($noticia) {
        // Admin pode excluir qualquer notícia, reporter só as suas
        if ($usuario_tipo === 'admin' || $noticia['autor'] == $usuario_id) {
            $stmt = $pdo->prepare('DELETE FROM noticias WHERE id = ?');
            $stmt->execute([$id_noticia]);
            
            // Mensagem de sucesso
            $_SESSION['mensagem'] = 'Notícia excluída com sucesso!';
            $_SESSION['tipo_mensagem'] = 'sucesso';
        } else {
            $_SESSION['mensagem'] = 'Você não tem permissão para excluir esta notícia.';
            $_SESSION['tipo_mensagem'] = 'erro';
        }
    } else {
        $_SESSION['mensagem'] = 'Notícia não encontrada.';
        $_SESSION['tipo_mensagem'] = 'erro';
    }
}

// Redireciona de volta
if ($usuario_tipo === 'admin') {
    header('Location: gerenciar_noticias.php');
} else {
    header('Location: dashboard.php');
}
exit;
?>
