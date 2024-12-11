<?php
include('conexao.php');

// Verifica se todos os dados foram enviados
if (isset($_POST['username'], $_POST['email'], $_POST['telefone'], $_POST['password'])) {
    $nome = $_POST['username'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['password'];

    // Protege contra injeção de SQL
    $nome = mysqli_real_escape_string($conexao, $nome);
    $email = mysqli_real_escape_string($conexao, $email);
    $telefone = mysqli_real_escape_string($conexao, $telefone);
    $senha = mysqli_real_escape_string($conexao, $senha);

    // Insere o usuário no banco
    $sql = "INSERT INTO cliente (nomecliente, email, senha, telefone) VALUES ('$nome', '$email', '$senha', '$telefone')";

    if (mysqli_query($conexao, $sql)) {
        echo "<script>alert('Cadastro realizado com sucesso!');</script>";
        echo "<a href='Index.html'>Voltar ao login</a>";
    } else {
        echo "Erro ao cadastrar: " . mysqli_error($conexao);
    }
}
?>
