<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: index.html"); // Redireciona para a tela de login
    exit;
}

// Obtém os dados do usuário da sessão
$nomeUsuario = htmlspecialchars($_SESSION['nome']);
$usuario_email = htmlspecialchars($_SESSION['email']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="css/carrinho.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/carrinho.js"></script>
</head>
<body>
<header>
        <img src="img/Logo.png" alt="Logo" class="header-logo">
        <nav class="header-nav">
            <a href="telaprincipal.php">PRINCIPAL</a>
            <a href="listasprodutos.php">PRODUTOS</a>
            <div class="dropdown">
                <a href="#">Categorias</a>
                <div class="dropdown-content">
                    <?php
                    // Exibe as categorias dinamicamente
                    if ($resultado_categorias && mysqli_num_rows($resultado_categorias) > 0) {
                        while ($categoria = mysqli_fetch_assoc($resultado_categorias)) {
                            $id = htmlspecialchars($categoria['idcategorias']);
                            $nome = htmlspecialchars($categoria['nome']);
                            echo "<a href='categoria.php?idcategoria=$id'>$nome</a>";
                        }
                    } else {
                        echo "<p>Nenhuma categoria encontrada.</p>";
                    }
                    ?>
                </div>
            </div>

        </nav>
        <div class="header-actions">
            <form method="GET" action="pesquisa.php">
                <input type="search" name="q" placeholder="Pesquisar" id="search-bar">
                <button type="submit">Buscar</button>
            </form>
            <button id="cart-button" onclick="window.location.href='carrinho.php'">🛒</button>
            <button id="profile-button" onclick="window.location.href='perfil.php'">
                <?php echo htmlspecialchars($nomeUsuario); ?>
            </button>
        </div>
    </header>

    <?php
    include("conexao.php");

    if (isset($_SESSION['cliente_id'])) {
        $cliente_id = $_SESSION['cliente_id'];

        // Busca o carrinho ativo
        $stmt_carrinho = $conexao->prepare("SELECT idcarrinho FROM carrinho WHERE cliente_idcliente = ? AND status = 'ativo'");
        $stmt_carrinho->bind_param("i", $cliente_id);
        $stmt_carrinho->execute();
        $resultado_carrinho = $stmt_carrinho->get_result();

        if ($resultado_carrinho->num_rows > 0) {
            $carrinho = $resultado_carrinho->fetch_assoc();
            $carrinho_id = $carrinho['idcarrinho'];

            // Busca os itens do carrinho
            $stmt_itens = $conexao->prepare("
                SELECT cp.iditens_carrinho, cp.quantidade, p.nome, p.preco, p.img 
                FROM itens_carrinho cp
                JOIN produtos p ON cp.produtos_idprodutos = p.idprodutos
                WHERE cp.carrinho_idcarrinho = ?");
            $stmt_itens->bind_param("i", $carrinho_id);
            $stmt_itens->execute();
            $resultado_itens = $stmt_itens->get_result();

            echo "<table class='tabela-carrinho'>";
            echo "<tr><th>Produto</th><th>Quantidade</th><th>Preço Unitário</th><th>Subtotal</th><th>Ação</th></tr>";

            $total = 0;
            while ($item = $resultado_itens->fetch_assoc()) {
                $subtotal = $item['quantidade'] * $item['preco'];
                $total += $subtotal;

                echo "<tr data-item-id='" . $item['iditens_carrinho'] . "'>";
                echo "<td><img src='" . htmlspecialchars($item['img']) . "' alt='" . htmlspecialchars($item['nome']) . "' class='imagem-produto'>" . htmlspecialchars($item['nome']) . "</td>";
                echo "<td><input type='number' value='" . $item['quantidade'] . "' min='1' class='quantidade' data-item-id='" . $item['iditens_carrinho'] . "'></td>";
                echo "<td>R$ " . number_format($item['preco'], 2, ',', '.') . "</td>";
                echo "<td class='subtotal'>R$ " . number_format($subtotal, 2, ',', '.') . "</td>";
                echo "<td><button class='remover-item' data-item-id='" . $item['iditens_carrinho'] . "'>Remover</button></td>";
                echo "</tr>";
            }

            echo "</table>";
            echo "<div class='total'>Total: R$ " . number_format($total, 2, ',', '.') . "</div>";

            echo "<div class='finalizar-compra'>";
            echo "<a href='pagamento.php?total=" . number_format($total, 2, '.', '') . "'>
                    <button type='button'>Finalizar Compra</button>
                  </a>";
            echo "</div>";
        } else {
            echo "<p>Seu carrinho está vazio.</p>";
        }
    } else {
        echo "<p>Você precisa estar logado para visualizar o carrinho.</p>";
    }
    ?>

<footer>
        <div class="footer-columns">
            <img src="img/Logo.png" alt="Logo" class="header-logo">
            <div class="footer-address">
                <strong>Politica e privacidade</strong><br>
                <a href="##">Politica do site</a>
            </div>
            <div class="footer-column">
                <h4>Redes sociais</h4>
                <div class="footer-links">
                    <a href="https://Instagram.com/th_penolakk">Instagram 1</a>
                    <a href="https://www.instagram.com/gabizx_dm/profilecard/?igsh=amQ4aHM4cDMydzg4">Instagram 2</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© 2024 Your Website. All rights reserved.</span>
        </div>
    </footer>
</body>
</html>
