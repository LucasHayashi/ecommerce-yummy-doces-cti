<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
$host = "banco72c.postgresql.dbaas.com.br";
$port = 5432;
$dbname = "banco72c";
$user = "banco72c";
$password = "#";

$con = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$con){
    echo "Erro ao iniciar a conexao, verificar!";
}
?>