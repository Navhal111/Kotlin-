<?php
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
               return $response->withStatus(200)->withJson($msg);
             }
      }catch(Exception $e){
            $msg = array('success'=>0,'msg'=>$e);
            return $response->withJson($msg);
        }

       $resule->close();
       mysqli_close($conn);
       $msg = array('success' => 0);
       return $response->withJson($msg);

});
$app->post('/reg', function($request,$response){

      $user_data = $request->getParsedBody();
      $conn = sqlConnection();
      $name = '1';
      $password = md5($user_data["password"]."key123");
      try{
            $resule=$conn->prepare("SELECT email from user where email =? AND status = 1");
            if(!$resule){
              echo "error";
            }
            // $resule->bind_param('s', $name);
            $resule->bind_param('s', $user_data["email"]);
            $resule->execute();
            $resule->store_result();
            if($resule->num_rows >= 1 ){
              $resule->close();
              mysqli_close($conn);
              $msg = array('success' => 2);
              return $response->withJson($msg);
             }
             if(isset($user_data["email"]) && isset($user_data["password"])){
             $conn->query("INSERT INTO user(first_name,last_name,email, password,phone_num,birth_date,gender,country,state,city,distric,sub_distric,pincode,otp_status) VALUES ('".$user_data["first_name"]."','".$user_data["last_name"]."','".$user_data["email"]."','".$password."',".$user_data["phone_num"].",'".$user_data["birth_date"]."','".$user_data["gender"]."','".$user_data["country"]."','".$user_data["state"]."','".$user_data["city"]."','".$user_data["distric"]."','".$user_data["sub_distric"]."',".$user_data["pincode"].",0)");

                     $msg = array('success' => 1);
                     $conn = NULL;
                    return $response->withJson($msg);


              }
      }catch(Exception $e){
            $msg=array("success"=>0,"msg"=>$e);
            return $response->withJson($msg);
        }
        $msg = array('success' => 0,'msg'=>'outer');
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
      //$my_value = $_SESSION['admin'];
      $msg = array('success' => 0,'id'=>$my_value);
      return $response->withJson($msg);
});
$app->get('/sta_json', function($request,$response){
  $conn = sqlConnection();
  $set=1;
  if($resule=$conn->query("SELECT id,country_name from country where status = 1 ")){
       $i=0;
       while($row = $resule->fetch_assoc()){
          $a1[$i]['id'] = $row['id'];
          $a1[$i]['text'] = $row['country_name'];
          $conn->query("INSERT INTO main_area(parent_id,name) value (0,'".$a1[$i]['text']."')");
          $country[$i]=array('id'=>$set,'text'=>$a1[$i]['text']);
          $set1=$set; $set++;
         if($resule1=$conn->query("SELECT id,state from state where country_id = ".$a1[$i]['id'] )){
           $j=0;
            while($row1 = $resule1->fetch_assoc()){
                $a2[$j]['id'] = $row1['id'];
                $a2[$j]['text']=$row1['state'];
                $conn->query("INSERT INTO main_area(parent_id,name) value (".$set1.",'".$a2[$j]['text']."')");
                $country[$i]['state'][$j]=array('id'=>$set,'text'=>$a2[$j]['text'],'country_id'=>$set1);
                $set2=$set; $set++;
                if($resule2=$conn->query("SELECT id,city from city where state_id = ".$a2[$j]['id'])){
                      $n=0;
                      while($row2=$resule2->fetch_assoc()){
                        $a3[$n]['id'] = $row2['id'];
                        $a3[$n]['text']=$row2['city'];
                        $conn->query("INSERT INTO main_area(id,parent_id,name) value (".$set2.",'".$a3[$n]['text']."')");
                        $country[$i]['state'][$j]['city'][$n]=array('id'=>$set,'text'=>$a3[$n]['text'],'state_id'=>$set2);
                        $set++;$n++;
                      }

                }
                $j++;
         }
        $i++;
       }

     }
}
  $resule->close();
  mysqli_close($conn);
  return $response->withJson($country);

});
?>
