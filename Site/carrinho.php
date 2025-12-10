<?php
session_start();
$total_produtos = 0;

// Contagem para o menu
$contador_itens = 0;
if (isset($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $contador_itens += $item['quantidade'];
        $total_produtos += $item['preco'] * $item['quantidade'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Seu Carrinho</title>
    <link rel="stylesheet" href="index.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; padding: 20px; }
        .cart-container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); }
        
        /* Tabela */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { text-align: left; color: #666; padding: 15px; border-bottom: 2px solid #eee; }
        td { padding: 15px; border-bottom: 1px solid #eee; vertical-align: middle; }
        
        .product-info { display: flex; align-items: center; }
        .product-info img { width: 60px; height: 60px; object-fit: contain; border: 1px solid #ddd; border-radius: 5px; margin-right: 15px; background: #fff; }
        
        .btn-remover { color: #ff4444; text-decoration: none; font-size: 0.9em; border: 1px solid #ff4444; padding: 5px 10px; border-radius: 4px; transition: 0.2s; }
        .btn-remover:hover { background: #ff4444; color: white; }
        
        /* Área de Totais e Frete */
        .cart-summary { display: flex; justify-content: space-between; margin-top: 30px; flex-wrap: wrap; gap: 20px; }
        
        .frete-calc { background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #eee; flex: 1; min-width: 280px; }
        .frete-calc h4 { margin-top: 0; color: #333; }
        .input-cep { padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 120px; }
        .btn-calc { padding: 10px 15px; background: #333; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .btn-calc:hover { background: #555; }

        .totais { text-align: right; flex: 1; min-width: 280px; }
        .totais p { margin: 5px 0; font-size: 1.1em; color: #666; }
        .total-final { font-size: 2em; color: #00915f; font-weight: bold; margin-top: 10px; }
        
        .btn-checkout { background-color: #00915f; color: white; padding: 15px 40px; text-decoration: none; font-size: 1.1em; border-radius: 5px; font-weight: bold; display: inline-block; margin-top: 15px; transition: 0.3s; }
        .btn-checkout:hover { background-color: #007a50; }
        .btn-voltar { color: #555; text-decoration: none; display: block; margin-top: 15px; }
    </style>
</head>
<body>

    <header style="background: white; padding: 15px; border-bottom: 1px solid #eee; margin-bottom: 20px;">
        <div style="max-width: 900px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
            <a href="index.php"><img src="Imagens/logo 2.png" alt="Logo" height="40"></a>
            <a href="produtos.php" style="text-decoration: none; color: #333; font-weight: bold;">← Continuar Comprando</a>
        </div>
    </header>

    <div class="cart-container">
        <h1>🛒 Seu Carrinho</h1>

        <?php if (empty($_SESSION['carrinho'])): ?>
            <div style="text-align: center; padding: 50px;">
                <p style="font-size: 1.2em; color: #666;">Seu carrinho está vazio.</p>
                <br>
                <a href="produtos.php" class="btn-checkout">Ver Produtos</a>
            </div>
        <?php else: ?>
            
            <table>
                <thead>
                    <tr>
                        <th width="50%">Produto</th>
                        <th>Preço</th>
                        <th>Qtd</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['carrinho'] as $id => $item): 
                        $subtotal = $item['preco'] * $item['quantidade'];
                        // Garante imagem válida
                        $img = !empty($item['img']) ? $item['img'] : 'https://via.placeholder.com/60?text=Foto';
                    ?>
                    <tr>
                        <td>
                            <div class="product-info">
                                <img src="<?= $img ?>" alt="Foto">
                                <h4><?= $item['nome'] ?></h4>
                            </div>
                        </td>
                        <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                        <td><?= $item['quantidade'] ?></td>
                        <td><strong>R$ <?= number_format($subtotal, 2, ',', '.') ?></strong></td>
                        <td><a href="remover_produto.php?id=<?= $id ?>" class="btn-remover">Remover</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-summary">
                <div class="frete-calc">
                    <h4>🚚 Calcular Frete e Prazo</h4>
                    <input type="text" id="cep" class="input-cep" placeholder="00000-000" maxlength="9">
                    <button onclick="calcularFrete()" class="btn-calc">Calcular</button>
                    <div id="msg-frete" style="margin-top: 10px; color: #00915f; font-weight: bold;"></div>
                </div>

                <div class="totais">
                    <p>Subtotal: <span id="subtotal">R$ <?= number_format($total_produtos, 2, ',', '.') ?></span></p>
                    <p>Frete: <span id="valor-frete">R$ 0,00</span></p>
                    <div class="total-final" id="total-final">R$ <?= number_format($total_produtos, 2, ',', '.') ?></div>
                    
                    <a href="fim_carrinho.php" class="btn-checkout">Finalizar Compra →</a>
                    <a href="produtos.php" class="btn-voltar">Escolher mais produtos</a>
                </div>
            </div>

        <?php endif; ?>
    </div>

    <script>
        function calcularFrete() {
            const cep = document.getElementById('cep').value;
            const msg = document.getElementById('msg-frete');
            const elFrete = document.getElementById('valor-frete');
            const elTotal = document.getElementById('total-final');
            
            // Valor base dos produtos (vinda do PHP)
            const subtotal = <?= $total_produtos ?>; 
            
            if (cep.length < 8) {
                msg.style.color = 'red';
                msg.innerHTML = "CEP inválido.";
                return;
            }

            msg.style.color = '#666';
            msg.innerHTML = "Calculando...";

            setTimeout(() => {
                // SIMULAÇÃO: Frete Fixo de R$ 10,00
                const valorFrete = 10.00;
                const totalComFrete = subtotal + valorFrete;

                msg.style.color = '#00915f';
                msg.innerHTML = "Sedex: 3 dias úteis";
                
                elFrete.innerHTML = "R$ " + valorFrete.toFixed(2).replace('.', ',');
                elTotal.innerHTML = "R$ " + totalComFrete.toFixed(2).replace('.', ',');
            }, 1000); // Demora 1 segundo para parecer real
        }
    </script>

</body>
</html>