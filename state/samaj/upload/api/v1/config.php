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
              $tokenExpiration = date('Y-m-d H:i:s', strtotime('+3 hour'));
              $token=array('token'=>$arrRtn,'exptime'=>$tokenExpiration);
              return $token;
  }

function login_user() {

                $arrRtn= bin2hex(openssl_random_pseudo_bytes(8)."KEY12345");
                $tokenExpiration = date('Y-m-d H:i:s', strtotime('+8 hour'));
                $token=array('token'=>$arrRtn,'exptime'=>$tokenExpiration);
                return $token;
  }


function cheak_token($id,$token){

      $server_path ="http://192.168.0.3:8080/api/v1/";
      $conn = sqlConnection();
        try{
          if($result = $conn->prepare("SELECT token_exp from sub_admin where id=? AND token = ? AND status =1 AND user_block = 1")) {
            $result->bind_param('ss', $id,$token);
            $result->execute();
            $result->store_result();
            $now = date('Y-m-d H:i:s');
            $result->bind_result($token_exp);
            $result->fetch();
            if($result->num_rows == 1 && strtotime($now) < strtotime($token_exp)){
               mysqli_close($conn);
               return array('success'=> 1);
             }else{

               mysqli_close($conn);
               return array('success'=> 0);
             }

           }
        }catch(Exception $e){
              return $e;
          }

  }

  function area_check($id,$token){

    $conn=sqlConnection();
    try{
      if($arearesult = $conn->query("SELECT * FROM aria_permition where sub_admin_id=".$id)){
             $row=$arearesult->fetch_assoc();
             switch ($row['permition_type']) {
                    case 'GO':
                          return array('per'=>'GO','data'=>'null');
                     break;

                     case 'CO':
                     if($result = $conn->query("SELECT country from sub_aria_permition where sub_admin_id = ".$id)) {
                            $m=0;
                            while($row = $result->fetch_assoc()) {
                              $area[$m]=$row['country'];
                              $m++;
                            }
                          return array('per'=>'CO','data'=>$area);
                      }
                    break;

                    case 'ST':
                    if($result = $conn->query("SELECT state from sub_aria_permition where sub_admin_id = ".$id)) {
                           $m=0;
                           while($row = $result->fetch_assoc()) {
                             $area[$m]=$row['state'];
                             $m++;
                           }
                          return array('per'=>'ST','data'=>$area);
                     }
                     break;

                   case 'CT':
                   if($result = $conn->query("SELECT distric from sub_aria_permition where sub_admin_id = ".$id)) {
                          $m=0;
                          while($row = $result->fetch_assoc()) {
                            $area[$m]=$row['distric'];
                            $m++;
                          }
                          return array('per'=>'CT','data'=>$area);
                    }
                    break;
                    case 'DI':
                    if($result = $conn->query("SELECT city from sub_aria_permition where sub_admin_id = ".$id)) {
                           $m=0;
                           while($row = $result->fetch_assoc()) {
                             $area[$m]=$row['city'];
                             $m++;
                           }
                          return array('per'=>'DI','data'=>$area);
                     }
                    break;
                  }
        }

    }catch(Exception $e){
        return array('success'=>0,'data'=>$e);
      }

  }
  function cheak_token_user($id,$token){
    $server_path ="http://192.168.0.3:8080/api/v1/";
        $conn = sqlConnection();
          try{
            if($result = $conn->prepare("SELECT token_exp from user where id=? AND token = ? AND status =1 AND user_block = 1")) {
              $result->bind_param('ss', $id,$token);
              $result->execute();
              $result->store_result();
              $now = date('Y-m-d H:i:s');
              $result->bind_result($token_exp);
              $result->fetch();
              if($result->num_rows == 1 && strtotime($now) < strtotime($token_exp)){
                 $result->close();
                 mysqli_close($conn);
                 return array('success'=> 1,'path'=>$server_path);
               }else{
                 $result->close();
                 mysqli_close($conn);
                 return json_encode(array('success'=> 0));
               }

             }
          }catch(Exception $e){
                  return array('success'=>0,'data'=>$e);
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
