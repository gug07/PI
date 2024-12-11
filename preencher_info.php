<?php
// Inicia a sessão e conecta ao banco de dados
session_start();

$host = 'localhost';
$dbname = 'techair';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
    exit;
}

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');  // Redireciona para login se não estiver logado
    exit;
}

$user_id = $_SESSION['user_id'];

// Buscar dados do cliente
$query = "SELECT c.nomecliente, c.email, c.telefone, e.rua, e.numero, e.complemento, e.bairro, e.cidade, e.estado, e.cep, e.pais
          FROM cliente c
          LEFT JOIN enderecos e ON c.idcliente = e.cliente_idcliente
          WHERE c.idcliente = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preencher Informações</title>
    <link rel="stylesheet" href="css/perfil.css">
</head>
<body>
    <!-- Cabeçalho -->
    <header>
        <div class="header-logo">Logo</div>
        <nav class="header-nav">
            <a href="telaprincipal.html">PRINCIPAL</a>
            <a href="listasprodutos.php">PRODUTOS</a>
            <a href="#">Link três</a>
            <a href="#">Link quatro</a>
        </nav>
        <div class="header-actions">
            <input type="text" placeholder="Pesquisar" id="search-bar">
            <button id="cart-button" onclick="window.location.href='carrinho.php'">🛒</button>
            <button id="profile-button" onclick="window.location.href='perfil.html'">Perfil</button>
        </div>
    </header>

    <!-- Seção principal -->
    <main>
        <section class="perfil-section">
            <h1>Meu Perfil</h1>
            
            <!-- Exibe os dados do usuário -->
            <form action="atualizar_perfil.php" method="POST">
                <div>
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" value="<?php echo $usuario['nomecliente']; ?>" required>
                </div>
                <div>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $usuario['email']; ?>" required>
                </div>
                <div>
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone" value="<?php echo $usuario['telefone']; ?>" required>
                </div>

                <h3>Endereço</h3>
                <div>
                    <label for="rua">Rua</label>
                    <input type="text" id="rua" name="rua" value="<?php echo $usuario['rua']; ?>">
                </div>
                <div>
                    <label for="numero">Número</label>
                    <input type="text" id="numero" name="numero" value="<?php echo $usuario['numero']; ?>">
                </div>
                <div>
                    <label for="complemento">Complemento</label>
                    <input type="text" id="complemento" name="complemento" value="<?php echo $usuario['complemento']; ?>">
                </div>
                <div>
                    <label for="bairro">Bairro</label>
                    <input type="text" id="bairro" name="bairro" value="<?php echo $usuario['bairro']; ?>">
                </div>
                <div>
                    <label for="cidade">Cidade</label>
                    <input type="text" id="cidade" name="cidade" value="<?php echo $usuario['cidade']; ?>">
                </div>
                <div>
                    <label for="estado">Estado</label>
                    <input type="text" id="estado" name="estado" value="<?php echo $usuario['estado']; ?>">
                </div>
                <div>
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep" value="<?php echo $usuario['cep']; ?>">
                </div>
                <div>
                    <label for="pais">País</label>
                    <input type="text" id="pais" name="pais" value="<?php echo $usuario['pais']; ?>">
                </div>

                <button type="submit">Salvar Alterações</button>
            </form>
        </section>
    </main>

    <!-- Rodapé -->
    <footer>
        <div class="footer-columns">
            <div>
                <h4>Logo</h4>
                <p>Endereço:<br>123 Main Street, City<br>State Province, Country</p>
            </div>
            <div>
                <h4>Colunas</h4>
                <a href="#">Link um</a><br>
                <a href="#">Link dois</a><br>
                <a href="#">Link três</a>
            </div>
            <div>
                <h4>Redes Sociais</h4>
                <a href="#">Instagram</a><br>
                <a href="#">Facebook</a><br>
                <a href="#">Twitter</a>
            </div>
        </div>
        <p>© 2024 Sua Empresa. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
