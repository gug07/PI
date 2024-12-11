<?php
// Inicia a sessão para obter os dados do usuário logado
session_start();

// Conectar ao banco de dados
$host = 'localhost';  // ou o host do seu banco de dados
$dbname = 'techair';  // nome do banco de dados
$username = 'root';  // seu nome de usuário do banco
$password = '';  // sua senha do banco

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    // Atualizar dados do cliente
    $query = "UPDATE cliente SET nomecliente = :nome, email = :email, telefone = :telefone WHERE idcliente = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    // Atualizar dados do endereço
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $cep = $_POST['cep'];
    $pais = $_POST['pais'];

    $query_endereco = "UPDATE enderecos SET rua = :rua, numero = :numero, complemento = :complemento, bairro = :bairro, cidade = :cidade, estado = :estado, cep = :cep, pais = :pais WHERE cliente_idcliente = :user_id";
    $stmt_endereco = $pdo->prepare($query_endereco);
    $stmt_endereco->bindParam(':rua', $rua);
    $stmt_endereco->bindParam(':numero', $numero);
    $stmt_endereco->bindParam(':complemento', $complemento);
    $stmt_endereco->bindParam(':bairro', $bairro);
    $stmt_endereco->bindParam(':cidade', $cidade);
    $stmt_endereco->bindParam(':estado', $estado);
    $stmt_endereco->bindParam(':cep', $cep);
    $stmt_endereco->bindParam(':pais', $pais);
    $stmt_endereco->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt_endereco->execute();

    // Redirecionar para o perfil após salvar
    header("Location: perfil.php");
    exit;
}
?>
