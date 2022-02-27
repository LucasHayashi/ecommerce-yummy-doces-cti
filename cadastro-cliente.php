<?php
$title = "Cadastrar usuário";
include_once('conexao.php');
include_once('header.php')
?>

<div class="sub-container">
    <!-- formulario -->
    <form action="" method="POST" class="formulario form-cliente">
        <label>Usuario: <spam class="campo-obrigatorios" >*</spam> </label></br>
            <input type="text" name="usuario" required placeholder="Escolha um nome de usuário"></br>
        <label>Senha:  <spam class="campo-obrigatorios" >*</spam></label></br>
            <input type="password" name="senha" required></br>
        <label>Nome: <spam class="campo-obrigatorios" >*</spam></label></br>
            <input type="text" name="nome" required></br>
        <label>Sobrenome</label></br>
            <input type="text" name="sobrenome"></br>
        <label>CPF:  <spam class="campo-obrigatorios" >*</spam></label></br>
            <input type="text" name="cpf" pattern=".{11,14}" required placeholder="Digite apenas números" title="O CPF deve possuir no mínimo 11 dígitos ou no máximo 14 com: . e -"></br>
        <label>Cel.:</label></br>
            <input type="text" name="celular"></br>
        <label>CEP:</label></br>
            <input type="text" name="cep" id="cep" onblur="pesquisacep(this.value)" placeholder="Informe o seu CEP"></br>
        <label>Logradouro / Nº:</label></br>
            <input type="text" name="rua" id="rua"></br>
        <label>Bairro:</label></br>
            <input type="text" name="bairro" id="bairro"></br>
        <label>Cidade:</label></br>
            <input type="text" name="cidade" id="cidade"></br>
        <label>Estado:</label></br>
            <input type="text" name="estado" id="estado"></br>
            <input type="submit" value="Cadastrar" id="cadastrar" name="submit">
    </form>
</div>

<?php
        include_once "conexao.php";
        if (isset($_POST['submit'])){
            $usuario   = $_REQUEST['usuario'];
            $senha     = md5($_REQUEST['senha']);
            $nome      = $_REQUEST['nome'];
            $sobrenome = $_REQUEST['sobrenome'];
            $cpf       = $_REQUEST['cpf'];
            $celular   = $_REQUEST['celular'];
            $cep       = $_REQUEST['cep'];
            $rua       = $_REQUEST['rua'];
            $bairro    = $_REQUEST['bairro'];
            $cidade    = $_REQUEST['cidade'];
            $estado    = $_REQUEST['estado'];

            $sql = "INSERT INTO e2.cadastros (
                        idusuario,
                        usuario,
                        senha,
                        nome,
                        sobrenome, 
                        cpf,
                        celular, 
                        cep,
                        logradouro,
                        bairro,
                        cidade,
                        estado,
                        adminuser,
                        excluido
                    ) 
                    VALUES  (
                        NEXTVAL('e2.seq_usuario'),
                        '$usuario',
                        '$senha',
                        '$nome',
                        '$sobrenome',
                        $cpf,
                        '$celular',
                         $cep,
                        '$rua',
                        '$bairro',
                        '$cidade',
                        '$estado',
                        'Não',
                        'Não'
                    )";
            $rs = pg_query($con, $sql);
            if ($rs){
                echo "
                        <div class='aviso sucesso'>
                            <p>Cadastro realizado com sucesso!</p>
                            <p>A partir de agora você poderá realizar a compra de nossos produtos.</p>
                        </div>
                    ";
            }else {
                echo "
                        <div class='aviso erro'>
                            <p>Erro ao salvar seu registro, verifique os dados e tente novamente! Se precisar de ajuda, não hesite em nos procurar.</p>
                        </div>
                     ";
            }
        }
        pg_close($con);
        include_once "footer.php";
       ?>


<script>

function limpa_formulário_cep() {
        //Limpa valores do formulário de cep.
        document.getElementById('rua').value=("");
        document.getElementById('bairro').value=("");
        document.getElementById('cidade').value=("");
        document.getElementById('estado').value=("");  
}

function meu_callback(conteudo) {
    if (!("erro" in conteudo)) {
        //Atualiza os campos com os valores.
        document.getElementById('rua').value=(conteudo.logradouro);
        document.getElementById('bairro').value=(conteudo.bairro);
        document.getElementById('cidade').value=(conteudo.localidade);
        document.getElementById('estado').value=(conteudo.uf);
    } //end if.
    else {
        //CEP não Encontrado.
        limpa_formulário_cep();
        alert("CEP não encontrado.");
    }
}
    
function pesquisacep(valor) {

    //Nova variável "cep" somente com dígitos.
    var cep = valor.replace(/\D/g, '');

    //Verifica se campo cep possui valor informado.
    if (cep != "") {

        //Expressão regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;

        //Valida o formato do CEP.
        if(validacep.test(cep)) {

            //Preenche os campos com "..." enquanto consulta webservice.
            document.getElementById('rua').value="...";
            document.getElementById('bairro').value="...";
            document.getElementById('cidade').value="...";
            document.getElementById('estado').value="...";

            //Cria um elemento javascript.
            var script = document.createElement('script');

            //Sincroniza com o callback.
            script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

            //Insere script no documento e carrega o conteúdo.
            document.body.appendChild(script);

        } //end if.
        else {
            //cep é inválido.
            limpa_formulário_cep();
            alert("Formato de CEP inválido.");
        }
    } //end if.
    else {
        //cep sem valor, limpa formulário.
        limpa_formulário_cep();
    }
};
</script>