<?php
session_start();

// Inicializa o carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
  $_SESSION['carrinho'] = [];
}

// Calcula quantidade total e valor total
$contador = array_sum(array_column($_SESSION['carrinho'], 'quantidade'));
$valor_total = 0;
foreach ($_SESSION['carrinho'] as $item) {
  $valor_total += $item['preco'] * $item['quantidade'];
}
$carrinho = $_SESSION['carrinho'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carrinho</title>

  <link rel="stylesheet" href="carrinho.css">
  <link rel="stylesheet" href="style-global.css">
</head>

<body>

  <header>
    <div class="cabecalho">
      <a href="index.html">
        <img src="Imagens/logo 2.png" alt="Logo da loja Arduino Amazônia">
      </a>
      <nav>
        <ul class="cabecalho-lista">
          <li><a href="index.html">Home</a></li>
          <li><a href="produtos.php">Produtos</a></li>
          <li><a href="contato.html">Contato</a></li>
          <li><a href="cadastro.html">Cadastro</a></li>
          <li><a href="login.html">Entrar</a></li>
        </ul>
      </nav>
    </div>

    <div class="barra-pesquisa-container">
      <form action="busca.php" method="GET" class="barra-pesquisa">
        <input type="text" name="search" placeholder="Procure por Produtos" class="campo-pesquisa">
        <button type="submit" class="botao-pesquisa"><img src="Imagens/lupa.png" alt="Pesquisar"></button>
      </form>
      <div class="carrinho-icone">
        <a href="carrinho.php">
          <img src="Imagens/carrinho.png" alt="Carrinho">
        </a>
        <span class="contador"><?= $contador ?></span>
        <span class="valor-total">R$<?= number_format($valor_total, 2, ',', '.') ?></span>
      </div>
    </div>
  </header>

  <main>
    <?php if (empty($carrinho)): ?>
      <section class="carrinho-vazio">
        <h2>Seu Carrinho</h2>
        <div class="caixa-carrinho">
          <img src="Imagens/carrinho-vazio.png" alt="Carrinho vazio" class="imagem-vazio">
          <p class="mensagem-vazio">Seu carrinho está vazio!</p>
          <p class="subtexto">Adicione produtos e continue suas compras com a gente!</p>
          <a href="produtos.php" class="botao-verde">Ver Produtos</a>
        </div>
      </section>
    <?php else: ?>
      <section class="carrinho-lista">
        <h2>Seu Carrinho</h2>
        <table>
          <tr>
            <th>Produto</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Subtotal</th>
            <th></th>
          </tr>

          <?php
          $total = 0;
          foreach ($carrinho as $id => $item):
            $subtotal = $item['preco'] * $item['quantidade'];
            $total += $subtotal;
          ?>
            <tr>
              <td><?= htmlspecialchars($item['nome']) ?></td>
              <td>R$<?= number_format($item['preco'], 2, ',', '.') ?></td>
              <td><?= $item['quantidade'] ?></td>
              <td>R$<?= number_format($subtotal, 2, ',', '.') ?></td>
              <td><a href="remover_produto.php?id=<?= $id ?>" title="Remover">❌</a></td>
            </tr>
          <?php endforeach; ?>
        </table>

        <h3>Total: R$<?= number_format($total, 2, ',', '.') ?></h3>
        <a href="fim_carrinho.php" class="botao-verde">Finalizar Compra</a>
      </section>
    <?php endif; ?>
  </main>

  <!-- Script para manter contador e total sincronizados -->
  <script>
    async function atualizarCabecalho() {
      const resposta = await fetch('status_carrinho.php');
      const json = await resposta.json();
      document.querySelector('.contador').textContent = json.quantidade;
      document.querySelector('.valor-total').textContent = 'R$' + json.total_formatado;
    }

    // Atualiza automaticamente a cada 5 segundos (opcional)
    setInterval(atualizarCabecalho, 5000);
  </script>

</body>

</html>