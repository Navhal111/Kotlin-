<?php
$app->get('/sta_json', function($request,$response,$dat){
  $username = 'root';
  $password = 'root';
  $host = 'localhost';
  $db = 'aria';
  $conn = mysqli_connect($host,$username,$password,$db) or die('not conection');

  // if($select=$conn->query("SELECT * FROM states")){
  //      while($row = $select->fetch_assoc()){
  //
  //         if($fatchstate = $conn->query('SELECT * from main_state where state ="'.$row['name'].'" AND country_id ='.$row['country_id'])){
  //             while($state = $fatchstate->fetch_assoc()){
  //               // return $response->withJson(array('aeeay'=>'update main_state SET main_id='.$row['id'].' where id= '.$state['id']));
  //                 $conn->query('update main_state SET main_id='.$row['id'].' where id= '.$state['id'].' AND country_id ='.$row['country_id']);
  //             }
  //         }
  //      }
  //
  // }
  // $str = file_get_contents('state.txt');
  // $state=json_decode($str,true);
  // $str2 = file_get_contents('city.txt');
  // $city=json_decode($str2,true);
  // $str3 = file_get_contents('district.txt');
  // $distric=json_decode($str3,true);
  // $i=0;
  // while(isset($state[$i]['state'])){
  //   $conn->query('INSERT INTO main_state(country_id,state)values(101,"'.$state[$i]['state'].'")');
  //   $state_id=$conn->insert_id;
  //   $j=0;
  //   while(isset($city[$j]['state_id'])){
  //
  //     if($city[$j]['state_id']==$state[$i]['id']){
  //
  //         $conn->query('INSERT INTO main_city(state_id,state)values('.$state_id.',"'.$city[$j]['districtname'].'")');
  //         $city_id=$conn->insert_id;
  //         $k=0;
  //         while(isset($distric[$k]['city_id'])){
  //             if($distric[$k]['city_id']==$city[$j]['id']){
  //
  //                 $conn->query('INSERT INTO main_city_area(city_id,aria)values('.$city_id.',"'.$distric[$k]['districtname'].'")');
  //             }
  //           $k++;
  //         }
  //
  //     }
  //    $j++;
  //   }
  //
  //   $i++;
  //
  // }
    // mysqli_close($conn);
    // return $response->withJson(array('aeeay'=>$destri));

//   $set=2;
//   if($resule=$conn->query("SELECT id,name from countries")){
//        $i=0;
//        while($row = $resule->fetch_assoc()){
//           $a1[$i]['id'] = $row['id'];
//           $a1[$i]['text'] = $row['name'];
//           $conn->query('INSERT INTO m_area(parent_id,name) values (1,"'.$a1[$i]['text'].'")');
//           $country[$i]=array('id'=>$set,'text'=>$a1[$i]['text']);
//           $set1=$set; $set++;
//          if($resule1=$conn->query("SELECT id,state,country_id,main_id from main_state where country_id = ".$a1[$i]['id'] )){
//            $j=0;
//             while($row1 = $resule1->fetch_assoc()){
//                 $a2[$j]['id'] = $row1['id'];
//                 $a2[$j]['text']=$row1['state'];
//                 $country_id=$row1['country_id'];
//                 $main_id=$row1['main_id'];
//                 $conn->query('INSERT INTO m_area(parent_id,name) values ('.$set1.',"'.$a2[$j]['text'].'")');
//                 $country[$i]['state'][$j]=array('id'=>$set,'text'=>$a2[$j]['text'],'country_id'=>$set1);
//                 $set2=$set; $set++;
//                 $city="cities";
//                 $name="name";
//                 if($country_id == 101){
//                    $city="main_city";
//                    $name="state";
//                    $main_id=$row1['id'];
//                 }
//
//                 if($resule2=$conn->query("SELECT id,".$name." from ".$city." where state_id = ".$main_id)){
//                       $n=0;
//                       if($resule2->num_rows>=1){
//                       while($row2=$resule2->fetch_assoc()){
//                         $a3[$n]['id'] = $row2['id'];
//                         $a3[$n]['text']=$row2[$name];
//                         // return $response->withJson(array('aeeay'=>"SELECT id,".$name." from ".$city." where state_id = ".$main_id,'adsff'=>'INSERT INTO m_area(parent_id,name) values ('.$set2.',"'.$a3[$n]['text'].'")'));
//                         $conn->query('INSERT INTO m_area(parent_id,name) values ('.$set2.',"'.$a3[$n]['text'].'")');
//                         $country[$i]['state'][$j]['city'][$n]=array('id'=>$set,'text'=>$a3[$n]['text'],'state_id'=>$set2);
//                         $set3=$set; $set++;
//
//                         if($country_id == 101){
//                         if($resule3=$conn->query("SELECT id,aria from main_city_area where city_id = ".$a3[$n]['id'])){
//                               $k=0;
//                               // $resule->num_rows >= 1
//                               if($resule3->num_rows >= 1){
//                               while($row3=$resule3->fetch_assoc()){
//                                 $a4[$k]['id'] = $row3['id'];
//                                 $a4[$k]['text']=$row3['aria'];
//                                 if($conn->query('INSERT INTO m_area(parent_id,name) values ('.$set3.',"'.$a4[$k]['text'].'")')){
//                                   $country[$i]['state'][$j]['city'][$n]['distric'][$k]=array('id'=>$set,'text'=>$a4[$n]['text'],'city_id'=>$set3);
//                                   $set++;$k++;
//                                 }
//
//                               }
//                          }
//                         }
//                       }
//                         $n++;
//                         // $resule->num_rows >= 1
//                       }
//                     }
//                 }
//                 $j++;
//          }
//         $i++;
//        }
//
//      }
// }
  mysqli_close($conn);
  return $response->withJson(array('aeeay'=>$country));

});
session_start();
require_once 'vendor/autoload.php';
require_once 'confng.php';
use Slim\LogFileWriter;
use Slim\Middleware\SessionCookie;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Tracy\Debugger;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

