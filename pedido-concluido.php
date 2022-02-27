<?php
session_start();
include_once "verificaSessao.php";
$title = "Pedido concluido";
include_once "conexao.php";
include_once "header.php";
$usuario = $_SESSION['usuario'];
$idusuario = $_SESSION['idusuario'];
$idvenda = $_GET['idvenda'];

echo "<div class='info-pedido'>
            <div class='info-pedido-icon'>
                <i class='material-icons'>check_circle</i>
            </div>
            <div class='info-pedido-text'>
                <h1>Muito obrigado pela sua compra!</h1>
                <p>Já estamos preparando o seu pedido.</p>
            </div>
            <div class='info-pedido-number'>
                <i class='material-icons'>info</i>
                    <span>Pedido #$idvenda</span>
            </div>
      </div>";



include_once "footer.php";
?>


