<?php
$servername = "192.168.0.3";
$username = "root";
$password = "temp123";
$dbname = "samaj_master";
$port = 3303;

$checkConnection =mysqli_connect($servername, $username, $password,$dbname,$port);

// Check connection
if ($checkConnection->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}
mysqli_close($checkConnection);

function getConnection(){
    $username = 'root';
    $password = 'temp123';
    $host = '192.168.0.3';
    $db = 'samaj_master';
    $port = 3303;
    $connection = new PDO("mysql:dbname=$db;host=$host", $username, $password);
    return $connection;
}

function sqlConnection(){
    $username = 'root';
    $password = 'temp123';
    $host = '192.168.0.3';
    $db = 'samaj_master';
    $port = 3303;
    $con = mysqli_connect($host,$username,$password,$db,$port) or die('not conection');
    return $con;
}

function login() {

              $arrRtn= bin2hex(openssl_random_pseudo_bytes(8)."KEY12345");
              $tokenExpiration = date('Y-m-d H:i:s', strtotime('+1 hour'));
              $token=array('token'=>$arrRtn,'exptime'=>$tokenExpiration);
              return $token;
  }
?>
