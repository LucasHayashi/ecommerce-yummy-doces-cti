<?php
session_start();
$title = "Estatísticas";
include_once "header.php";
include_once "conexao.php";
?>

<div id="estatisticas-container">
    <h2>Relatório mensal de vendas / Beta</h2>
    <form action="" id="form-data-relatorio" method="POST">
        <label for="data-relatorio">Escolha o mês e o ano:</label><br>
        <input type="month" name="data-relatorio" id="data-relatorio" required>
        <input type="submit" value="gerar relatório" name="submit">
    </form>
</div>

<!--Div that will hold the pie chart-->
    <!-- <div id="donutchart" style="width: 900px; height: 500px;"></div> 
          <div id='donutchart' style='width: 900px; height: 500px;'></div>
    -->

<?php

$meses =  array(1  => "janeiro", 
                2  => "fevereiro", 
                3  => "março", 
                4  => "abril", 
                5  => "maio", 
                6  => "junho", 
                7  => "julho",
                8  => "agosto", 
                9  => "setembro", 
                10 => "outubro", 
                11 => "novembro", 
                12 => "dezembro");

if (isset($_POST['submit'])){
  $data = $_POST['data-relatorio'];
  $ano  = explode('-',$data)[0]; 
  $mes  = explode('-',$data)[1];
  $mesIndex = ltrim($mes,0);

  $sql = "SELECT P.idproduto \"idproduto\", P.NOME \"nome\",
              P.SKU \"sku\",
              SUM(C.QTDCOMPRA) \"qtdtotal\",
              P.PRECO_VENDA \"preco_venda\",
          (SUM(C.QTDCOMPRA) * P.PRECO_VENDA) \"total\"
          FROM E2.CARRINHO C
          INNER JOIN E2.PRODUTO P ON P.IDPRODUTO = C.IDPRODUTO
          INNER JOIN E2.VENDAS V ON V.IDVENDA = C.IDVENDA
          WHERE EXTRACT(MONTH FROM v.dataconfirmacao) = $mes
          AND EXTRACT(YEAR FROM v.dataconfirmacao) = $ano
          GROUP BY P.idproduto
          ORDER BY p.nome ASC";

  $rs = pg_query($con, $sql);

  $linhas = pg_affected_rows($rs);

  echo "<div class='relatorio-container'>";

  if ($linhas > 0){
    echo "<table>
            <tr>
              <th>Produto</th>
              <th>Código</th>
              <th>Qtd. de vendas</th>
              <th>Preço unitário</th>
              <th>Total de vendas</th>
            </tr>";
            
      $chartData = array(array('Produto','Vendas'));

    while($data = pg_fetch_array($rs)){
      $nome        = $data['nome'];
      $sku         = $data['sku'];
      $qtdtotal    = $data['qtdtotal'];
      $preco_venda = str_replace('.',',',$data['preco_venda']);
      $total       = str_replace('.',',',$data['total']);

      echo "<td>$nome</td>
            <td>$sku</td>
            <td>$qtdtotal</td>
            <td>R$ $preco_venda</td>
            <td>R$ $total</td>
            </tr>"; 

      $info = array($nome, intval($qtdtotal));

      array_push($chartData, $info);
    }

    echo "</table>";

    $chartDataInJson = json_encode($chartData);

    echo "<div id='donutchart'></div>";
  }else {
      echo "<div>
                <span>Não houve nenhuma venda em $meses[$mesIndex] de $ano.</span>
            </div>";
  }
  
  echo "</div>";
}

?>

<?php
include_once "footer.php";
?>

   <!--Load the AJAX API-->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script  type="text/javascript">
    let item = document.getElementById('menu-estatisticas');
    item.classList.add('menu-ativo');

    google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable(<?php echo $chartDataInJson; ?>);

        var options = {
          title: 'Estatísticas de vendas <?php echo $meses[$mesIndex];?> de <?php echo $ano?>',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
</script>