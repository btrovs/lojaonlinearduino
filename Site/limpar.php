<?php
session_start(); // Inicia a sessão
session_destroy(); // DESTROI a sessão (limpa o carrinho)
header('Location: carrinho.php'); // Te manda de volta pro carrinho (vazio)
exit;
?>