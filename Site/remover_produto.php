<?php
session_start();

$id = $_GET['id'] ?? null;

if ($id && isset($_SESSION['carrinho'][$id])) {

    // Se a quantidade for maior que 1, apenas diminui 1
    if ($_SESSION['carrinho'][$id]['quantidade'] > 1) {
        $_SESSION['carrinho'][$id]['quantidade'] -= 1;
    } else {
        // Se a quantidade for 1 ou menos, remove o item completamente
        unset($_SESSION['carrinho'][$id]);
    }
}

// Redireciona de volta para o carrinho
header('Location: carrinho.php');
exit;
