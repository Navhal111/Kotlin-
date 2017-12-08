<?php

$app->post('/corporate_add', function($request,$response,$args) {
           $admin_data = $request->getParsedBody();
          $cheak = cheak_token($admin_data[1]['value'],$admin_data[2]['value']);
           if($cheak['success'] == 0){
             $msg = array("success" => 0,'msg'=>'unotherise');
             return $response->withJson($msg);
           }

           $conn = sqlConnection();
           if($result = $conn->query("SELECT corporater from module_permition where sub_admin_id = ".$admin_data[1]['value'])) {

                    while($row = $result->fetch_assoc()) {
                      $module_permition[] =$row;
                    }
           }

           if($module_permition[0]['corporater']==0000){
             $msg =array('success'=>0,'msg'=>'admin have not permission');
             return $response->withJson($msg);

           }
    try{
          if($admin_data[0]['value'] =='add'){
            $resule=$conn->prepare("SELECT email from corporate_details where email =? AND status = 1");
            if(!$resule){
              echo "error";
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
             $data=area_check($admin_data[1]['value'],$admin_data[2]['value']);
             // return $response->withJson(array('data'=>$data));
             $flag=0;
             switch($data['per']){
               case 'GO':
                 $flag=1;
               case 'CO':
                  $CH=0;
                  while(isset($data['data'][$CH])){
                         if($data['data'][$CH]==$admin_data[9]['value']){
                           $flag=1;
                         }
                   $CH++;
                  }
               case 'ST':
                     $CH=0;
                     while(isset($data['data'][$CH])){
                            if($data['data'][$CH]==$admin_data[10]['value']){
                              $flag=1;
                            }
                            $CH++;
                     }


               case 'DI':
                 $CH=0;

                   while(isset($data['data'][$CH])){
                          if($data['data'][$CH]==$admin_data[11]['value']){
                            $flag=1;
                          }
                          $CH++;
                   }

               case 'CT':
                 $CH=0;
                     while(isset($data['data'][$CH])){
                            if($data['data'][$CH]==$admin_data[12]['value']){
                              $flag=1;
                            }
                          $CH++;
                     }

             }
             // return $response->withJson(array('data'=>$flag));
             if ($flag==0) {

               return $response->withJson(array('success'=>3,'msg'=>'Admin have not permission of this area'));
             }

                if($module_permition[0]['corporater']==1100 OR $module_permition[0]['corporater']==1110 OR $module_permition[0]['corporater']==1101 OR $module_permition[0]['corporater']==1111){
                      if($conn->query("INSERT INTO corporate_details(admin_id,name,address,country,state,city,distric,sub_diatric,phone_num,phone_num1,email,contact_name) VALUES ('".$admin_data[1]['value']."','".$admin_data[3]['value']."','".$admin_data[8]['value']."',".$admin_data[9]['value'].",".$admin_data[10]['value'].",".$admin_data[11]['value'].",".$admin_data[12]['value'].",'".$admin_data[13]['value']."',".$admin_data[5]['value'].",".$admin_data[6]['value'].",'".$admin_data[4]['value']."','".$admin_data[7]['value']."')" )){
                       mysqli_close($conn);
                       $msg =array('success'=>1,'msg'=>'add data');
                       return $response->withJson($msg);

                      }

                      $msg =array('success'=>0,'msg'=>'not add');
                      return $response->withJson($msg);
                }

        }
        if($admin_data[0]['value'] == 'edit'){
              // return $response->withJson('success');
              $resule=$conn->prepare("SELECT email from corporate_details where email =? AND status = 1 AND id!= ".$admin_data[14]['value']);
              if(!$resule){
                echo "error";
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
              if($module_permition[0]['corporater']==1110 OR $module_permition[0]['corporater']==1011 OR $module_permition[0]['corporater']==1010 OR $module_permition[0]['corporater']==1111){
                    if($conn->query("UPDATE corporate_details SET admin_id = ".$admin_data[1]['value'].",name = '".$admin_data[3]['value']."',address = '".$admin_data[8]['value']."',country = ".$admin_data[9]['value'].",state = ".$admin_data[10]['value'].",city = ".$admin_data[11]['value'].",distric = ".$admin_data[12]['value'].",sub_diatric = '".$admin_data[13]['value']."',phone_num = ".$admin_data[5]['value'].",phone_num1 = ".$admin_data[6]['value'].",email = '".$admin_data[4]['value']."',contact_name = '".$admin_data[7]['value']."' where id = ".$admin_data[14]['value'])){

                     $msg =array('success'=>1,'msg'=>'update data');
                     return $response->withJson($msg);

                  }

              }
              $msg =array('success'=>0,'msg'=>'admin have not permission');
              return $response->withJson($msg);

      }

    }catch(Exception $e){
      $msg = array("success" => 0,'mas'=>$e);
      return $response->withJson(array('response'=>$msg));
    }
    $msg =array('success'=>0,'msg'=>'data not edited');
    return $response->withJson($msg);
});

$app->post('/corporate_list', function($request,$response,$args) {
       $admin_data = $request->getParsedBody();
       $cheak = cheak_token($admin_data['id'],$admin_data['token']);
       if($cheak['success'] == 0){
         $msg = array("success" => 0,'msg'=>'unotherise');
         return $response->withJson($msg);
       }

       $conn = sqlConnection();
             if($result = $conn->query("SELECT role,corporater from module_permition where sub_admin_id = ".$admin_data['id'])) {
                      while($row = $result->fetch_assoc()) {

                        $module_permition[] =$row;
                      }
             }
             if($module_permition[0]["role"] == 1){
                       try{

                         if($result = $conn->query("SELECT id,name,address,country,state,city,phone_num,email,contact_name,block_status from corporate_details where status =1")) {
                             $i=0;
                             while($row = $result->fetch_assoc()) {
                                    $res=$conn->query("SELECT name from m_area where id = ".$row['country']);
                                    $country=$res->fetch_assoc();
                                    $res=$conn->query("SELECT name from m_area where id = ".$row['state']);
                                    $state=$res->fetch_assoc();
                                    $res=$conn->query("SELECT name from m_area where id = ".$row['city']);
                                    $city=$res->fetch_assoc();
                                     $myArray[$i]['id'] = $row['id'];
                                     $myArray[$i]['name'] = $row['name'];
                                     $myArray[$i]['address'] = $row['address'];
                                     $myArray[$i]['email'] = $row['email'];
                                     $myArray[$i]['phone_num'] = $row['phone_num'];
                                     $myArray[$i]['contact_name'] = $row['contact_name'];
                                     $myArray[$i]['country'] = $country['name'];
                                     $myArray[$i]['state'] = $state['name'];
                                     $myArray[$i]['city'] = $city['name'];
                                     $myArray[$i]['status']=$row['block_status'];
                                    //  $myArray[$i]['created_at'] = $row['created_at'];
                                $i++;
                             }
                             $data = array("status"=>1, "data"=>$myArray);
                             return $response->withJson($myArray);
                          }

                             // $resule->insert_id;
                       }catch(Exception $e){
                         $msg = array("success" => 0,'mas'=>$e);
                         return $response->withJson(array('response'=>$msg));
                         }
                         return $response->withJson(array('success'=>0,'msg'=>'something went wrong'));
                 }
if($module_permition[0]["role"] == 2){
        if($module_permition[0]['corporater'] == 00){
                $result->close;
                mysqli_close($conn);
                $msg = array("success" => 0,'mas'=>'admin no permition');
                return $response->withJson($msg);
            }
  try{
      if($result = $conn->query("SELECT permition_type from aria_permition where sub_admin_id = " .$admin_data['id'])) {
              $permition=$result->fetch_assoc();

              switch ($permition['permition_type']) {
                  case 'GO':
                  if($result = $conn->query("SELECT id,name,address,country,state,city,phone_num,email,contact_name,block_status from corporate_details where status =1")) {
                      $i=0;
                      while($row = $result->fetch_assoc()) {
                             $res=$conn->query("SELECT name from a_main where id = ".$row['country']);
                             $country=$res->fetch_assoc();
                             $res=$conn->query("SELECT name from a_main where id = ".$row['state']);
                             $state=$res->fetch_assoc();
                             $res=$conn->query("SELECT name from a_main where id = ".$row['city']);
                             $city=$res->fetch_assoc();
                              $myArray[$i]['id'] = $row['id'];
                              $myArray[$i]['name'] = $row['name'];
                              $myArray[$i]['address'] = $row['address'];
                              $myArray[$i]['email'] = $row['email'];
                              $myArray[$i]['phone_num'] = $row['phone_num'];
                              $myArray[$i]['contact_name'] = $row['contact_name'];
                              $myArray[$i]['country'] = $country['name'];
                              $myArray[$i]['state'] = $state['name'];
                              $myArray[$i]['city'] = $city['name'];
                              $myArray[$i]['status']=$row['block_status'];
                             //  $myArray[$i]['created_at'] = $row['created_at'];
                         $i++;
                      }
                      $data = array("status"=>1, "data"=>$myArray);
                      return $response->withJson($myArray);
                   }
                         break;
                    case 'CO':
                      if($result = $conn->query("SELECT country from sub_aria_permition where sub_admin_id = ".$admin_data['id'])) {
                             $m=0;
                             while($row = $result->fetch_assoc()) {
                               $area[$m]=$row['country'];
                               $m++;
                             }
                       }
                       if($result = $conn->query("SELECT id,name,address,country,state,city,phone_num,email,contact_name,block_status from corporate_details where status =1 AND country IN (" . implode(',', array_map('intval', $area)) . ")")) {
                               $i=0;
                               while($row = $result->fetch_assoc()) {
                                      $res=$conn->query("SELECT name from a_main where id = ".$row['country']);
                                      $country=$res->fetch_assoc();
                                      $res=$conn->query("SELECT name from a_main where id = ".$row['state']);
                                      $state=$res->fetch_assoc();
                                      $res=$conn->query("SELECT name from a_main where id = ".$row['city']);
                                      $city=$res->fetch_assoc();
                                       $myArray[$i]['id'] = $row['id'];
                                       $myArray[$i]['name'] = $row['name'];
                                       $myArray[$i]['address'] = $row['address'];
                                       $myArray[$i]['email'] = $row['email'];
                                       $myArray[$i]['phone_num'] = $row['phone_num'];
                                       $myArray[$i]['contact_name'] = $row['contact_name'];
                                       $myArray[$i]['country'] = $country['name'];
                                       $myArray[$i]['state'] = $state['name'];
                                       $myArray[$i]['city'] = $city['name'];
                                       $myArray[$i]['status']=$row['block_status'];
                                      //  $myArray[$i]['created_at'] = $row['created_at'];
                                  $i++;
                               }
                               $data = array("status"=>1, "data"=>$myArray);
                               return $response->withJson($myArray);
                       }
                    case 'ST':
                      if($result = $conn->query("SELECT state from sub_aria_permition where sub_admin_id = ".$admin_data['id'])) {
                             $m=0;
                             while($row = $result->fetch_assoc()) {
                               $area[$m]=$row['state'];
                               $m++;
                             }
                       }
                       if($result = $conn->query("SELECT id,name,address,country,state,city,phone_num,email,contact_name,block_status from corporate_details where status =1 AND state IN (" . implode(',', array_map('intval', $area)) . ")")) {
                               $i=0;
                               while($row = $result->fetch_assoc()) {
                                      $res=$conn->query("SELECT name from a_main where id = ".$row['country']);
                                      $country=$res->fetch_assoc();
                                      $res=$conn->query("SELECT name from a_main where id = ".$row['state']);
                                      $state=$res->fetch_assoc();
                                      $res=$conn->query("SELECT name from a_main where id = ".$row['city']);
                                      $city=$res->fetch_assoc();
                                       $myArray[$i]['id'] = $row['id'];
                                       $myArray[$i]['name'] = $row['name'];
                                       $myArray[$i]['address'] = $row['address'];
                                       $myArray[$i]['email'] = $row['email'];
                                       $myArray[$i]['phone_num'] = $row['phone_num'];
                                       $myArray[$i]['contact_name'] = $row['contact_name'];
                                       $myArray[$i]['country'] = $country['name'];
                                       $myArray[$i]['state'] = $state['name'];
                                       $myArray[$i]['city'] = $city['name'];
                                       $myArray[$i]['status']=$row['block_status'];
                                      //  $myArray[$i]['created_at'] = $row['created_at'];
                                  $i++;
                               }
                               $data = array("status"=>1, "data"=>$myArray);
                               return $response->withJson($myArray);
                       }
                      case 'DI':

                      if($result = $conn->query("SELECT city from sub_aria_permition where sub_admin_id = ".$admin_data['id'])) {
                             $m=0;
                             while($row = $result->fetch_assoc()) {
                               $area[$m]=$row['city'];
                               $m++;
                             }
                       }
                       if($result = $conn->query("SELECT id,name,address,country,state,city,phone_num,email,contact_name,block_status from corporate_details where status =1 AND city IN (" . implode(',', array_map('intval', $area)) . ")")) {
                               $i=0;
                               while($row = $result->fetch_assoc()) {
                                      $res=$conn->query("SELECT name from a_main where id = ".$row['country']);
                                      $country=$res->fetch_assoc();
                                      $res=$conn->query("SELECT name from a_main where id = ".$row['state']);
                                      $state=$res->fetch_assoc();
                                      $res=$conn->query("SELECT name from a_main where id = ".$row['city']);
                                      $city=$res->fetch_assoc();
                                       $myArray[$i]['id'] = $row['id'];
                                       $myArray[$i]['name'] = $row['name'];
                                       $myArray[$i]['address'] = $row['address'];
                                       $myArray[$i]['email'] = $row['email'];
                                       $myArray[$i]['phone_num'] = $row['phone_num'];
                                       $myArray[$i]['contact_name'] = $row['contact_name'];
                                       $myArray[$i]['country'] = $country['name'];
                                       $myArray[$i]['state'] = $state['name'];
                                       $myArray[$i]['city'] = $city['name'];
                                       $myArray[$i]['status']=$row['block_status'];
                                      //  $myArray[$i]['created_at'] = $row['created_at'];
                                  $i++;
                               }
                               $data = array("status"=>1, "data"=>$myArray);
                               return $response->withJson($myArray);
                       }
                      case 'CT':
                      if($result = $conn->query("SELECT distric from sub_aria_permition where sub_admin_id = ".$admin_data['id'])) {
                             $m=0;
                             while($row = $result->fetch_assoc()) {
                               $area[$m]=$row['distric'];
                               $m++;
                             }
                       }
                       if($result = $conn->query("SELECT id,name,address,country,state,city,phone_num,email,contact_name,block_status from corporate_details where status =1 AND distric IN (" . implode(',', array_map('intval', $area)) . ")")) {
                               $i=0;
                               while($row = $result->fetch_assoc()) {
                                      $res=$conn->query("SELECT name from a_main where id = ".$row['country']);
                                      $country=$res->fetch_assoc();
                                      $res=$conn->query("SELECT name from a_main where id = ".$row['state']);
                                      $state=$res->fetch_assoc();
                                      $res=$conn->query("SELECT name from a_main where id = ".$row['city']);
                                      $city=$res->fetch_assoc();
                                       $myArray[$i]['id'] = $row['id'];
                                       $myArray[$i]['name'] = $row['name'];
                                       $myArray[$i]['address'] = $row['address'];
                                       $myArray[$i]['email'] = $row['email'];
                                       $myArray[$i]['phone_num'] = $row['phone_num'];
                                       $myArray[$i]['contact_name'] = $row['contact_name'];
                                       $myArray[$i]['country'] = $country['name'];
                                       $myArray[$i]['state'] = $state['name'];
                                       $myArray[$i]['city'] = $city['name'];
                                       $myArray[$i]['status']=$row['block_status'];
                                      //  $myArray[$i]['created_at'] = $row['created_at'];
                                  $i++;
                               }
                               $data = array("status"=>1, "data"=>$myArray);
                               return $response->withJson($myArray);
                       }
              }
            }
  }catch(Exception $e){
    $msg = array("success" => 0,'mas'=>$e);
    return $response->withJson(array('response'=>$msg));
  }
 return $response->withJson(array('success'=>0,'msg'=>'something went wrong'));
}

});

$app->post('/corporate_block', function($request,$response) {
      $admin_data = $request->getParsedBody();
      $cheak = cheak_token($admin_data['id'],$admin_data['token']);

      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
      $conn = sqlConnection();
      if($result = $conn->query("SELECT corporater from module_permition where sub_admin_id = ".$admin_data['id'])) {

               while($row = $result->fetch_assoc()) {
                 $module_permition[] =$row;
               }

      }

      if($module_permition[0]['corporater'] == 1001 OR $module_permition[0]['corporater'] == 1011 OR $module_permition[0]['corporater'] == 1111 OR $module_permition[0]['corporater'] == 1101 ){

          if($result = $conn->query("SELECT block_status from corporate_details where id = ".$admin_data['corporate_id'])) {
              while($row = $result->fetch_assoc()) {
                    $myArray[] = $row;
              }
           }
           if($myArray[0]['block_status']==0){
             try{
                   $result=$conn->prepare("UPDATE corporate_details set block_status = 1 where id= ?");
                   // $resule->bind_param('s', $name);
                   $result->bind_param('s', $admin_data['corporate_id']);
                   $result->execute();
                   $result->store_result();
                   $result->close();
                   mysqli_close($conn);
                   $msg = array('success' => 1,'msg'=>'corporater will enable');
                   return $response->withJson($msg);
              }catch(Exception $e){
                $result->close();
                mysqli_close($conn);
                $msg =array('success'=>0,'msg'=>$e);
                return $response->withJson($msg);
              }
              $result->close();
              mysqli_close($conn);
              $msg = array('success' => 0,'msg'=>'enable to read data');
              return $response->withJson($msg);

           }else{
               try{
                     $result=$conn->prepare("UPDATE corporate_details set block_status = 0 where id= ?");
                     // $resule->bind_param('s', $name);
                     $result->bind_param('s', $admin_data['corporate_id']);
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
                $msg = array('success' => 1,'msg'=>'corporater will disable');
                return $response->withJson($msg);
           }
           }


      $msg = array("success" => 0,'msg'=>'not permission to remove');
      return $response->withJson($msg);

});

$app->post('/corporate_edit', function($request,$response) {

      $admin_data = $request->getParsedBody();
      // $file = fopen("corporater.txt","w");
      // echo fwrite($file,json_encode($admin_data));
      // fclose($file);
      // $msg = array("success" => 1);
      // return $response->withJson($msg);
      $cheak = cheak_token($admin_data['id'],$admin_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }

      $conn =sqlConnection();
      if($result = $conn->query("SELECT corporater from module_permition where sub_admin_id = ".$admin_data['id'])) {

               while($row = $result->fetch_assoc()) {
                 $module_permition[] =$row;
               }
      }

      if($module_permition[0]['corporater']==1110 OR $module_permition[0]['corporater']==1011 OR $module_permition[0]['corporater']==1010 OR $module_permition[0]['corporater']==1111){

            if($result=$conn->query("SELECT * from corporate_details where id= ".$admin_data['corporate_id'])){

                     while($row = $result->fetch_assoc()){
                           $corporate_data[]=$row;

                     }
             return $response->withJson(array('opt'=>'edit','data'=>$corporate_data));

           }
      }else{
           return $response->withJson(array('success'=>0,'msg'=>'not permission'));
      }

  $msg=array('success'=>0,'data'=>'something went wrong');
  return $response->withJson($msg);

});

$app->post('/corporate_view', function($request,$response) {

     $admin_data = $request->getParsedBody();
     $cheak = cheak_token($admin_data['id'],$admin_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }

      $conn = sqlConnection();
      if($result = $conn->query("SELECT corporater from module_permition where sub_admin_id = ".$admin_data['id'])) {

               while($row = $result->fetch_assoc()) {
                 $module_permition[] =$row;
               }
      }
      if($module_permition[0]['corporater'] == 00){
              $result->close;
              mysqli_close($conn);
              $msg = array("success" => 0,'mas'=>'admin no permition');
              return $response->withJson($msg);
        }
try{
          if($result = $conn->query("SELECT id,name,address,country,state,city,distric,sub_diatric,phone_num,phone_num1,email,contact_name,created_at,block_status from corporate_details where status =1 AND id =".$admin_data['corporate_id'])) {
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
                    $myArray[$i]['id'] = $row['id'];
                    $myArray[$i]['name'] = $row['name'];
                    $myArray[$i]['address'] = $row['address'];
                    $myArray[$i]['email'] = $row['email'];
                    $myArray[$i]['phone_num'] = $row['phone_num'];
                    $myArray[$i]['phone_num1'] = $row['phone_num1'];
                    $myArray[$i]['contact_name'] = $row['contact_name'];
                    $myArray[$i]['country'] = $country['name'];
                    $myArray[$i]['state'] = $state['name'];
                    $myArray[$i]['city'] = $city['name'];
                    $myArray[$i]['distric'] = $distric['name'];
                    $myArray[$i]['sub_diatric'] = $row['sub_diatric'];
                    $myArray[$i]['created_at'] = $row['created_at'];
                   //  $myArray[$i]['created_at'] = $row['created_at'];
               $i++;
            }
            return $response->withJson($myArray);
        }

      }catch(Exception $e){
        $msg = array("success" => 0,'mas'=>$e);
        return $response->withJson(array('response'=>$msg));
      }
     return $response->withJson(array('success'=>0,'mag'=>'something  get wrong'));

});
$app->post('/corporate_remove', function($request,$response) {
      $admin_data = $request->getParsedBody();
      $cheak = cheak_token($admin_data['id'],$admin_data['token']);

      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
      $conn = sqlConnection();
      if($result = $conn->query("SELECT corporater from module_permition where sub_admin_id = ".$admin_data['id'])) {

               while($row = $result->fetch_assoc()) {
                 $module_permition[] =$row;
               }
      }

      if($module_permition[0]['corporater'] == 1001 OR $module_permition[0]['corporater'] == 1011 OR $module_permition[0]['corporater'] == 1111 OR $module_permition[0]['corporater'] == 1101 ){

        try{
              $result=$conn->prepare("UPDATE corporate_details set status = 0 where id= ?");
              // $resule->bind_param('s', $name);
              $result->bind_param('s', $admin_data['corporate_id']);
              $result->execute();
              $result->store_result();
              $result->close();
              mysqli_close($conn);
              $msg = array('success' => 1,'msg'=>'corporater will enable');
              return $response->withJson($msg);
         }catch(Exception $e){
           $result->close();
           mysqli_close($conn);
           $msg =array('success'=>0,'msg'=>$e);
           return $response->withJson($msg);
         }
         $result->close();
         mysqli_close($conn);
         $msg = array('success' => 0,'msg'=>'enable to read data');
         return $response->withJson($msg);

      }
      $result->close();
      mysqli_close($conn);
      $msg = array('success' => 0,'msg'=>'enable to read data');
      return $response->withJson($msg);
});

?>
