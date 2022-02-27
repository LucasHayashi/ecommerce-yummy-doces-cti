<?php
ob_start();
session_start();
$title = "Atualizar Cadastro";
include_once "autenticacao.php";
include_once "header.php";
include_once "submenu.php";
include_once "conexao.php";

$id = $_GET['id'];

$sql = "SELECT  usuario     \"usuario\",
                nome        \"nome\",
                sobrenome   \"sobrenome\", 
                cpf         \"cpf\",
                celular     \"celular\", 
                cep         \"cep\",
                logradouro  \"rua\",
                bairro      \"bairro\",
                cidade      \"cidade\",
                estado      \"estado\",
                adminuser   \"adminuser\",
                excluido    \"excluido\"
        FROM e2.cadastros
            WHERE idusuario = '$id'";
$rs = pg_query($con,$sql);
$linhas = pg_affected_rows($rs);

while ($data = pg_fetch_array($rs)){
    echo "
    <div class='sub-container'>
        <form action='' method='POST' class='formulario form-cliente'>
            <label>Usuario:</label><br>
                <input type='text' name='usuario' value='{$data['usuario']}' disabled><br>
            <label>Senha:</label><br>
                <input type='password' name='senha' required><br>
            <label>Nome</label><br>
                <input type='text' name='nome' value='{$data['nome']}' required><br>
            <label>Sobrenome</label><br>
                <input type='text' name='sobrenome' value='{$data['sobrenome']}' required><br>
            <label>CPF:</label><br>
                <input type='text' name='cpf' value='{$data['cpf']}' disabled><br>
            <label>Celular:</label><br>
                <input type='text' name='celular' value='{$data['celular']}' required><br>
            <label>CEP:</label><br>
                <input type='text' name='cep' value='{$data['cep']}' required><br>
            <label>Logradouro / Nº:</label><br>
                <input type='text' name='rua' value='{$data['rua']}' required><br>
            <label>Bairro:</label><br>
                <input type='text' name='bairro' value='{$data['bairro']}' required><br>
            <label>Cidade:</label><br>
                <input type='text' name='cidade' value='{$data['cidade']}' required><br>
            <label>Estado</label><br>
                <input type='text' name='estado' value='{$data['estado']}' required><br>
            <label>Adminuser</label><br>
                <select name='adminuser' required>
                    <option value=''>--Selecione--</option>
                    <option value='Sim'>Sim</option>
                    <option value='Não'>Não</option>
                </select><br>
            <label>Excluído</label><br>
                <select name='excluido' required>
                    <option value=''>--Selecione--</option>
                        <option value='Sim'>Sim</option>
                        <option value='Não'>Não</option>
                </select><br>
                <input type='submit' value='Atualizar' name='atualizar'>
        </form>
    </div>
";  
}


if (isset($_POST['atualizar'])){
    $senha     = md5($_POST['senha']);
    $nome      = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $celular   = $_POST['celular']; 
    $cep       = $_POST['cep'];
    $rua       = $_POST['rua'];
    $bairro    = $_POST['bairro'];
    $cidade    = $_POST['cidade'];
    $estado    = $_POST['estado'];
    $adminuser = $_POST['adminuser'];
    $excluido  = $_POST['excluido'];

    $sql = "UPDATE e2.cadastros
            SET senha      = '$senha',
                nome       = '$nome',
                sobrenome  = '$sobrenome', 
                celular    = '$celular', 
                cep        = '$cep',
                logradouro = '$rua',
                bairro     = '$bairro',
                cidade     = '$cidade',
                estado     = '$estado',
                adminuser  = '$adminuser',
                excluido   = '$excluido'
            WHERE idusuario = $id";

    $rs = pg_query($con, $sql);
    $linhas = pg_affected_rows($rs);
    echo $sql;
    if ($linhas>0){
    header('location:usuarios.php?msg3=Cadastro atualizado com sucesso');
    ob_end_flush();
    }else {
    header('location:usuarios.php?msg4=Falha ao atualizar o cadastro');
    ob_end_flush();
    }
    pg_close($con);
}
include_once "footer.php";
?>

<script>
        let item = document.getElementById('submenu-usuarios');
        item.classList.add('submenu-ativo');
</script>  