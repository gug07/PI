<?php
session_start();
include("conexao.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['nome'])) {
    echo "<h1>Você não está logado</h1>";
    exit;
}

// Obtém o nome do usuário logado
$nomeUsuario = $_SESSION['nome'];
$usuario_email = $_SESSION['email'];

// Consulta para obter as categorias
$query_categorias = "SELECT idcategorias, nome FROM categorias";
$resultado_categorias = mysqli_query($conexao, $query_categorias);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erro ao conectar ao banco: ' . $e->getMessage();
    exit;
}

// Verifica se há pesquisa
$searchTerm = isset($_GET['q']) ? $_GET['q'] : '';

if ($searchTerm) {
    // Consulta ao banco de dados para buscar os produtos
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE nome LIKE :searchTerm");
    $stmt->execute(['searchTerm' => '%' . $searchTerm . '%']);
    $produtos = $stmt->fetchAll();
} else {
    $produtos = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Pesquisa</title>
    <link rel="stylesheet" href="css/pesquisa.css">
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

    <h2>Resultados da Pesquisa</h2>
    <section class="product-grid">
        <?php if (empty($produtos)): ?>
            <p>Nenhum produto encontrado.</p>
        <?php else: ?>
            <?php foreach ($produtos as $produto): ?>
                <div class="product-item">
                    <!-- Link para a página de detalhes do produto -->
                    <a href="detalhes_produto.php?id=<?php echo htmlspecialchars($produto['idprodutos']); ?>">
                        <img src="<?php echo htmlspecialchars($produto['img']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                        <p><?php echo htmlspecialchars($produto['nome']); ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

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