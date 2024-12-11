<?php
$host = "localhost";
$usuario = "root";
$senha = "1234";
$dbname = "techair1";

$conexao = new mysqli($host, $usuario, $senha, $dbname);

if($conexao->connect_error){

    echo "Erro" .mysqli_error($conexao);


}else{

   // echo "Conexão realizada com sucesso.";

    //header('Location: index.html');

}
?>