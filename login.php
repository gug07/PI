<?php
include('conexao.php');

// Verifica se as variáveis de email e senha foram enviadas via POST
if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["username"])) {

    // Obtém os valores de email e senha do formulário
    $user = $_POST['username'];
    $email = $_POST['email'];
    $senha = $_POST['password'];

    // Prepara a consulta SQL para verificar as credenciais do usuário
    $login = "SELECT * FROM cliente WHERE email = '$email' AND senha = '$senha' AND nomecliente = '$user'";
    $acesso = mysqli_query($conexao, $login);

    // Verifica se a consulta foi bem-sucedida
    if (!$acesso) {
        echo "Falha de login: " . mysqli_error($conexao); // Exibe erro se houver
        exit;
    }

    // Obtém as informações do usuário a partir do resultado da consulta
    $informaçao = mysqli_fetch_assoc($acesso);

    // Verifica se não encontrou nenhum resultado
    if (empty($informaçao)) {
        echo "Email ou Senha incorretos."; // Mensagem de erro
    } else {
        // Armazena os dados do usuário na sessão
        session_start();
        $_SESSION['cliente_id'] = $informaçao['idcliente']; // Salva o ID do cliente na sessão
        $_SESSION['nome'] = $informaçao['nomecliente'];    // Salva o nome do cliente
        $_SESSION['email'] = $informaçao['email'];         // Salva o email

        // Redireciona para a página principal em caso de sucesso
        header("Location: telaprincipal.php");
        exit; // Encerra o script para garantir que não haja mais saída
    }
}
?>
