<?php
include("conexao.php");
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: index.html"); // Redireciona se o cliente não estiver logado
    exit;
}

$id_cliente = $_SESSION['cliente_id']; // Obtém o ID do cliente logado

// Recebendo dados do formulário
$id_produto = isset($_POST['id_produto']) ? intval($_POST['id_produto']) : 0;
$quantidade = isset($_POST['quantidade']) ? intval($_POST['quantidade']) : 0;

if ($id_produto <= 0 || $quantidade <= 0) {
    die("Produto ou quantidade inválidos.");
}

// Verifica carrinho ativo do cliente
$stmt_carrinho = $conexao->prepare("SELECT idcarrinho FROM carrinho WHERE cliente_idcliente = ? AND status = 'ativo'");
$stmt_carrinho->bind_param("i", $id_cliente);
$stmt_carrinho->execute();
$result_carrinho = $stmt_carrinho->get_result();

if ($result_carrinho && $result_carrinho->num_rows > 0) {
    $carrinho = $result_carrinho->fetch_assoc();
    $id_carrinho = $carrinho['idcarrinho'];
} else {
    // Cria um novo carrinho
    $stmt_novo_carrinho = $conexao->prepare("INSERT INTO carrinho (cliente_idcliente, status) VALUES (?, 'ativo')");
    $stmt_novo_carrinho->bind_param("i", $id_cliente);
    $stmt_novo_carrinho->execute();
    $id_carrinho = $conexao->insert_id;
}

// Verifica se o item já existe no carrinho
$stmt_item = $conexao->prepare("SELECT iditens_carrinho, quantidade FROM itens_carrinho WHERE carrinho_idcarrinho = ? AND produtos_idprodutos = ?");
$stmt_item->bind_param("ii", $id_carrinho, $id_produto);
$stmt_item->execute();
$result_item = $stmt_item->get_result();

if ($result_item && $result_item->num_rows > 0) {
    // Incrementa a quantidade
    $item = $result_item->fetch_assoc();
    $nova_quantidade = $item['quantidade'] + $quantidade;
    $stmt_update = $conexao->prepare("UPDATE itens_carrinho SET quantidade = ? WHERE iditens_carrinho = ?");
    $stmt_update->bind_param("ii", $nova_quantidade, $item['iditens_carrinho']);
    $stmt_update->execute();
} else {
    // Insere um novo item no carrinho
    $stmt_preco = $conexao->prepare("SELECT preco FROM produtos WHERE idprodutos = ?");
    $stmt_preco->bind_param("i", $id_produto);
    $stmt_preco->execute();
    $result_preco = $stmt_preco->get_result();
    
    if ($result_preco->num_rows > 0) {
        $preco_unitario = $result_preco->fetch_assoc()['preco'];

        $stmt_add_item = $conexao->prepare("INSERT INTO itens_carrinho (carrinho_idcarrinho, produtos_idprodutos, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
        $stmt_add_item->bind_param("iiii", $id_carrinho, $id_produto, $quantidade, $preco_unitario);
        $stmt_add_item->execute();
    } else {
        die("Produto não encontrado.");
    }
}

mysqli_close($conexao);

// Redireciona para a visualização do carrinho
header("Location: carrinho.php");
exit;
?>
