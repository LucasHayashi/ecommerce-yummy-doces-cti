<?php
ob_start();
session_start();
$title = "Adiciona Item";
include_once "conexao.php";

$idusuario = $_SESSION['idusuario'];
$idproduto = $_GET['idproduto'];

$sql = "UPDATE e2.carrinho
        SET qtdcompra = qtdcompra + 1
        WHERE idusuario = '$idusuario'
        AND idproduto = $idproduto";

$rs = pg_query($con, $sql);

$linhas = pg_affected_rows($rs);
if ($linhas>0){
    header('location:carrinho.php');
	ob_end_flush();
}else {
    echo "Ops! Houve um erro ao atualizar a quantidade do produto no carrinho de compras.";
}
pg_close($con);
?>