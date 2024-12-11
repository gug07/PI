<?php
session_start();

if (!isset($_SESSION['cliente_id'])) {
    header("Location: index.html"); // Redireciona para a tela de login
    exit;
}

$nomeUsuario = $_SESSION['nome'];
$usuario_email = $_SESSION['email'];
$totalPagamento = isset($_GET['total']) ? floatval($_GET['total']) : 0.00;

if ($totalPagamento <= 0) {
    die("Valor inválido para pagamento.");
}

include("conexao.php");

// Função para criar um pedido no banco de dados
function criarPedido($conexao, $cliente_id, $total) {
    $stmt = $conexao->prepare("INSERT INTO pedidos (cliente_idcliente, data, status, total) VALUES (?, NOW(), 'pendente', ?)");
    $stmt->bind_param("id", $cliente_id, $total);
    $stmt->execute();
    return $stmt->insert_id;
}

// Função para adicionar os itens do carrinho ao pedido
function adicionarItensAoPedido($conexao, $pedido_id, $carrinho_id) {
    $stmt_itens = $conexao->prepare("SELECT p.idprodutos, cp.quantidade, cp.preco_unitario FROM itens_carrinho cp JOIN produtos p ON cp.produtos_idprodutos = p.idprodutos WHERE cp.carrinho_idcarrinho = ?");
    $stmt_itens->bind_param("i", $carrinho_id);
    $stmt_itens->execute();
    $result_itens = $stmt_itens->get_result();

    while ($item = $result_itens->fetch_assoc()) {
        $stmt_insert = $conexao->prepare("INSERT INTO itens_pedidos (pedidos_idpedidos, produtos_idprodutos, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
        $stmt_insert->bind_param("iiis", $pedido_id, $item['idprodutos'], $item['quantidade'], $item['preco_unitario']);
        $stmt_insert->execute();
    }
}

// Função para registrar o pagamento
function registrarPagamento($conexao, $pedido_id, $total) {
    $stmt = $conexao->prepare("INSERT INTO pagamento (metodo_pagamento, status_pagamento, data, valor_total, pedidos_idpedidos) VALUES ('PayPal', 'aprovado', NOW(), ?, ?)");
    $stmt->bind_param("di", $total, $pedido_id);
    $stmt->execute();
}


// Agora, crie o pedido e registre o pagamento
if (isset($_SESSION['cliente_id'])) {
    $cliente_id = $_SESSION['cliente_id'];

    // Verifique o carrinho do cliente
    $stmt_carrinho = $conexao->prepare("SELECT idcarrinho FROM carrinho WHERE cliente_idcliente = ? AND status = 'ativo'");
    $stmt_carrinho->bind_param("i", $cliente_id);
    $stmt_carrinho->execute();
    $result_carrinho = $stmt_carrinho->get_result();
    if ($result_carrinho->num_rows > 0) {
        $carrinho = $result_carrinho->fetch_assoc();
        $carrinho_id = $carrinho['idcarrinho'];

        // Criar o pedido
        $pedido_id = criarPedido($conexao, $cliente_id, $totalPagamento);

        // Adicionar os itens do carrinho ao pedido
        adicionarItensAoPedido($conexao, $pedido_id, $carrinho_id);

        // Registrar o pagamento
        registrarPagamento($conexao, $pedido_id, $totalPagamento);

        // Atualizar o status do carrinho
        $stmt_update_carrinho = $conexao->prepare("UPDATE carrinho SET status = 'finalizado' WHERE idcarrinho = ?");
        $stmt_update_carrinho->bind_param("i", $carrinho_id);
        $stmt_update_carrinho->execute();

    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento</title>
    <script src="https://www.paypal.com/sdk/js?client-id=AbHfJFGLRvHM4V81AqLEPytxWNLKyLsisbjQXlr_OL3V3cOHLkGv3fSjJ5K9Un59zWRywlXVWYFrlDA6&currency=BRL"></script>
    <link rel="stylesheet" href="css/pagamento.css"> <!-- Adicione seu CSS aqui -->
</head>
<body>
    <header>
        <h1>Pagamento</h1>
        <p>Olá, <?php echo htmlspecialchars($nomeUsuario); ?>. Você está prestes a finalizar sua compra.</p>
    </header>

    <main>
        <div id="resumo-pedido">
            <h2>Resumo do Pedido</h2>
            <p><strong>Total:</strong> R$ <?php echo number_format($totalPagamento, 2, ',', '.'); ?></p>
        </div>

        <div id="paypal-button-container"></div>
    </main>

    <script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '<?php echo number_format($totalPagamento, 2, '.', ''); ?>'
                    },
                    description: "Pagamento de produtos do carrinho"
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                alert('Pagamento realizado com sucesso! Obrigado, ' + details.payer.name.given_name + '.');

                // Enviar os dados do pagamento para o servidor para registro
                $.ajax({
                    url: 'processar_pagamento.php',
                    type: 'POST',
                    data: {
                        orderID: details.id,
                        cliente_id: <?php echo $_SESSION['cliente_id']; ?>,
                        total: <?php echo number_format($totalPagamento, 2, '.', ''); ?>,
                        status_pagamento: 'Aprovado',
                        data_pagamento: new Date().toISOString()
                    },
                    success: function(response) {
                        if (response.success) {
                            // Redirecionar para a página de confirmação após registrar o pagamento
                            window.location.href = 'confirmacao_pagamento.php?orderID=' + details.id;
                        } else {
                            alert('Erro ao processar o pagamento. Tente novamente.');
                        }
                    },
                    error: function(err) {
                        console.error(err);
                        alert('Ocorreu um erro no processamento. Tente novamente.');
                    }
                });
            });
        },
        onError: function(err) {
            console.error(err);
            alert('Ocorreu um erro durante o pagamento. Tente novamente.');
        }
    }).render('#paypal-button-container');
</script>

</body>
</html>
