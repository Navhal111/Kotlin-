<?php
$app->POST('/userlogin', function($request,$response){
      $user_data = $request->getParsedBody();
      $conn = sqlConnection();
      $password = md5($user_data["password"]."key123");
      $name = '1';
      try{
            $resule=$conn->prepare("SELECT id from user where email =? AND password =? AND status =1 AND user_block = 1");
            // $resule->bind_param('s', $name);
            $resule->bind_param('ss', $user_data["email"],$password);
            $resule->execute();
            $resule->store_result();
            if($resule->num_rows >= 1 ){
               $resule->bind_result($id);
               $resule->fetch();
               $token = login_user();
               $res_update=$conn->prepare("UPDATE user set token = ?,token_exp = ? where id= ?");
               if(!$res_update){
                 $msg = array('success'=>0,'msg'=>'error');
                 return $response->withJson(array('response'=>$msg));
               }
               $res_update->bind_param('sss',$token['token'],$token['exptime'],$id);
               $res_update->execute();
                     $resule->close();
                     $res_update->close();
                     mysqli_close($conn);
               $msg=array('success'=>1,'msg'=>'login','id'=>$id,'token'=>$token['token']);
               return $response->withJson(array('response'=>$msg));
             }
      }catch(Exception $e){
            $msg = array('success'=>0,'msg'=>$e);
            return $response->withJson(array('response'=>$msg));
        }
       mysqli_close($conn);
       $msg = array('success' => 0,'msg'=>'UserName and password not match');
       return $response->withJson(array('response'=>$msg));

});

