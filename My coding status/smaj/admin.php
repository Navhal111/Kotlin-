<?php
$app->POST('/adminlogin', function($request,$response) use ($app){
      $user_data = $request->getParsedBody();
      $conn = sqlConnection();
      // print_r($user_data);
      $name = '1';
      try{
            $resule=$conn->prepare("SELECT id from admin_mn where email =? AND password =? ");
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
               $res_update=$conn->prepare("UPDATE admin_mn set token = ?,token_exp = ? where id= ?");
               if(!$res_update){
                 return "error";
               }
               $res_update->bind_param('sss',$token['token'],$token['exptime'],$id);
               $res_update->execute();

                     $resule->close();
                     $res_update->close();
                     mysqli_close($conn);
                     $msg =array('success'=>'1','token'=>$token['token'],'id'=>$id);
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
$app->post('/user', function($request,$response){

      $user_data = $request->getParsedBody();
      $conn = sqlConnection();
      $name = '1';
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

    try{
      if($result = $conn->query("SELECT id,first_name,last_name,email,phone_num,country,created_at,user_block from user ")) {
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
$app->post('/userremove', function($request,$response,$arg){
          $user_data =$request->getParsedBody();
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
                     return json_encode(array('success'=> 0 ,'msg'=>'unauthorise access'));
                   }

                 }
          }
          catch(Exception $e){
                return $e;
           }

          if($result = $conn->query("SELECT user_block from user where id = ".$user_data['user_id'])) {
              while($row = $result->fetch_assoc()) {
                    $myArray[] = $row;
              }
           }
      if($myArray[0]['user_block']==0){

        try{
              $result=$conn->prepare("UPDATE user set user_block = 1 where id= ?");
              // $resule->bind_param('s', $name);
              $result->bind_param('s', $user_data['user_id']);
              $result->execute();
              $result->store_result();
         }catch(Exception $e){
           $result->close();
           mysqli_close($conn);
           $msg =array('success'=>0,'msg'=>$e);
           return $response->withJson($msg);
         }
         $result->close();
         mysqli_close($conn);
         $msg = array('success' => 1,'msg'=>'user will enable');
         return $response->withJson($msg);

      }else{
      try{
            $result=$conn->prepare("UPDATE user set user_block = 0 where id= ?");
            $result->bind_param('s', $user_data['user_id']);
            $result->execute();
            $result->store_result();
       }catch(Exception $e){
         $result->close();
         mysqli_close($conn);
         $msg =array('success'=>0,'msg'=>$e);
         return $response->withJson($msg);
       }
       $result->close();
       mysqli_close($conn);
      $msg = array('success' => 1,'msg'=>'user will disable');
      return $response->withJson($msg);
    }//return $response->withStatus(200);

});

$app->post('/subadmin', function($request,$response,$arg){
      $admin_data = $request->getParsedBody();
      $cheak = cheak_token($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);

      }
       $conn =sqlConnection();
        return $response->withJson($admin_data);
        if($flg!=1){
          $msg=array('msg'=>'error');
          return $request->withJson($msg);
        }
        $password = md5($admin_data["password"]."key123");
        try{
            if($conn->query("INSERT INTO sub_admin(first_name,last_name,email,password,phone_num,gender,country,state,city,distric,sub_distric) values ('".$admin_data["first_name"]."','".$admin_data["last_name"]."','".$admin_data["email"]."','".$password."',".$admin_data["phone_num"].",".$admin_data["country"].",".$admin_data["state"].",".$admin_data['city'].",".$admin_data["distric"].",".$admin_data["sub_distric"].")")){

                    $sub_admin_id = $conn->insert_id;
                        if($conn->query("INSERT INTO module_permition(sub_admin_id,role,matrimony,register_user,blood_doner,noklari,nokari_user,seva_orgnization,bissness_user,bissness,servay_ans,pages,addvtisement,corporater,servay_qus) values (".$admin_data["sub_admin_id"].",1,".$admin_data["matrimony"].",".$admin_data["register_user"].",".$admin_data["blood_doner"].",".$admin_data["noklari"].",".$admin_data["nokari_user"].",".$admin_data["seva_orgnization"].",".$admin_data["bissness_user"].",".$admin_data["bissness"].",".$admin_data["sub_distric"].",".$admin_data["servay_ans"].",".$admin_data["pages"].",".$admin_data["addvtisement"].",".$admin_data["corporater"].",".$admin_data["servay_qus"].")")){

                                if($conn->query("INSERT INTO aria_permition(sub_admin_id,permition_type) values (".$admin_data["sub_admin_id"].",'".$admin_data["permition_type"]."')")){
                                         $area_id = $conn->insert_id;
                                       if($conn->query("INSERT INTO sub_admin(aria_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_id.",".$admin_data["sub_admin_id"].",".$admin_data["country"].",".$admin_data["state"].",".$admin_data["city"].",".$admin_data["distric"].",".$admin_data["sub_distric"].")")){
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
        mysqli_close($conn);
        $msg = array('success' => 0,'msg'=>'faild to add');
        return $response->withJson($msg);

});

?>