$app = new \Slim\App;
$app->add(new Silalahi\Slim\Logger());
$app->add(new \Slim\Middleware\Session());

// $container = $app->getContainer();
function login() {

            $arrRtn= bin2hex(openssl_random_pseudo_bytes(16)."KEY12345");
            $tokenExpiration = date('Y-m-d H:i:s', strtotime('+1 hour'));
              $token=array('token'=>$arrRtn,'exptime'=>$tokenExpiration);
              return $token;
  }


$app->get('/', function($request, $response, $args){

//  $token=login("admin","admin");

$mail = new PHPMailer(true);
$mail->IsSMTP();
$mail->isHTML(true);
$mail->SMTPDebug  = 0;
$mail->SMTPAuth   = true;
$mail->SMTPSecure = "tls";
$mail->Host       = "smtp.gmail.com";
$mail->Port       = 587;
$mail->AddAddress("ylight528@gmail.com");
$mail->Username   ="milanabdul0@gmail.com";
$mail->Password   ="abmi1234";
$mail->SetFrom('ylight528@gmail.com','App Message');
$mail->AddReplyTo("ylight528@gmail.com","App Massage");
$mail->Subject    = "new massage";
$mail->Body    = "hello";
$mail->AltBody    = "hey milan";

if($seo=$mail->Send()){
 $s="send";
}

 $token = login();
 $time = date('H:i');
 $msg = array('success' => 1,'time'=>$time);
  return $response->withJson($msg);
  //return $response->withJson($msg);
});


