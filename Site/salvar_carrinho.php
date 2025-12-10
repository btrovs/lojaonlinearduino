<?php
session_start();

// Verifica se os dados do produto chegaram
if (isset($_POST['id']) && isset($_POST['nome']) && isset($_POST['preco'])) {
    
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $preco = (float)$_POST['preco'];
    $qtd = (int)$_POST['quantidade'];
    $img = $_POST['img']; // Pega o caminho da imagem

    // Cria o carrinho se não existir
    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    // Se o produto já existe, soma a quantidade. Se não, cria novo.
    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]['quantidade'] += $qtd;
    } else {
        $_SESSION['carrinho'][$id] = [
            'id' => $id,
            'nome' => $nome,
            'preco' => $preco,
            'quantidade' => $qtd,
            'img' => $img
        ];
    }
}

// Manda para a página visual do carrinho
header('Location: carrinho.php');
exit();
?>