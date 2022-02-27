<?php
session_start();
include_once "verificaSessao.php";
$title = "Carrinho";
include_once "conexao.php";
include_once "header.php";
$usuario = $_SESSION['usuario'];
$idusuario = $_SESSION['idusuario'];

#defini a qtd. do carrinho, e atribui o valor usuário da sessão p. variável
$qtdcarrinho = 0;

#faz uma consultado da coluna qtd na tabela carrinho, que estão relacionados ao usuário
$countCart = "SELECT qtdcompra \"qtd\"
                FROM e2.carrinho 
              WHERE idusuario = '$idusuario'
              AND concluido = 'Não'";

$rs = pg_query($con, $countCart);
$linhas = pg_affected_rows($rs);

#se o retorno for maior que 0, significa que possui dados, então fazemos um auto-incremento através do loop
#se o retorno for igual a 0, significa que não existe produto, então o valor do carrinho será 0;
if ($linhas > 0){
    while ($data = pg_fetch_array($rs)){
        $qtdcarrinho += $data['qtd'];
    }    
}else {
    $qtdcarrinho = 0;
}

#após a validação, geramos um elemento HTML com a quantidade, que ficará acima do carrinho

echo "<span class='contador-carrinho'>$qtdcarrinho</span>";

echo 
    "<div id='div-cart'>
        <table>
            <tr>
                <th>Produto</th>
                <th>Tamanho</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Subtotal</th>
                <th>Excluir</th>
            </tr>
            ";

$sql = "SELECT 
            p.idproduto   \"idproduto\",
            p.nome        \"nome\",
            t.nome        \"tamanho\",
            c.qtdcompra   \"qtd\",
            p.preco_venda \"preco_venda\"
        FROM e2.carrinho c, e2.produto p, e2.tamanho t
        WHERE c.idusuario = '$idusuario'
            AND c.idproduto = p.idproduto
            AND p.idtamanho = t.idtamanho
            AND c.concluido = 'Não'
        ORDER BY p.nome";
$rs = pg_query ($con, $sql);

$total = 0;
$soma  = 0;

while ($data = pg_fetch_array($rs)){
    $subtotal    = str_replace('.',',',$data['preco_venda']*$data['qtd']);
    $preco_venda = str_replace('.',',',$data['preco_venda']);
    $soma       +=$data['preco_venda']*$data['qtd'];
    echo 
        "<tr>
            <td>{$data['nome']}</td>
            <td>{$data['tamanho']}</td>
            <td>
               <ul class='qtd-carrinho'>
                    <li> <a href='remove-item.php?idproduto={$data['idproduto']}'><i class='material-icons'>remove</i></a></li>
                    <li>{$data['qtd']}</li>
                    <li><a href='adiciona-item.php?idproduto={$data['idproduto']}'><i class='material-icons'>add</i></a></li>
               </ul>
            </td>
            <td>R$ {$preco_venda}</td>
            <td>R$ {$subtotal}</td>
            <td><a href='excluir-item.php?idproduto={$data['idproduto']}' title='excluir'><i class='material-icons'>clear</i></a></td>
        </tr>";
}

    $total = str_replace('.',',',$soma);
echo "<tfoot>
            <tr>
                <td>Total:</td>
                <td>R$ $total</td>
                <td colspan='4'><button id='btn-comprar'><a href='finalizar-pedido.php'>Finalizar a compra</a></button></td>
            </tr>
        </tfoot>
        </table> </div>";
        pg_close($con);
include_once "footer.php";
?>

<script>
    let item = document.getElementById('menu-carrinho');
    item.classList.add('menu-ativo');
</script>