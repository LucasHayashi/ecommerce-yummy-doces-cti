<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reset.css">
</head>
<body>
<div class="container">
    <!-- cabeçalho -->
    <div class="header" id="ancora">
            <span class="logo">
                <a href="home.html"><img src="img/logo.png" alt="Yummy Doces Caseiros"></a>
            </span>
            <div class="menu">
                <ul>
                    <li>
                        <a href="home.html">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>                       
                        
                    </li>
                    <li id="menu-produtos">
                        <a href="produtos.php">
                            <i class="material-icons">store</i>
                            <span>Produtos</span>
                        </a>
                    </li>
                    <li id="menu-estatisticas">
                        <a href="estatisticas.php">
                            <i class="material-icons">trending_up</i>
                            <span>Estatísticas</span>
                        </a>
                    </li>
                    <li>
                        <a href="devs.html">
                            <i class="material-icons">logo_dev</i>
                            <span>Devs</span>
                        </a></li>
                    <li id="menu-login">
                        <a href="login.php" id="login">
                            <i class="material-icons">login</i>
                            <span>Login</span>
                        </a>
                    </li>
                    <li id="menu-carrinho">
                        <a href="carrinho.php" id="carrinho">
                            <i class="material-icons">shopping_cart</i>
                            <span>Carrinho</span>
                        </a>
                    </li>
                    <?php
                        if(isset ($_SESSION['usuario']) == true)
                        {
                            echo '<li id="account-settings">
                                        <a href="#" id="">
                                            <i class="material-icons">account_circle</i>
                                            <span>Conta</span>
                                        </a>
                                        <ul class="submenu">
                                            <li><a href="cadastro-produto.php">Editar produtos</a></li>
                                            <li><a href="encerrar-sessao.php">Sair</a></li>
                                        </ul>                          
                                  </li>';
                        }
                    ?>                    
                </ul>
            </div>
        </div>