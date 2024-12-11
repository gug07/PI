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

<?php
session_start();
include("conexao.php");

// Verifica se os dados necessários foram recebidos
if (isset($_POST['orderID'], $_POST['cliente_id'], $_POST['total'], $_POST['status_pagamento'], $_POST['data_pagamento'])) {
    $orderID = $_POST['orderID'];
    $cliente_id = $_POST['cliente_id'];
    $total = $_POST['total'];
    $status_pagamento = $_POST['status_pagamento'];
    $data_pagamento = $_POST['data_pagamento'];

    // Cria um novo pedido
    $stmt_pedido = $conexao->prepare("INSERT INTO pedidos (cliente_idcliente, data, status, total) VALUES (?, ?, ?, ?)");
    $stmt_pedido->bind_param("isss", $cliente_id, $data_pagamento, $status_pagamento, $total);
    $stmt_pedido->execute();
    $pedido_id = $stmt_pedido->insert_id; // Obtém o ID do pedido gerado

    // Transfere os itens do carrinho para a tabela de itens_pedidos
    $stmt_itens = $conexao->prepare("SELECT cp.iditens_carrinho, cp.quantidade, p.idprodutos, cp.preco_unitario
                                     FROM itens_carrinho cp
                                     JOIN produtos p ON cp.produtos_idprodutos = p.idprodutos
                                     WHERE cp.carrinho_idcarrinho = (SELECT idcarrinho FROM carrinho WHERE cliente_idcliente = ? AND status = 'ativo')");
    $stmt_itens->bind_param("i", $cliente_id);
    $stmt_itens->execute();
    $resultado_itens = $stmt_itens->get_result();

    while ($item = $resultado_itens->fetch_assoc()) {
        // Insere os itens no pedido
        $stmt_item_pedido = $conexao->prepare("INSERT INTO itens_pedidos (pedidos_idpedidos, produtos_idprodutos, quantidade, preco_unitario)
                                               VALUES (?, ?, ?, ?)");
        $stmt_item_pedido->bind_param("iiis", $pedido_id, $item['idprodutos'], $item['quantidade'], $item['preco_unitario']);
        $stmt_item_pedido->execute();
    }

    // Registra o pagamento
    $stmt_pagamento = $conexao->prepare("INSERT INTO pagamento (metodo_pagamento, status_pagamento, data, valor_total, pedidos_idpedidos)
                                         VALUES (?, ?, ?, ?, ?)");
    $stmt_pagamento->bind_param("ssssi", 'PayPal', $status_pagamento, $data_pagamento, $total, $pedido_id);
    $stmt_pagamento->execute();

    // Finaliza a resposta
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
}
?>