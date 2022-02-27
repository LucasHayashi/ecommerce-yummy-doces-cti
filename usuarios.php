<?php
session_start();
$title = "Clientes";
include_once "header.php";
include_once "autenticacao.php";
include_once "submenu.php";
include_once "conexao.php";

echo 
    "<div id='cadastros'>
        <table>
            <tr>
                <th>IdUsuario</th>
                <th>Usuario</th>
                <th>Nome / Sobrenome</th>
                <th>Celular</th>
                <th>Cidade</th>
                <th>Estado</th>
                <th>Adminuser</th>
                <th>Excluído</th>
                <th>Ação</th>
            </tr>
            ";

$sql = "SELECT idusuario \"idusuario\",
               usuario \"usuario\",
               nome \"nome\",
               sobrenome \"sobrenome\",
               celular \"celular\",
               cidade \"cidade\",
               estado \"estado\" ,
               adminuser \"adminuser\",
               excluido \"excluido\"
            FROM e2.cadastros
            ORDER BY idusuario
        ";
$rs = pg_query ($con, $sql);

while ($data = pg_fetch_array($rs)){
    echo 
        "<tr>
            <td><b>{$data['idusuario']}</b></td>
            <td>{$data['usuario']}</td>
            <td>{$data['nome']} {$data['sobrenome']}</td>
            <td>{$data['celular']}</td>
            <td>{$data['cidade']}</td>
            <td>{$data['estado']}</td>
            <td>{$data['adminuser']}</td>
            <td>{$data['excluido']}</td>
            <td>
                <a href='atualizar-cadastro.php?id={$data['idusuario']}' id='editar' title='Editar'><i class='material-icons'>mode_edit</i></a>
                <a href='excluir-cliente.php?id={$data['idusuario']}' id='excluir' title='Excluir'><i class='material-icons'>delete</i></a>
            </td>
        </tr>";
}
echo " </table> </div>";

if (isset($_GET['msg1'])){
    $msg = $_GET['msg1'];
    echo "
    <div class='aviso sucesso'>
        <p>$msg</p>
    </div>
 ";
}else if (isset($_GET['msg2'])){
    $msg = $_GET['msg2'];
    echo "
    <div class='aviso erro'>
        <p>$msg</p>
    </div>
 ";    
} else if (isset($_GET['msg3'])){
    $msg = $_GET['msg3'];
    echo "
    <div class='aviso sucesso'>
        <p>$msg</p>
    </div>
 ";    
} else if (isset($_GET['msg4'])){
    $msg = $_GET['msg4'];
    echo "
    <div class='aviso erro'>
        <p>$msg</p>
    </div>
 ";    
}
pg_close($con);
include_once "footer.php";
?>


<script>
        let item = document.getElementById('submenu-usuarios');
        item.classList.add('submenu-ativo');
</script>  