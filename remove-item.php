<?php
ob_start();
session_start();
$title = "Adiciona Item";
include_once "conexao.php";

$idusuario = $_SESSION['idusuario'];
$idproduto = $_GET['idproduto'];

$sql = "UPDATE e2.carrinho
        SET qtdcompra = qtdcompra - 1
        WHERE idusuario = '$idusuario'
        AND idproduto = $idproduto
        AND concluido = 'Não'";

$rs = pg_query($con, $sql);
$linhas = pg_affected_rows($rs);

if ($linhas > 0){
    $sql = "SELECT qtdcompra \"qtd\"
            FROM e2.carrinho
                WHERE idusuario = '$idusuario'
            AND idproduto = $idproduto
            AND concluido = 'Não'";

    $rs     = pg_query($con, $sql);

    #retorna exatamente o valor da consulta, seja número, string ou booleano.
    $return = pg_fetch_result($rs,0);
    
    if ($return == 0){
        header("location: excluir-item.php?idproduto='$idproduto'");
	ob_end_flush();
    }else {
        header('location:carrinho.php');
	ob_end_flush();
    }
}else {
    echo "Ops! Houve um erro ao atualizar a quantidade do produto no carrinho de compras.";
}
pg_close($con);
?>