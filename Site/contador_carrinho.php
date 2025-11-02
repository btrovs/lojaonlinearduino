<?php
session_start();

$quantidade = array_sum(array_column($_SESSION['carrinho'] ?? [], 'quantidade'));
$total = 0;
foreach ($_SESSION['carrinho'] ?? [] as $item) {
    $total += $item['preco'] * $item['quantidade'];
}

echo json_encode([
    'quantidade' => $quantidade,
    'total' => $total,
    'total_formatado' => number_format($total, 2, ',', '.')
]);
