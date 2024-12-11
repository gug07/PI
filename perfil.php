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
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="css/perfil.css">
</head>

<body>
    <!-- Cabeçalho -->
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

    <!-- Seção principal -->
    <main>
        <section class="user-info">
            <div class="user-details">
            <h2>Informações do Usuário</h2>
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($nomeUsuario); ?></p>
            <p><strong>E-mail:</strong> <?php echo htmlspecialchars($usuario_email); ?></p>
            <button onclick="window.location.href='endereco.php'" style="margin-top: 10px; padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Gerenciar Endereço
            </button>
            </div>
        </section>

        <section class="main-info">
            <h1>Descubra o segredo do seu cabelo!</h1>
            <p>
                Você já se perguntou qual é o tipo do seu cabelo? Curly, liso, ondulado ou crespo?
                O seu cabelo tem uma personalidade única, e é hora de desvendar qual é! Responda nosso formulário rápido
                e fácil e descubra dicas incríveis para cuidar melhor dos fios que são só seus.
            </p>
            <button onclick="window.location.href='formulario.html'">Adicionar Formulário</button>
            <button onclick="window.location.href='Index.html'">Sair</button>
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