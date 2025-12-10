<?php
session_start();
if (empty($_SESSION['carrinho'])) {
    header('Location: produtos.php');
    exit;
}

$subtotal = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $subtotal += $item['preco'] * $item['quantidade'];
}

$valor_frete = 10.00;
$total_final = $subtotal + $valor_frete;

$total_para_boleto = str_pad(str_replace(['.', ','], '', number_format($total_final, 2, ',', '.')), 10, '0', STR_PAD_LEFT);
$codigo_barras_exemplo = "34191.79001 01043.510047 91020.150008 8 " . $total_para_boleto;
$codigo_pix_falso = "00020126580014BR.GOV.BCB.PIX0136123e4567-e89b-12d3-a456-426614174000520400005303986540" . number_format($total_final, 2, '', '') . "5802BR5913ArduinoLoja6008Belem62070503***6304";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Pagamento</title>
    <link rel="stylesheet" href="index.css">
    <style>
        body { background-color: #f4f7f6; font-family: Arial, sans-serif; }
        .checkout-container { max-width: 800px; margin: 40px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        
        .resumo-box { background-color: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #e9ecef; margin-bottom: 20px; }
        .linha-resumo { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 1.1em; color: #555; }
        .linha-total { display: flex; justify-content: space-between; margin-top: 15px; padding-top: 15px; border-top: 2px solid #ddd; font-size: 1.6em; font-weight: bold; color: #00915f; }

        .bancos-aceitos { text-align: center; margin-bottom: 20px; padding: 10px; }
        .bancos-aceitos img { height: 35px; margin: 0 8px; vertical-align: middle; }

        .payment-tabs { display: flex; justify-content: center; gap: 10px; margin-bottom: 20px; }
        .tab-btn { padding: 12px 10px; border: 1px solid #ddd; background: #fff; cursor: pointer; border-radius: 5px; font-weight: bold; color: #555; transition: all 0.2s ease-in-out; }
        .tab-btn:hover { background-color: #f0f0f0; }
        .tab-btn.active { background-color: #00915f; color: white; border-color: #00915f; }

        .payment-content { display: none; padding: 20px; border: 1px solid #eee; border-radius: 5px; background-color: #fff; animation: fadeIn 0.5s; }
        .payment-content.active { display: block; }

        input { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .btn-finalizar { width: 100%; padding: 15px; background-color: #00915f; color: white; border: none; font-size: 1.2em; border-radius: 5px; cursor: pointer; margin-top: 20px; transition: 0.3s; }
        .btn-finalizar:hover { background-color: #007a50; }

        /* PIX INTERATIVO */
        .pix-container { text-align: center; position: relative; min-height: 300px; }
        
        .qr-moldura { 
            display: inline-block; 
            padding: 10px; 
            border: 2px solid #00915f; 
            border-radius: 10px; 
            margin-bottom: 15px; 
            box-shadow: 0 0 10px rgba(0, 145, 95, 0.3);
            cursor: pointer; /* Mãozinha para indicar clique */
            transition: transform 0.1s;
        }
        .qr-moldura:active { transform: scale(0.95); }
        
        .aviso-simulacao { 
            font-size: 0.9em; 
            color: #00915f; 
            margin-top: 5px; 
            font-weight: bold; 
            animation: piscar 2s infinite; 
        }
        @keyframes piscar { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }

        /* ÁREA DE SUCESSO (COMEÇA INVISÍVEL) */
        .sucesso-pix { display: none; text-align: center; animation: zoomIn 0.5s; }
        
        .sucesso-pix img { 
            width: 200px; 
            height: auto; 
            margin-bottom: 15px; 
            border-radius: 15px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.2); 
        }
        .sucesso-pix h3 { color: #00915f; margin: 0; font-size: 1.5em; }
        
        .pix-copia-cola { background: #eee; padding: 10px; font-family: monospace; font-size: 0.9em; word-break: break-all; border-radius: 5px; color: #555; margin-bottom: 10px; border: 1px dashed #aaa; }
        .btn-copiar { background-color: #333; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-size: 0.9em; }
        
        .boleto-area { text-align: center; }
        .codigo-barras { background: #eee; padding: 15px; font-family: monospace; letter-spacing: 1px; margin: 10px 0; word-break: break-all; border-radius: 5px; font-size: 1.1em; color: #444; border: 1px dashed #bbb; }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes zoomIn { from { transform: scale(0); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    </style>
</head>
<body>

<div class="checkout-container">
    <h2>Finalizar Compra</h2>

    <div class="resumo-box">
        <div class="linha-resumo"><span>Subtotal:</span><span>R$ <?= number_format($subtotal, 2, ',', '.') ?></span></div>
        <div class="linha-resumo"><span>Frete (Sedex):</span><span>R$ <?= number_format($valor_frete, 2, ',', '.') ?></span></div>
        <div class="linha-total"><span>Total a Pagar:</span><span>R$ <?= number_format($total_final, 2, ',', '.') ?></span></div>
    </div>

    <div class="bancos-aceitos">
        <p>Aceitamos:</p>
        <img src="Imagens/visa.webp" alt="Visa">
        <img src="Imagens/mastercard.png" alt="Mastercard">
        <img src="Imagens/elo.png" alt="Elo">
        <img src="Imagens/pix.png" alt="Pix">
        <img src="Imagens/boleto.png" alt="Boleto">
    </div>

    <form action="pedido_concluido.php" method="POST">
        <h3>1. Seus Dados</h3>
        <input type="text" name="nome" placeholder="Nome Completo" required>
        <input type="text" name="endereco" placeholder="Endereço de Entrega" required>

        <h3>2. Pagamento</h3>
        <div class="payment-tabs">
            <div class="tab-btn active" onclick="mudarAba('cartao', event)">💳 Cartão</div>
            <div class="tab-btn" onclick="mudarAba('pix', event)">💠 Pix</div>
            <div class="tab-btn" onclick="mudarAba('boleto', event)">📄 Boleto</div>
        </div>

        <div id="cartao" class="payment-content active">
            <input type="text" placeholder="Número do Cartão">
            <div style="display: flex; gap: 10px;">
                <input type="text" placeholder="Validade">
                <input type="text" placeholder="CVV">
            </div>
            <input type="text" placeholder="Nome no Cartão">
            <button type="submit" class="btn-finalizar">CONFIRMAR PAGAMENTO</button>
        </div>

        <div id="pix" class="payment-content">
            <div class="pix-container">
                
                <div id="area-qr-code">
                    <p style="font-weight: bold; margin-bottom: 5px;">Escaneie o QR Code:</p>
                    
                    <div class="qr-moldura" onclick="simularAprovacao()">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=<?= $codigo_pix_falso ?>" alt="QR Code Pix">
                    </div>
                    
                    <p class="aviso-simulacao">👉 Clique no QR Code para simular o pagamento!</p>
                    
                    <input type="text" value="<?= $codigo_pix_falso ?>" id="inputPix" style="position: absolute; left: -9999px;">
                    <div class="pix-copia-cola"><?= substr($codigo_pix_falso, 0, 30) ?>...</div>
                    <button type="button" class="btn-copiar" onclick="copiarCodigo()">📋 Copiar Código Pix</button>
                </div>

                <div id="area-sucesso-pix" class="sucesso-pix">
                    <img src="Imagens/macaco.webp" alt="Pagamento Aprovado">
                    
                    <h3>Pagamento Recebido!</h3>
                    <p>O estagiário macaco confirmou seu Pix.</p>
                    <button type="submit" class="btn-finalizar" style="margin-top: 10px;">CONCLUIR PEDIDO</button>
                </div>

            </div>
        </div>

        <div id="boleto" class="payment-content boleto-area">
            <p>Boleto Gerado:</p>
            <img src="Imagens/boleto (2).png" alt="Boleto" style="max-width: 100%; border: 1px solid #ddd; padding: 5px;">
            <div class="codigo-barras"><?= $codigo_barras_exemplo ?></div>
            <button type="submit" class="btn-finalizar">IMPRIMIR E FINALIZAR</button>
        </div>

    </form>
    <br><center><a href="carrinho.php" style="color: #666;">Voltar</a></center>
</div>

<script>
    function mudarAba(id, event) {
        document.querySelectorAll('.payment-content').forEach(d => d.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById(id).classList.add('active');
        event.currentTarget.classList.add('active');
        
        // Se sair da aba Pix, reseta para o QR Code aparecer de novo se voltar
        if (id !== 'pix') {
            document.getElementById('area-qr-code').style.display = 'block';
            document.getElementById('area-sucesso-pix').style.display = 'none';
        }
    }

    function simularAprovacao() {
        // Esconde QR Code e mostra Macaco
        document.getElementById('area-qr-code').style.display = 'none';
        document.getElementById('area-sucesso-pix').style.display = 'block';
    }

    function copiarCodigo() {
        var copyText = document.getElementById("inputPix");
        copyText.select();
        navigator.clipboard.writeText(copyText.value);
        
        var btn = document.querySelector('.btn-copiar');
        var original = btn.innerHTML;
        btn.innerHTML = "✅ Copiado!";
        btn.style.background = "#00915f";
        setTimeout(() => {
            btn.innerHTML = original;
            btn.style.background = "#333";
        }, 2000);
    }
    
    // Garante que o cartão abre primeiro
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('cartao').classList.add('active');
        document.querySelector('.tab-btn').classList.add('active');
    });
</script>
</body>
</html>