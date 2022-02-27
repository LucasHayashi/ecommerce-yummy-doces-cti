<?php
session_start();
include_once "verificaSessao.php";
$title = "Produtos";
include "conexao.php";
include_once "header.php";

    #defini a qtd. do carrinho, e atribui o valor usuário da sessão p. variável
    $qtdcarrinho = 0;
    $idusuario = $_SESSION['idusuario'];

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

#Verifica se existe produtos disponíveis na tabela produto

$sql = "SELECT
            p.idproduto     \"id_produto\",
            p.nome          \"nome\",
            p.preco_venda   \"preco_venda\",
            p.quantidade    \"qtd\",
            p.imgname       \"imgname\",
            t.nome          \"tamanho\"
        FROM e2.produto p, e2.tamanho t
            WHERE p.idtamanho = t.idtamanho
            AND p.disponivel  = 'Sim'
            ORDER BY p.dt_cadastro";

$rs = pg_query($con,$sql);

echo "<div class='grid-container'>";

#enquanto tiver dados, ele vai gerar os elementos conforme o código abaixo
/*na linha 75, quando o usuário clicar no botão, enviamos o idproduto para a página adicionar-ao-carrinho
*que é responsável por realizar a inclusão do produto no carrinho do cliente
*/
while ($data = pg_fetch_array($rs)){
    $nome_produto = $data['nome'];
    $preco_venda  = str_replace('.',',',$data['preco_venda']);
    $tamanho      = substr($data['tamanho'],0,1);
    $quantidade   = $data['qtd'];
    $imagem_nome  = $data['imgname'];
    $id_produto   = $data['id_produto'];

    echo "
    <div class='grid-item'>
        <div>
            <img src='../ecommerce/img/produtos/$imagem_nome'>
        </div>
        <div>
            <h3>$nome_produto</h3>
        </div>
        <div>
            <span class='preco'>R$ $preco_venda</span>
        </div>
        <div>
            <button><a href='adicionar-ao-carrinho.php?idproduto=$id_produto'>Adicionar ao carrinho</a></button>
        </div>
        <div class='info'>
            <div class='info-item'>
                <div class='title'>
                    <span>$tamanho</span>
                </div>
                <div class='subtext'>
                    <small>TAMANHO</small>
                </div>
            </div>
            <div class='info-item'>
                    <div class='title'>
                        <span>$quantidade</span>
                    </div>
                    <div class='subtext'>
                        <small>RESTANTE</small>
                    </div>
            </div>
        </div>
    </div>";
}

echo "</div>";

pg_close($con);


if (isset($_GET['msg'])){
    $msg = $_GET['msg'];
    echo "
    <div class='aviso sucesso'>
        <p>$msg</p>
    </div>
 ";
} else if (isset($_GET['msg2'])){
    $msg = $_GET['msg2'];
    echo "
    <div class='aviso sucesso'>
        <p>$msg</p>
    </div>
 ";
}

include "footer.php";
?>


<script>
    /*
     * Script para adicionar a classe menu-ativo na li produtos do menu
     */
    let item = document.getElementById('menu-produtos');
    item.classList.add('menu-ativo');
</script>