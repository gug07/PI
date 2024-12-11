$(document).ready(function () {
    // Atualizar a quantidade do item
    $(".quantidade").change(function () {
        var itemId = $(this).data('item-id');
        var quantidade = $(this).val();

        // Atualizar a quantidade no banco de dados via AJAX
        $.ajax({
            url: 'atualizar_quantidade.php',
            type: 'POST',
            data: { item_id: itemId, quantidade: quantidade },
            success: function (response) {
                if (response === 'success') {
                    // Se a quantidade foi atualizada com sucesso, atualizar o subtotal na tabela
                    var precoUnitario = parseFloat($("tr[data-item-id='" + itemId + "'] td:nth-child(3)").text().replace('R$ ', '').replace(',', '.'));
                    var subtotal = precoUnitario * quantidade;

                    // Atualizar o subtotal na tabela
                    $("tr[data-item-id='" + itemId + "'] .subtotal").text("R$ " + subtotal.toFixed(2).replace('.', ','));
                    
                    // Atualizar o total do carrinho
                    atualizarTotal();
                } else {
                    alert("Erro ao atualizar a quantidade.");
                }
            }
        });
    });

    // Remover um item do carrinho
    $(".remover-item").click(function () {
        var itemId = $(this).data('item-id');

        // Remover o item do banco de dados via AJAX
        $.ajax({
            url: 'remover_item.php',
            type: 'POST',
            data: { item_id: itemId },
            success: function (response) {
                if (response === 'success') {
                    // Se a remoção foi bem-sucedida, remover o item da tabela
                    $("tr[data-item-id='" + itemId + "']").remove();
                    
                    // Atualizar o total do carrinho
                    atualizarTotal();
                } else {
                    alert("Erro ao remover o item.");
                }
            }
        });
    });

    // Atualizar o total do carrinho
    function atualizarTotal() {
        var total = 0;
        $(".subtotal").each(function() {
            total += parseFloat($(this).text().replace('R$ ', '').replace(',', '.'));
        });
        $(".total").text("Total: R$ " + total.toFixed(2).replace('.', ','));
    }
});
