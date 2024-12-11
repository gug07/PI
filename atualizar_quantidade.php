<?php
session_start();
include("conexao.php");

if (isset($_POST['item_id']) && isset($_POST['quantidade'])) {
    $item_id = $_POST['item_id'];
    $quantidade = $_POST['quantidade'];

    // Verifica se a quantidade é válida (não pode ser negativa)
    if ($quantidade > 0) {
        // Atualizar a quantidade do item no carrinho
        $stmt = $conexao->prepare("UPDATE itens_carrinho SET quantidade = ? WHERE iditens_carrinho = ?");
        $stmt->bind_param("ii", $quantidade, $item_id);

        if ($stmt->execute()) {
            echo "success"; // Se a atualização foi bem-sucedida
        } else {
            echo "error"; // Se houver um erro
        }

        $stmt->close();
    } else {
        echo "invalid_quantity"; // Caso a quantidade seja inválida
    }
}
?>
