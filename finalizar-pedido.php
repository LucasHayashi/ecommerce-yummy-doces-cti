<?php
ob_start();
session_start();
include_once "verificaSessao.php";
$title = "Finalizar pedido";
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

echo "<div class='container-carrinho'>";

$sql = "SELECT
            nome       \"nome\",
            sobrenome  \"sobrenome\",
            cpf        \"cpf\",
            celular    \"celular\",
            logradouro \"rua\",
            bairro     \"bairro\",
            cidade     \"cidade\",
            estado     \"estado\",
            cep        \"cep\"
        FROM e2.Cadastros
        WHERE idusuario = $idusuario";

$rs = pg_query($con,$sql);

while ($data = pg_fetch_array($rs)){

echo "<div class='destino'>
        <h3>Destino</h3>
            <ul>
                <li>{$data['nome']} {$data['sobrenome']} - CPF {$data['cpf']}</li>
                <li>{$data['rua']}</li>
                <li>{$data['bairro']}, {$data['cidade']},{$data['estado']}</li>
                <li><b>{$data['cep']}</b></li>
                <li>Cel.: {$data['celular']}</li>
            </ul>
      </div>";

}

$sql2 = "SELECT 
            p.idproduto \"idproduto\",
            p.nome \"nome\",
            t.nome \"tamanho\",
            c.qtdcompra \"qtd\",
            p.preco_venda \"preco_venda\"
        FROM e2.carrinho c, e2.produto p, e2.tamanho t
        WHERE c.idusuario = '$idusuario'
            AND c.idproduto = p.idproduto
            AND p.idtamanho = t.idtamanho
            AND c.concluido = 'Não'";
$rs2 = pg_query ($con, $sql2);

$total = 0;
$soma  = 0;

echo "<div class='myorder'>
            <h3>Seu pedido</h3>
                <table>";

while ($data = pg_fetch_array($rs2)){
    $preco_venda = str_replace('.',',',$data['preco_venda']);
    $subtotal  = str_replace('.',',',$data['preco_venda']*$data['qtd']);
    $soma     +=$data['preco_venda']*$data['qtd'];
    echo 
    "<tr>
        <td>{$data['nome']}</td>
        <td><b>Qtd.</b> {$data['qtd']}</td>
        <td><b>Sub. </b>R$ {$subtotal}</td>
    </tr>";
}
$total = str_replace('.',',',$soma);
echo "<tr>
        <td colspan='3'><b>Total:</b> R$ $total</td>
     </tr>";
echo "</table></div>";
?>

<div class="payment-div">
        <h3>Forma de pagamento</h3>
        <form action="" method="POST" id='payment-methods'>
            <div class="payment-div-item">            
                <label for="Cartão de crédito"><input type="radio" name="payment-id" value="Cartão de crédito" checked>Cartão de crédito</label>
                <ul>
                    <li><img src="img/payment/elo.svg"></li>
                    <li><img src="img/payment/hipercard.svg"></li>
                    <li><img src="img/payment/mastercard.svg"></li>
                    <li><img src="img/payment/visa.svg"></li>
                </ul>
            </div>
            <div class="payment-div-item">
                <label for="Boleto bancário"><input type="radio" name="payment-id" value="Boleto bancário">Boleto bancário</label>
                <ul>
                    <li><img src="img/payment/boleto.svg"></li>
                </ul>
            </div>
            <div class="payment-div-item">
                <label for="Pix"><input type="radio" name="payment-id" value="Pix">Pix</label>
                <ul>
                    <li><img src="img/payment/pix.svg"></li>
                </ul>
            </div>
            <div class="payment-div-item">
                <label for="Mercado Pago"><input type="radio" name="payment-id" value="Mercado Pago">Mercado Pago / PagSeguro</label>
                <ul>
                    <li><img src="img/payment/mercadopago.svg"></li>
                    <li><img src="img/payment/pagseguro.svg"></li>
                </ul>
            </div>
            <div class="payment-div-item">           
                <label for="PayPal"><input type="radio" name="payment-id" value="PayPal">PayPal</label>
                <ul>
                    <li><img src="img/payment/paypal.svg"></li>
                </ul>
            </div>

            <div class="payment-div-item">           
                <input type="submit" name="submit" value="Confirmar compra" id="confirmar">
            </div>
        </form>
    </div>

<?php
echo "</div>";



if (isset($_POST['submit'])){
    $formaDePagamento = $_POST['payment-id'];

    echo $formaDePagamento;

    $idvenda = date('Ymdis');

    $addVenda = "INSERT INTO e2.vendas (idvenda,idusuario,total,formaDePagamento,dataConfirmacao)
                    VALUES ('$idvenda',$idusuario,'$total','$formaDePagamento',CURRENT_TIMESTAMP)";
    
    $rs2 = pg_query($con,$addVenda);

    $sql = "UPDATE e2.carrinho
                SET concluido = 'Sim',
                    idvenda   = $idvenda
            WHERE idusuario   = $idusuario
                AND concluido = 'Não'";

    $rs = pg_query($con, $sql);

    $baixaestoque = "UPDATE e2.produto
        SET quantidade = quantidade - e2.carrinho.qtdcompra
    FROM e2.carrinho
        WHERE e2.produto.idproduto  =  e2.carrinho.idproduto
        AND e2.carrinho.concluido = 'Sim'
        AND e2.carrinho.idvenda = $idvenda";

    $rs3 = pg_query($con, $baixaestoque);

    if ($rs && $rs2 && $rs3){
        $sql = "UPDATE e2.produto SET disponivel = 'Não' WHERE e2.produto.quantidade <= '0'";
        $rs  = pg_query($con,$sql);
        header("location: pedido-concluido.php?idvenda=$idvenda");
        ob_end_flush();
    }else {
        echo "OPS, houve um erro ao concluir o seu pedido.";
    }
}

pg_close();
include_once "footer.php";
?>