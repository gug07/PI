<?php
include("conexao.php");

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: index.html"); // Redireciona para a tela de login
    exit;
}

// Obtém os dados do usuário da sessão
$nomeUsuario = htmlspecialchars($_SESSION['nome']);
$usuario_email = htmlspecialchars($_SESSION['email']);

// Obtém o ID do produto via GET
$id_produto = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_produto <= 0) {
    die("ID do produto inválido.");
}

// Função para buscar produto
function getProduto($conexao, $id_produto) {
    $stmt = $conexao->prepare("SELECT * FROM produtos WHERE idprodutos = ?");
    $stmt->bind_param("i", $id_produto);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Função para buscar produtos semelhantes
function getProdutosSemelhantes($conexao, $categoria_id, $id_produto) {
    $stmt = $conexao->prepare(
        "SELECT * FROM produtos WHERE categorias_idcategorias = ? AND idprodutos != ? LIMIT 3"
    );
    $stmt->bind_param("ii", $categoria_id, $id_produto);
    $stmt->execute();
    return $stmt->get_result();
}

// Busca as informações do produto
$produto = getProduto($conexao, $id_produto);
if (!$produto) {
    die("Produto não encontrado.");
}

$categoria_id = $produto['categorias_idcategorias'];
$produtos_semelhantes = getProdutosSemelhantes($conexao, $categoria_id, $id_produto);

mysqli_close($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Detalhes do produto <?php echo htmlspecialchars($produto['nome']); ?> e sugestões de produtos semelhantes.">
    <title>Detalhes do Produto</title>
    <link rel="stylesheet" href="css/detalhes_produto.css">
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

    <main>
        <h1><?php echo htmlspecialchars($produto['nome']); ?></h1>
        <img src="<?php echo htmlspecialchars($produto['img']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>" id="img1">
        <p><?php echo htmlspecialchars($produto['descricao']); ?></p>
        <p><strong>Preço: R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></strong></p>
        <p>Estoque: <?php echo $produto['quantidade_estoque']; ?> unidades</p>

        <!-- Formulário para adicionar ao carrinho -->
        <form action="adicionar_ao_carrinho.php" method="POST">
            <label for="quantidade">Quantidade:</label>
            <select name="quantidade" id="quantidade">
                <?php for ($i = 1; $i <= $produto['quantidade_estoque']; $i++) : ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
            <input type="hidden" name="id_produto" value="<?php echo $produto['idprodutos']; ?>">
            <button type="submit">Adicionar ao Carrinho</button>
        </form>
        <form action="pagamento.php" method="GET">
    <input type="hidden" name="total" value="<?php echo number_format($produto['preco'], 2, '.', ''); ?>">
    <button type="submit">Comprar Agora</button>
</form>

        <!-- Produtos semelhantes -->
        <section class="similar-products">
            <h2>Produtos Semelhantes</h2>
            <div class="carousel">
                <?php if ($produtos_semelhantes->num_rows > 0) : ?>
                    <?php while ($row_similar = $produtos_semelhantes->fetch_assoc()) : ?>
                        <div class="item">
                            <a href="detalhes_produto.php?id=<?php echo $row_similar['idprodutos']; ?>">
                                <img src="<?php echo htmlspecialchars($row_similar['img']); ?>" alt="<?php echo htmlspecialchars($row_similar['nome']); ?>">
                            </a>
                            <p><?php echo htmlspecialchars($row_similar['nome']); ?></p>
                            <p><strong>R$ <?php echo number_format($row_similar['preco'], 2, ',', '.'); ?></strong></p>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
                    <p>Nenhum produto semelhante encontrado.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
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
