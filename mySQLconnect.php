<?php
$user = 'root';
$password ='';
$db = 'planetarium';
$host = 'localhost';

$dsn = 'mysql:host='.$host.';dbname='.$db;
//$dsn_Option = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]; //вывод ошибок с бд
$pdo = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
//$pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
?>
