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
echo "connection successfully";

?>
