<?php
ob_start();
session_start();
$title = "Exclusão de produto";
include_once "autenticacao.php";
include_once "conexao.php";

$id = $_GET['id'];

$sql = "UPDATE e2.produto
            SET disponivel = 'Não'
        WHERE idproduto = '$id'";

$rs = pg_query($con, $sql);

$linhas = pg_affected_rows($rs);

if ($linhas>0){
    header('location:lista-produtos.php?msg1=Produto excluído com sucesso');
    ob_end_flush();
}else {
    header('location:lista-produtos.php?msg2=Falha ao excluir o produto');
    ob_end_flush();
}
pg_close($con);
?>