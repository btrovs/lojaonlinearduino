<?php
session_start();

// Inicializa o carrinho
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Contador e total
$contador = array_sum(array_column($_SESSION['carrinho'], 'quantidade'));
$total = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $total += $item['preco'] * $item['quantidade'];
}

// PRODUTOS (LISTA COMPLETA E ATUALIZADA com as últimas correções de imagem)
$produtos = [
    1 => ["nome" => "Arduino Nano V3.0 Entrada USB-C (sem cabo)", "preco" => 45.50, "img" => "Imagens/Arduino Nano V3.0 Entrada USB-C (sem cabo).jpg", "categoria" => "Arduino, Placas e Protoboards", "disponibilidade" => "Em Estoque", "promocao" => false, "preco_antigo" => 0.00],
    2 => ["nome" => "Kit Uno Iniciante – 80 Peças para Arduino", "preco" => 70.00, "img" => "Imagens/Kit Uno Iniciante – 80 Peças para Arduino.png", "categoria" => "Kits Arduino, Educação e Iniciantes", "disponibilidade" => "Esgotado", "promocao" => false, "preco_antigo" => 0.00],
    3 => ["nome" => "Micro Motor 3-6V", "preco" => 12.00, "img" => "Imagens/Micro Motor 3-6V.webp", "categoria" => "Motores, Robótica", "disponibilidade" => "Em Estoque", "promocao" => false, "preco_antigo" => 0.00],
    4 => ["nome" => "Módulo WiFi ESP8266 NodeMcu LiLon CH340G", "preco" => 32.90, "img" => "Imagens/Módulo WiFi ESP8266 NodeMcu LiLon CH340G.jpg", "categoria" => "Módulos, Conectividade", "disponibilidade" => "Em Estoque", "promocao" => false, "preco_antigo" => 0.00],
    5 => ["nome" => "Cabo USB-C 7.1A Leon 1000mm", "preco" => 18.00, "img" => "Imagens/Cabo Usb-C 7.1A Leon 1000mm.png", "categoria" => "Cabos, Acessórios", "disponibilidade" => "Esgotado", "promocao" => false, "preco_antigo" => 0.00],
    6 => ["nome" => "Cabo Micro USB para Esp32, Esp8266 e Arduino Leonardo", "preco" => 22.00, "img" => "Imagens/Cabo Micro USB para Esp32, Esp8266 e Arduino Leonardo.jpeg", "categoria" => "Cabos, Acessórios", "disponibilidade" => "Em Estoque", "promocao" => true, "preco_antigo" => 25.00],
    7 => ["nome" => "Antena Wi-fi Adaptador Wireless 1800mbps USB 2.0 Receptor", "preco" => 45.00, "img" => "Imagens/Antena Wi-fi Adaptador Wireless 1800mbps Usb 2.0 Receptor.webp", "categoria" => "Outros", "disponibilidade" => "Em Estoque", "promocao" => false, "preco_antigo" => 0.00],
    8 => ["nome" => "Placa Arduino UNO R3 (Compatível)", "preco" => 55.00, "img" => "Imagens/arduino 3.png", "categoria" => "Placas, Arduino", "disponibilidade" => "Em Estoque", "promocao" => false, "preco_antigo" => 0.00],
    9 => ["nome" => "Display Gráfico LCD 128x64 para Impressora 3D RAMPS RepRap", "preco" => 60.00, "img" => "Imagens/Display Gráfico LCD 128×64 para Impressora 3D RAMPS RepRap.jpg", "categoria" => "Displays, Impressora 3D", "disponibilidade" => "Em Estoque", "promocao" => false, "preco_antigo" => 0.00],
    10 => ["nome" => "Display Led 7 Segmentos 1 Dígito – Cátodo", "preco" => 4.50, "img" => "Imagens/Display Led 7 Segmentos 1 Dígito – Cátodo.jpg", "categoria" => "Displays, Componentes", "disponibilidade" => "Em Estoque", "promocao" => true, "preco_antigo" => 5.50],
    11 => ["nome" => "Fonte 9V 2A Arduino e Uso Geral", "preco" => 27.50, "img" => "Imagens/Fonte 9V 2A Arduino e uso geral.jpg", "categoria" => "Fontes, Acessórios", "disponibilidade" => "Em Estoque", "promocao" => false, "preco_antigo" => 0.00],
    12 => ["nome" => "Jumper Fêmea/Fêmea x 20 Unidades de 20cm", "preco" => 12.00, "img" => "Imagens/Jumper Fêmea - Fêmea x20 uni 20cm.jpg", "categoria" => "Cabos, Jumpers", "disponibilidade" => "Em Estoque", "promocao" => false, "preco_antigo" => 0.00],
    13 => ["nome" => "Kit Resistor 1/4W x 10 Unidades", "preco" => 4.00, "img" => "Imagens/Kit resistor 1-4W x10 Unidades.jpg", "categoria" => "Kits, Componentes", "disponibilidade" => "Em Estoque", "promocao" => false, "preco_antigo" => 0.00],
    14 => ["nome" => "Kit Montagem Robô Seguidor de Linha 2 Rodas", "preco" => 150.00, "img" => "Imagens/Kit Montagem Robô Seguidor de Linha 2 Rodas.jpg", "categoria" => "Kits, Robótica", "disponibilidade" => "Em Estoque", "promocao" => false, "preco_antigo" => 0.00],
    15 => ["nome" => "Módulo de Sensor de Gás Inflamável e Fumaça MQ-2", "preco" => 18.50, "img" => "Imagens/Módulo Sensor de Gás Inflamável e Fumaça MQ-2 2.jpg", "categoria" => "Sensores, Módulos", "disponibilidade" => "Em Estoque", "promocao" => false, "preco_antigo" => 0.00],
    16 => ["nome" => "Módulo de Sensor de Chuva", "preco" => 15.00, "img" => "Imagens/Módulo sensor de chuva.png", "categoria" => "Sensores, Módulos", "disponibilidade" => "Em Estoque", "promocao" => false, "preco_antigo" => 0.00],
    17 => ["nome" => "Placa de Controle de Nível Automático Caixa d'Água Liga e Desliga Bomba", "preco" => 75.00, "img" => "Imagens/Placa Controle Nível Automático Caixa Água Liga E Desliga Bomba.jpg", "categoria" => "Placas, Controle", "disponibilidade" => "Em Estoque", "promocao" => false, "preco_antigo" => 0.00],
    18 => ["nome" => "Protoboard 830 Pontos", "preco" => 30.00, "img" => "Imagens/Protoboard 830 Pontos.jpg", "categoria" => "Protoboards, Placas", "disponibilidade" => "Em Estoque", "promocao" => false, "preco_antigo" => 0.00],
    19 => ["nome" => "Sensor de Cor TCS3200", "preco" => 45.00, "img" => "Imagens/Sensor de Cor TCS3200.jpg", "categoria" => "Sensores, Robótica", "disponibilidade" => "Em Estoque", "promocao" => false, "preco_antigo" => 0.00],
    20 => ["nome" => "Sensor de Temperatura LM35 DZ", "preco" => 8.00, "img" => "Imagens/Sensor de Temperatura LM35 DZ.jpg", "categoria" => "Sensores", "disponibilidade" => "Em Estoque", "promocao" => false, "preco_antigo" => 0.00],
    21 => ["nome" => "Kit de 20 Leds de 5mm Difusos Coloridos", "preco" => 10.00, "img" => "Imagens/Kit 20 Leds 5mm difusos coloridos.jpg", "categoria" => "Componentes, Leds", "disponibilidade" => "Em Estoque", "promocao" => false, "preco_antigo" => 0.00]
];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Produtos - Arduino Amazônia</title>
    <link rel="stylesheet" href="produtos.css">
    <link rel="stylesheet" href="index.css">
    <style>
        /* Estilos CSS Específicos para os Cards */
        .card {
            position: relative;
        }

        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            color: white;
            font-weight: bold;
            border-radius: 4px;
            font-size: 0.8em;
            z-index: 10;
        }

        .promo-badge {
            background-color: #e74c3c;
            /* Vermelho para promoção */
        }

        .esgotado-badge {
            background-color: #e74c3c;
            /* VERMELHO para Esgotado */
        }

        .preco-container {
            font-size: 1.2em;
            font-weight: 700;
        }

        .preco-antigo {
            text-decoration: line-through;
            color: #999;
            font-size: 0.8em;
            font-weight: 400;
            display: block;
        }

        .preco-novo {
            color: #1c593a;
            /* Verde principal */
        }

        /* NOVO ESTILO: Botão Indisponível (Esgotado) */
        .botao-verde[disabled] {
            background-color: #95a5a6 !important;
            /* Cinza para o botão */
            cursor: not-allowed;
            opacity: 0.8;
        }
    </style>
