<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: index.html"); // Redireciona para a tela de login
    exit;
}

// Obtém os dados do usuário da sessão
$nomeUsuario = $_SESSION['nome'];
$usuario_email = $_SESSION['email'];

include("conexao.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['nome'])) {
    echo "<h1>Você não está logado</h1>";
    exit;
}

// Obtém o nome do usuário logado
$nomeUsuario = $_SESSION['nome'];

// Consulta para obter as categorias
$query_categorias = "SELECT idcategorias, nome FROM categorias";
$resultado_categorias = mysqli_query($conexao, $query_categorias);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/cat.css">
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
</body>

</html>

<div class="product-grid1">
    <?php
    include("conexao.php");

    // Obtém o ID da categoria via GET
    $idcategoria = isset($_GET['idcategoria']) ? intval($_GET['idcategoria']) : 0;

    // Valida o ID da categoria
    if ($idcategoria <= 0) {
        die("Categoria inválida.");
    }

    // Consulta SQL para selecionar produtos pela categoria
    $query_produtos = "SELECT * FROM produtos WHERE categorias_idcategorias = $idcategoria";
    $resultado_produtos = mysqli_query($conexao, $query_produtos);

    // Consulta para obter o nome da categoria
    $query_categoria = "SELECT nome FROM categorias WHERE idcategorias = $idcategoria";
    $resultado_categoria = mysqli_query($conexao, $query_categoria);
    $categoria = mysqli_fetch_assoc($resultado_categoria)['nome'];

    if ($resultado_produtos && mysqli_num_rows($resultado_produtos) > 0) {
        echo "<h2>Produtos da Categoria: $categoria</h2>";
        echo "<div class='product-grid'>"; // Grid de produtos conforme o primeiro código
        while ($produto = mysqli_fetch_assoc($resultado_produtos)) {
            $id = htmlspecialchars($produto['idprodutos']);
            $nome = htmlspecialchars($produto['nome']);
            $descricao = htmlspecialchars($produto['descricao']);
            $preco = number_format($produto['preco'], 2, ',', '.');
            $img = htmlspecialchars($produto['img']);

            echo "
            <div class='product-item'>
                <a href='detalhes_produto.php?id=$id'>
                    <img src='$img' alt='$nome'>
                </a>
                <h4>$nome</h4>
                <p>$descricao</p>
                <p><strong>R$ $preco</strong></p>
            </div>";
        }
        echo "</div>"; // Fechando a div do grid de produtos
    } else {
        echo "<p>Nenhum produto encontrado na categoria $categoria.</p>";
    }

    // Fecha a conexão com o banco de dados
    mysqli_close($conexao);
    ?>
</div>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width='device-width', initial-scale=1.0">
    <title>Document</title>
</head>
<body>
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