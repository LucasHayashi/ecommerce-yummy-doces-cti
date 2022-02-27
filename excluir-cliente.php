<?php
ob_start();
session_start();
$title = "Exclusão de cliente";
include_once "autenticacao.php";
include_once "conexao.php";

$id = $_GET['id'];

$sql = "UPDATE e2.cadastros
            SET excluido = 'Sim',
		dt_exclusao = CURRENT_TIMESTAMP		
        WHERE idusuario = '$id'";

$rs = pg_query($con, $sql);

$linhas = pg_affected_rows($rs);

if ($linhas>0){
    header('location:usuarios.php?msg1=Cadastro excluído com sucesso');
    ob_end_flush();
}else {
    header('location:usuarios.php?msg2=Falha ao excluir o cadastro');
    ob_end_flush();
}
pg_close($con);
?>
