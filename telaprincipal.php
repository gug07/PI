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
    <title>E-commerce de Produtos para Cabelo</title>
    <link rel="stylesheet" href="css/voumematar.css">
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

    <!-- Grid de Produtos -->
    <section class="product-grid">
        <div class="product-item">
            <a href="detalhes_produto.php?id=38">
                <img src="img/shampoo1.jpg" alt="Shampoo Pantene Controle de Queda 400ml">
            </a>
        </div>
        <div class="product-item">
            <a href="detalhes_produto.php?id=39">
                <img src="img/shampoo2.jpg" alt="Shampoo Elseve L’Oréal Reparação Total 5 200ml">
            </a>
        </div>
        <div class="product-item">
            <a href="detalhes_produto.php?id=40">
                <img src="img/shampoo3.jpg" alt="Shampoo Pantene Controle de Queda 400ml">
            </a>
        </div>
        <div class="product-item">
            <a href="detalhes_produto.php?id=41">
                <img src="img/shampoo4.jpg" alt="Shampoo Tresemmé Detox Capilar 400ml">
            </a>
        </div>
        <div class="product-item">
            <a href="detalhes_produto.php?id=42">
                <img src="img/creme1.jpg" alt="Creme de Pentear Seda Cachos Definidos 300ml">
            </a>
        </div>
        <div class="product-item">
            <a href="detalhes_produto.php?id=43">
                <img src="img/creme2.jpg" alt="Creme de Pentear Elseve Liso dos Sonhos 250ml">
            </a>
        </div>
    </section>

    <!-- Carrossel de Categorias -->
    <section class="carousel">
        <button class="carousel-button left">❮</button>
        <div class="carousel-images">
            <?php
            // Consulta para obter as categorias dinamicamente
            $query_categorias = "SELECT idcategorias, nome, imgcategoria FROM categorias";
            $resultado_categorias = mysqli_query($conexao, $query_categorias);

            if ($resultado_categorias && mysqli_num_rows($resultado_categorias) > 0) {
                while ($categoria = mysqli_fetch_assoc($resultado_categorias)) {
                    $id = htmlspecialchars($categoria['idcategorias']);
                    $nome = htmlspecialchars($categoria['nome']);
                    $img = htmlspecialchars($categoria['imgcategoria']); // Caminho da imagem no banco
                    echo "<a href='categoria.php?idcategoria=$id'>
                            <img src='$img' alt='$nome'>
                          </a>";
                }
            } else {
                echo "<p>Nenhuma categoria encontrada.</p>";
            }
            ?>
        </div>
        <button class="carousel-button right">❯</button>
    </section>

    <!-- Grid de Produtos -->
    <section class="product-grid">
        <div class="product-item">
            <a href="detalhes_produto.php?id=44">
                <img src="img/creme3.jpg" alt="Creme de Pentear Pantene Cachos Hidra-Vitaminados 240ml">
            </a>
        </div>
        <div class="product-item">
            <a href="detalhes_produto.php?id=45">
                <img src="img/creme4.jpg" alt="Creme de Pentear Novex Meus Cachos de Cinema 300ml">
            </a>
        </div>
        <div class="product-item">
            <a href="detalhes_produto.php?id=46">
                <img src="img/oleo1.jpg" alt="Óleo de Argan Moroccanoil 100ml">
            </a>
        </div>
        <div class="product-item">
            <a href="detalhes_produto.php?id=47">
                <img src="img/oleo2.jpg" alt="Óleo Capilar Elseve Óleo Extraordinário 100ml">
            </a>
        </div>
        <div class="product-item">
            <a href="detalhes_produto.php?id=48">
                <img src="img/oleo3.jpg" alt="Óleo de Coco Salon Line Umectante 100ml">
            </a>
        </div>
        <div class="product-item">
            <a href="detalhes_produto.php?id=49">
                <img src="img/oleo4.jpg" alt="Óleo Reparador Garnier Fructis Liso Absoluto 100ml">
            </a>
        </div>
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

    <script src="js/telaprincipal.js"></script>
</body>
<script>
        // Seleção de elementos
const carousel = document.querySelector('.carousel-images');
const images = document.querySelectorAll('.carousel-images img');
const btnLeft = document.querySelector('.carousel-button.left');
const btnRight = document.querySelector('.carousel-button.right');

// Configuração inicial
let currentIndex = 0;
const visibleImages = 3; // Número de imagens visíveis por vez
const imageWidth = 110; // Largura da imagem + margem

// Função para atualizar o carrossel
function updateCarousel() {
    const offset = -currentIndex * imageWidth;
    carousel.style.transform = `translateX(${offset}px)`;
}

// Botão Esquerdo (Retroceder)
btnLeft.addEventListener('click', () => {
    if (currentIndex > 0) {
        currentIndex--;
    } else {
        currentIndex = images.length - visibleImages; // Vai para o último conjunto
    }
    updateCarousel();
});

// Botão Direito (Avançar)
btnRight.addEventListener('click', () => {
    if (currentIndex < images.length - visibleImages) {
        currentIndex++;
    } else {
        currentIndex = 0; // Volta para o início
    }
    updateCarousel();
});

    </script>

</html>