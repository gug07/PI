<?php
session_start();
include("conexao.php");

if (!isset($_SESSION['cliente_id'])) {
    header("Location: index.html");
    exit;
}

$cliente_id = $_SESSION['cliente_id'];
$rua = $_POST['rua'];
$numero = $_POST['numero'];
$complemento = $_POST['complemento'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$cep = $_POST['cep'];
$pais = $_POST['pais'];

// Verifica se o endereço já existe
$query = $conexao->prepare("SELECT idenderecos FROM enderecos WHERE cliente_idcliente = ?");
$query->bind_param("i", $cliente_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    // Atualiza o endereço existente
    $query_update = $conexao->prepare("
        UPDATE enderecos 
        SET rua = ?, numero = ?, complemento = ?, bairro = ?, cidade = ?, estado = ?, cep = ?, pais = ?
        WHERE cliente_idcliente = ?
    ");
    $query_update->bind_param("ssssssssi", $rua, $numero, $complemento, $bairro, $cidade, $estado, $cep, $pais, $cliente_id);
    $query_update->execute();
} else {
    // Insere um novo endereço
    $query_insert = $conexao->prepare("
        INSERT INTO enderecos (cliente_idcliente, rua, numero, complemento, bairro, cidade, estado, cep, pais) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $query_insert->bind_param("issssssss", $cliente_id, $rua, $numero, $complemento, $bairro, $cidade, $estado, $cep, $pais);
    $query_insert->execute();
}

header("Location: perfil.php");
exit;
?>
