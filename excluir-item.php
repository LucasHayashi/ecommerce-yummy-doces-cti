<?php
ob_start();
session_start();
$title = "Exclui item do carrinho";
include_once "conexao.php";

$idusuario = $_SESSION['idusuario'];

$idproduto = $_GET['idproduto'];

$sql = "DELETE FROM e2.carrinho
            WHERE idproduto = $idproduto
            AND idusuario = '$idusuario'";

$rs = pg_query($con, $sql);

$linhas = pg_affected_rows($rs);

if ($linhas>0){
    header('location:carrinho.php');
    ob_end_flush();
}else {
    echo "Ops! Houve um erro ao excluir o produto do carrinho de compras.";
}
pg_close($con);
?>