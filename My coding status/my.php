<?php
$username = 'root';
$password = 'root';
$host = 'localhost';
$db = 'user';
$con = mysqli_connect("localhost",$username,$password,$db) or die('die conn');
$name='1';
$resule=$con->prepare("CREATE TABLE profile_met(id int NOT NULL AUTO_INCREMENT,profile_name,)");
if(!$resule){
  echo "error";
}
$resule->bind_param('s', $name);
$resule->execute();
mysqli_close($con);
?>
