<?php
// NADA antes deste bloco! (sem espaços/linhas)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Detalhes fixos do produto (ID 8)
$PROD_ID = 8;
$PROD_NOME = "Placa Arduino UNO R3 (Compatível)";
// Obtém dados do produto (usando a matriz expandida)
$PRODUTO = [
    "preco" => 55.00,
    "img" => "Imagens/arduino 3.png",
    "categoria" => "Placas, Arduino",
    "disponibilidade" => "Em Estoque",
    "promocao" => false,
    "preco_antigo" => 0.00
];

$PROD_PRECO = $PRODUTO['preco'];
$PROD_PRECO_ANTIGO = $PRODUTO['preco_antigo'];
$PROD_PROMOCAO = $PRODUTO['promocao'];
$PROD_DISPONIBILIDADE = $PRODUTO['disponibilidade'];
$PROD_ESGOTADO = $PROD_DISPONIBILIDADE === 'Esgotado';


// Calcula contador e total atuais do carrinho (usando o PROD_PRECO atual)
$contador = isset($_SESSION['carrinho']) ? array_sum(array_column($_SESSION['carrinho'], 'quantidade')) : 0;
$total = 0.0;
if (!empty($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        // Certifique-se de que 'preco' e 'quantidade' são numéricos
        $total += (float)($item['preco'] ?? 0.0) * (int)($item['quantidade'] ?? 0);
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
            <a href="index.php">
                <img src="Imagens/logo 2.png" alt="A logo da loja">
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
            <button id="menu-hamburguer" title="Abrir Menu">&#9776;</button>

            <form action="busca.php" method="GET" class="barra-pesquisa">
                <input type="text" name="search" placeholder="Procure por Produtos" class="campo-pesquisa">
                <button type="submit" class="botao-pesquisa">
                    <img src="Imagens/lupa.png" alt="Pesquisar">
                </button>
            </form>

            <div class="carrinho-icone">
                <a href="carrinho.php">
                    <img src="Imagens/carrinho.png" alt="Carrinho">
                </a>
                <span class="contador"><?= $contador ?></span>
                <span class="valor-total">R$<?= number_format($total, 2, ',', '.') ?></span>
            </div>
        </div>
    </header>

    <div id="header-container">
        <aside id="menu-sidebar">
            <button id="fechar-menu" title="Fechar Menu">
                &times;
            </button>
            <nav>
                <ul>
                    <li class="item-com-submenu"><a href="#">ARDUINO </a>
                        <ul class="submenu">
                            <li><a href="placa-uno.php">Placa UNO</a></li>
                            <li><a href="placa-mega.php">Placa MEGA</a></li>
                            <li><a href="modulos.php">Módulos</a></li>
                            <li><a href="motores.php">Motores</a></li>
                        </ul>
                    </li>

                    <li class="item-com-submenu"><a href="#">KITS E PROJETOS </a>
                        <ul class="submenu">
                            <li><a href="kit-iniciante.php">Kit Iniciante</a></li>
                            <li><a href="kit-avancado.php">Kit Avançado</a></li>
                        </ul>
                    </li>

                    <li class="item-com-submenu">
                        <a href="robotica-motores.php">ROBÓTICA E MOTORES </a>
                        <ul class="submenu">
                            <li><a href="micro-motor.php">Micro Motor</a></li>
                            <li><a href="kit-robo.php">Kit Robô</a></li>
                        </ul>
                    </li>

                    <li class="item-com-submenu"><a href="#">NOVIDADES </a>
                        <ul class="submenu">
                            <li><a href="promocoes.php">Promoções</a></li>
                            <li><a href="outros.php">Outros</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </aside>
        <div id="menu-overlay"></div>
    </div>

    <main class="produto-container">
        <?php if ($PROD_ESGOTADO): ?>
            <span class="status-badge esgotado-badge">ESGOTADO</span>
        <?php elseif ($PROD_PROMOCAO): ?>
            <?php $desconto = round((($PROD_PRECO_ANTIGO - $PROD_PRECO) / $PROD_PRECO_ANTIGO) * 100); ?>
            <span class="status-badge promo-badge"><?= $desconto ?>% OFF</span>
        <?php endif; ?>

        <div class="img-produto">
            <img src="<?= htmlspecialchars($PRODUTO['img']) ?>" alt="<?= htmlspecialchars($PROD_NOME) ?>">
        </div>

        <div class="info-produto">
            <p class="classificacao"><?= htmlspecialchars($PRODUTO['categoria']) ?></p>
            <h1 class="titulo-produto"><?= htmlspecialchars($PROD_NOME) ?></h1>
            <p class="disponibilidade">Disponibilidade:
                <span class="<?= $PROD_ESGOTADO ? 'esgotado-text' : 'em-estoque' ?>">
                    <?= htmlspecialchars($PROD_DISPONIBILIDADE) ?>
                </span>
            </p>

            <p class="descricao">
                A Placa Arduino UNO R3 é a placa de desenvolvimento mais popular e versátil, ideal para iniciantes em eletrônica e programação. Possui microcontrolador ATmega328, 14 pinos digitais e 6 analógicos.
            </p>

            <h2 class="preco">
                <?php if ($PROD_PROMOCAO && $PROD_PRECO_ANTIGO > 0): ?>
                    <span class="preco-antigo">R$<?= number_format($PROD_PRECO_ANTIGO, 2, ',', '.') ?></span>
                <?php endif; ?>
                R$<?= number_format($PROD_PRECO, 2, ',', '.') ?>
            </h2>

            <p class="parcelamento">
                💳 Até 12x sem juros no cartão.
            </p>

            <div class="metodos-pagamento">
                <h4>Formas de Pagamento:</h4>
                <div class="logos-pagamento">
                    <img src="Imagens/boleto.png" alt="Boleto">
                    <img src="Imagens/mastercard.png" alt="Mastercard">
                    <img src="Imagens/mercado.png" alt="Mercado Pago">
                    <img src="Imagens/pix.png" alt="Pix">
                </div>
            </div>

            <div class="frete">
                <h3>Simulação de frete</h3>
                <input type="text" placeholder="Informe seu CEP">
            </div>

            <form action="salvar_carrinho.php" method="POST" class="form-carrinho-adicionar">
                <div class="acoes">
                    <input type="number" name="quantidade" value="1" min="1" <?= $PROD_ESGOTADO ? 'disabled' : '' ?>>
                    <input type="hidden" name="id" value="<?= $PROD_ID ?>">
                    <input type="hidden" name="nome" value="<?= htmlspecialchars($PROD_NOME) ?>">
                    <input type="hidden" name="preco" value="<?= $PROD_PRECO ?>">
                    <input type="hidden" name="img" value="<?= htmlspecialchars($PRODUTO['img']) ?>">
                    <button type="submit" class="btn-carrinho" <?= $PROD_ESGOTADO ? 'disabled' : '' ?> style="<?= $PROD_ESGOTADO ? 'background-color: #95a5a6; cursor: not-allowed;' : '' ?>">
                        🛒 <?= $PROD_ESGOTADO ? 'Indisponível' : 'Adicionar ao carrinho' ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <section class="descricao-detalhada">
        <h2>Especificações Técnicas</h2>
        <ul>
            <li>- Microcontrolador: ATmega328</li>
            <li>- Tensão de Operação: 5V</li>
            <li>- Pinos Digitais: 14 (6 com saída PWM)</li>
            <li>- Pinos Analógicos: 6</li>
        </ul>
        <table>
            <div class="info-adicional">
                <strong>Categorias:</strong> <?= htmlspecialchars($PRODUTO['categoria']) ?> </p>
            </div>
            <tr>
                <th>Peso</th>
                <td>0,03 kg</td>
            </tr>
            <tr>
                <th>Dimensões</th>
                <td>6.8 × 5.3 × 1 cm</td>
            </tr>
        </table>
    </section>

    <section class="produtos-relacionados">
        <h2>Você também pode gostar</h2>
        <div class="produtos-container">

            <a href="kit-uno.php" class="produto-card">
                <img src="Imagens/Kit Uno Iniciante – 80 Peças para Arduino.png" alt="Kit Uno Iniciante">
                <h3>Kit Uno Iniciante – 80 Peças para Arduino</h3>
                <p class="preco">R$70,00</p>
            </a>

            <a href="fonte-arduino.php" class="produto-card">
                <img src="Imagens/Fonte 9V 2A Arduino e uso geral.jpg" alt="Fonte 9V 2A">
                <h3>Fonte 9V 2A Arduino e Uso Geral</h3>
                <p class="preco">R$27,50</p>
            </a>

            <a href="protoboard.php" class="produto-card">
                <img src="Imagens/Protoboard 830 Pontos.jpg" alt="Protoboard 830 Pontos">
                <h3>Protoboard 830 Pontos</h3>
                <p class="preco">R$30,00</p>
            </a>

            <a href="jumper-femea.php" class="produto-card">
                <img src="Imagens/Jumper Fêmea - Fêmea x20 uni 20cm.jpg" alt="Jumper Fêmea/Fêmea">
                <h3>Jumper Fêmea/Fêmea x 20 Unidades</h3>
                <p class="preco">R$12,00</p>
            </a>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-column contact-info">
                <img src="Imagens/logo 2.png" alt="Logo da loja" class="footer-logo">
                <h4>ATENDIMENTO EM HORÁRIO COMERCIAL!</h4>
                <p class="phone-number">(91) 93834-1493</p>

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
                <p>A loja Arduino Belém, nasceu com o intuito de fomentar a demanda local por produtos e equipamentos
                    necessários para o desenvolvimento maker local, e temos como missão permitir a Engenheiros,
                    Professores, Inventores, Designers, Estudantes e até mesmo curiosos da tecnologia, o acesso a
                    tecnologias e equipamentos, que possibilitem a concretização das suas ideias e projetos em
                    realidade. A Arduino Belém não é só uma loja, prestamos consultoria em projetos, produzimos códigos
                    sob encomenda.</p>
            </div>

            <div class="footer-column attention">
                <h4>Atenção</h4>
                <p>As imagens do site são meramente ilustrativas, entre em contato para saber mais!</p>
            </div>
        </div>

        <div class="footer-bottom-bar">
            <p class="copyright">&copy; Arduino Amazônia - CNPJ: xx.xxx.xxx/xxxx-xx - Todos os direitos reservados - 2025
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
        document.querySelector('.form-carrinho-adicionar').addEventListener('submit', async e => {
            e.preventDefault();
            const form = e.currentTarget;
            const dados = new FormData(form);

            const botao = form.querySelector('.btn-carrinho');
            if (botao.disabled) return;

            const resposta = await fetch('salvar_carrinho.php', {
                method: 'POST',
                body: dados
            });
            const json = await resposta.json();

            if (json.sucesso) {
                // 1. Atualiza o contador e total no cabeçalho
                document.querySelector('.contador').textContent = json.quantidade;
                document.querySelector('.valor-total').textContent = 'R$' + json.total_formatado;

                // 2. Feedback visual no botão
                const textoOriginal = botao.textContent;
                const corOriginal = botao.style.backgroundColor;

                botao.textContent = "✔ Adicionado!";
                botao.style.backgroundColor = "#00915f";

                setTimeout(() => {
                    // Tenta restaurar a cor original, se houver
                    botao.style.backgroundColor = corOriginal || '';
                    botao.textContent = textoOriginal;
                }, 1200);
            } else {
                alert('Erro ao adicionar produto: ' + (json.mensagem || ''));
            }
        });

        // Script para atualizar o cabeçalho a cada 5 segundos
        async function atualizarCabecalho() {
            const resposta = await fetch('contador_carrinho.php');
            const json = await resposta.json();
            document.querySelector('.contador').textContent = json.quantidade;
            document.querySelector('.valor-total').textContent = 'R$' + json.total_formatado;
        }

        // Atualiza automaticamente o cabeçalho a cada 5 segundos
        setInterval(atualizarCabecalho, 5000);
    </script>
    <script src="promocoes.js"></script>
    <script src="index.js"></script>

</body>

</html>