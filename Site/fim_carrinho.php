<?php
session_start();

$carrinho = $_SESSION['carrinho'] ?? [];
$total = 0.0;
$qtd = 0;
foreach ($carrinho as $it) {
    $qtd += $it['quantidade'];
    $total += (float)($it['preco'] ?? 0.0) * (int)($it['quantidade'] ?? 0);
}

// Lógica de processamento da compra
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ação: Processamento do pedido/pagamento e limpeza do carrinho
    $_SESSION['carrinho'] = [];
    // Redireciona para uma página de confirmação ou produtos.php
    header('Location: produtos.php?status=pedido_finalizado');
    exit;
}

// Calcula contador e total atuais do carrinho (para o header)
$contador = isset($_SESSION['carrinho']) ? array_sum(array_column($_SESSION['carrinho'], 'quantidade')) : 0;
$valor_total_header = 0.0;
if (!empty($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $valor_total_header += (float)($item['preco'] ?? 0.0) * (int)($item['quantidade'] ?? 0);
    }
}
$script_nome_contador = 'contador_carrinho.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra</title>

    <link rel="stylesheet" href="style-global.css">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="Style-Produto.css">
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
                <span class="valor-total">R$<?= number_format($valor_total_header, 2, ',', '.') ?></span>
            </div>
        </div>

    </header>
    <main class="finalizacao-main">
        <div class="resumo-pedido">
            <h1>Confirmação de Pedido</h1>

            <?php if (!empty($carrinho)): ?>

                <div class="detalhes-resumo">
                    <p>Total de Itens: <strong><?= $qtd ?></strong></p>
                    <p>Valor Total da Compra: <strong>R$ <?= number_format($total, 2, ',', '.') ?></strong></p>

                    <h3 style="margin-top: 20px;">Próximos Passos:</h3>
                    <p>Ao clicar em "Confirmar Compra", seu carrinho será processado e zerado. Você será redirecionado para a página de produtos.</p>
                </div>

                <form method="post" class="form-finalizacao" style="margin-top: 30px;">
                    <button type="submit" class="botao-verde">
                        Confirmar Compra e Pagar
                    </button>
                </form>

            <?php else: ?>
                <p style="text-align: center; margin-top: 50px;">Seu carrinho está vazio. Não há pedido a ser finalizado.</p>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="produtos.php" class="botao-verde">Voltar para Produtos</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        // Script para atualizar o contador no cabeçalho
        async function atualizarCabecalho() {
            const resposta = await fetch('<?= $script_nome_contador ?>');
            const json = await resposta.json();
            document.querySelector('.contador').textContent = json.quantidade;
            document.querySelector('.valor-total').textContent = 'R$' + json.total_formatado;
        }

        atualizarCabecalho();
        setInterval(atualizarCabecalho, 5000);
    </script>
</body>

</html>