$app->post('/city', function(ServerRequestInterface $request, ResponseInterface $response){

  $user_data = $request->getParsedBody();
  $conn = sqlConnection();
  $resule=$conn->prepare("SELECT city from city where state_id= ? AND status = 1 ");
      if(!$resule){
        echo "error";

      }
  $resule->execute();
  $resule->bind_param('s', $user_data["state"]);
  // $resule->insert_id;
  $resule->bind_result($city_data);
       while($resule->fetch()){
         echo $city_data;
       }
  print_r($resule);
  $resule->close();
  mysqli_close($conn);

  try{
      if($conn->query("INSERT INTO sub_admin(first_name,last_name,email,password,phone_num,gender,country,state,city,distric,sub_distric) values ('".$first_name."','".$last_name."','".$email."','".$password."',".$phone_num.",".$country.",".$state.",".$city.",".$distric.",".$sub_distric.")")){

              $sub_admin_id = $conn->insert_id;
                  if($conn->query("INSERT INTO module_permition(sub_admin_id,role,matrimony,register_user,blood_doner,noklari,nokari_user,seva_orgnization,bissness_user,bissness,servay_ans,pages,addvtisement,corporater,servay_qus) values (".$sub_admin_id.",".$role.",".$admin_data["matrimony"].",".$admin_data["register_user"].",".$admin_data["blood_doner"].",".$admin_data["noklari"].",".$admin_data["nokari_user"].",".$admin_data["seva_orgnization"].",".$admin_data["bissness_user"].",".$admin_data["bissness"].",".$admin_data["sub_distric"].",".$admin_data["servay_ans"].",".$admin_data["pages"].",".$admin_data["addvtisement"].",".$admin_data["corporater"].",".$admin_data["servay_qus"].")")){

                          if($conn->query("INSERT INTO aria_permition(sub_admin_id,permition_type) values (".$sub_admin_id.",'".$admin_data["permition_type"]."')")){
                                   $area_id = $conn->insert_id;
                                  if($admin_data["permition_type"]=='GO'){
                                        if($conn->query("INSERT INTO sub_aria_permition(aria_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_id.",".$sub_admin_id.",0,0,0,0,0)")){
                                          mysqli_close($conn);
                                          $msg =array('success'=>1,'msg'=>'added sub admin');
                                          return $request->withJson($msg);

                                         }
                                   }
                                   if($admin_data["permition_type"]=='ST'){
                                       if($conn->query("INSERT INTO sub_aria_permition(aria_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_id.",".$sub_admin_id.",".$admin_data['country'].",".$admin_data['state'].",0,0,0)")){
                                         mysqli_close($conn);
                                         $msg =array('success'=>1,'msg'=>'added sub admin');
                                         return $request->withJson($msg);

                                        }

                                   }
                                   if($admin_data["permition_type"]=='CO'){
                                       if($conn->query("INSERT INTO sub_aria_permition(aria_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_id.",".$sub_admin_id.",".$admin_data['country'].",0,0,0,0)")){
                                         mysqli_close($conn);
                                         $msg =array('success'=>1,'msg'=>'added sub admin');
                                         return $request->withJson($msg);

                                        }

                                   }
                                   if($admin_data["permition_type"]=='CT'){
                                       if($conn->query("INSERT INTO sub_aria_permition(aria_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_id.",".$sub_admin_id.",".$admin_data['country'].",".$admin_data['state'].",".$admin_data['city'].",0,0)")){
                                         mysqli_close($conn);
                                         $msg =array('success'=>1,'msg'=>'added sub admin');
                                         return $request->withJson($msg);

                                        }

                                   }

                                   if($admin_data["permition_type"]=='DI'){
                                       if($conn->query("INSERT INTO sub_aria_permition(aria_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_id.",".$sub_admin_id.",".$admin_data['country'].",".$admin_data['state'].",".$admin_data['city'].",".$admin_data['distric'].",0)")){
                                         mysqli_close($conn);
                                         $msg =array('success'=>1,'msg'=>'added sub admin');
                                         return $request->withJson($msg);

                                        }

                                   }
                                 if($conn->query("INSERT INTO sub_aria_permition(aria_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_id.",".$sub_admin_id.",".$admin_data["country"].",".$admin_data["state"].",".$admin_data["city"].",".$admin_data["distric"].",".$admin_data["sub_distric"].")")){
                                   mysqli_close($conn);
                                   $msg =array('success'=>1,'msg'=>'added sub admin');
                                   return $request->withJson($msg);

                                  }

                          }
                   }
       }
  }catch(Exception $e){
     $msg = array('success'=>0,'msg'=>$e);
     return $request->withJson($msg);
  }
   //return $user_data["state"];
});
$app->get('/user_filter',function($request,$response,$args){

    $conn = sqlConnection();
    $a =[1,4];
    $sql = "SELECT email FROM user WHERE state IN (" . implode(',', array_map('intval', $a)) . ")";
    try{
      if ($result = $conn->query("SELECT id,email FROM user WHERE city IN (" . implode(',', array_map('intval', $a)) . ")")) {

          while($row = $result->fetch_assoc()) {
                $myArray[] = $row;
          }
        return $response->withJson(array('data'=>$myArray));
       }
    return $response->withJson(array('que'=>$sql,'mag'=>'not'));
    }catch(Exception $e){
          return $e;
      }
      $result->close();
      mysqli_close();
});

