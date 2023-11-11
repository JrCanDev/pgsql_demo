<?php

$host = $_ENV['database__connection__host'];
$username = $_ENV['database__connection__user'];
$password = $_ENV['database__connection__password'];

$connect = "pgsql:host=".$host.";port=5432";
$pdo = new PDO($connect, $username, $password) or die('cannot instantiate PDO instance');
?>
