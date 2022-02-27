CREATE SEQUENCE seq_usuario;
  
CREATE TABLE cadastros
  (
     idusuario    INT NOT NULL,
     usuario      VARCHAR (25) NOT NULL,
     senha        VARCHAR (32) NOT NULL,
     nome		  VARCHAR (25) NOT NULL,
     sobrenome    VARCHAR(50),
	 cpf          VARCHAR(14) NOT NULL,
     celular      VARCHAR (15),
     logradouro   VARCHAR(50),
     bairro       VARCHAR(20),
     cidade       VARCHAR(20),
     estado       VARCHAR(2),
     cep          VARCHAR(9),
     adminuser    VARCHAR(3),
     excluido     VARCHAR(3),
     dt_exclusao  DATE,
     CONSTRAINT pk_idusuario PRIMARY KEY (idusuario),
     CONSTRAINT uk_usuario UNIQUE (usuario, cpf)
  );
  
  
  -----------------------------------------------------------


  
  CREATE TABLE tamanho(
		idtamanho INT NOT NULL,
		nome VARCHAR(15),
	  	CONSTRAINT pd_idtamanho PRIMARY KEY (idtamanho)
  );
	
	INSERT INTO tamanho (idtamanho, nome) VALUES ('1','Médio');
	INSERT INTO tamanho (idtamanho, nome) VALUES ('2','Grande');
	INSERT INTO tamanho (idtamanho, nome) VALUES ('3','Pequeno');


 -----------------------------------------------------------

CREATE SEQUENCE seq_produto START 1000; 
 
CREATE TABLE produto
  (
     idproduto   INT NOT NULL,
     nome        VARCHAR(50) NOT NULL,
     preco       DECIMAL(7,2) NOT NULL,
     quantidade  INT NOT NULL,
	 desconto    INT,
	 descpreco   DECIMAL(7,2),
     disponivel  VARCHAR(3),
     dt_cadastro DATE,
	 idtamanho   INT NOT NULL,
	 CONSTRAINT fk_idtamanho FOREIGN KEY (idtamanho) REFERENCES tamanho(idtamanho),
     CONSTRAINT pk_idproduto PRIMARY KEY (idproduto),
	 CONSTRAINT uk_nome	UNIQUE (nome)
  );
  
  
    -----------------------------------------------------------


--INSERÇÃO DE DADOS SQL - USUARIO



INSERT INTO cadastros (
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
						
						
						
INSERT INTO CADASTROS (IDUSUARIO,
						USUARIO,
						SENHA,
						NOME,
						SOBRENOME,
						CPF,
						CELULAR,
						CEP,
						LOGRADOURO,
						BAIRRO,
						CIDADE,
						ESTADO,
						ADMINUSER,
						EXCLUIDO)
VALUES (NEXTVAL('seq_usuario'), 'usuario', 'f8032d5cae3de20fcec887f395ec9a6a', 'usuario', 'teste', 45439866874, '14988003272', 17032520, 'Rua Vicente Pellegrini Savastano', 'Jardim Dona Lili', 'Bauru', 'SP', 'Não','Não')

--INSERÇÃO DE DADOS SQL - ADMIN
INSERT INTO CADASTROS (IDUSUARIO,
						USUARIO,
						SENHA,
						NOME,
						SOBRENOME,
						CPF,
						CELULAR,
						CEP,
						LOGRADOURO,
						BAIRRO,
						CIDADE,
						ESTADO,
						ADMINUSER,
						EXCLUIDO)
VALUES (NEXTVAL('seq_usuario'), 'admin', '21232f297a57a5a743894a0e4a801fc3', 'usuario', 'teste', 45439866873, '14988003272', 17032520, 'Rua Vicente Pellegrini Savastano', 'Jardim Dona Lili', 'Bauru', 'SP', 'Sim','Não')