$app->POST('/userlogin', function($request,$response) use ($app){

      $user_data = $request->getParsedBody();
      $conn = sqlConnection();
      $password = md5($user_data["password"]."ritesh");
      $name = '1';
      try{
            $resule=$conn->prepare("SELECT email from user where email =? AND password =? ");
            if(!$resule){
              echo "error";
            }
            // $resule->bind_param('s', $name);
            $resule->bind_param('ss', $user_data["email"],$password);
            $resule->execute();
            $resule->store_result();
            if($resule->num_rows >= 1 ){
               $resule->close();
               mysqli_close($conn);
               $token = login();
               $msg = array('success' => 1);
               return $response->withJson($msg);
             }
            // $resule->bind_result($data);
            // $resule->fetch();
            // $resule->insert_id;
      }catch(Exception $e){
            $msg = array('success'=>0,'msg'=>$e);
            return $response->withJson($msg);
        }

       $resule->close();
       mysqli_close($conn);
       $msg = array('success' => 0);
       return $response->withJson($msg);
});
$app->POST('/adminlogin', function($request,$response) use ($app){
      $user_data = $request->getParsedBody();
      $conn = sqlConnection();
      // print_r($user_data);
      $name = '1';
      try{
            $resule=$conn->prepare("SELECT id from admin_mn where email =? AND password =? AND status = 1");
            if(!$resule){
              echo "error";
            }
            // $resule->bind_param('s', $name);
            $resule->bind_param('ss', $user_data["email"],$user_data["password"]);
            $resule->execute();

            $resule->store_result();
            if($resule->num_rows >= 1 ){
              $resule->bind_result($id);
              $resule->fetch();
               $token = login();
               $conn->query("INSERT INTO auth(user_id, token, exp_time) VALUES ('".$id."','".$token['token']."','".$token['exptime']."')");              $msg = array('success' => 1,'token'=>$token['token'],'id'=>$id);
               $resule->close();
               mysqli_close($conn);
               return $response->withJson($msg);
             }
      }catch(Exception $e){
            return $e;
        }

       $resule->close();
       mysqli_close($conn);
       $msg = array('success' => 0,'msg'=> 'unauthorise axcess');
       return $response->withJson($msg);

});

$app->post('api/user', function($request,$response){

      $user_data = $request->getParsedBody();
      $conn = sqlConnection();
      try{
        if($result = $conn->prepare("SELECT token_exp from admin_mn where id=? AND token = ?")) {
          $result->bind_param('ss', $user_data["id"],$user_data["token"]);
          $result->execute();
          $result->store_result();
          $now = date('Y-m-d H:i:s');
          $result->bind_result($token_exp);
          $result->fetch();
          if($result->num_rows == 1 && strtotime($now) < strtotime($token_exp)){
             $flg=1;
           }else{
             $result->close();
             mysqli_close($conn);
             return json_encode(array('success'=> 2 ,'msg'=>'unauthorise access'));
           }

         }
      }catch(Exception $e){
            return $e;
        }

    try{
      if($result = $conn->query("SELECT id,email from user where status =1")) {
        // return $response->withJson($result->fetch_array());

          while($row = $result->fetch_assoc()) {
                $myArray[] = $row;
          }
       }
          $data = array("status"=>1, "data"=>$myArray);
          return $response->withJson($myArray);
          // $resule->insert_id;
    }catch(Exception $e){
          return $e;
      }
      $msg = array("success" => 0);
      return $response->withJson($msg);
      //  $conn->close();
       $result->close();
       mysqli_close($conn);

});

