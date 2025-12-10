<?php
session_start();

// Lógica do Carrinho
$contador = 0;
$total = 0.0;
$itensCarrinho = [];

if (isset($_SESSION['carrinho'])) {
    $itensCarrinho = $_SESSION['carrinho'];
    foreach ($itensCarrinho as $item) {
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
    <title>Login - Arduino Amazônia</title>

    <link rel="stylesheet" href="style-global.css">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="index.css">
    
    <style>
        /* --- CSS DO MINI CARRINHO --- */
        .carrinho-icone {
            position: relative;
            display: flex; align-items: center; height: 100%; top: 0; gap: 8px;
        }
        .mini-carrinho-dropdown {
            visibility: hidden; opacity: 0; position: absolute;
            top: 100%; right: 0; width: 320px;
            background-color: #fff; border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2); z-index: 1000;
            transition: all 0.3s ease; transform: translateY(10px);
            border-top: 4px solid #00915f; text-align: left;
        }
        .mini-carrinho-dropdown::before {
            content: ""; position: absolute; top: -10px; right: 10px;
            border-width: 0 10px 10px 10px; border-style: solid;
            border-color: transparent transparent #00915f transparent;
        }
        .carrinho-icone:hover .mini-carrinho-dropdown {
            visibility: visible; opacity: 1; transform: translateY(0);
        }
        .mini-carrinho-lista {
            max-height: 300px; overflow-y: auto; padding: 0; margin: 0; list-style: none;
        }
        .mini-carrinho-item {
            display: flex; align-items: center; padding: 10px 15px; border-bottom: 1px solid #eee;
        }
        .mini-carrinho-item:hover { background-color: #f9f9f9; }
        .mini-carrinho-img {
            width: 50px; height: 50px; object-fit: contain; border: 1px solid #ddd; border-radius: 4px; margin-right: 10px;
        }
        .mini-carrinho-detalhes { flex: 1; }
        .mini-carrinho-nome { font-size: 0.9rem; color: #333; font-weight: 600; display: block; margin-bottom: 3px; }
        .mini-carrinho-preco { font-size: 0.85rem; color: #666; }
        .mini-carrinho-rodape {
            padding: 15px; background-color: #f8f8f8; border-radius: 0 0 8px 8px; text-align: center;
        }
        .mini-carrinho-total {
            display: flex; justify-content: space-between; font-weight: bold; font-size: 1.1rem; color: #333; margin-bottom: 10px;
        }
        .btn-ir-carrinho {
            background-color: #00915f; color: white; text-decoration: none; padding: 10px 20px;
            border-radius: 5px; display: block; font-weight: bold; transition: background 0.2s;
        }
        .btn-ir-carrinho:hover { background-color: #007a50; }
        .carrinho-vazio-msg { padding: 20px; text-align: center; color: #888; font-style: italic; }

        /* --- NOVO CSS DO RODAPÉ (PADRONIZADO) --- */
        footer {
            background-color: #0b3d2b; /* Verde Escuro */
            border-top: 5px solid #00ff88; /* Linha verde neon */
            padding-top: 50px;
            margin-top: 50px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #ffffff;
            text-align: center;
        }
        .footer-content {
            max-width: 800px; margin: 0 auto; display: flex;
            flex-direction: column; align-items: center;
            text-align: center; gap: 40px; padding: 0 20px 40px 20px;
        }
        .footer-column { width: 100%; max-width: 500px; }
        .footer-logo {
            max-width: 140px; margin-bottom: 30px;
            background: white; padding: 10px; border-radius: 8px;
            display: inline-block;
        }
        .footer-column h4 {
            color: #00ff88; font-size: 1.2rem; margin-bottom: 15px;
            text-transform: uppercase; letter-spacing: 2px;
            border-bottom: 2px solid #00ff88; padding-bottom: 8px;
            display: inline-block;
        }
        .footer-column p {
            line-height: 1.6; color: #e0e0e0; font-size: 1rem; margin-bottom: 10px;
        }
        .phone-number {
            font-size: 1.4rem !important; font-weight: bold;
            color: #fff !important; margin-top: 5px;
        }
        .social-icons {
            margin-top: 20px; display: flex; justify-content: center; gap: 20px;
        }
        .social-icons img {
            width: 36px; height: 36px; transition: transform 0.2s;
        }
        .social-icons img:hover { transform: scale(1.2); }
        .footer-bottom-bar {
            background-color: #06291d; padding: 30px 20px;
            text-align: center; border-top: 1px solid #1a5c45;
        }
        .copyright {
            font-size: 0.9rem; color: #88cba9; margin-bottom: 20px;
        }
        .footer-payment-icons {
            display: flex; justify-content: center; align-items: center;
            gap: 15px; flex-wrap: wrap;
        }
        .footer-payment-icons img {
            height: 35px; object-fit: contain; background: rgba(255,255,255,0.9);
            padding: 3px; border-radius: 4px;
        }
        .back-to-top {
            position: fixed; bottom: 20px; right: 20px;
            background-color: #00ff88; color: #0b3d2b;
            width: 45px; height: 45px; text-align: center;
            line-height: 45px; border-radius: 50%;
            text-decoration: none; font-size: 1.4rem; font-weight: bold;
            box-shadow: 0 4px 10px rgba(0,0,0,0.4); z-index: 999;
            transition: transform 0.3s;
        }
        .back-to-top:hover { transform: translateY(-5px); background-color: #fff; }
    </style>
</head>

<body>
    <header>
        <div class="cabecalho">
            <a href="index.php">
                <img src="Imagens/logo 2.png" alt="Logo">
            </a>
            <nav>
                <ul class="cabecalho-lista">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="produtos.php">Produtos</a></li>
                    <li><a href="contato.php">Contato</a></li>
                    <li><a href="cadastro.php">Cadastro</a></li>
                    <li><a href="login.php">Entrar</a></li>
                </ul>
            </nav>
        </div>
        <div class="barra-pesquisa-container">
            <form action="busca.php" method="GET" class="barra-pesquisa">
                <input type="text" name="search" placeholder="Procure por Produtos" class="campo-pesquisa">
                <button type="submit" class="botao-pesquisa">
                    <img src="Imagens/lupa.png" alt="Pesquisar">
                </button>
            </form>

            <div class="carrinho-icone">
                <a href="carrinho.php">
                    <img src="Imagens/carrinho.png" alt="Carrinho">
                    <?php if($contador > 0): ?>
                        <span style="background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; vertical-align: top; margin-left: -10px;"><?= $contador ?></span>
                    <?php endif; ?>
                </a>

                <div class="mini-carrinho-dropdown">
                    <?php if(empty($itensCarrinho)): ?>
                        <div class="carrinho-vazio-msg">
                            <p>Seu carrinho está vazio.</p>
                        </div>
                    <?php else: ?>
                        <ul class="mini-carrinho-lista">
                            <?php foreach($itensCarrinho as $item): ?>
                                <li class="mini-carrinho-item">
                                    <?php $imgProduto = isset($item['imagem']) ? $item['imagem'] : 'Imagens/logo 2.png'; ?>
                                    <img src="<?= $imgProduto ?>" alt="Produto" class="mini-carrinho-img">
                                    <div class="mini-carrinho-detalhes">
                                        <span class="mini-carrinho-nome"><?= isset($item['nome']) ? substr($item['nome'], 0, 20).'...' : 'Produto' ?></span>
                                        <span class="mini-carrinho-preco">
                                            <?= $item['quantidade'] ?>x R$ <?= number_format($item['preco'], 2, ',', '.') ?>
                                        </span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="mini-carrinho-rodape">
                            <div class="mini-carrinho-total">
                                <span>Total:</span>
                                <span>R$ <?= number_format($total, 2, ',', '.') ?></span>
                            </div>
                            <a href="carrinho.php" class="btn-ir-carrinho">Ver Carrinho</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="form-container">
            <h2>Acessar Conta</h2>
            <form action="#" method="post">
                <div class="form-item">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="input-padrao" required>
                </div>

                <div class="form-item">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" class="input-padrao" required>
                </div>

                <label class="checkbox">
                    <input type="checkbox" id="lembrar"> Lembrar-me
                </label>

                <div class="botao">
                    <input type="submit" value="Acessar" class="enviar">
                    <input type="reset" value="Limpar" class="limpar">
                </div>

                <p class="links-secundarios">
                    <a href="#">Esqueceu sua senha?</a><br>
                    Ainda não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a>
                </p>
            </form>
        </div>
    </main>

    <footer>
        <img src="Imagens/logo 2.png" alt="Arduino Amazônia" class="footer-logo">

        <div class="footer-content">
            <div class="footer-column contact-info">
                <p><strong>ATENDIMENTO EM HORÁRIO COMERCIAL!</strong></p>
                <p class="phone-number">(91) 93834-1493</p>
                
                <div style="margin-top: 20px;">
                    <h4>Endereço</h4>
                    <p>Funcionamos apenas online</p>
                    <p>Belém - PA</p>
                </div>

                <div class="social-icons">
                    <a href="#" title="Facebook"><img src="Imagens/facebook.png" alt="Facebook"></a>
                    <a href="#" title="WhatsApp"><img src="Imagens/whatsapp.png" alt="WhatsApp"></a>
                    <a href="#" title="Instagram"><img src="Imagens/instagram.png" alt="Instagram"></a>
                </div>
            </div>

            <div class="footer-column about-us">
                <h4>Sobre Nós</h4>
                <p>A loja Arduino Amazônia é especializada em componentes eletrônicos para estudantes, hobistas e profissionais.</p>
                <p>Nossa missão é facilitar o acesso à tecnologia na região norte com preços justos e entrega rápida.</p>
            </div>

            <div class="footer-column attention">
                <h4>Informações Importantes</h4>
                <p>⚠ As imagens dos produtos são meramente ilustrativas.</p>
                <p>🚚 Enviamos para toda a região metropolitana de Belém.</p>
                <p>📄 Consulte nossas políticas de troca e devolução.</p>
            </div>
        </div>

        <div class="footer-bottom-bar">
            <p class="copyright">&copy; 2025 Arduino Amazônia - Todos os direitos reservados.</p>
            <div class="footer-payment-icons">
                <img src="Imagens/visa.webp" alt="Visa">
                <img src="Imagens/mastercard.png" alt="Mastercard">
                <img src="Imagens/Pix.png" alt="Pix">
                <img src="Imagens/mercado.png" alt="Mercado Pago">
                <img src="Imagens/elo.png" alt="Elo">
                <img src="Imagens/boleto.png" alt="Boleto">
            </div>
        </div>
        
        <a href="#" class="back-to-top" title="Voltar ao topo">↑</a>
    </footer>
</body>
</html>