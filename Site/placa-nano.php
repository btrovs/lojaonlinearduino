<?php
// NADA antes deste bloco! (sem espaços/linhas)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Carrega o status do carrinho
$contador = isset($_SESSION['carrinho']) ? array_sum(array_column($_SESSION['carrinho'], 'quantidade')) : 0;
$total = 0.0;
if (!empty($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $total += (float)($item['preco'] ?? 0.0) * (int)($item['quantidade'] ?? 0);
    }
}

// ----------------------------------------------------
// DADOS DE PRODUTO ESPECÍFICO (SIMULAÇÃO)
// Para que a página tenha conteúdo e use o CSS de listagem.
// ----------------------------------------------------
$PROD_ID = 1; // ID fictício para a Placa Nano
$PROD_NOME = "Arduino Nano V3.0 Entrada USB-C (sem cabo)";
$PROD_PRECO = 45.50;
$PROD_IMG = "Imagens/Arduino Nano V3.0 Entrada USB-C (sem cabo).jpg"; // Use o nome exato da sua imagem
$PROD_CATEGORIA = "Arduino, Placas";
$PROD_DISPONIBILIDADE = "Em Estoque";
$PROD_PROMOCAO = false;

// Array de produtos a ser exibido NESTA PÁGINA
$produtos_filtrados = [
    $PROD_ID => [
        "nome" => $PROD_NOME,
        "preco" => $PROD_PRECO,
        "img" => $PROD_IMG,
        "categoria" => $PROD_CATEGORIA,
        "disponibilidade" => $PROD_DISPONIBILIDADE
    ],
    // Você pode adicionar mais produtos aqui se houver mais de um modelo Mega
];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placa MEGA | Arduino Amazônia</title>
    <link rel="stylesheet" href="produtos.css">
    <link rel="stylesheet" href="index.css">

    <style>
        /* Estilos básicos para o badge (se não estiver em produtos.css) */
        .badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: bold;
            color: white;
            z-index: 10;
        }

        .esgotado {
            background-color: #e74c3c;
        }

        .esgotado-text {
            color: #e74c3c;
            font-weight: bold;
        }

        .em-estoque-text {
            color: #1c593a;
            font-weight: bold;
        }
    </style>
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
            <button id="fechar-menu" title="Fechar Menu">&times;</button>
            <nav>
                <ul>
                    <li class="item-com-submenu"><a href="#">ARDUINO <span class="seta-submenu">›</span></a>
                        <ul class="submenu">
                            <li><a href="placa-nano.php">Placa NANO</a></li>
                            <li><a href="modulos.php">Módulos</a></li>
                            <li><a href="motores.php">Motores</a></li>
                        </ul>
                    </li>
                    <li class="item-com-submenu"><a href="#">KITS E PROJETOS <span class="seta-submenu">›</span></a>
                        <ul class="submenu">
                            <li><a href="kit-iniciante.php">Kit Iniciante</a></li>
                            <li><a href="kit-avancado.php">Kit Avançado</a></li>
                        </ul>
                    </li>
                    <li class="item-com-submenu"><a href="#">ROBÓTICA E MOTORES <span class="seta-submenu">›</span></a>
                        <ul class="submenu">
                            <li><a href="motores-dc.php">Motores DC</a></li>
                            <li><a href="servo-dc.php">Servo DC</a></li>
                        </ul>
                    </li>
                    <li class="item-com-submenu"><a href="#">NOVIDADES <span class="seta-submenu">›</span></a>
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

    <main class="container">

        <h1 style="text-align: center; margin-top: 30px;">Placa UNO</h1>

        <div class="filtros">
            <div class="ordenar-por">
                <label for="ordenacao">Ordenar por:</label>
                <select id="ordenacao">
                    <option value="padrao">Padrão</option>
                    <option value="menor-preco">Menor Preço</option>
                    <option value="maior-preco">Maior Preço</option>
                </select>
            </div>
            <div class="mostrar">
                <label for="mostrar">Mostrar:</label>
                <select id="mostrar">
                    <option value="10">10 por página</option>
                    <option value="20">20 por página</option>
                    <option value="todos">Todos</option>
                </select>
            </div>
        </div>

        <section class="produtos">
            <?php foreach ($produtos_filtrados as $id => $p): ?>
                <?php $esgotado = ($p['disponibilidade'] === 'Esgotado'); ?>
                <div class="card" data-preco="<?= $p['preco'] ?>" data-disponibilidade="<?= $p['disponibilidade'] ?>">

                    <a href="placa-mega-detalhe.php?id=<?= $id ?>" class="card-link">
                        <?php if ($esgotado): ?>
                            <span class="badge esgotado">ESGOTADO</span>
                        <?php endif; ?>

                        <img src="<?= htmlspecialchars($p['img']) ?>" alt="<?= htmlspecialchars($p['nome']) ?>">

                        <h3><?= htmlspecialchars($p['nome']) ?></h3>
                        <p class="preco">R$<?= number_format($p['preco'], 2, ',', '.') ?></p>
                        <p class="disponibilidade <?= $esgotado ? 'esgotado-text' : 'em-estoque-text' ?>">
                            <?= htmlspecialchars($p['disponibilidade']) ?>
                        </p>
                    </a>

                    <form action="salvar_carrinho.php" method="POST" class="form-carrinho">
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
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-column contact-info">
                <img src="Imagens/logo 2.png" alt="Logo da loja" class="footer-logo">
                <h4>ATENDIMENTO EM HORÁRIO COMERCIAL!</h4>
                <p class="phone-number">(91) 93834-1493</p>
                <h4>Endereço</h4>
                <p>Funcionamos apenas online, com produtos a pronta entrega. Os clientes podem retirar os produtos, ou podemos enviar via Correios ou Uber Flash (Valores repassados via WhatsApp ou Email).</p>
                <div class="social-icons">
                    <a href="#"><img src="Imagens/facebook.png" alt="Facebook"></a>
                    <a href="#"><img src="Imagens/whatsapp.png" alt="WhatsApp"></a>
                    <a href="#"><img src="Imagens/instagram.png" alt="Instagram"></a>
                </div>
            </div>
            <div class="footer-column about-us">
                <h4>Sobre Nós</h4>
                <p>A loja Arduino Belém, nasceu com o intuito de fomentar a demanda local por produtos e equipamentos necessários para o desenvolvimento maker local, e temos como missão permitir a Engenheiros, Professores, Inventores, Designers, Estudantes e até mesmo curiosos da tecnologia, o acesso a tecnologias e equipamentos, que possibilitem a concretização das suas ideias e projetos em realidade. A Arduino Belém não é só uma loja, prestamos consultoria em projetos, produzimos códigos sob encomenda.</p>
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

                const textoOriginal = botao.textContent;
                const corOriginal = botao.style.backgroundColor;

                botao.textContent = "Adicionado!";
                botao.style.backgroundColor = "#00915f";

                setTimeout(() => {
                    botao.textContent = textoOriginal;
                    botao.style.backgroundColor = corOriginal || "#1c593a";
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

        setInterval(atualizarCabecalho, 5000);
    </script>

    <script src="produtos.js"></script>
    <script src="promocoes.js"></script>
    <script src="index.js"></script>
</body>

</html>