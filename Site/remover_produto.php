<?php
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Remove o item específico
    if (isset($_SESSION['carrinho'][$id])) {
        unset($_SESSION['carrinho'][$id]);
    }
}

// Volta para o carrinho
header('Location: carrinho.php');
exit();
?>