<?php
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

function cheak_token($id,$token){
      $conn = sqlConnection();
        try{
          if($result = $conn->prepare("SELECT token_exp from admin_mn where id=? AND token = ?")) {
            $result->bind_param('ss', $id,$token);
            $result->execute();
            $result->store_result();
            $now = date('Y-m-d H:i:s');
            $result->bind_result($token_exp);
            $result->fetch();
            if($result->num_rows == 1 && strtotime($now) < strtotime($token_exp)){
               $result->close();
               mysqli_close($conn);
               return array('success'=> 1);
             }else{
               $result->close();
               mysqli_close($conn);
               return json_encode(array('success'=> 0));
             }

           }
        }catch(Exception $e){
              return $e;
          }

  }

function createTree(&$list, $parent)
{
      $tree = array();
      foreach ($parent as $k=>$l) {
          if (isset($list[$l['id']])) {
              $l['items'] = createTree($list, $list[$l['id']]);
          }
          $tree[] = $l;
      }
      return $tree;
}
?>
