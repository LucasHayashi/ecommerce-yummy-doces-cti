<?php
ob_start();
session_start();
$title = "Adiciona produto no carrinho";
include_once "conexao.php";

$idusuario = $_SESSION['idusuario'];

$idproduto = $_GET['idproduto'];


$sql = "SELECT 1 
        FROM e2.carrinho
            WHERE idusuario = $idusuario
            AND idproduto   = $idproduto
            AND concluido   = 'Não'";

$rs = pg_query($con, $sql);

$linhas = pg_affected_rows($rs);

if ($linhas == 0){
    $sql = "INSERT INTO e2.carrinho (idusuario, idproduto, qtdcompra, concluido) 
            VALUES ($idusuario,$idproduto, 1, 'Não')";

    $rs = pg_query($con,$sql);

    header('location:produtos.php?msg=Produto adicionado ao carrinho!');
    ob_end_flush();

}else if ($linhas > 0) {

    $sql = "UPDATE e2.carrinho
                SET qtdcompra = qtdcompra + 1
            WHERE idusuario = $idusuario
            AND idproduto = $idproduto";

    $rs = pg_query($con,$sql);

    header('location:produtos.php?msg=Produto adicionado ao carrinho!');
    ob_end_flush();
}else {
    header('location:produtos.php?msg2="Erro ao adicionar o produto no carrinho!"');
    ob_end_flush();
}
pg_close($con);
?>