$app->post('/user_view_app_side',function($request,$response,$args){

  $user_data = $request->getParsedBody();
  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
  $conn = sqlConnection();

      if($result = $conn->query("SELECT id,first_name,last_name,email,phone_num,birth_date,gender,country,state,city,distric,sub_distric,address,created_at,user_block,pincode from user  where status=1 AND id = ".$user_data['id'])) {
        $i=0;

        while($row = $result->fetch_assoc()) {
               $res=$conn->query("SELECT name from a_main where id = ".$row['country']);
               $country=$res->fetch_assoc();
               $res=$conn->query("SELECT name from a_main where id = ".$row['state']);
               $state=$res->fetch_assoc();
               $res=$conn->query("SELECT name from a_main where id = ".$row['city']);
               $city=$res->fetch_assoc();
               $res=$conn->query("SELECT name from a_main where id = ".$row['distric']);
               $distric=$res->fetch_assoc();
                $myArray[$i]['id'] = $row['id'];
                $myArray[$i]['first_name'] = $row['first_name'];
                $myArray[$i]['last_name'] = $row['last_name'];
                $myArray[$i]['email'] = $row['email'];
                $myArray[$i]['phone_num'] = $row['phone_num'];
                $myArray[$i]['country'] = $country['name'];
                $myArray[$i]['country_id'] = $row['country'];
                $myArray[$i]['state'] = $state['name'];
                $myArray[$i]['city'] = $city['name'];
                $myArray[$i]['distric'] = $distric['name'];
                $myArray[$i]['sub_distric'] = $row['sub_distric'];
                $myArray[$i]['address'] = $row['address'];
                $myArray[$i]['gender'] = $row['gender'];
                $myArray[$i]['birth_date'] = $row['birth_date'];
                $myArray[$i]['pincode'] = $row['pincode'];
                $myArray[$i]['created_at'] = $row['created_at'];
                $myArray[$i]['status'] = $row['user_block'];
           $i++;
        }
        mysqli_close($conn);
        $data = array("status"=>1, "data"=>$myArray);
        return $response->withJson($data);


      }
      mysqli_close($conn);
      $msg = array("success" => 0,'msg'=>'undefind the user');
      return $response->withJson($msg);


});
$app->post('/reg', function($request,$response){

      $user_data = $request->getParsedBody();
      $conn = sqlConnection();
      $name = '1';
      $password = md5($user_data["password"]."key123");
      // $file = fopen("suerveyanswer1.txt","w");
      // echo fwrite($file,json_encode($user_data));
      // fclose($file);
      // $msg = array("success" => 0,'msg'=>'somthing went wrong');
      // return $response->withJson($msg);
        try{
            if($resule=$conn->prepare("SELECT email from user where email =? AND status = 1")){
            $resule->bind_param('s', $user_data["email"]);
            $resule->execute();
            $resule->store_result();
            if($resule->num_rows >= 1 ){
              $resule->close();
              mysqli_close($conn);
              $msg = array('success'=>2,'msg'=>'User already exists');
              return $response->withJson(array('response'=>$msg));
             }
             $areaurl = str_replace(" ","%20","https://maps.googleapis.com/maps/api/geocode/json?address=".$user_data['address']."&key=AIzaSyDFtLiPr8vHgsFqeOuhuVw_XNeJE7WPT7Y");
             $json = file_get_contents($areaurl);
             $obj = json_decode($json,true);
             if($obj['status']=='ZERO_RESULTS'){

               $msg = array('success' => 0,'msg'=>'Pleass Cheack your Address...');
               mysqli_close($conn);
               return $response->withJson(array('response'=>$msg));
             }
             // $obj["results"][0]["geometry"]["location"]
            //  return $response->withJson(array("data"=>$obj,"response"=>"INSERT INTO user(first_name,last_name,email, password,phone_num,birth_date,gender,address,country,state,city,distric,sub_distric,pincode,otp_status,lat,lng) VALUES ('".$user_data["first_name"]."','".$user_data["last_name"]."','".$user_data["email"]."','".$password."',".$user_data["phone_num"].",'".$user_data["birth_date"]."','".$user_data["gender"]."','".$user_data['address']."',".$user_data["country"].",".$user_data["state"].",".$user_data["city"].",".$user_data["distric"].",'".$user_data["sub_distric"]."',".$user_data["pincode"].",0,".$obj["results"][0]["geometry"]["location"]['lat'].",".$obj["results"][0]["geometry"]["location"]["lng"].")"));

          if(isset($user_data["email"]) && isset($user_data["password"])){
if($conn->query("INSERT INTO user(first_name,last_name,email, password,phone_num,birth_date,gender,address,country,state,city,distric,sub_distric,pincode,otp_status,lat,lng) VALUES ('".$user_data["first_name"]."','".$user_data["last_name"]."','".$user_data["email"]."','".$password."',".$user_data["phone_num"].",'".$user_data["birth_date"]."','".$user_data["gender"]."','".$user_data['address']."',".$user_data["country"].",".$user_data["state"].",".$user_data["city"].",".$user_data["distric"].",'".$user_data["sub_distric"]."',".$user_data["pincode"].",0,".$obj["results"][0]["geometry"]["location"]['lat'].",".$obj["results"][0]["geometry"]["location"]["lng"].")")){

              $msg = array('success'=>1,'msg'=>'Registration has been completed');
              mysqli_close($conn);
              return $response->withJson(array('response'=>$msg));
            }
              }
          }
      }catch(Exception $e){
            $msg=array("success"=>0,"msg"=>$e);
            return $response->withJson($msg);
        }
        $msg = array('success' => 0,'msg'=>'wrong');
        mysqli_close($conn);
        return $response->withJson(array('response'=>$msg));

});

