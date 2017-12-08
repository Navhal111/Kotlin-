<?php
session_start();
$_SESSION['userlogin']='success';

if(isset($_POST['user_id']) AND isset($_POST['token'])){
  $_SESSION['user_id']=$_POST['user_id'];
  $_SESSION['token']=$_POST['token'];
  $_SESSION['email']=$_POST['email'];
}
?>
