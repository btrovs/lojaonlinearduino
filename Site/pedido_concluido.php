<?php
session_start();
unset($_SESSION['carrinho']); // Esvazia o carrinho
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedido Realizado</title>
    <style>
        body { text-align: center; padding-top: 50px; font-family: Arial, sans-serif; }
        h1 { color: #00915f; font-size: 3em; }
    </style>
</head>
<body>
    <h1>🎉 Sucesso!</h1>
    <h2>Seu pedido foi realizado.</h2>
    <p>Obrigado por comprar conosco.</p>
    <br><br>
    <a href="index.php" style="background: #333; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Voltar para a Loja</a>
</body>
</html>