$app->post('/userremove', function(ServerRequestInterface $request,ResponseInterface $response,$arg) use ($app){
  $id =$request-getbody();
  // $json=json_encode($response->withStatus(500)->withHeader('Content-Type', 'text/html')->write('Something went wrong!'));
  $conn = sqlConnection();
  $name = '1';

  try{
        $resule=$conn->prepare("UPDATE user set status = 0 where id= ?");
        if(!$resule){
          echo "error";
        }
        // $resule->bind_param('s', $name);
        $resule->bind_param('s', $id['id']);
        $resule->execute();
        $resule->store_result();
   }catch(Exception $e){

     $msg =array('success'=>0,'msg'=>$e);
     return $response->withJson($msg);
   }

  $msg = array('success' => 1,'msg'=>'user will disable');
  return $response->withJson($msg);
  //return $response->withStatus(200);
    //return 'It works!';
});

$app->post('/reg', function(ServerRequestInterface $request, ResponseInterface $response){

      $user_data = $request->getParsedBody();
      $conn = sqlConnection();
      $name = '1';
      $password = md5($user_data["password"]."ritesh");
      try{
            $resule=$conn->prepare("SELECT email from user where email =? AND status = 1");
            if(!$resule){
              echo "error";
            }
            $resule->bind_param('s', $user_data["email"]);
            $resule->execute();
            $resule->store_result();
            if($resule->num_rows >= 1 ){
              $resule->close();
              mysqli_close($conn);
              $msg = array('success' => 2);
              return $response->withJson($msg);
             }
             if(isset($user_data["email"]) && isset($password)){
                   if($conn->query("INSERT INTO user(email, password) VALUES ('".$user_data["email"]."','".$password."')")){
                     $msg = array('success' => 1);
                       $conn = NULL;
                       return $response->withJson($msg);
                   }
               }
      }catch(Exception $e){
            $msg=array("success"=>0,"msg"=>$e);
            return $response->withJson($msg);
        }
        $msg = array('success' => 0);
        $conn = NULL;
        return $response->withJson($msg);

});
$app->get('/logout', function($request,$response) {
      if(isset($_SESSION['admin'])){
          $my_value = $_SESSION['admin'];
          session_destroy();
          $msg = array('success' => 1,"id"=>$my_value);
            return $response->withJson($msg);
        }
      $msg = array('success' => 0,'id'=>$my_value);
      return $response->withJson($msg);
});

$app->post('/subadmin' function($request,$response,$arg){

  $admin_data = $request->getParsedBody();
  $conn =sqlConnection();
  try{
    if($result = $conn->prepare("SELECT token_exp from admin_mn where id=? AND token = ?")) {
      $result->bind_param('ss', $user_data["id"],$user_data["token"]);
      $result->execute();
      $result->store_result();
      $now = date('Y-m-d H:i:s');
      $result->bind_result($token_exp);
      $result->fetch();
      if($result->num_rows == 1 && strtotime($now) < strtotime($token_exp)){
         $flg=1;
       }else{
         $result->close();
         mysqli_close($conn);
         return json_encode(array('success'=> 0 ,'msg'=>'unauthorise access'));
       }

     }
  }catch(Exception $e){
        return $e;
    }
    $password = md5($admin_data["password"]."key123");
    try{
        if($conn->query("INSERT INTO sub_admin(first_name,last_name,email,password,state,city,distric,sub_distric) values ('".$admin_data["first_name"]."','".$admin_data["last_name"]."','".$admin_data["email"]."','".$admin_data["password"]."','".$admin_data["state"]."','".$admin_data["city"]."','".$admin_data["distric"]."','".$admin_data["sub_distric"]."')")){
        $msg =array('success'=>1);
        return $request->withJson($msg);
         }
    }catch(Exception $e){
       $msg = array('success'=>0,'msg'=>$e);
       return $request->withJson($msg);
    }
    $result->close();
    mysqli_close($conn);
    $msg = array('success' => 1,'msg'=>'user will disable');
    return $response->withJson($msg);

});

$app->run();
?>
