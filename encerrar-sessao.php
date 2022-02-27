<?php

session_start();

unset($_SESSION['usuario']);
unset($_SESSION['senha']);
unset($_SESSION['idusuario']);
unset($_SESSION['admin']);

header('location:login.php');

?>