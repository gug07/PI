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
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" href="css/produtos.css">
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


    <div class="product-grid">
        <?php
        // Inclui o arquivo de conexão com o banco de dados
        include("conexao.php");

        /// Consulta SQL para listar os produtos
        $query = "SELECT * FROM produtos";
        $result = mysqli_query($conexao, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($produto = mysqli_fetch_assoc($result)) {
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
        } else {
            echo "<p>Nenhum produto encontrado.</p>";
        }
        // Fecha a conexão com o banco de dados
        mysqli_close($conexao);
        ?>
    </div>
</body>

</html>