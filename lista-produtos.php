<?php
session_start();
$title = "Lista de produtos";
include_once "header.php";
include_once "autenticacao.php";
include_once "submenu.php";
include_once "conexao.php";


echo 
    "<div id='cadastros'>
        <table>
            <tr>
                <th>IdProduto</th>
                <th>Nome</th>
                <th>Preço de custo</th>
                <th>Margem de lucro</th>
                <th>ICMS</th>
                <th>Estoque</th>
                <th>Disponível</th>
                <th>Tamanho</th>
                <th>Ação</th>
            </tr>
            ";

$sql = "SELECT 
            p.idproduto    \"idproduto\",
            p.nome         \"nome\",
            p.preco_custo  \"preco_custo\",
            p.margem_lucro \"margem_lucro\",
            p.icms         \"icms\",
            p.quantidade   \"quantidade\",
            p.disponivel   \"disponivel\",
            t.nome         \"tamanho\"
        FROM e2.produto p , e2.tamanho t
            WHERE p.idtamanho = t.idtamanho
        ORDER BY p.idproduto";
$rs = pg_query ($con, $sql);

while ($data = pg_fetch_array($rs)){
    $isActive = $data['disponivel']=="Sim"? "<tr>" : "<tr class='produto-desativado'>";
    echo    $isActive.
            "<td><b>{$data['idproduto']}</b></td>
            <td>{$data['nome']}</td>
            <td>{$data['preco_custo']}</td>
            <td>{$data['margem_lucro']} %</td>
            <td>{$data['icms']} %</td>
            <td>{$data['quantidade']} un.</td>
            <td>{$data['disponivel']}</td>
            <td>{$data['tamanho']}</td>
            <td>
                <a href='atualizar-produto.php?id={$data['idproduto']}' id='editar' title='Editar'><i class='material-icons'>mode_edit</i></a>
                <a href='excluir-produto.php?id={$data['idproduto']}' id='excluir' title='Excluir'><i class='material-icons'>delete</i></a>
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

include_once "footer.php";
?>


<script>
        let item = document.getElementById('submenu-produtos');
        item.classList.add('submenu-ativo');
</script>  