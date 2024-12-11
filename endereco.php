<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['nome'])) {
    echo "<h1>Você não está logado</h1>";
    exit;
}

// Obtém o nome do usuário logado
$nomeUsuario = $_SESSION['nome'];
$usuario_email = $_SESSION['email'];
$cliente_id = $_SESSION['cliente_id'];
include("conexao.php");

// Busca o endereço atual do usuário
$query = $conexao->prepare("SELECT * FROM enderecos WHERE cliente_idcliente = ?");
$query->bind_param("i", $cliente_id);
$query->execute();
$result = $query->get_result();
$endereco = $result->fetch_assoc();


// Consulta para obter as categorias
$query_categorias = "SELECT idcategorias, nome FROM categorias";
$resultado_categorias = mysqli_query($conexao, $query_categorias);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endereço</title>
    <link rel="stylesheet" href="css/endereco.css">
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
        <h1>Endereço de Entrega</h1>
        <form action="salvar_endereco.php" method="POST">
            <label for="rua">Rua:</label>
            <input type="text" id="rua" name="rua" value="<?php echo htmlspecialchars($endereco['rua'] ?? ''); ?>" required>

            <label for="numero">Número:</label>
            <input type="text" id="numero" name="numero" value="<?php echo htmlspecialchars($endereco['numero'] ?? ''); ?>" required>

            <label for="complemento">Complemento:</label>
            <input type="text" id="complemento" name="complemento" value="<?php echo htmlspecialchars($endereco['complemento'] ?? ''); ?>">

            <label for="bairro">Bairro:</label>
            <input type="text" id="bairro" name="bairro" value="<?php echo htmlspecialchars($endereco['bairro'] ?? ''); ?>" required>

            <label for="cidade">Cidade:</label>
            <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($endereco['cidade'] ?? ''); ?>" required>

            <label for="estado">Estado:</label>
            <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($endereco['estado'] ?? ''); ?>" maxlength="2" required>

            <label for="cep">CEP:</label>
            <input type="text" id="cep" name="cep" value="<?php echo htmlspecialchars($endereco['cep'] ?? ''); ?>" required>

            <label for="pais">País:</label>
            <input type="text" id="pais" name="pais" value="<?php echo htmlspecialchars($endereco['pais'] ?? 'Brasil'); ?>" required>

            <button type="submit">Salvar Endereço</button>
        </form>
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