<!-- Adicione ao final da seção de detalhes do produto -->
<div id="paypal-button-container"></div>

<script src="https://www.paypal.com/sdk/js?client-id=YOUR_CLIENT_ID"></script>
<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '<?php echo $produto['preco']; ?>'
                    },
                    description: '<?php echo addslashes($produto['nome']); ?>'
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                alert('Pagamento aprovado! Obrigado, ' + details.payer.name.given_name);
                // Aqui você pode redirecionar ou salvar o pedido no banco
                window.location.href = "confirmacao.php?pedido_id=" + data.orderID;
            });
        },
        onError: function(err) {
            console.error(err);
            alert('Erro ao processar o pagamento. Tente novamente.');
        }
    }).render('#paypal-button-container');
</script>