$app->post('/update_main_user', function($request,$response){

      $user_data = $request->getParsedBody();
      $cheak = cheak_token_user($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
      $conn = sqlConnection();
      $name = '1';
      $file = fopen("user_update.txt","w");
      echo fwrite($file,json_encode($user_data));
      fclose($file);
      // $msg = array("success" => 0,'msg'=>'somthing went wrong');
      // return $response->withJson($msg);
        try{
            if($resule=$conn->prepare("SELECT email from user where email =? AND status = 1 AND id!=".$user_data['id'])){
            $resule->bind_param('s', $user_data["email"]);
            $resule->execute();
            $resule->store_result();
            if($resule->num_rows >= 1 ){
              $resule->close();
              mysqli_close($conn);
              $msg = array('success'=>2,'msg'=>'Email Already Use');
              return $response->withJson(array('response'=>$msg));
             }
             $areaurl = str_replace(" ","%20","https://maps.googleapis.com/maps/api/geocode/json?address=".$user_data['address']."&key=AIzaSyDFtLiPr8vHgsFqeOuhuVw_XNeJE7WPT7Y");
             $json = file_get_contents($areaurl);
             $obj = json_decode($json,true);
             if($obj['status']=='ZERO_RESULTS'){
               $msg = array('success' => 0,'msg'=>'Pleass Cheack your Address...');
               mysqli_close($conn);
               return $response->withJson(array('response'=>$msg));
             }
             // $obj["results"][0]["geometry"]["location"]
            //  return $response->withJson(array("data"=>$obj,"response"=>"UPDATE user SET first_name = '".$user_data["first_name"]."', last_name = '".$user_data["last_name"]."',email='".$user_data["email"]."',phone_num=".$user_data["phone_num"].",birth_date='".$user_data["birth_date"]."',gender='".$user_data["gender"]."',address='".$user_data['address']."',country=".$user_data["country"].",state=".$user_data["state"].",city=".$user_data["city"].",distric=".$user_data["distric"].",sub_distric='".$user_data["sub_distric"]."',pincode=".$user_data["pincode"].",lat=".$obj["results"][0]["geometry"]["location"]['lat'].",lng=".$obj["results"][0]["geometry"]["location"]["lng"]." WHERE id = ".$user_data['id']));

          if(isset($user_data["email"])){
if($conn->query("UPDATE user SET first_name = '".$user_data["first_name"]."', last_name = '".$user_data["last_name"]."',email='".$user_data["email"]."',phone_num=".$user_data["phone_num"].",birth_date='".$user_data["birth_date"]."',gender='".$user_data["gender"]."',address='".$user_data['address']."',country=".$user_data["country"].",state=".$user_data["state"].",city=".$user_data["city"].",distric=".$user_data["distric"].",sub_distric='".$user_data["sub_distric"]."',pincode=".$user_data["pincode"].",lat=".$obj["results"][0]["geometry"]["location"]['lat'].",lng=".$obj["results"][0]["geometry"]["location"]["lng"]." WHERE id = ".$user_data['id'])){

              $msg = array('success'=>1,'msg'=>'Update has been completed');
              mysqli_close($conn);
              return $response->withJson(array('response'=>$msg));
            }

              }
          }
      }catch(Exception $e){
            $msg=array("success"=>0,"msg"=>$e);
            return $response->withJson($msg);
        }
        $msg = array('success' => 0,'msg'=>'Problam In Update Profile Check Your Data');
        mysqli_close($conn);
        return $response->withJson(array('response'=>$msg));

});

$app->post('/logout', function($request,$response) {

      $data = $request->getParsedBody();
      $cheak = cheak_token($data['id'],$data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
      $conn = sqlConnection();
      $res_update=$conn->prepare("UPDATE sub_admin set token = NULL,token_exp = NULL where id= ?");
      if(!$res_update){
        return $response->withJson(array('success'=>0,'msg'=>'error'));
      }
      $res_update->bind_param('s',$data['id']);
      $res_update->execute();
      mysql_close($conn);
      $msg = array('success' => 1);
      return $response->withJson($msg);
});

$app->post('/check_token_beginnig', function($request,$response) {

      $data = $request->getParsedBody();
      $cheak = cheak_token_user($data['id'],$data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson(array('response'=>$msg));
      }

      $msg = array("success" => 1,'msg'=>'otherise');
      return $response->withJson(array('response'=>$msg));
});
?>
