<?php
session_start();
$PROD_ID = 31;
$PROD_NOME = "Arduino Nano V3.0 Entrada USB-C (sem cabo)";
$PROD_PRECO = 45.50;
$PROD_IMG = "Imagens/Arduino Nano V3.0 Entrada USB-C (sem cabo).jpg"; 
$PROD_CATEGORIA = "Placas, Microcontroladores";
$PROD_DISPONIBILIDADE = "Em Estoque";
$contador = 0; $total = 0.0;
if (isset($_SESSION['carrinho'])) { foreach ($_SESSION['carrinho'] as $item) { $contador += $item['quantidade']; $total += $item['preco'] * $item['quantidade']; } }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($PROD_NOME) ?></title>
    <link rel="stylesheet" href="style-produto.css">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <header>
        <div class="cabecalho"><a href="index.php"><img src="Imagens/logo 2.png" alt="Logo"></a><nav><ul class="cabecalho-lista"><li><a href="index.php">Home</a></li><li><a href="produtos.php">Produtos</a></li><li><a href="carrinho.php">Carrinho</a></li></ul></nav></div>
        <div class="barra-pesquisa-container"><form action="busca.php" method="GET" class="barra-pesquisa"><input type="text" name="search" placeholder="Procure por Produtos" class="campo-pesquisa"><button type="submit" class="botao-pesquisa"><img src="Imagens/lupa.png" alt="Pesquisar"></button></form><div class="carrinho-icone"><a href="carrinho.php"><img src="Imagens/carrinho.png" alt="Carrinho"></a><?php if($contador > 0): ?> <span class="contador" style="background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; vertical-align: top; margin-left: -10px;"><?= $contador ?></span> <?php endif; ?></div></div>
    </header>
    <main class="produto-container">
        <div class="img-produto"><img src="<?= $PROD_IMG ?>" alt="<?= htmlspecialchars($PROD_NOME) ?>"></div>
        <div class="info-produto">
            <p class="classificacao"><?= htmlspecialchars($PROD_CATEGORIA) ?></p>
            <h1 class="titulo-produto"><?= htmlspecialchars($PROD_NOME) ?></h1>
            <p class="disponibilidade">Disponibilidade: <span class="em-estoque"><?= $PROD_DISPONIBILIDADE ?></span></p>
            <p class="descricao">O Arduino Nano é uma placa pequena, completa e compatível com protoboards. Possui a mesma funcionalidade do Arduino Uno, mas em tamanho compacto. Conexão USB-C moderna.</p>
            <h2 class="preco">R$<?= number_format($PROD_PRECO, 2, ',', '.') ?></h2>
            <p class="parcelamento">💳 Até 12x sem juros no cartão.</p>
            <div class="metodos-pagamento" style="margin: 20px 0;"><h4 style="margin-bottom: 10px;">Formas de Pagamento:</h4><div class="logos-pagamento"><img src="Imagens/boleto.png" alt="Boleto" height="25"><img src="Imagens/mastercard.png" alt="Mastercard" height="25"><img src="Imagens/elo.png" alt="Elo" height="25"><img src="Imagens/pix.png" alt="Pix" height="25"></div></div>
            <div class="frete" style="margin-bottom: 20px;"><h4 style="margin-bottom: 5px;">Simulação de frete</h4><input type="text" placeholder="Informe seu CEP" style="padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 100%;"></div>
            <form action="salvar_carrinho.php" method="POST">
                <div class="acoes" style="display: flex; gap: 10px; align-items: center;">
                    <input type="number" name="quantidade" value="1" min="1" max="10" style="width: 60px; padding: 15px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px;">
                    <input type="hidden" name="id" value="<?= $PROD_ID ?>"><input type="hidden" name="nome" value="<?= htmlspecialchars($PROD_NOME) ?>"><input type="hidden" name="preco" value="<?= $PROD_PRECO ?>"><input type="hidden" name="img" value="<?= $PROD_IMG ?>">
                    <button type="submit" class="btn-carrinho" style="background-color: #1c593a; color: white; border: none; padding: 15px 30px; cursor: pointer; font-size: 16px; border-radius: 25px; font-weight: bold; width: 100%;">🛒 Adicionar ao carrinho</button>
                </div>
            </form>
        </div>
    </main>

    <section class="descricao-detalhada">
        <h2>Especificações Técnicas</h2>
        <ul>
            <li>- Microcontrolador: ATmega328P</li>
            <li>- Tensão de Operação: 5V</li>
            <li>- Conexão: USB-C</li>
            <li>- Dimensões: 18 x 45 mm</li>
            <li>- Compatível com Protoboard</li>
        </ul>
    </section>

    <section class="produtos-relacionados">
        <h2>Você também pode gostar</h2>
        <div class="produtos-container">
            <a href="arduino.php" class="produto-card">
                <img src="Imagens/arduino 3.png" alt="Uno">
                <h3>Arduino Uno</h3>
                <p class="preco">R$ 55,00</p>
            </a>
            <a href="cabo-usb.php" class="produto-card">
                <img src="Imagens/Cabo Usb-C 7.1A Leon 1000mm.png" alt="Cabo">
                <h3>Cabo USB-C</h3>
                <p class="preco">R$ 18,00</p>
            </a>
        </div>
    </section>
    <footer><div class="footer-bottom-bar"><p class="copyright">&copy; 2025 Arduino Amazônia</p></div></footer>
</body>
</html>