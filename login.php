<?php
ob_start();
$title = "Entrar na conta da Yummy";
include_once "header.php";
?>

<div class="sub-container">
    <!-- formulario -->
    <form action="" method="post" class="formulario form-login">
        <img src="img/yummy.png">
        <label for="usuario">Usuário</label><br/>
        <input type="text" name="usuario" placeholder="Digite seu nome de usuario"><br/>
        <label for="senha">Senha</label><br/>
        <input type="password" name="senha" placeholder="Digite sua senha"><br/>
        <span>Não tem uma conta? <a href="cadastro-cliente.php">Crie uma!</a><br/></span>
        <input type="submit" value="Entrar" id="enviar" name="submit"><br/>
    </form>
</div>


<?php
include_once "conexao.php";
if ( session_status() !== PHP_SESSION_ACTIVE )
{
  session_start();
}

if (isset($_POST['submit'])){
    
    unset($_SESSION['usuario']);
    unset($_SESSION['idusuario']);
    unset($_SESSION['senha']);
    unset($_SESSION['admin']);
    $usuario = $_POST['usuario'];
    $senha   = md5($_POST['senha']);

    $sql = "SELECT c.idusuario \"idusuario\" 
                 FROM e2.cadastros c
                    WHERE c.usuario = '$usuario'
                 AND c.senha = '$senha'
                 AND C.adminuser = 'Sim'
                 AND C.excluido  = 'Não'";
    $rs = pg_query($con,$sql);
    
    $result = pg_fetch_result($rs,0);

    $linhas = pg_affected_rows($rs);

    if ($linhas > 0){
        $_SESSION['usuario']   = $usuario;
        $_SESSION['senha']     = $senha;
        $_SESSION['idusuario'] = $result;
        $_SESSION['admin']     = 'Sim';
        header('location:cadastro-produto.php');
        ob_end_flush();
    }else {
        $sql = "SELECT c.idusuario \"idusuario\"
                     FROM e2.cadastros c
                        WHERE c.usuario = '$usuario'
                    AND c.senha = '$senha'
                    AND C.adminuser = 'Não'
                    AND C.excluido  = 'Não'";
        $rs = pg_query($con,$sql);

        $result = pg_fetch_result($rs,0);

        $linhas = pg_affected_rows($rs);

        if ($linhas > 0){
            $_SESSION['usuario'] = $usuario;
            $_SESSION['senha']   = $senha;
            $_SESSION['idusuario'] = $result;
            $_SESSION['admin']   = 'Não';
            header('location:produtos.php');
	    ob_end_flush();
        }else {
            unset($_SESSION['usuario']);
            unset($_SESSION['senha']);
            unset($_SESSION['idusuario']);
            unset($_SESSION['admin']);
            echo "
            <div class='aviso erro'>
                <p>Usuário ou senha inválido!</p>
            </div>
         ";           
        }       
    }
    pg_close($con);
}

if (isset($_GET['msg'])){
    $msg = $_GET['msg'];
    echo "
    <div class='aviso sucesso'>
        <p>$msg</p>
    </div>";
}
?>

<?php
    include_once "footer.php";
?>

<script>
    let item = document.getElementById('menu-login');
    item.classList.add('menu-ativo');
</script>