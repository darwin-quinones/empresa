<?php
$server = "mysql:dbname=empresa;host=127.0.0.1";
$user = "root";
$password = "";

// set connection
try {
    // set configuration for spanish characters
    $collation = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
    $conn = new PDO($server, $user, $password, $collation);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
} catch (PDOException $e) {
    echo "Something went wrong :( ". $e->getMessage();
}