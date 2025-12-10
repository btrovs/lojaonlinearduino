<?php
session_start();

// Contagem de itens no carrinho
$contador = 0;
$total = 0.0;

// Verificação para evitar erros se a sessão não existir
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
    <title>Loja Arduino Amazônia</title>

    <link rel="stylesheet" href="style-global.css">
    <link rel="stylesheet" href="index.css">
    
    <style>
        /* Layout da Grade de Destaques */
        .container-destaques {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 25px;
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Card de Produto (Home) */
        .produto-card {
            position: relative;
            background: white; padding: 15px; border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center;
            text-decoration: none; color: inherit; display: flex;
            flex-direction: column; justify-content: space-between;
            transition: transform 0.2s; height: 380px;
        }
        .produto-card:hover { transform: translateY(-5px); }
        .produto-card img { width: 100%; height: 160px; object-fit: contain; margin-bottom: 10px; }
        .produto-card h3 { font-size: 1rem; margin: 10px 0; color: #333; }
        
        /* Preços e Botões */
        .produto-card .preco { 
            color: #00915f; font-size: 1.3em; font-weight: bold; 
            min-height: 30px; display: flex; align-items: center; justify-content: center;
            position: relative;
            cursor: help;
        }
        .preco-antigo { text-decoration: line-through; font-size: 0.8em; color: #999; margin-right: 10px; }
        .preco-novo { color: #00915f; }
        
        .btn-ver { 
            background: #00915f; color: white; padding: 10px; 
            border-radius: 5px; display: block; margin-top: 10px; font-weight: bold;
        }
        .btn-indisponivel { background-color: #999; cursor: default; }

        /* Selo Esgotado */
        .selo-esgotado-home {
            position: absolute; top: 10px; right: 10px; width: 80px; height: auto;
            z-index: 10; transform: rotate(-15deg);
            filter: drop-shadow(2px 2px 2px rgba(0,0,0,0.3));
        }
        
        /* Banner */
        .banner { background-color: #eee; text-align: center; padding: 50px 20px; margin-bottom: 20px; }
        .banner h1 { color: #00915f; font-size: 2.5em; }

        /* --- CSS DA PROMOÇÃO DINÂMICA --- */
        .badge-desconto {
            position: absolute; top: -10px; left: -5px;
            background-color: #ff4757; color: white;
            padding: 5px 12px; border-radius: 15px;
            font-weight: bold; font-size: 0.9rem;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
            z-index: 20; animation: pulse 2s infinite ease-in-out;
        }
        .tooltip-economia {
            visibility: hidden; background-color: #2ecc71; color: #fff;
            text-align: center; border-radius: 6px; padding: 5px 10px;
            position: absolute; z-index: 100; bottom: 105%; left: 50%;
            transform: translateX(-50%); opacity: 0; transition: opacity 0.3s, bottom 0.3s;
            font-size: 0.85rem; white-space: nowrap; box-shadow: 0px 4px 6px rgba(0,0,0,0.15);
            pointer-events: none;
        }
        .tooltip-economia::after {
            content: ""; position: absolute; top: 100%; left: 50%; margin-left: -5px;
            border-width: 5px; border-style: solid; border-color: #2ecc71 transparent transparent transparent;
        }
        .produto-card .preco:hover .tooltip-economia { visibility: visible; opacity: 1; bottom: 115%; }
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 2px 2px 5px rgba(0,0,0,0.2); }
            50% { transform: scale(1.1); box-shadow: 2px 2px 15px rgba(255, 71, 87, 0.6); }
            100% { transform: scale(1); box-shadow: 2px 2px 5px rgba(0,0,0,0.2); }
        }
        .card-promocao-ativa { border: 1px solid #ff4757; }
        .preco-destaque-grande { color: #e84118 !important; font-size: 1.8rem !important; font-weight: 800; display: block; }
        .preco-antigo-topo { display: block; color: #a4b0be; font-size: 0.9rem; text-decoration: line-through; margin-bottom: -5px; margin-right: 0 !important; }


        /* --- CSS DO MINI CARRINHO --- */
        .carrinho-icone { position: relative; padding-bottom: 10px; }
        .mini-carrinho-dropdown {
            visibility: hidden; opacity: 0; position: absolute; top: 100%; right: 0;
            width: 320px; background-color: #fff; border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2); z-index: 1000;
            transition: all 0.3s ease; transform: translateY(10px); border-top: 4px solid #00915f;
        }
        .mini-carrinho-dropdown::before {
            content: ""; position: absolute; top: -10px; right: 15px;
            border-width: 0 10px 10px 10px; border-style: solid;
            border-color: transparent transparent #00915f transparent;
        }
        .carrinho-icone:hover .mini-carrinho-dropdown { visibility: visible; opacity: 1; transform: translateY(0); }
        .mini-carrinho-lista { max-height: 300px; overflow-y: auto; padding: 0; margin: 0; list-style: none; }
        .mini-carrinho-item { display: flex; align-items: center; padding: 10px 15px; border-bottom: 1px solid #eee; }
        .mini-carrinho-item:hover { background-color: #f9f9f9; }
        .mini-carrinho-img { width: 50px; height: 50px; object-fit: contain; border: 1px solid #ddd; border-radius: 4px; margin-right: 10px; }
        .mini-carrinho-detalhes { flex: 1; }
        .mini-carrinho-nome { font-size: 0.9rem; color: #333; font-weight: 600; display: block; margin-bottom: 3px; }
        .mini-carrinho-preco { font-size: 0.85rem; color: #666; }
        .mini-carrinho-rodape { padding: 15px; background-color: #f8f8f8; border-radius: 0 0 8px 8px; text-align: center; }
        .mini-carrinho-total { display: flex; justify-content: space-between; font-weight: bold; font-size: 1.1rem; color: #333; margin-bottom: 10px; }
        .btn-ir-carrinho {
            background-color: #00915f; color: white; text-decoration: none; padding: 10px 20px;
            border-radius: 5px; display: block; font-weight: bold; transition: background 0.2s;
        }
        .btn-ir-carrinho:hover { background-color: #007a4f; }
        .carrinho-vazio-msg { padding: 20px; text-align: center; color: #888; font-style: italic; }

        /* --- NOVO CSS DO RODAPÉ (VERTICAL E VERDE ESCURO) --- */
        footer {
            background-color: #0b3d2b; /* Verde Escuro */
            border-top: 5px solid #00ff88; /* Linha verde neon no topo para destaque */
            padding-top: 50px;
            margin-top: 50px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #ffffff; /* Texto Branco */
            text-align: center; /* Centraliza tudo no rodapé */
        }

        .footer-content {
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 40px;
            padding: 0 20px 40px 20px;
        }

        .footer-column {
            width: 100%;
            max-width: 500px;
        }

        .footer-logo {
            max-width: 140px;
            margin-bottom: 30px; /* Aumentei a margem para separar do resto */
            background: white;
            padding: 10px;
            border-radius: 8px;
            display: inline-block; /* Garante que a logo fique centralizada */
        }

        .footer-column h4 {
            color: #00ff88;
            font-size: 1.2rem;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 2px solid #00ff88;
            padding-bottom: 8px;
            display: inline-block;
        }

        .footer-column p {
            line-height: 1.6;
            color: #e0e0e0;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .phone-number {
            font-size: 1.4rem !important;
            font-weight: bold;
            color: #fff !important;
            margin-top: 5px;
        }

        .social-icons {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .social-icons img {
            width: 36px;
            height: 36px;
            transition: transform 0.2s;
            /* Cores originais (sem filtro) */
        }

        .social-icons img:hover {
            transform: scale(1.2);
        }

        .footer-bottom-bar {
            background-color: #06291d;
            padding: 30px 20px;
            text-align: center;
            border-top: 1px solid #1a5c45;
        }

        .copyright {
            font-size: 0.9rem;
            color: #88cba9;
            margin-bottom: 20px;
        }

        .footer-payment-icons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .footer-payment-icons img {
            height: 35px;
            object-fit: contain;
            background: rgba(255,255,255,0.9);
            padding: 3px;
            border-radius: 4px;
        }

        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #00ff88;
            color: #0b3d2b;
            width: 45px;
            height: 45px;
            text-align: center;
            line-height: 45px;
            border-radius: 50%;
            text-decoration: none;
            font-size: 1.4rem;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0,0,0,0.4);
            z-index: 999;
            transition: transform 0.3s;
        }

        .back-to-top:hover {
            transform: translateY(-5px);
            background-color: #fff;
        }
    </style>
</head>

<body>
    
    <header>
        <div class="cabecalho">
            <a href="index.php">
                <img src="Imagens/logo 2.png" alt="Arduino Amazônia">
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
                <a href="carrinho.php" style="text-decoration: none;">
                    <img src="Imagens/carrinho.png" alt="Carrinho">
                    <?php if($contador > 0): ?>
                        <span class="contador" style="background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; vertical-align: top; margin-left: -10px;">
                            <?= $contador ?>
                        </span>
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

    <section class="banner-container">
        <img src="Imagens/banner 2.png" alt="banner da loja virtual">
    </section>

    <main>
        <div class="cards-container">
            <div class="card">
                <a href="contato.php"><img src="Imagens/codigo.png" alt="Código"></a>
                <div class="card-texto">
                    <h3>Encomende seu <strong>código</strong> agora mesmo</h3>
                    <a href="contato.php" class="botao-card">Clique Aqui <span>&#10132;</span></a>
                </div>
            </div>
            <div class="card">
                <a href="produtos.php"><img src="Imagens/arduino-produto.png" alt="Kit Arduino"></a>
                <div class="card-texto">
                    <h3>Peça o seu <strong>Kit Arduino</strong></h3>
                    <a href="produtos.php" class="botao-card">Clique Aqui <span>&#10132;</span></a>
                </div>
            </div>
            <div class="card">
                <a href="contato.php"><img src="Imagens/arduino 2.webp" alt="Arduino"></a>
                <div class="card-texto">
                    <h3>Encomende <strong>o seu projeto completo</strong></h3>
                    <a href="contato.php" class="botao-card">Clique Aqui <span>&#10132;</span></a>
                </div>
            </div>
        </div>

        <section class="mais-vendidos">
            <h2 style="text-align: center; color: #333; margin-top: 40px;">🔥 Mais Vendidos</h2>
            
            <div class="container-destaques">
                
                <a href="kit-uno.php" class="produto-card">
                    <img src="Imagens/esgotado.png" class="selo-esgotado-home" alt="Esgotado">
                    <img src="Imagens/Kit Uno Iniciante – 80 Peças para Arduino.png" alt="Kit Uno Iniciante">
                    <h3>Kit Uno Iniciante – 80 Peças</h3>
                    <p class="preco preco-antigo">R$ 70,00</p>
                    <span class="btn-ver btn-indisponivel">Indisponível</span>
                </a>
                
                <a href="arduino-nano.php" class="produto-card">
                    <img src="Imagens/Arduino Nano V3.0 Entrada USB-C (sem cabo).jpg" alt="Arduino Nano">
                    <h3>Arduino Nano V3.0 USB-C</h3>
                    <p class="preco">R$ 45,50</p>
                    <span class="btn-ver">Ver Detalhes</span>
                </a>
                
                <a href="jumper-femea.php" class="produto-card" id="promo-jumper">
                    <img src="Imagens/Jumper Fêmea - Fêmea x20 uni 20cm.jpg" alt="Jumper Fêmea">
                    <h3>Jumper Fêmea/Fêmea x 20</h3>
                    <div class="preco">
                        <span class="preco-antigo">R$ 15,00</span>
                        <span class="preco-novo">R$ 12,00</span>
                    </div>
                    <span class="btn-ver">Ver Detalhes</span>
                </a>
                
                <a href="modulo-gas.php" class="produto-card">
                    <img src="Imagens/Módulo Sensor de Gás Inflamável e Fumaça MQ-2 2.jpg" alt="Módulo Sensor de Gás">
                    <h3>Módulo Sensor de Gás MQ-2</h3>
                    <p class="preco">R$ 18,50</p>
                    <span class="btn-ver">Ver Detalhes</span>
                </a>

            </div>
        </section>
    </main>

    <footer>
        <img src="Imagens/logo.png" alt="Arduino Amazônia" class="footer-logo">

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
                <img src="imagens/mercado.png" alt="MercadoPago">
                <img src="Imagens/elo.png" alt="Elo">
                <img src="Imagens/boleto.png" alt="Boleto">
            </div>
        </div>
        
        <a href="#" class="back-to-top" title="Voltar ao topo">↑</a>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cardPromo = document.getElementById('promo-jumper');

            if (cardPromo) {
                const elementoPrecoAntigo = cardPromo.querySelector('.preco-antigo');
                const elementoPrecoNovo = cardPromo.querySelector('.preco-novo');
                const containerPreco = cardPromo.querySelector('.preco');

                // Converte texto para número
                const valorAntigo = parseFloat(elementoPrecoAntigo.innerText.replace('R$', '').replace('.', '').replace(',', '.').trim());
                const valorNovo = parseFloat(elementoPrecoNovo.innerText.replace('R$', '').replace('.', '').replace(',', '.').trim());

                if (valorAntigo > valorNovo) {
                    const desconto = Math.round(((valorAntigo - valorNovo) / valorAntigo) * 100);
                    const economia = (valorAntigo - valorNovo).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

                    const badge = document.createElement('div');
                    badge.className = 'badge-desconto';
                    badge.innerText = `${desconto}% OFF`;
                    cardPromo.appendChild(badge);
                    
                    const tooltip = document.createElement('span');
                    tooltip.className = 'tooltip-economia';
                    tooltip.innerText = `Economize ${economia}!`;
                    containerPreco.appendChild(tooltip);

                    cardPromo.classList.add('card-promocao-ativa');
                    containerPreco.style.display = 'block';
                    containerPreco.style.textAlign = 'center';
                    
                    elementoPrecoAntigo.innerHTML = `de ${elementoPrecoAntigo.innerText}`;
                    elementoPrecoNovo.innerHTML = `por ${elementoPrecoNovo.innerText}`;
                    
                    elementoPrecoAntigo.classList.add('preco-antigo-topo');
                    elementoPrecoNovo.classList.add('preco-destaque-grande');
                }
            }
        });
    </script>

</body>
</html>