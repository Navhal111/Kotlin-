<?php

function getConnection(){
    $username = 'root';
    $password = 'root';
    $host = 'localhost';
    $db = 'basepro';
    $connection = new PDO("mysql:dbname=$db;host=$host", $username, $password);
    return $connection;
}

function sqlConnection(){
    $username = 'root';
    $password = 'root';
    $host = 'localhost';
    $db = 'basepro';
    $con = mysqli_connect("localhost",$username,$password,$db) or die('die conn');
    return $con;
}

?>
