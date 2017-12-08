<?php
$app->POST('/adminlogin', function($request,$response) use ($app){
      $user_data = $request->getParsedBody();
      $conn = sqlConnection();
      $password = md5($user_data["password"]."key123");
      try{
            $resule=$conn->prepare("SELECT id,first_name,last_name from sub_admin where email =? AND password =? AND status = 1 AND user_block = 1 ");
            $resule->bind_param('ss', $user_data["email"],$password);
            $resule->execute();

            $resule->store_result();
            if($resule->num_rows >= 1 ){
               $resule->bind_result($id,$first_name,$last_name);

               $resule->fetch();
               $token = login();
              //  $datap=area_check($id,$token);
               $res_update=$conn->prepare("UPDATE sub_admin set token = ?,token_exp = ? where id= ?");
               if(!$res_update){
                 return $response->withJson(array('msg'=>'error'));
               }
               $res_update->bind_param('sss',$token['token'],$token['exptime'],$id);
               $res_update->execute();
               if($result = $conn->query("SELECT * from module_permition where sub_admin_id = ".$id)) {
                        while($row = $result->fetch_assoc()) {
                          $module_permition[] =$row;
                        }

               }

               if($result=$conn->query("SELECT * from aria_permition where status = 1 AND sub_admin_id= ".$id)){

                        while($row = $result->fetch_assoc()){
                              $admin_permition[]=$row;
                        }
                        if(!isset($admin_permition[0]['permition_type'])){
                          $msg = array("success" => 3,'mas'=>'not a spacific addvtisement');
                          return $response->withJson($msg);
                        }
                        switch ($admin_permition[0]['permition_type']) {
                          case 'GO':
                              $resule->close();
                              $res_update->close();
                              mysqli_close($conn);
                              $msg =array('success'=>'1','token'=>$token['token'],'id'=>$id,'user_name'=>$first_name." ".$last_name,'data'=>$module_permition[0],'type'=>'GO');
                              return $response->withJson($msg);
                              break;

                           case 'CO';
                               if($result = $conn->query("SELECT * from sub_aria_permition where status =1 AND sub_admin_id=".$id)) {
                                   $i=0;
                                   while($row = $result->fetch_assoc()) {
                                     $co[$i] = $row['country'];
                                     $i++;
                                   }
                                }
                                $resule->close();
                                $res_update->close();
                                mysqli_close($conn);
                                $msg =array('success'=>'1','token'=>$token['token'],'id'=>$id,'user_name'=>$first_name." ".$last_name,'data'=>$module_permition[0],'type'=>'CO','country'=>$co);
                                return $response->withJson($msg);
                               break;
                           case 'ST';
                                 if($result = $conn->query("SELECT * from sub_aria_permition where status =1 AND sub_admin_id=".$id)) {
                                     $i=0;
                                   while($row = $result->fetch_assoc()) {
                                   $co = $row['country'];
                                   $st[$i]= $row['state'];
                                   $i++;
                                   }
                               }
                               $resule->close();
                               $res_update->close();
                               mysqli_close($conn);
                               $msg =array('success'=>'1','token'=>$token['token'],'id'=>$id,'user_name'=>$first_name." ".$last_name,'data'=>$module_permition[0],'type'=>'ST','country'=>$co,'state'=>$st);
                               return $response->withJson($msg);
                               break;
                           case 'DI';
                               if($result = $conn->query("SELECT * from sub_aria_permition where status =1 AND sub_admin_id=".$id)) {
                                 $i=0;
                                 while($row = $result->fetch_assoc()) {
                                 $co = $row['country'];
                                 $st= $row['state'];
                                 $ct[$i]=$row['city'];
                                 $i++;
                                  }
                               }
                               $resule->close();
                               $res_update->close();
                               mysqli_close($conn);
                               $msg =array('success'=>'1','token'=>$token['token'],'id'=>$id,'user_name'=>$first_name." ".$last_name,'data'=>$module_permition[0],'type'=>'DI','country'=>$co,'state'=>$st,'city'=>$ct);
                               return $response->withJson($msg);
                               break;
                           case 'CT';
                                 if($result = $conn->query("SELECT * from sub_aria_permition where status =1 AND sub_admin_id=".$id)) {
                                 $i=0;
                                 while($row = $result->fetch_assoc()) {
                                     $co = $row['country'];
                                     $st= $row['state'];
                                     $ct=$row['city'];
                                     $di[$i]=$row['distric'];
                                     $i++;
                                  }
                                 }
                                 $resule->close();
                                 $res_update->close();
                                 mysqli_close($conn);
                                 $msg =array('success'=>'1','token'=>$token['token'],'id'=>$id,'user_name'=>$first_name." ".$last_name,'data'=>$module_permition[0],'type'=>'CT','country'=>$co,'state'=>$st,'city'=>$ct,'distric'=>$di);
                                 return $response->withJson($msg);
                                 break;

                        }

                      }
                     $resule->close();
                     $res_update->close();
                     mysqli_close($conn);
                     $msg =array('success'=>0,'msg'=>'Somthing wrong');
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

$app->get('/user', function($request,$response){

        $conn = sqlConnection();
    try{
      if($result = $conn->query("SELECT id,first_name,last_name,email,phone_num,country,state,created_at,user_block from user ")) {
          $i=0;
          while($row = $result->fetch_assoc()) {
                 $res=$conn->query("SELECT name from main_area where id = ".$row['country']);
                 $country=$res->fetch_assoc();
                 $res=$conn->query("SELECT name from main_area where id = ".$row['state']);
                 $state=$res->fetch_assoc();
                  $myArray[$i]['id'] = $row['id'];
                  $myArray[$i]['first_name'] = $row['first_name'];
                  $myArray[$i]['last_name'] = $row['last_nameper'];
                  $myArray[$i]['email'] = $row['email'];
                  $myArray[$i]['phone_num'] = $row['phone_num'];
                  $myArray[$i]['country'] = $country['name'];
                  $myArray[$i]['state'] = $state['name'];
                  $myArray[$i]['created_at'] = $row['created_at'];
             $i++;
          }
          $data = array("status"=>1, "data"=>$myArray);
          return $response->withJson($data);
       }

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

$app->post('/permition', function($request,$response){

      $user_data = $request->getParsedBody();
      $conn = sqlConnection();
      $cheak = cheak_token($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);

      }
      if($result = $conn->query("SELECT * from module_permition where sub_admin_id = ".$user_data['id'])) {
               while($row = $result->fetch_assoc()) {

                 $module_permition[] =$row;
               }
      }
      mysqli_close($conn);
      $msg =array('success'=>'1','token'=>$user_data['token'],'id'=>$user_data['id'],'data'=>$module_permition[0]);
      return $response->withJson($msg);

});

$app->post('/users', function($request,$response){

      $user_data = $request->getParsedBody();
      $cheak = cheak_token($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,"msg"=>"unotherise");
        return $response->withJson($msg);

      }
      $conn = sqlConnection();
            if($result = $conn->query("SELECT role,register_user from module_permition where sub_admin_id = ".$user_data['id'])) {
                     while($row = $result->fetch_assoc()) {

                       $module_permition[] =$row;
                     }

            }

if($module_permition[0]["role"] == 1){
          try{
            if($result = $conn->query("SELECT id,first_name,last_name,email,phone_num,birth_date,gender,country,state,city,distric,sub_distric,created_at,user_block from user  where status=1")) {
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
                        $myArray[$i]['state'] = $state['name'];
                        $myArray[$i]['city'] = $city['name'];
                        $myArray[$i]['distric'] = $distric['name'];
                        $myArray[$i]['sub_distric'] = $row['sub_distric'];
                        $myArray[$i]['gender'] = $row['gender'];
                        $myArray[$i]['birth_date'] = $row['birth_date'];
                        $myArray[$i]['created_at'] = $row['created_at'];
                        $myArray[$i]['status'] = $row['user_block'];
                   $i++;
                }

                $data = array("status"=>1, "data"=>$myArray);
                return $response->withJson($myArray);
             }
                // $resule->insert_id;
          }catch(Exception $e){
                return $e;
            }
    }

 if($module_permition[0]["role"] == 2){

         if($module_permition[0]['register_user'] == 00){
           $msg = array("success" => 0,'mas'=>'admin no permition');
           return $response->withJson($msg);
         }
        try{
           if($result = $conn->query("SELECT permition_type from aria_permition where sub_admin_id = " .$user_data['id'])) {
                   $permition=$result->fetch_assoc();
                   switch ($permition['permition_type']) {
                          case 'GO':
                          if($result = $conn->query("SELECT id,first_name,last_name,email,phone_num,birth_date,gender,country,state,city,distric,sub_distric,created_at,user_block from user where status=1")) {
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
                                 $myArray[$i]['state'] = $state['name'];
                                 $myArray[$i]['city'] = $city['name'];
                                 $myArray[$i]['distric'] = $distric['name'];
                                 $myArray[$i]['sub_distric'] = $row['sub_distric'];
                                 $myArray[$i]['gender'] = $row['gender'];
                                 $myArray[$i]['birth_date'] = $row['birth_date'];
                                 $myArray[$i]['created_at'] = $row['created_at'];
                                 $myArray[$i]['status'] = $row['user_block'];
                                 $i++;
                              }
                              $data = array("status"=>1, "data"=>$myArray);
                              return $response->withJson($myArray);
                           }
                                 break;
                          case 'CO':
                           if($result = $conn->query("SELECT country from sub_aria_permition where sub_admin_id = ".$user_data['id'])) {
                                  $m=0;
                                  while($row = $result->fetch_assoc()) {
                                    $area[$m]=$row['country'];
                                    $m++;
                                  }

                            }
                          if($result = $conn->query("SELECT id,first_name,last_name,email,phone_num,birth_date,gender,country,state,city,distric,sub_distric,created_at,user_block from user where status =1 AND country IN (" . implode(',', array_map('intval', $area)) . ")")) {
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
                                 $myArray[$i]['state'] = $state['name'];
                                 $myArray[$i]['city'] = $city['name'];
                                 $myArray[$i]['distric'] = $distric['name'];
                                 $myArray[$i]['sub_distric'] = $row['sub_distric'];
                                 $myArray[$i]['gender'] = $row['gender'];
                                 $myArray[$i]['birth_date'] = $row['birth_date'];
                                 $myArray[$i]['created_at'] = $row['created_at'];
                                 $myArray[$i]['status'] = $row['user_block'];
                                 $i++;
                              }
                              $data = array("status"=>1, "data"=>$myArray);
                              return $response->withJson($myArray);
                           }
                               break;
                          case 'ST':
                          if($result = $conn->query("SELECT state from sub_aria_permition where sub_admin_id = ".$user_data['id'])) {
                                 $m=0;
                                 while($row = $result->fetch_assoc()) {
                                   $area[$m]=$row['state'];
                                   $m++;
                                 }

                           }
                          if($result = $conn->query("SELECT id,first_name,last_name,email,phone_num,birth_date,gender,country,state,city,distric,sub_distric,created_at,user_block from user where status =1 AND state IN (" . implode(',', array_map('intval', $area)) . ")")) {
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
                                $myArray[$i]['state'] = $state['name'];
                                $myArray[$i]['city'] = $city['name'];
                                $myArray[$i]['distric'] = $distric['name'];
                                $myArray[$i]['sub_distric'] = $row['sub_distric'];
                                $myArray[$i]['gender'] = $row['gender'];
                                $myArray[$i]['birth_date'] = $row['birth_date'];
                                $myArray[$i]['created_at'] = $row['created_at'];
                                $myArray[$i]['status'] = $row['user_block'];
                                $i++;
                             }
                             $data = array("status"=>1, "data"=>$myArray);
                             return $response->withJson($myArray);
                          }
                              break;
                          case 'CT':
                          if($result = $conn->query("SELECT city from sub_aria_permition where sub_admin_id = ".$user_data['id'])) {
                                 $m=0;
                                 while($row = $result->fetch_assoc()) {
                                   $area[$m]=$row['city'];
                                   $m++;
                                 }

                           }

                          if($result = $conn->query("SELECT id,first_name,last_name,email,phone_num,birth_date,gender,country,state,city,distric,sub_distric,created_at,user_block from user where status =1 AND city IN (" . implode(',', array_map('intval', $area)) . ")")) {
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
                                $myArray[$i]['state'] = $state['name'];
                                $myArray[$i]['city'] = $city['name'];
                                $myArray[$i]['distric'] = $distric['name'];
                                $myArray[$i]['sub_distric'] = $row['sub_distric'];
                                $myArray[$i]['gender'] = $row['gender'];
                                $myArray[$i]['birth_date'] = $row['birth_date'];
                                $myArray[$i]['created_at'] = $row['created_at'];
                                $myArray[$i]['status'] = $row['user_block'];
                                $i++;
                             }
                             $data = array("status"=>1, "data"=>$myArray);
                             return $response->withJson($myArray);
                          }
                                  // $sql = "INSERT INTO sub_aria_permition(area_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_permition_id.",".$sub_admin_id.",".$co.",".$st.",".$admin_data[$p]['value'].",0,0)";
                                  // return $response->withJson(array('q'=>$sql));
                                break;
                          case 'DI':
                          if($result = $conn->query("SELECT distric from sub_aria_permition where sub_admin_id = ".$user_data['id'])) {
                                 $m=0;
                                 while($row = $result->fetch_assoc()) {
                                   $area[$m]=$row['distric'];
                                   $m++;
                                 }

                           }
                          if($result = $conn->query("SELECT id,first_name,last_name,email,phone_num,birth_date,gender,country,state,city,distric,sub_distric,created_at,user_block from user where status =1 AND distric IN (" . implode(',', array_map('intval', $area)) . ")")) {
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
                                $myArray[$i]['state'] = $state['name'];
                                $myArray[$i]['city'] = $city['name'];
                                $myArray[$i]['distric'] = $distric['name'];
                                $myArray[$i]['sub_distric'] = $row['sub_distric'];
                                $myArray[$i]['gender'] = $row['gender'];
                                $myArray[$i]['birth_date'] = $row['birth_date'];
                                $myArray[$i]['created_at'] = $row['created_at'];
                                $myArray[$i]['status'] = $row['user_block'];
                                $i++;
                             }
                             $data = array("status"=>1, "data"=>$myArray);
                             return $response->withJson($myArray);
                          }
                                break;
                   }
           }


          }catch(Exception $e){
                return $e;
            }
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
      $cheak = cheak_token($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
      if(!isset($user_data['user_id'])){
        $msg = array('success' => 0,'msg'=>'not have permission');
        return $response->withJson($msg);

      }

      if($result = $conn->query("SELECT register_user from module_permition where sub_admin_id = ".$user_data['id'])) {

               while($row = $result->fetch_assoc()) {
                 $module_permition[] =$row;
               }
      }

      if($module_permition[0]['register_user'] == 10 OR $module_permition[0]['register_user'] == 00){
        $result->close();
        mysqli_close($conn);
          return $response->withJson(array('success'=>0,'mag'=>'not permition to remove user'));

      }
      if($result = $conn->query("SELECT user_block from user where id = ".$user_data['user_id'])) {
          while($row = $result->fetch_assoc()) {
                $myArray[] = $row;
          }
       }
        if($myArray[0]['user_block']==0){

          try{
                $result=$conn->prepare("UPDATE user set user_block = 1 where id= ?");
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
$msg = array('success' => 0,'msg'=>'admin have not permission');
return $response->withJson($msg);

});

$app->post('/subadmin', function($request,$response,$arg){

  $admin_data = $request->getParsedBody();
  // $file = fopen("nokari_list.txt","w");
  // echo fwrite($file,json_encode($admin_data));
  // fclose($file);
  $cheak = cheak_token($admin_data[0]['value'],$admin_data['1']['value']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
    $conn = sqlConnection();
  if($result = $conn->query("SELECT role from module_permition where sub_admin_id = ".$admin_data[0]['value'])) {

           while($row = $result->fetch_assoc()) {
             $module_permition[] =$row;
           }
  }

      if($module_permition[0]['role']!=1){

         $msg = array('success'=>0,'msg'=>'u have not permition to add sub admin');
         return $response->withJson($mag);

      }

    $first_name = $admin_data[2]["value"];
    $last_name = $admin_data[3]["value"];
    $email = $admin_data[4]["value"];
    $phone_num = $admin_data[5]["value"];
    $password = $admin_data[6]["value"];
    $country = $admin_data[8]["value"];
    $state = $admin_data[9]["value"];
    $city = $admin_data[10]["value"];
    $distric = $admin_data[11]["value"];
    $sub_diatric = $admin_data[12]["value"];
    $area_permition = $admin_data[13]["value"];
    $register = 00;
    $matrimony = 00;
    $blood = 00;
    $naukari = 00;
    $naukarilist = 00;
    $seva =00;
    $business = 00;
    $businesspost = 00;
    $survey = 00;
    $advertise = 000;
    $corporate = 000;
    $surveyquestion = 000;
    $pages = 000;

    $role = 2;
    $p=14;
    $password = md5($password."key123");
    if(!isset($admin_data[4]['value']) && !isset($user_data[6]['value'])){

        return $response ->withJson(array('success'=>0,'msg'=>'data not found'));
    }
    // return $response->withJson(array('q'=>"INSERT INTO sub_admin(first_name,last_name,email,password,phone_num,gender,country,state,city,distric,sub_distric) values ('".$first_name."','".$last_name."','".$email."','".$password."',".$phone_num.",'male',".$country.",".$state.",".$city.",".$distric.",".$sub_diatric.")"));
    try{

      $resule=$conn->prepare("SELECT email from sub_admin where email =? AND status = 1");
      if(!$resule){
        return $response->withJson(array('success'=>0,'msg'=>'somting went wrong'));
            }
      $resule->bind_param('s', $admin_data[4]['value']);
      $resule->execute();
      $resule->store_result();
      if($resule->num_rows >= 1 ){
        $resule->close();
        mysqli_close($conn);
        $msg = array('success' => 2,'msg'=>'user allredy exist ');
        return $response->withJson($msg);
       }
          //  $sql ="INSERT INTO sub_admin(first_name,last_name,email,password,phone_num,gender,country,state,city,distric,sub_distric) values ('".$first_name."','".$last_name."','".$email."','".$password."',".$phone_num.",'male',".$country.",".$state.",".$city.",".$distric.",'".$sub_diatric."')";
          //   return $response->withJson($sql);
        if($conn->query("INSERT INTO sub_admin(first_name,last_name,email,password,phone_num,gender,country,state,city,distric,sub_distric) values ('".$first_name."','".$last_name."','".$email."','".$password."',".$phone_num.",'male',".$country.",".$state.",".$city.",".$distric.",'".$sub_diatric."')")){

                $sub_admin_id = $conn->insert_id;
                $add_user = 1;

         }else{
           $msg = array('success' => 0,'msg'=>' not add admin');
           return $response->withJson($msg);
                }
        if($conn->query("INSERT INTO aria_permition(sub_admin_id,permition_type) values (".$sub_admin_id.",'".$area_permition."')")){
             $area_permition_id=$conn->insert_id;
             switch ($area_permition) {
                    case 'GO':
                          if($conn->query("INSERT INTO sub_aria_permition(area_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_permition_id.",".$sub_admin_id.",0,0,0,0,0)")){

                          $area_permision='GO';

                           }
                           break;
                    case 'CO':
                          while($admin_data[$p]['name']=="country_permit"){
                          if($conn->query("INSERT INTO sub_aria_permition(area_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_permition_id.",".$sub_admin_id.",".$admin_data[$p]['value'].",0,0,0,0)")){
                            $area_permision="co";
                             $p++;
                           }
                          }
                         break;
                    case 'ST':
                          $co=$admin_data[$p]['value'];$p++;
                          while($admin_data[$p]['name']=="state_permit"){
                          if($conn->query("INSERT INTO sub_aria_permition(area_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_permition_id.",".$sub_admin_id.",".$co.",".$admin_data[$p]['value'].",0,0,0)")){
                            $area_permision="st";
                            $p++;
                          }
                         }
                        break;
                    case 'DI':
                          $co=$admin_data[$p]['value'];$p++;
                          $st=$admin_data[$p]['value'];$p++;
                          while($admin_data[$p]['name']=="city_permit"){
                              if($conn->query("INSERT INTO sub_aria_permition(area_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_permition_id.",".$sub_admin_id.",".$co.",".$st.",".$admin_data[$p]['value'].",0,0)")){
                                $area_permision="ct";
                                $p++;
                              }
                             }
                            // $sql = "INSERT INTO sub_aria_permition(area_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_permition_id.",".$sub_admin_id.",".$co.",".$st.",".$admin_data[$p]['value'].",0,0)";
                            // return $response->withJson(array('q'=>$sql));
                          break;
                    case 'CT':
                          $co=$admin_data[$p]['value'];$p++;
                          $st=$admin_data[$p]['value'];$p++;
                          $ct=$admin_data[$p]['value'];$p++;
                          while($admin_data[$p]['name']=="district_permit"){
                              if($conn->query("INSERT INTO sub_aria_permition(area_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_permition_id.",".$sub_admin_id.",".$co.",".$st.",".$ct.",".$admin_data[$p]['value'].",0)")){
                                  $area_permision="di";
                                  $p++;
                                }
                            }
                          break;

             }

        }

                 if($admin_data[$p]['value']=="register_read"){
                      $register=10;$p++;

                      if($admin_data[$p]['value']=="register_status"){
                        $register=11;$p++;
                      }

                 }
                 if($admin_data[$p]['value']=="register_status"){
                   $register=11;$p++;
                 }

                 if($admin_data[$p]['value']=="matrimony_read"){
                      $matrimony=10;$p++;
                      if($register==0){
                        $register=10;
                      }

                      if($admin_data[$p]['value']=="matrimony_status"){
                        $matrimony=11;$p++;
                      }

                 }
                 if($admin_data[$p]['value']=="matrimony_status"){
                   $matrimony=11;$p++;
                   if($register==0){
                     $register=10;
                   }
                 }

                 if($admin_data[$p]['value']=="blood_read"){
                      $blood=10;$p++;
                      if($register==0){
                        $register=10;
                      }
                      if($admin_data[$p]['value']=="blood_status"){
                        $blood=11;$p++;
                      }

                 }
                 if($admin_data[$p]['value']=="blood_status"){
                   $blood=11;$p++;
                   if($register==0){
                     $register=10;
                   }
                 }

                 if($admin_data[$p]['value']=="naukari_read"){
                      $naukari=10;$p++;
                      if($register==0){
                        $register=10;
                      }
                      if($admin_data[$p]['value']=="naukari_status"){
                        $naukari=11;$p++;
                      }

                 }
                 if($admin_data[$p]['value']=="naukari_status"){
                   $naukari=11;$p++;
                   if($register==0){
                     $register=10;
                   }
                 }

                 if($admin_data[$p]['value']=="naukarilist_read"){
                      $naukarilist=10;$p++;
                      if($register==0){
                        $register=10;
                      }
                      if($admin_data[$p]['value']=="naukarilist_status"){
                        $naukarilist=11;$p++;
                      }

                 }
                 if($admin_data[$p]['value']=="naukarilist_status"){
                   $naukarilist=11;$p++;
                   if($register==0){
                     $register=10;
                   }
                 }

                 if($admin_data[$p]['value']=="seva_read"){
                      $seva=1000;$p++;

                      if($admin_data[$p]['value']=="seva_add"){
                        $seva=1100;$p++;
                        if($admin_data[$p]['value']=="seva_status"){
                           $seva=1101;$p++;
                        }
                        if($admin_data[$p]['value']=="seva_update"){
                           $seva=1110;$p++;
                           if($admin_data[$p]['value']=="seva_status"){
                              $seva=1111;$p++;
                           }
                        }
                      }
                      if($admin_data[$p]['value']=="seva_status"){
                         $seva=1001;$p++;
                      }
                         if($admin_data[$p]['value']=="seva_update"){
                            $seva=1010;$p++;
                            if($admin_data[$p]['value']=="seva_status"){
                               $seva=1011;$p++;

                            }
                         }
                 }
                 if($admin_data[$p]['value']=="seva_add"){
                    $seva=1100;$p++;
                    if($admin_data[$p]['value']=="seva_status"){
                       $seva=1101;$p++;

                    }
                    if($admin_data[$p]['value']=="seva_update"){
                       $seva=1110;$p++;
                       if($admin_data[$p]['value']=="seva_status"){
                          $seva=1111;$p++;

                       }
                    }

                 }
                 if($admin_data[$p]['value']=="seva_update"){
                    $seva=1010;$p++;
                    if($admin_data[$p]['value']=="seva_status"){
                       $seva=1011;$p++;

                    }
                 }
                 if($admin_data[$p]['value']=="seva_status"){
                    $seva=1001;$p++;
                 }

                //  if($admin_data[$p]['value']=="seva_read"){
                //       $seva=10;$p++;
                 //
                //       if($admin_data[$p]['value']=="seva_status"){
                //         $seva=11;$p++;
                //       }
                 //
                //  }
                //  if($admin_data[$p]['value']=="seva_status"){
                //    $seva=11;$p++;
                //  }

                 if($admin_data[$p]['value']=="business_read"){
                      $business=10;$p++;
                      if($register==0){
                        $register=10;
                      }
                      if($admin_data[$p]['value']=="business_status"){
                        $business=11;$p++;
                      }

                 }
                 if($admin_data[$p]['value']=="business_status"){
                   $business=11;$p++;
                   if($register==0){
                     $register=10;
                   }
                 }

                 if($admin_data[$p]['value']=="businesspost_read"){
                      $businesspost=10;$p++;
                      if($register==0){
                        $register=10;
                      }
                      if($admin_data[$p]['value']=="businesspost_status"){
                        $businesspost=11;$p++;
                      }

                 }
                 if($admin_data[$p]['value']=="businesspost_status"){
                   $businesspost=11;$p++;
                   if($register==0){
                     $register=10;
                   }
                 }

                 if($admin_data[$p]['value']=="survey_read"){
                      $survey=10;$p++;

                      if($admin_data[$p]['value']=="survey_status"){
                        $survey=11;$p++;
                      }

                 }
                 if($admin_data[$p]['value']=="survey_status"){
                   $survey=11;$p++;
                 }

                 if($admin_data[$p]['value']=="pages_read"){
                      $pages=1000;$p++;

                      if($admin_data[$p]['value']=="pages_add"){
                        $pages=1100;$p++;
                        if($admin_data[$p]['value']=="pages_status"){
                           $pages=1101;$p++;
                        }
                        if($admin_data[$p]['value']=="pages_update"){
                           $pages=1110;$p++;
                           if($admin_data[$p]['value']=="pages_status"){
                              $pages=1111;$p++;
                           }
                        }
                      }
                      if($admin_data[$p]['value']=="pages_status"){
                         $pages=1001;$p++;
                      }
                         if($admin_data[$p]['value']=="pages_update"){
                            $pages=1010;$p++;
                            if($admin_data[$p]['value']=="pages_status"){
                               $pages=1011;$p++;

                            }
                         }
                 }
                 if($admin_data[$p]['value']=="pages_add"){
                    $pages=1100;$p++;
                    if($admin_data[$p]['value']=="pages_status"){
                       $pages=1101;$p++;

                    }
                    if($admin_data[$p]['value']=="pages_update"){
                       $pages=1110;$p++;
                       if($admin_data[$p]['value']=="pages_status"){
                          $pages=1111;$p++;

                       }
                    }

                 }
                 if($admin_data[$p]['value']=="pages_update"){
                    $pages=1010;$p++;
                    if($admin_data[$p]['value']=="pages_status"){
                       $pages=1011;$p++;

                    }
                 }
                 if($admin_data[$p]['value']=="pages_status"){
                    $pages=1001;$p++;
                 }

                 if($admin_data[$p]['value']=="advertise_read"){
                      $advertise=1000;$p++;

                      if($admin_data[$p]['value']=="advertise_add"){
                        $advertise=1100;$p++;
                        if($admin_data[$p]['value']=="advertise_status"){
                           $advertise=1101;$p++;
                        }
                        if($admin_data[$p]['value']=="advertise_update"){
                           $advertise=1110;$p++;
                           if($admin_data[$p]['value']=="advertise_status"){
                              $advertise=1111;$p++;
                           }
                        }


                      }

                      if($admin_data[$p]['value']=="advertise_status"){
                         $advertise=1001;$p++;
                      }
                         if($admin_data[$p]['value']=="advertise_update"){
                            $advertise=1010;$p++;
                            if($admin_data[$p]['value']=="advertise_status"){
                               $advertise=1011;$p++;

                            }
                         }
                 }
                 if($admin_data[$p]['value']=="advertise_add"){
                    $advertise=1100;$p++;
                    if($admin_data[$p]['value']=="advertise_status"){
                       $advertise=1101;$p++;
                    }
                    if($admin_data[$p]['value']=="advertise_update"){
                       $advertise=1110;$p++;
                       if($admin_data[$p]['value']=="advertise_status"){
                          $advertise=1111;$p++;

                       }
                    }

                 }
                 if($admin_data[$p]['value']=="advertise_update"){
                    $advertise=1010;$p++;
                    if($admin_data[$p]['value']=="advertise_status"){
                       $advertise=1011;$p++;

                    }
                 }
                 if($admin_data[$p]['value']=="advertise_status"){
                    $advertise=1001;$p++;
                 }

                 if($admin_data[$p]['value']=="corporate_read"){
                      $corporate=1000;$p++;

                      if($admin_data[$p]['value']=="corporate_add"){
                        $corporate=1100;$p++;
                        if($admin_data[$p]['value']=="corporate_status"){
                           $corporate=1101;$p++;
                        }
                        if($admin_data[$p]['value']=="corporate_update"){
                           $corporate=1110;$p++;
                           if($admin_data[$p]['value']=="corporate_status"){
                              $corporate=1111;$p++;
                           }
                        }
                      }
                      if($admin_data[$p]['value']=="corporate_status"){
                         $corporate=1001;$p++;
                      }
                         if($admin_data[$p]['value']=="corporate_update"){
                            $corporate=1010;$p++;
                            if($admin_data[$p]['value']=="corporate_status"){
                               $corporate=1011;$p++;

                            }
                         }
                 }
                 if($admin_data[$p]['value']=="corporate_add"){
                    $corporate=1100;$p++;
                    if($admin_data[$p]['value']=="corporate_status"){
                       $corporate=1101;$p++;
                    }
                    if($admin_data[$p]['value']=="corporate_update"){
                       $corporate=1110;$p++;
                       if($admin_data[$p]['value']=="corporate_status"){
                          $corporate=1111;$p++;

                       }
                    }

                 }
                 if($admin_data[$p]['value']=="corporate_update"){
                    $corporate=1010;$p++;
                    if($admin_data[$p]['value']=="corporate_status"){
                       $corporate=1011;$p++;

                    }
                 }
                 if($admin_data[$p]['value']=="corporate_status"){
                    $corporate=1001;$p++;
                 }

                 if($admin_data[$p]['value']=="surveyquestion_read"){
                      $surveyquestion=1000;$p++;

                      if($admin_data[$p]['value']=="surveyquestion_add"){
                        $surveyquestion=1100;$p++;
                        if($admin_data[$p]['value']=="surveyquestion_status"){
                           $surveyquestion=1101;$p++;
                        }
                        if($admin_data[$p]['value']=="surveyquestion_update"){
                           $surveyquestion=1110;$p++;
                           if($admin_data[$p]['value']=="surveyquestion_status"){
                              $surveyquestion=1111;$p++;
                           }
                        }
                      }
                      if($admin_data[$p]['value']=="surveyquestion_status"){
                         $surveyquestion=1001;$p++;
                      }
                         if($admin_data[$p]['value']=="surveyquestion_update"){
                            $surveyquestion=1010;$p++;
                            if($admin_data[$p]['value']=="surveyquestion_status"){
                               $surveyquestion=1011;$p++;

                            }
                         }
                 }
                 if($admin_data[$p]['value']=="surveyquestion_add"){
                    $surveyquestion=1100;$p++;
                    if($admin_data[$p]['value']=="surveyquestion_status"){
                       $surveyquestion=1101;$p++;
                    }
                    if($admin_data[$p]['value']=="surveyquestion_update"){
                       $surveyquestion=1110;$p++;
                       if($admin_data[$p]['value']=="surveyquestion_status"){
                          $surveyquestion=1111;$p++;

                       }
                    }

                 }
                 if($admin_data[$p]['value']=="surveyquestion_update"){
                    $corporate=1010;$p++;
                    if($admin_data[$p]['value']=="surveyquestion_status"){
                       $surveyquestion=1011;$p++;

                    }
                 }
                 if($admin_data[$p]['value']=="surveyquestion_status"){
                    $surveyquestion=1001;$p++;
                 }
        if($conn->query("INSERT INTO module_permition(sub_admin_id,role,matrimony,register_user,blood_doner,noklari,nokari_user,seva_orgnization,bissness_user,bissness,servay_ans,pages,addvtisement,corporater,servay_qus) values (".$sub_admin_id.",".$role.",".$matrimony.",".$register.",".$blood.",".$naukari.",".$naukarilist.",".$seva.",".$business.",".$businesspost.",".$survey.",".$pages.",".$advertise.",".$corporate.",".$surveyquestion.")")){
           $mdoule ='1';
        }

      $msg =array('success'=>1,'msg'=>'user add');
      return $response->withJson($msg);

    }catch(Exception $e){
       $msg = array('success'=>0,'msg'=>$e);
       return $request->withJson($msg);
    }
    mysqli_close($conn);
    $msg = array('success' => 0,'msg'=>'faild to add');
    return $response->withJson($msg);

});

$app->post('/sub_admin_list',function($request,$response,$args){

    $admin_data = $request->getParsedBody();
    $cheak = cheak_token($admin_data['id'],$admin_data['token']);
    if($cheak['success'] == 0){
      $msg = array("success" => 0,'msg'=>'unotherise');
      return $response->withJson($msg);
    }
    $conn = sqlConnection();
    try{
        if($result = $conn->query("SELECT role from module_permition where status =1 AND sub_admin_id = ".$admin_data['id'])) {
                 while($row = $result->fetch_assoc()) {

                   $module_permition[] =$row;
                 }
        }
       if($module_permition[0]['role'] != 1){

        $result->close();
        mysqli_close($conn);
        $msg=array('success'=>0,'msg'=>'not permision to access');
        return $response->withJson($msg);

       }

        if ($result = $conn->query("SELECT id,first_name,last_name,email,phone_num,user_block FROM sub_admin where status = 1 AND id != ".$admin_data['id'])) {
          while($row = $result->fetch_assoc()) {
                $sub_admin[] = $row;
          }
          $result->close();
          mysqli_close($conn);
          $msg = array('success'=>1,'data'=>$sub_admin);
          return $response->withJson($msg);
        }

  }catch(Exception $e){
     return $response->withJson($e);
  }
   return $response->withJson(array('success'=>0,'msg'=>'not access'));
    $result->close();
    mysqli_close($conn);
});

$app->post('/admin_view',function($request,$response,$args){

  $admin_data = $request->getParsedBody();
  // $file = fopen("admin_view.txt","w");
  // echo fwrite($file,json_encode($user_data));
  // fclose($file);
  $cheak = cheak_token($admin_data['id'],$admin_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
  $conn = sqlConnection();
  try{
      if($result = $conn->query("SELECT role from module_permition where status =1 AND sub_admin_id = ".$admin_data['id'])) {
               while($row = $result->fetch_assoc()) {

                 $module_permition[] =$row;
               }
      }
     if($module_permition[0]['role'] != 1){

      $result->close();
      mysqli_close($conn);
      $msg=array('success'=>0,'msg'=>'not permision to access');
      return $response->withJson($msg);

     }
      if ($result = $conn->query("SELECT id,first_name,last_name,email,phone_num,country,state,city,distric,sub_distric,user_block,created_at FROM sub_admin where status = 1 AND id = ".$admin_data['admin_id'])) {
          $i=0;

        while($row = $result->fetch_assoc()) {
              $res=$conn->query("SELECT name from m_area where id = ".$row['country']);
              $country=$res->fetch_assoc();
              $res=$conn->query("SELECT name from m_area where id = ".$row['state']);
              $state=$res->fetch_assoc();
              $res=$conn->query("SELECT name from m_area where id = ".$row['city']);
              $city=$res->fetch_assoc();
              $res=$conn->query("SELECT name from m_area where id = ".$row['distric']);
              $distric=$res->fetch_assoc();
              $sub_admin[$i]['first_name'] = $row['first_name'];
              $sub_admin[$i]['last_name'] = $row['last_name'];
              $sub_admin[$i]['email'] = $row['email'];
              $sub_admin[$i]['phone_num'] = $row['phone_num'];
              $sub_admin[$i]['country'] = $country['name'];
              $sub_admin[$i]['state'] = $state['name'];
              $sub_admin[$i]['city'] = $city['name'];
              $sub_admin[$i]['distric'] = $distric['name'];
              $sub_admin[$i]['sub_diatric'] = $row['sub_distric'];
              $sub_admin[$i]['created_at'] = $row['created_at'];

              if($result = $conn->query("SELECT * from module_permition where sub_admin_id = ".$row['id'])){
                  $row2 = $result->fetch_assoc();
                  $sub_admin[$i]['permition']=$row2;
              }
              if($area = $conn->query("SELECT id,permition_type FROM aria_permition where sub_admin_id = ".$row['id'])){
                $arearow = $area->fetch_assoc();
                $sub_admin[$i]['area_permition']=$arearow['permition_type'];

                switch($sub_admin[$i]['area_permition']){
                  case 'CO':
                  // return $response->withJson(array("data"=>"SELECT country,state,city,distric FROM sub_aria_permition WHERE area_id=".$arearow['id']." AND  sub_admin_id=".$row['id']));
                      if($subarea = $conn->query("SELECT country,state,city,distric FROM sub_aria_permition WHERE area_id=".$arearow['id']." AND  sub_admin_id=".$row['id'])){
                            $j=0;
                             while($subarearow = $subarea->fetch_assoc()){
                               $res=$conn->query("SELECT name from m_area where id = ".$subarearow['country']);
                               $country=$res->fetch_assoc();
                               $sub_admin[$i]['area_country'][$j]=$country['name'];
                               $j++;
                             }
                        }
                 break;
                  case 'CT':
                      // return $response->withJson(array("data"=>"SELECT country,state,city,distric FROM sub_aria_permition WHERE area_id=".$arearow['id']." AND  sub_admin_id=".$row['id']));
                      if($subarea = $conn->query("SELECT country,state,city,distric FROM sub_aria_permition WHERE area_id=".$arearow['id']." AND  sub_admin_id=".$row['id'])){
                            $j=0;
                             while($subarearow = $subarea->fetch_assoc()){
                               $res=$conn->query("SELECT name from m_area where id = ".$subarearow['country']);
                               $country=$res->fetch_assoc();
                               $res=$conn->query("SELECT name from m_area where id = ".$subarearow['state']);
                               $state=$res->fetch_assoc();
                               $res=$conn->query("SELECT name from m_area where id = ".$subarearow['city']);
                               $city=$res->fetch_assoc();
                               $res=$conn->query("SELECT name from m_area where id = ".$subarearow['distric']);
                               $distric=$res->fetch_assoc();
                               $sub_admin[$i]['area_country']=$country['name'];
                               $sub_admin[$i]['area_state']=$state['name'];
                               $sub_admin[$i]['area_distric']=$city['name'];
                               $sub_admin[$i]['area_city'][$j]=$distric['name'];
                               $j++;
                             }
                        }
                 break;
                 case 'ST':
                     if($subarea = $conn->query("SELECT country,state,city,distric FROM sub_aria_permition WHERE area_id=".$arearow['id']." AND  sub_admin_id=".$row['id'])){
                           $j=0;
                            while($subarearow = $subarea->fetch_assoc()){
                              $res=$conn->query("SELECT name from m_area where id = ".$subarearow['country']);
                              $country=$res->fetch_assoc();
                              $res=$conn->query("SELECT name from m_area where id = ".$subarearow['state']);
                              $state=$res->fetch_assoc();
                              $res=$conn->query("SELECT name from m_area where id = ".$subarearow['city']);
                              $city=$res->fetch_assoc();
                              $res=$conn->query("SELECT name from m_area where id = ".$subarearow['distric']);
                              $distric=$res->fetch_assoc();
                              $sub_admin[$i]['area_country']=$country['name'];
                              $sub_admin[$i]['area_state'][$j]=$state['name'];
                              $sub_admin[$i]['area_distric']=$city['name'];
                              $sub_admin[$i]['area_city']=$distric['name'];
                              $j++;
                            }
                       }
                break;
                case 'DI':
                    if($subarea = $conn->query("SELECT country,state,city,distric FROM sub_aria_permition WHERE area_id=".$arearow['id']." AND  sub_admin_id=".$row['id'])){
                          $j=0;
                           while($subarearow = $subarea->fetch_assoc()){
                             $res=$conn->query("SELECT name from m_area where id = ".$subarearow['country']);
                             $country=$res->fetch_assoc();
                             $res=$conn->query("SELECT name from m_area where id = ".$subarearow['state']);
                             $state=$res->fetch_assoc();
                             $res=$conn->query("SELECT name from m_area where id = ".$subarearow['city']);
                             $city=$res->fetch_assoc();
                             $res=$conn->query("SELECT name from m_area where id = ".$subarearow['distric']);
                             $distric=$res->fetch_assoc();
                             $sub_admin[$i]['area_country']=$country['name'];
                             $sub_admin[$i]['area_state']=$state['name'];
                             $sub_admin[$i]['area_distric'][$j]=$city['name'];
                             $sub_admin[$i]['area_city']=$distric['name'];
                             $j++;
                           }
                      }
               break;
            }

              $i++;
        }
        $result->close();
        mysqli_close($conn);
        return $response->withJson($sub_admin);
      }
   }
}catch(Exception $e){
   return $response->withJson($e);
}
$result->close();
mysqli_close($conn);
 return $response->withJson(array('success'=>0,'msg'=>'not access of data'));

});

$app->post('/user_view',function($request,$response,$args){

  $user_data = $request->getParsedBody();
  $cheak = cheak_token($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
  $conn = sqlConnection();
        if($result = $conn->query("SELECT role,register_user from module_permition where sub_admin_id = ".$user_data['id'])) {
                 while($row = $result->fetch_assoc()) {

                   $module_permition[] =$row;
                 }

        }

      if($module_permition[0]['register_user']==00){
        $result->close();
        mysqli_close($conn);
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);

      }

      if($result = $conn->query("SELECT id,first_name,last_name,email,phone_num,birth_date,gender,country,state,city,distric,sub_distric,created_at,user_block from user  where status=1 AND id = ".$user_data['user_id'])) {
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
                $myArray[$i]['state'] = $state['name'];
                $myArray[$i]['city'] = $city['name'];
                $myArray[$i]['distric'] = $distric['name'];
                $myArray[$i]['sub_distric'] = $row['sub_distric'];
                $myArray[$i]['gender'] = $row['gender'];
                $myArray[$i]['birth_date'] = $row['birth_date'];
                $myArray[$i]['created_at'] = $row['created_at'];
                $myArray[$i]['status'] = $row['user_block'];
           $i++;
        }
        $result->close();
        mysqli_close($conn);
        $data = array("status"=>1, "data"=>$myArray);
        return $response->withJson($myArray);


      }
      $result->close();
      mysqli_close($conn);
      $msg = array("success" => 0,'msg'=>'undefind the user');
      return $response->withJson($msg);


});

$app->post('/sub_admin_block',function($request,$response,$args){
      $admin_data = $request->getParsedBody();

      $cheak = cheak_token($admin_data['id'],$admin_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }

      $conn =sqlConnection();
    try{
      if($result = $conn->query("SELECT role from module_permition where sub_admin_id = ".$admin_data['id'])) {
               while($row = $result->fetch_assoc()) {

                 $module_permition[] =$row;
               }
      }
      if($module_permition[0]['role'] != 1){
        $result->close();
        mysqli_close($conn);
       $msg =array('success'=>0,'msg'=>'you have not permision');
       return $response->withJson($msg);
      }

      if($result = $conn->query("SELECT role from module_permition where sub_admin_id = ".$admin_data['sub_admin_id'])) {
               while($row = $result->fetch_assoc()) {

                 $sub_admin_permision[] =$row;
               }
      }

      if($sub_admin_permision[0]['role'] == 1){
        $result->close();
        mysqli_close($conn);
       $msg =array('success'=>0,'msg'=>'main admin will not disable');
       return $response->withJson($msg);
      }

      if($result = $conn->query("SELECT user_block from sub_admin where id = ".$admin_data['sub_admin_id'])) {
          while($row = $result->fetch_assoc()) {
                $myArray[] = $row;
                }
          }else{
            $msg =array('success'=>0,'msg'=>'main admin will not disable');
            return $response->withJson($msg);
          }

            if($myArray[0]['user_block']==1){

                  try{
                        $result=$conn->prepare("UPDATE sub_admin set user_block = 0 where id= ? AND id !=".$admin_data['id']);
                        // $resule->bind_param('s', $name);
                        $result->bind_param('s', $admin_data['sub_admin_id']);
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
                   $msg = array('success' => 1,'msg'=>'sub admin will disable');
                   return $response->withJson($msg);
             }else{
               try{
                     $result=$conn->prepare("UPDATE sub_admin set user_block = 1 where id= ? AND id != ".$admin_data['id']);
                     // $resule->bind_param('s', $name);
                     $result->bind_param('s', $admin_data['sub_admin_id']);
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
                $msg = array('success' => 1,'msg'=>'sub admin will enable');
                return $response->withJson($msg);

             }
    }catch(Exception $e){

      return $response->withJson(array('data'=>$e));
    }
    $msg= array('success'=>0,'msg'=>'somethng went wrong');
    return $response->withJson($msg);


});
$app->post('/sub_admin_forget',function($request,$response,$args){
      $admin_data = $request->getParsedBody();
      if(isset($admin_data['email'])){
        $msg= array('success'=>0,'msg'=>'somethng went wrong');
        return $response->withJson($msg);
      }
      $conn =sqlConnection();
      if($sub_admin_data=$conn->query("SELECT id,first_name,last_name,email FROM sub_admin where email=".$admin_data['email'])){
         if($sub_admin_data->num_rows = 0){
           $arrRtn= bin2hex(openssl_random_pseudo_bytes(16)."light1432");
           $tokenExpiration = date('Y-m-d H:i:s', strtotime('+120 seconds'));

         $row = $sub_admin_data->fetch_assoc();
         $msg =array('success'=>1,'name'=>$row['first_name']." ".$row['last_name'],'mag'=>'its u? or not');
         return $response->withJson($msg);
        }
}
        $msg= array('success'=>0,'msg'=>'somethng went wrong');
        return $response->withJson($msg);
});

?>
