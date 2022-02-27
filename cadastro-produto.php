<?php
session_start();
$title = "Cadastro de produtos";
include_once "autenticacao.php";
include_once "header.php";
include_once "submenu.php";
?>
    <div class="sub-container">
        <!-- formulario -->
        <form action="" method="POST" class="formulario" enctype="multipart/form-data">
            <label for="nome">Nome:</label><br>
                <input type="text" name="nome" id="nome" placeholder="Digite o nome do produto" required><br>
				
            <label for="sku">Código do produto:</label><br>
                <input type="text" name="sku" id="sku" placeholder="Escolha um código para o produto" required><br>
				
            <label for="preco">Preço de custo:</label><br>
                <input type="text" name="preco_custo" id="preco_custo" placeholder="Digite o preco do produto" required><br>
				
            <label for="margem_lucro">Margem de lucro (%):</label><br>
                <input type="text" name="margem_lucro" id="margem_lucro" placeholder="Informe a margem de lucro em %" required><br>
                
			<label for="preco">ICMS:</label><br>
				<input type="text" name="icms" id="icms" placeholder="Informe o valor do ICMS do seu estado" required><br>
				
            <label for="quantidade">Quantidade em estoque:</label><br>
                <input type="number" name="quantidade" id="quantidade" placeholder="Digite a quantidade disponivel" required><br>

            <label for="tamanho">Tamanho:</label><br>
                <select name="tamanho" id="tamanho">
                    <option value="">--Selecione--</option>
                    <option value="1">Médio</option>
                    <option value="2">Grande</option>
                </select><br>
				
            <label for="foto">Escolha uma imagem para o produto: </label>
                <input type="hidden" name="MAX_FILE_SIZE" value="4194304"/>
                <input type="file" name="foto" id="foto" required/><br/>
        <br>

    <input type="submit" value="Cadastrar" id="cadastrar" name="submit">
</form>
        
    </div>

        <?php
        include_once "conexao.php";
        if (isset($_POST['submit'])){

            $nome         = $_POST['nome'];
            $sku          = $_POST['sku'];
            $preco_custo  = floatval(trim(str_replace(',','.',$_POST['preco_custo'])));
            $icms         = $_POST['icms'];
            $margem_lucro = $_POST['margem_lucro'];
            $preco_venda  = number_format(($preco_custo * ( 1 + ($margem_lucro/100))) / (1 - $icms/100), 2);
            $quantidade   = $_POST['quantidade']; 
            $idtamanho    = $_POST['tamanho'];

            // variáveis para manipulação da foto
            $foto          = $_FILES['foto'];
            $filename      = $_FILES['foto']['name'];
            $fileTmp       = $_FILES['foto']['tmp_name'];
            $fileSize      = $_FILES['foto']['size'];
            $fileError     = $_FILES['foto']['error'];
            $fileExt       = explode('.',$filename);
            $fileExtActive = strtolower(end($fileExt));
            $nameAlt       = strval(time());
            $dir           = '../ecommerce/img/produtos/';
            //$dir           = 'img/produtos/';
            $uploadfile    = $nameAlt . '.' .$fileExtActive;
            $extAllowed    = array('jpg', 'png', 'jpeg', 'bpm', 'webp','svg');

            $phpUploadErros = array(
                0 => 'não houve erro, o upload foi bem sucedido.',
                1 => 'O arquivo enviado excede o limite definido na diretiva upload_max_filesize do php.ini.',
                2 => 'O arquivo excede o limite definido em MAX_FILE_SIZE no formulário HTML.',
                3 => 'O upload do arquivo foi feito parcialmente.',
                4 => 'Nenhum arquivo foi enviado.',
                6 => 'Pasta temporária ausênte.',
                7 => 'Falha em escrever o arquivo em disco.',
                8 => 'Uma extensão do PHP interrompeu o upload do arquivo.',
            );
            
            if ($fileError == 0){
                if (in_array($fileExtActive, $extAllowed)){
                    if (move_uploaded_file($fileTmp,$dir.$uploadfile)){
                        $sql = "INSERT INTO e2.produto (
                                    idproduto,
                                    nome,
                                    sku,
                                    preco_custo,
                                    icms, 
                                    margem_lucro,
                                    preco_venda,
                                    quantidade,
                                    dt_cadastro,
                                    idtamanho,
                                    disponivel,
                                    imgname
                                ) 
                                VALUES  (
                                    NEXTVAL('e2.seq_produto'),
                                    '$nome',
                                    '$sku',
                                    $preco_custo,
                                    $icms,
                                    $margem_lucro,
                                    $preco_venda,
                                    $quantidade,
                                    CURRENT_TIMESTAMP,
                                    $idtamanho,
                                    'Sim',
                                    '$uploadfile')";

                        $rs = pg_query($con, $sql);

                        if ($rs){
                            echo "
                            <div class='aviso sucesso'>
                                <p>O produto $nome foi cadastrado com sucesso, você poderá vê-lo na página produtos.</p>
                            </div>
                        ";
                        }else {
                            echo "
                            <div class='aviso erro'>
                                <p>Ops! Ocorreu um erro ao salvar este produto, verifique os dados e tente novamente.</p>
                            </div>
                         ";
                        }
            
                    }else {
                        echo "Falha ao fazer o upload da imagem!";
                    }
                }else {
                    echo "Envie imagens nos formatos: jpg, png, jpeg, webp ou svg!";
                }
            }else {
                echo $phpUploadErros[$fileError];
            }
        }
        
        pg_close($con);
        include_once "footer.php";
       ?>

    <script>
        let item = document.getElementById('submenu-cadastro');
        let erro = document.getElementById('erro-php');
        item.classList.add('submenu-ativo');
        erro.firstChild.remove();
    </script>  