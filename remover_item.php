<?php
session_start();
include("conexao.php");

if (isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];

    // Remover o item do carrinho no banco de dados
    $stmt = $conexao->prepare("DELETE FROM itens_carrinho WHERE iditens_carrinho = ?");
    $stmt->bind_param("i", $item_id);

    if ($stmt->execute()) {
        echo "success"; // Se a remoção foi bem-sucedida
    } else {
        echo "error"; // Se houver um erro
    }

    $stmt->close();
}
?>