</head>

<body>

    <header>
        <div class="cabecalho">
            <a href="index.php"><img src="Imagens/logo 2.png" alt="Logo da loja Arduino Amazônia"></a>
            <nav>
                <ul class="cabecalho-lista">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="produtos.php" class="ativo">Produtos</a></li>
                    <li><a href="carrinho.php">Carrinho</a></li>
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
                <a href="carrinho.php"><img src="Imagens/carrinho.png" alt="Carrinho"></a>
                <span class="contador"><?= $contador ?></span>
                <span class="valor-total">R$<?= number_format($total, 2, ',', '.') ?></span>
            </div>
        </div>
    </header>

    <div id="menu-overlay"></div>
    </div>
    <section class="filtros">
        <div class="filtro-esq">
            <button class="modo ativo"><i class="fas fa-th"></i></button>
            <button class="modo"><i class="fas fa-list"></i></button>
        </div>
        <div class="filtro-dir">
            <select id="ordenacao">
                <option value="padrao">Ordenação padrão</option>
                <option value="menor-preco">Menor preço</option>
                <option value="maior-preco">Maior preço</option>
            </select>

            <select id="mostrar">
                <option value="10">Mostrar 10</option>
                <option value="20">Mostrar 20</option>
                <option value="todos">Mostrar todos</option>
            </select>
        </div>
    </section>

    <main class="produtos">
        <?php foreach ($produtos as $id => $p):
        ?>
            <?php
            // Determina o link do produto
            $link = match ($id) {
                1 => "arduino-nano.php",
                2 => "kit-uno.php",
                3 => "micro-motor.php",
                4 => "modulo-wifi.php",
                5 => "cabo-usb.php",
                6 => "cabo-micro.php",
                7 => "antena.php",
                8 => "arduino.php",
                9 => "display-grafico.php",
                10 => "display-led.php",
                11 => "fonte-arduino.php",
                12 => "jumper-femea.php",
                13 => "kit-resistor.php",
                14 => "kit-robo.php",
                15 => "modulo-gas.php",
                16 => "modulo-sensor.php",
                17 => "placa-controle.php",
                18 => "protoboard.php",
                19 => "sensor-cor.php",
                20 => "sensor-temperatura.php",
                21 => "leds.php",
                default => "produto.php?id=" . $id,
            };

            // Verifica status
            $esgotado = $p['disponibilidade'] === 'Esgotado';
            $promocao = $p['promocao'];

            // Calcula o desconto para exibição do badge
            $desconto = 0;
            if ($promocao && $p['preco_antigo'] > 0) {
                $desconto = round((($p['preco_antigo'] - $p['preco']) / $p['preco_antigo']) * 100);
            }
            ?>
            <div class="card" data-preco="<?= $p['preco'] ?>">

                <?php if ($esgotado): ?>
                    <span class="status-badge esgotado-badge">ESGOTADO</span>
                <?php elseif ($promocao): ?>
                    <span class="status-badge promo-badge"><?= $desconto ?>% OFF</span>
                <?php endif; ?>

                <a href="<?= $link ?>" class="card-link">
                    <img src="<?= htmlspecialchars($p['img']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>">
                </a>

                <p class="classificacao"><?= htmlspecialchars($p['categoria']) ?></p>
                <h3><?= htmlspecialchars($p['nome']) ?></h3>

                <p class="preco-container">
                    <?php if ($promocao && $p['preco_antigo'] > 0): ?>
                        <span class="preco-antigo">R$<?= number_format($p['preco_antigo'], 2, ',', '.') ?></span>
                        <span class="preco-novo">R$<?= number_format($p['preco'], 2, ',', '.') ?></span>
                    <?php else: ?>
                        <span class="preco-novo">R$<?= number_format($p['preco'], 2, ',', '.') ?></span>
                    <?php endif; ?>
                </p>

                <form action="salvar_carrinho.php" method="post" class="form-carrinho">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <input type="hidden" name="nome" value="<?= htmlspecialchars($p['nome']) ?>">
                    <input type="hidden" name="preco" value="<?= $p['preco'] ?>">
                    <input type="hidden" name="quantidade" value="1">
                    <button type="submit" class="botao-verde" <?= $esgotado ? 'disabled' : '' ?>>
                        <?= $esgotado ? 'Indisponível' : 'Adicionar ao Carrinho' ?>
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
        <section class="produtos">
            <?php foreach ($produtos as $id => $p):
                $disponivel = $p['disponibilidade'] === 'Em Estoque';
            ?>
                <div class="card" data-preco="<?= $p['preco'] ?>" data-disponivel="<?= $disponivel ? 'true' : 'false' ?>">
                    <a href="#" class="card-link">
                    </a>

                    <form action="salvar_carrinho.php" method="POST" class="form-carrinho">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="hidden" name="nome" value="<?= htmlspecialchars($p['nome']) ?>">
                        <input type="hidden" name="preco" value="<?= $p['preco'] ?>">
                        <input type="hidden" name="quantidade" value="1">
                        <input type="hidden" name="img" value="<?= htmlspecialchars($p['img']) ?>">
                        <button type="submit" class="botao-verde" <?= $disponivel ? '' : 'disabled style="background-color: #95a5a6;"' ?>>
                            <?= $disponivel ? 'Adicionar ao Carrinho' : 'Indisponível' ?>
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-column contact-info">
                <img src="Imagens/logo 2.png" alt="Logo da loja Arduino Amazônia" class="footer-logo">
                <h4>ATENDIMENTO EM HORÁRIO COMERCIAL!</h4>
                <p class="phone-number">(91) 93834-1434</p>

                <h4>Endereço</h4>
                <p>Funcionamos apenas online, com produtos a pronta entrega. Os clientes podem retirar os produtos, ou
                    podemos enviar via Correios ou Uber Flash (Valores repassados via WhatsApp ou Email).</p>

                <div class="social-icons">
                    <a href="#"><img src="Imagens/facebook.png" alt="Facebook"></a>
                    <a href="#"><img src="Imagens/whatsapp.png" alt="WhatsApp"></a>
                    <a href="#"><img src="Imagens/instagram.png" alt="Instagram"></a>
                </div>
            </div>

            <div class="footer-column about-us">
                <h4>Sobre Nós</h4>
                <p> A loja Arduino Belém, nasceu com o intuito de fomentar a demanda local por produtos e equipamentos
                    necessários para o desenvolvimento maker local, e temos como missão permitir a Engenheiros,
                    Professores, Inventores, Designers, Estudantes e até mesmo curiosos da tecnologia, o acesso a
                    tecnologias e equipamentos, que possibilitem a concretização das suas ideias e projetos em
                    realidade. A Arduino Belém não é só uma loja, prestamos consultoria em projetos, produzimos códigos
                    sob encomenda.</p>
            </div>

            <div class="footer-column attention">
                <h4>Atenção:</h4>
                <p>As imagens do site são meramente ilustrativas, entre em contato para saber mais!</p>
            </div>
        </div>

        <div class="footer-bottom-bar">
            <p class="copyright">&copy; Arduino Amazônia - CNPJ: xx.xxx.xxx/xxxx-xx - Todos os direitos reservados - 2025</p>

            <div class="footer-payment-icons">
                <img src="Imagens/visa.webp" alt="Visa">
                <img src="Imagens/mastercard.png" alt="Mastercard">
                <img src="Imagens/Pix.png" alt="pix">
                <img src="imagens/mercado.png" alt="mercadopago">
                <img src="Imagens/elo.png" alt="Elo">
                <img src="Imagens/boleto.png" alt="boleto">
            </div>
        </div>

        <a href="#" class="back-to-top" title="Voltar ao topo">↑</a>
    </footer>
    <script>
        document.querySelectorAll('.form-carrinho').forEach(form => {
            form.addEventListener('submit', async e => {
                e.preventDefault();
                const dados = new FormData(form);
                const botao = form.querySelector('.botao-verde');

                if (botao.disabled) return;

                const resposta = await fetch('salvar_carrinho.php', {
                    method: 'POST',
                    body: dados
                });
                const json = await resposta.json();

                if (json.sucesso) {
                    document.querySelector('.contador').textContent = json.quantidade;
                    document.querySelector('.valor-total').textContent = 'R$' + json.total_formatado;
                }

                const textoOriginal = 'Adicionar ao Carrinho';
                const corOriginal = "#1c593a";

                botao.textContent = "Adicionado!";
                botao.style.backgroundColor = "#00915f";

                setTimeout(() => {
                    botao.textContent = textoOriginal;
                    botao.style.backgroundColor = corOriginal;
                }, 1200);
            });
        });

        // Script de atualização automática do cabeçalho (contador_carrinho.php)
        async function atualizarCabecalho() {
            const resposta = await fetch('contador_carrinho.php');
            const json = await resposta.json();
            document.querySelector('.contador').textContent = json.quantidade;
            document.querySelector('.valor-total').textContent = 'R$' + json.total_formatado;
        }

        // Atualiza automaticamente o cabeçalho a cada 5 segundos
        setInterval(atualizarCabecalho, 5000);
    </script>

    <script src="produtos.js"></script>
    <script src="promocoes.js"></script>
    <script src="index.js"></script>

</body>

</html>