<?php
session_start();

// Inicializa contadores do carrinho
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
    <title>Todos os Produtos</title>
    <link rel="stylesheet" href="produtos.css">
    <style>
        /* Importação de Fonte e Reset */
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0; padding: 0; background-color: #f8f9fa; color: #333;
        }

        a { text-decoration: none; color: inherit; }
        ul { list-style: none; padding: 0; margin: 0; }

        /* Header e Navegação */
        header {
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: relative; z-index: 100;
        }
        .cabecalho {
            display: flex; justify-content: space-around; align-items: center; padding: 15px 5%; flex-wrap: wrap;
        }
        .cabecalho img { max-height: 50px; }
        .cabecalho-lista { display: flex; gap: 20px; }

        /* Barra de Pesquisa e Carrinho */
        .barra-pesquisa-container {
            display: flex; align-items: center; justify-content: center; background-color: #1c593a; padding: 15px 5%; gap: 20px; position: relative;
        }
        .barra-pesquisa {
            display: flex; align-items: center; width: 100%; max-width: 700px; background-color: white; border-radius: 30px; overflow: hidden;
        }
        .campo-pesquisa { flex: 2; border: none; padding: 10px 15px; outline: none; font-size: 16px; }
        .botao-pesquisa { background-color: #1c593a; border: none; padding: 10px 15px; cursor: pointer; }
        .botao-pesquisa img { width: 20px; height: 20px; filter: brightness(0) invert(1); }

        .carrinho-icone {
            display: flex; align-items: center; gap: 8px; position: absolute; right: 5%; height: 100%; top: 0;
        }
        .carrinho-icone > a { display: flex; align-items: center; }
        .carrinho-icone img { width: 28px; height: 28px; filter: brightness(0) invert(1); }

        /* --- CSS DA PRÉVIA DO CARRINHO --- */
        .mini-carrinho-dropdown {
            visibility: hidden; opacity: 0; position: absolute; top: 80%; right: 0; width: 320px;
            background-color: #fff; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); z-index: 1000;
            transition: all 0.3s ease; transform: translateY(10px); border-top: 4px solid #00915f; color: #333; text-align: left;
        }
        .mini-carrinho-dropdown::before {
            content: ""; position: absolute; top: -10px; right: 10px;
            border-width: 0 10px 10px 10px; border-style: solid; border-color: transparent transparent #00915f transparent;
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
            border-radius: 5px; display: block; font-weight: bold; transition: background 0.2s; text-align: center;
        }
        .btn-ir-carrinho:hover { background-color: #007a50; }
        .carrinho-vazio-msg { padding: 20px; text-align: center; color: #888; font-style: italic; }

        /* --- CSS DA PROMOÇÃO DINÂMICA PADRONIZADA --- */
        .badge-desconto {
            position: absolute; top: -10px; left: -10px; /* Ajuste leve para grid */
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
            font-size: 0.85rem; white-space: nowrap; box-shadow: 0px 4px 6px rgba(0,0,0,0.15); pointer-events: none;
        }
        .tooltip-economia::after {
            content: ""; position: absolute; top: 100%; left: 50%; margin-left: -5px;
            border-width: 5px; border-style: solid; border-color: #2ecc71 transparent transparent transparent;
        }
        
        /* Ajuste específico para o hover funcionar neste layout */
        .preco { position: relative; cursor: help; }
        .preco:hover .tooltip-economia { visibility: visible; opacity: 1; bottom: 115%; }
        
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 2px 2px 5px rgba(0,0,0,0.2); }
            50% { transform: scale(1.1); box-shadow: 2px 2px 15px rgba(255, 71, 87, 0.6); }
            100% { transform: scale(1); box-shadow: 2px 2px 5px rgba(0,0,0,0.2); }
        }
        
        .card-promocao-ativa { border: 1px solid #ff4757 !important; }
        .preco-destaque-grande { color: #e84118 !important; font-size: 1.8rem !important; font-weight: 800; display: block; }
        .preco-antigo-topo { display: block; color: #a4b0be; font-size: 0.9rem; text-decoration: line-through; margin-bottom: -5px; margin-right: 0 !important; }

        /* Filtros e Controles */
        .barra-filtros {
            max-width: 1120px; margin: 20px auto 0; padding: 15px 40px;
            display: flex; justify-content: space-between; align-items: center;
            background-color: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .barra-filtros label { font-weight: bold; color: #555; margin-right: 15px; }
        .barra-filtros select { padding: 10px 20px; border: 2px solid #eee; border-radius: 30px; background-color: #fff; cursor: pointer; outline: none; }

        .controles-visualizacao { display: flex; gap: 10px; }
        .btn-view {
            border: 1px solid #ddd; background: white; padding: 8px 15px; border-radius: 20px;
            cursor: pointer; font-weight: bold; color: #555; transition: all 0.3s;
            display: flex; align-items: center; gap: 5px; font-size: 0.9rem;
        }
        .btn-view span { font-size: 1.2rem; line-height: 1; }
        .btn-view:hover, .btn-view.ativo { background-color: #00915f; color: white; border-color: #00915f; }

        /* Grid de Produtos */
        .container-produtos {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 25px; padding: 30px 40px 60px 40px; max-width: 1200px; margin: 0 auto;
        }

        /* Card de Produto - Estilo Base */
        .produto-card {
            position: relative; background: white; padding: 15px; border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center;
            text-decoration: none; color: inherit; transition: transform 0.3s ease;
            display: flex; flex-direction: column; justify-content: space-between;
            height: auto; min-height: 380px; border: 1px solid #f0f0f0;
        }
        .produto-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.15); border-color: #00915f; }
        .produto-card img { width: 100%; height: 160px; object-fit: contain; margin-bottom: 15px; }
        .produto-card h3 { font-size: 1rem; color: #333; margin: 10px 0; font-weight: 600; }
        .produto-card .preco { color: #00915f; font-size: 1.5em; font-weight: bold; }

        .btn-ver {
            background: #00915f; color: white; padding: 12px; border-radius: 8px;
            font-weight: bold; display: block; margin-top: 10px;
        }
        .btn-ver:hover { background: #007a50; }

        .selo-esgotado-lista {
            position: absolute; top: 10px; right: 10px; width: 70px; z-index: 20;
            transform: rotate(-15deg); filter: drop-shadow(2px 2px 2px rgba(0,0,0,0.3));
        }

        /* Classes auxiliares para o JS (preços) */
        .preco-antigo { font-size: 0.9rem; color: #999; text-decoration: line-through; margin-right: 10px; }
        .preco-novo { color: #00915f; }

        /* Visualização em Lista */
        .container-produtos.modo-lista { grid-template-columns: 1fr; gap: 15px; }
        .container-produtos.modo-lista .produto-card {
            flex-direction: row; align-items: center; text-align: left;
            height: auto; min-height: auto; padding: 20px;
        }
        .container-produtos.modo-lista .produto-card img { width: 140px; height: 140px; margin-bottom: 0; margin-right: 30px; }
        .container-produtos.modo-lista .produto-card h3 { flex-grow: 1; font-size: 1.3rem; margin: 0; }
        .container-produtos.modo-lista .info-direita {
            display: flex; flex-direction: column; align-items: flex-end; min-width: 150px; margin-left: 20px;
        }
        .container-produtos.modo-lista .produto-card .btn-ver { width: 100%; text-align: center; margin-top: 0; }

        /* Responsividade */
        @media (max-width: 600px) {
            .container-produtos.modo-lista .produto-card { flex-direction: column; text-align: center; }
            .container-produtos.modo-lista .produto-card img { margin-right: 0; margin-bottom: 15px; }
            .container-produtos.modo-lista .info-direita { align-items: center; margin-left: 0; width: 100%; }
            .barra-filtros { flex-direction: column; gap: 15px; }
            .barra-pesquisa-container { flex-direction: column; gap: 10px; padding-bottom: 15px; }
            .carrinho-icone { position: static; margin-top: 10px; justify-content: center;}
            .barra-pesquisa { width: 100%; }
        }
    </style>
</head>
<body>

    <header>
        <div class="cabecalho">
            <a href="index.php"><img src="Imagens/logo 2.png" alt="Logo"></a>
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
                <button type="submit" class="botao-pesquisa"><img src="Imagens/lupa.png" alt="Pesquisar"></button>
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

    <h1 style="text-align: center; margin-top: 40px; color: #333; font-size: 2em;">Nossos Produtos</h1>

    <div class="barra-filtros">
        <div class="controles-visualizacao">
            <button class="btn-view ativo" onclick="mudarVisualizacao('grade')" id="btn-grade">
                <span>&#8862;</span> Grade
            </button>
            <button class="btn-view" onclick="mudarVisualizacao('lista')" id="btn-lista">
                <span>&#9776;</span> Lista
            </button>
        </div>

        <div>
            <label for="qtd-select">Mostrar:</label>
            <select id="qtd-select" onchange="filtrarQuantidade()">
                <option value="12">12 produtos</option>
                <option value="20" selected>20 produtos</option>
                <option value="todos">Todos</option>
            </select>
        </div>
    </div>

    <div class="container-produtos" id="lista-produtos">
        
        <a href="antena.php" class="produto-card">
            <img src="Imagens/antena.webp" alt="Antena">
            <h3>Antena Wi-fi Adaptador</h3>
            <p class="preco">R$ 45,00</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>

        <a href="arduino.php" class="produto-card">
            <img src="Imagens/arduino 3.png" alt="Arduino">
            <h3>Placa Arduino UNO R3</h3>
            <p class="preco">R$ 55,00</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>
        
        <a href="kit-uno.php" class="produto-card">
            <img src="Imagens/esgotado.png" class="selo-esgotado-lista" alt="Esgotado">
            <img src="Imagens/Kit Uno Iniciante – 80 Peças para Arduino.png" alt="Kit Uno">
            <h3>Kit Uno Iniciante</h3>
            <p class="preco" style="color: #999; text-decoration: line-through;">R$ 70,00</p>
            <span class="btn-ver" style="background-color: #999;">Indisponível</span>
        </a>

        <a href="modulo-gas.php" class="produto-card">
            <img src="Imagens/Módulo Sensor de Gás Inflamável e Fumaça MQ-2 2.jpg" alt="Sensor Gás">
            <h3>Módulo Sensor de Gás</h3>
            <p class="preco">R$ 18,50</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>

        <a href="kit-resistor.php" class="produto-card">
            <img src="Imagens/Kit resistor 1-4W x10 Unidades.jpg" alt="Resistores">
            <h3>Kit Resistor 1/4W</h3>
            <p class="preco">R$ 4,00</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>

        <a href="leds.php" class="produto-card">
            <img src="Imagens/Kit 20 Leds 5mm difusos coloridos.jpg" alt="LEDs">
            <h3>Kit 20 LEDs Coloridos</h3>
            <p class="preco">R$ 10,00</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>

        <a href="arduino-nano.php" class="produto-card">
            <img src="Imagens/Arduino Nano V3.0 Entrada USB-C (sem cabo).jpg" alt="Nano">
            <h3>Arduino Nano V3.0</h3>
            <p class="preco">R$ 45,50</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>

        <a href="protoboard.php" class="produto-card">
            <img src="Imagens/Protoboard 830 Pontos.jpg" alt="Protoboard">
            <h3>Protoboard 830 Pontos</h3>
            <p class="preco">R$ 30,00</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>

        <a href="sensor-temperatura.php" class="produto-card">
            <img src="Imagens/Sensor de Temperatura LM35 DZ.jpg" alt="Temp">
            <h3>Sensor Temp LM35</h3>
            <p class="preco">R$ 8,00</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>

        <a href="sensor-cor.php" class="produto-card">
            <img src="Imagens/Sensor de Cor TCS3200.jpg" alt="Sensor Cor">
            <h3>Sensor de Cor TCS3200</h3>
            <p class="preco">R$ 45,00</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>

        <a href="jumper-femea.php" class="produto-card" id="promo-jumper">
            <img src="Imagens/Jumper Fêmea - Fêmea x20 uni 20cm.jpg" alt="Jumper">
            <h3>Jumper Fêmea/Fêmea</h3>
            <div class="preco">
                <span class="preco-antigo">R$ 15,00</span>
                <span class="preco-novo">R$ 12,00</span>
            </div>
            <span class="btn-ver">Ver Detalhes</span>
        </a>

        <a href="micro-motor.php" class="produto-card">
            <img src="Imagens/Micro Motor 3-6V.webp" alt="Motor">
            <h3>Micro Motor 3-6V</h3>
            <p class="preco">R$ 12,00</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>

        <a href="cabo-usb.php" class="produto-card">
            <img src="Imagens/Cabo Usb-C 7.1A Leon 1000mm.png" alt="Cabo">
            <h3>Cabo USB-C</h3>
            <p class="preco">R$ 18,00</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>
        
        <a href="placa-controle.php" class="produto-card">
            <img src="Imagens/Placa Controle Nível Automático Caixa Água Liga E Desliga Bomba.jpg" alt="Placa">
            <h3>Placa de Controle</h3>
            <p class="preco">R$ 75,00</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>

        <a href="display-grafico.php" class="produto-card">
            <img src="Imagens/Display Gráfico LCD 128×64 para Impressora 3D RAMPS RepRap.jpg" alt="Display">
            <h3>Display Gráfico LCD</h3>
            <p class="preco">R$ 60,00</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>

        <a href="display-led.php" class="produto-card">
            <img src="Imagens/Display Led 7 Segmentos 1 Dígito – Cátodo.jpg" alt="Display">
            <h3>Display 7 Segmentos</h3>
            <p class="preco">R$ 4,50</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>

        <a href="kit-robo.php" class="produto-card">
            <img src="Imagens/Kit Montagem Robô Seguidor de Linha 2 Rodas.jpg" alt="Robo">
            <h3>Kit Robô Seguidor</h3>
            <p class="preco">R$ 150,00</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>

        <a href="fonte-arduino.php" class="produto-card">
            <img src="Imagens/Fonte 9V 2A Arduino e uso geral.jpg" alt="Fonte">
            <h3>Fonte 9V 2A</h3>
            <p class="preco">R$ 27,50</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>

        <a href="modulo-sensor.php" class="produto-card">
            <img src="Imagens/Módulo Sensor de Chuva.png" alt="Chuva">
            <h3>Módulo de Chuva</h3>
            <p class="preco">R$ 15,00</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>

        <a href="cabo-micro.php" class="produto-card">
            <img src="Imagens/Cabo Micro USB para Esp32, Esp8266 e Arduino Leonardo.jpeg" alt="Cabo Micro">
            <h3>Cabo Micro USB</h3>
            <p class="preco">R$ 22,00</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>

        <a href="modulo-wifi.php" class="produto-card">
            <img src="Imagens/Módulo WiFi ESP8266 NodeMcu LiLon CH340G.jpg" alt="Wifi">
            <h3>Módulo Wi-Fi</h3>
            <p class="preco">R$ 32,90</p>
            <span class="btn-ver">Ver Detalhes</span>
        </a>

    </div>

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

    <script>
        // Filtra produtos baseado na quantidade selecionada
        function filtrarQuantidade() {
            const seletor = document.getElementById('qtd-select');
            if(!seletor) return;
            
            const valor = seletor.value;
            const produtos = document.querySelectorAll('.produto-card');
            
            produtos.forEach((produto, index) => {
                if (valor === 'todos' || index < valor) {
                    produto.style.display = 'flex';
                } else {
                    produto.style.display = 'none';
                }
            });
        }

        // Alterna entre visualização de Grade e Lista
        function mudarVisualizacao(modo) {
            const container = document.getElementById('lista-produtos');
            const btnGrade = document.getElementById('btn-grade');
            const btnLista = document.getElementById('btn-lista');
            const cards = document.querySelectorAll('.produto-card');

            if (modo === 'lista') {
                container.classList.add('modo-lista');
                btnLista.classList.add('ativo');
                btnGrade.classList.remove('ativo');

                cards.forEach(card => {
                    // Move preço e botão para a direita se ainda não estiverem lá
                    if (!card.querySelector('.info-direita')) {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'info-direita';
                        
                        const preco = card.querySelector('.preco');
                        const btn = card.querySelector('.btn-ver');

                        if(preco) wrapper.appendChild(preco);
                        if(btn) wrapper.appendChild(btn);
                        
                        card.appendChild(wrapper);
                    }
                });
            } else {
                container.classList.remove('modo-lista');
                btnGrade.classList.add('ativo');
                btnLista.classList.remove('ativo');
            }
        }

        // Inicia filtro ao carregar a página
        window.onload = filtrarQuantidade;
    </script>

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


</body>
</html>