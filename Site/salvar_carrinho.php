<?php
session_start();

$id    = $_POST['id'] ?? null;
$nome  = $_POST['nome'] ?? '';
$preco = floatval($_POST['preco'] ?? 0);
$qtde  = max(1, intval($_POST['quantidade'] ?? 1)); // Usa a quantidade escolhida
// ADICIONADO: Variável para capturar o caminho da imagem
$img   = $_POST['img'] ?? '';

if (!$id) {
    header('Content-Type: application/json');
    echo json_encode(['erro' => true, 'mensagem' => 'ID inválido']);
    exit;
}

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if (isset($_SESSION['carrinho'][$id])) {
    $_SESSION['carrinho'][$id]['quantidade'] += $qtde; // Soma a quantidade
} else {
    $_SESSION['carrinho'][$id] = [
        'nome' => $nome,
        'preco' => $preco,
        'quantidade' => $qtde,
        'img' => $img // CORREÇÃO: Salva o caminho da imagem na sessão
    ];
}

$quantidade_total = array_sum(array_column($_SESSION['carrinho'], 'quantidade'));
$total = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $total += (float)($item['preco'] ?? 0.0) * (int)($item['quantidade'] ?? 0);
}

header('Content-Type: application/json');
echo json_encode([
    'sucesso' => true,
    'quantidade' => $quantidade_total,
    'total' => $total,
    'total_formatado' => number_format($total, 2, ',', '.')
]);
