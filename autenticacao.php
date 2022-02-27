<?php
if(  (!isset ($_SESSION['usuario']) == true)   and   (!isset ($_SESSION['senha']) == true)   or   $_SESSION['admin'] == 'Não')
    {
        unset($_SESSION['usuario']);
        unset($_SESSION['idusuario']);
        unset( $_SESSION['senha']);
        unset( $_SESSION['admin']);
        header('location:login.php');
    } else {
        $usuario = $_SESSION['usuario'];
    }
?>