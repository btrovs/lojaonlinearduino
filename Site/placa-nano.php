<?php
session_start();

// Detalhes fixos do produto (ID 31 - Mudei para não bater com a outra placa)
$PROD_ID = 31;
$PROD_NOME = "Placa Arduino Nano V3.0 (Compatível)";
$PROD_PRECO = 45.50;
// Tentei adivinhar o nome da imagem baseado nos anteriores. 
// Se a imagem não aparecer, verifique se o nome do arquivo na pasta Imagens é este mesmo.
$PROD_IMG = "Imagens/Arduino Nano V3.0 Entrada USB-C (sem cabo).jpg"; 
$PROD_CATEGORIA = "Placas, Microcontroladores";
$PROD_DISPONIBILIDADE = "Em Estoque";

// Calcula contador e total do carrinho
$contador = 0;
$total = 0.0;
if (isset($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $contador += $item['quantidade'];
        $total += $item['preco'] * $item['quantidade'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($PROD_NOME) ?></title>
    <link rel="stylesheet" href="style-produto.css">
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <header>
        <div class="cabecalho">
            <a href="index.php"><img src="Imagens/logo 2.png" alt="Logo"></a>
            <nav><ul class="cabecalho-lista"><li><a href="index.php">Home</a></li><li><a href="produtos.php">Produtos</a></li><li><a href="carrinho.php">Carrinho</a></li></ul></nav>
        </div>
        <div class="barra-pesquisa-container">
             <div style="margin-left: auto; padding-right: 20px; font-weight: bold;">
                <a href="carrinho.php" style="text-decoration: none; color: black;">
                    🛒 Carrinho: <span style="color: #00915f;"><?= $contador ?> itens</span> (R$ <?= number_format($total, 2, ',', '.') ?>)
                </a>
            </div>
        </div>
    </header>

    <main class="produto-container">
        <div class="img-produto">
            <img src="<?= $PROD_IMG ?>" alt="<?= htmlspecialchars($PROD_NOME) ?>">
        </div>

        <div class="info-produto">
            <p class="classificacao"><?= htmlspecialchars($PROD_CATEGORIA) ?></p>
            <h1 class="titulo-produto"><?= htmlspecialchars($PROD_NOME) ?></h1>
            <p class="disponibilidade">Disponibilidade: <span class="em-estoque"><?= $PROD_DISPONIBILIDADE ?></span></p>
            
            <h2 class="preco">R$<?= number_format($PROD_PRECO, 2, ',', '.') ?></h2>

            <form action="salvar_carrinho.php" method="POST">
                <div class="acoes">
                    <input type="number" name="quantidade" value="1" min="1" max="5" style="width: 50px;">
                    
                    <input type="hidden" name="id" value="<?= $PROD_ID ?>">
                    <input type="hidden" name="nome" value="<?= htmlspecialchars($PROD_NOME) ?>">
                    <input type="hidden" name="preco" value="<?= $PROD_PRECO ?>">
                    <input type="hidden" name="img" value="<?= $PROD_IMG ?>">
                    
                    <button type="submit" class="btn-carrinho" style="background-color: #00915f; color: white; border: none; padding: 10px; cursor: pointer;">
                        🛒 Adicionar ao carrinho
                    </button>
                </div>
            </form>
        </div>
    </main>

    <section class="descricao-detalhada">
        <h2>Especificações Técnicas</h2>
        <ul>
            <li>- Microcontrolador: ATmega328P</li>
            <li>- Tensão de Operação: 5V</li>
            <li>- Pinos Digitais: 14 (6 PWM)</li>
            <li>- Pinos Analógicos: 8</li>
            <li>- Conexão: Mini USB ou USB-C (Verificar modelo)</li>
        </ul>
    </section>

    <section class="produtos-relacionados">
        <h2>Você também pode gostar</h2>
        <div class="produtos-container">
            <a href="fonte-arduino.php" class="produto-card">
                <img src="Imagens/Fonte 9V 2A Arduino e uso geral.jpg" alt="Fonte">
                <h3>Fonte 9V 2A</h3>
                <p class="preco">R$27,50</p>
            </a>
             <a href="protoboard.php" class="produto-card">
                <img src="Imagens/Protoboard 830 Pontos.jpg" alt="Protoboard">
                <h3>Protoboard 830 Pontos</h3>
                <p class="preco">R$30,00</p>
            </a>
        </div>
    </section>

</body>
</html>