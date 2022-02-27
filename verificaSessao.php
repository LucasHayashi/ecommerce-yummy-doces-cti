<?php
if(  (!isset ($_SESSION['usuario']) == true)   and   (!isset ($_SESSION['senha']) == true))
    {
        unset($_SESSION['usuario']);
        unset($_SESSION['idusuario']);
        unset( $_SESSION['senha']);
        unset( $_SESSION['admin']);
        header('location:login.php?msg=Você precisa estar logado para acessar a página produtos ou carrinho!!!');
    } else {
        $usuario = $_SESSION['usuario'];
    }
?>