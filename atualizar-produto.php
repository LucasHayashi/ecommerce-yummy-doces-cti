<?php
ob_start();
session_start();
$title = "Atualizar Cadastro";
include_once "autenticacao.php";
include_once "header.php";
include_once "submenu.php";
include_once "conexao.php";

$id = $_GET['id'];

$sql = "SELECT
            p.nome         \"nome\",
            p.preco_custo  \"preco_custo\",
            p.margem_lucro \"margem_lucro\",
            p.icms         \"icms\",
            p.quantidade   \"qtd\",
            p.disponivel   \"disponivel\",
            t.nome         \"tamanho\"
        FROM e2.produto p , e2.tamanho t
            WHERE p.idtamanho = t.idtamanho
            AND idproduto = '$id'
        ORDER BY p.idproduto";

$rs = pg_query($con,$sql);

$linhas = pg_affected_rows($rs);

while ($data = pg_fetch_array($rs)){
    echo "
    <div class='sub-container'>
        <form action='' method='POST' class='formulario form-cliente'>
            <label>Nome:</label><br>
                <input type='text' name='nome' value='{$data['nome']}' required><br>
            <label>Preço de custo:</label><br>
                <input type='text' name='preco_custo' value='{$data['preco_custo']}' required><br>
            <label>Margem de lucro %:</label><br>
                <input type='number' name='margem_lucro' value='{$data['margem_lucro']}' required><br>
            <label>ICMS %:</label><br>
                <input type='number' name='icms' value='{$data['icms']}' required><br>
            <label>Quantidade</label><br>
                <input type='number' name='quantidade' value='{$data['qtd']}' required><br>
            <label>Tamanho:</label><br>
                <select name='idtamanho' required>
                    <option value=''>--Selecione--</option>
                    <option value='1'>Médio</option>
                    <option value='2'>Grande</option>
                </select><br>
            <label>Disponível:</label><br>
                <select name='disponivel' required>
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
        $nome           = $_POST['nome'];
        $preco_custo    = floatval(trim(str_replace(',','.',$_POST['preco_custo'])));
        $margem_lucro   = $_POST['margem_lucro'];
        $icms           = $_POST['icms'];
        $preco_venda    = number_format(($preco_custo * ( 1 + ($margem_lucro/100))) / (1 - $icms/100), 2);
        $quantidade     = $_POST['quantidade'];
        $idtamanho      = $_POST['idtamanho'];
        $disponivel     = $_POST['disponivel'];

        $sql = "UPDATE e2.produto
                SET nome         = '$nome',
                    preco_custo  = $preco_custo,
                    margem_lucro = $margem_lucro, 
                    icms         = $icms, 
                    preco_venda  = $preco_venda,
                    quantidade   = $quantidade,
                    idtamanho    = $idtamanho,
                    disponivel   = '$disponivel'
                WHERE idproduto = $id";

        $rs = pg_query($con, $sql);

        $linhas = pg_affected_rows($rs);

        if ($linhas>0){
            header('location:lista-produtos.php?msg3=Produto atualizado com sucesso');
            ob_end_flush();
        }else {
            header('location:lista-produtos.php?msg4=Falha ao atualizar o produto');
            ob_end_flush();
        }
        pg_close($con);
    }
include_once "footer.php";
?>

<script>
        let item = document.getElementById('submenu-produtos');
        item.classList.add('submenu-ativo');
</script>  