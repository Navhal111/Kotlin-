<?php
$app->post('/admin_Blood_list', function($request,$response,$args) {
       $admin_data = $request->getParsedBody();
       $cheak = cheak_token($admin_data['id'],$admin_data['token']);

       if($cheak['success'] == 0){
         $msg = array("success" => 0,'msg'=>'unotherise');
         return $response->withJson($msg);
       }
       if(!isset($admin_data['Userid'])){

           return $response->withJson(array('success'=>0,'msg'=>'not get any data'));
       }

       $conn = sqlConnection();
             if($result = $conn->query("SELECT role,blood_doner from module_permition where sub_admin_id = ".$admin_data['id'])) {
                      while($row = $result->fetch_assoc()) {

                        $module_permition[] =$row;
                      }
             }
             if($module_permition[0]['blood_doner'] == 00){
                     $result->close;
                     mysqli_close($conn);
                     $msg = array("success" => 0,'mas'=>'admin no permition');
                     return $response->withJson($msg);
                }

               if($result=$conn->query("SELECT id,DonerName,Gender,BloodGroup,block_status from Blood_Donation where status =1 AND user_id=".$admin_data['Userid'])){
                   if($result->num_rows == 0){
                     $msg= array('success'=>3,'mag'=>'not have profile');
                     return $response->withJson($msg);
                   }

                 while($row=$result->fetch_assoc()){
                    $blood_profile_data[]=$row;
                 }

                 $result->close();
                 mysqli_close($conn);
                 $msg= array('success'=>1,'data'=>$blood_profile_data);
                 return $response->withJson($msg);
             }

             $msg = array("success" => 0,'mas'=>'admin no permition');
             return $response->withJson($msg);

});

$app->post('/admin_Blood_view', function($request,$response,$args) {
       $admin_data = $request->getParsedBody();
       $cheak = cheak_token($admin_data['id'],$admin_data['token']);

       if($cheak['success'] == 0){
         $msg = array("success" => 0,'msg'=>'unotherise');
         return $response->withJson($msg);
       }
       if(!isset($admin_data['Userid'])){

           return $response->withJson(array('success'=>0,'msg'=>'not get any data'));
       }

       $conn = sqlConnection();
             if($result = $conn->query("SELECT role,blood_doner from module_permition where sub_admin_id = ".$admin_data['id'])) {
                      while($row = $result->fetch_assoc()) {

                        $module_permition[] =$row;
                      }
             }

             if($module_permition[0]['blood_doner'] == 00){
                     $result->close;
                     mysqli_close($conn);
                     $msg = array("success" => 0,'mas'=>'admin no permition');
                     return $response->withJson($msg);
                }
                   if(!isset($admin_data['blood_id']) OR empty($admin_data['blood_id'])){
                       $msg = array("success" => 0,'mas'=>'admimn have not get data');
                       return $response->withJson($msg);

                   }
      if($blood_data=$conn->query("SELECT * from Blood_Donation where id=".$admin_data['blood_id']." AND status =1 AND user_id=".$admin_data['Userid'])){
            $i=0;
            if($blood_data->num_rows <= 0 ){
                      $msg = array("success" => 2,'msg'=>'not found');
                    return $response->withJson($msg);
            }
             while($blood=$blood_data->fetch_assoc()){
               $blood_view[$i]['id']=$blood['id'];
               $blood_view[$i]['DonerName']=$blood['DonerName'];
               $blood_view[$i]['BloodGroup']=$blood['BloodGroup'];
               $blood_view[$i]['Gender']=$blood['Gender'];
               $blood_view[$i]['dob']=$blood['dob'];
               $blood_view[$i]['DonerWeight']=$blood['DonerWeight'];
               $blood_view[$i]['Contact']=$blood['Contact'];
               $blood_view[$i]['Address']=$blood['Address'];
               $blood_view[$i]['Contact_2']=$blood['Contact_2'];
               $blood_view[$i]['created_at']=$blood['created_at'];
                  $res=$conn->query("SELECT name from a_main where id = ".$blood['country']);
                  $country=$res->fetch_assoc();
                  $res=$conn->query("SELECT name from a_main where id = ".$blood['state']);
                  $state=$res->fetch_assoc();
                  $res=$conn->query("SELECT name from a_main where id = ".$blood['city']);
                  $city=$res->fetch_assoc();
                  $res=$conn->query("SELECT name from a_main where id = ".$blood['district']);
                  $distric=$res->fetch_assoc();
                  $blood_view[$i]['Country']=$country['name'];
                  $blood_view[$i]['State']=$state['name'];
                  $blood_view[$i]['City']=$city['name'];
                  $blood_view[$i]['distric']=$distric['name'];
                 $i++;

             }
             $msg = array('success'=>1,'data'=>$blood_view);
             return $response->withJson($msg);
          }

             $msg = array('success'=>0,'msg'=>$sql);
             return $response->withJson($msg);
 });

 $app->post('/admin_Blood_block', function($request,$response,$args) {
       $admin_data = $request->getParsedBody();
       $cheak = cheak_token($admin_data['id'],$admin_data['token']);

       if($cheak['success'] == 0){
         $msg = array("success" => 0,'msg'=>'unotherise');
         return $response->withJson($msg);
       }
       if(!isset($admin_data['Userid'])){

           return $response->withJson(array('success'=>0,'msg'=>'not get any data'));
       }

       $conn = sqlConnection();
             if($result = $conn->query("SELECT role,blood_doner from module_permition where sub_admin_id = ".$admin_data['id'])) {
                      while($row = $result->fetch_assoc()) {

                        $module_permition[] =$row;
                      }
             }

             if($module_permition[0]['blood_doner'] == 00){
                     $result->close;
                     mysqli_close($conn);
                     $msg = array("success" => 0,'mas'=>'admin no permition');
                     return $response->withJson($msg);
                }

            if($module_permition[0]['blood_doner'] == 11){

                if($result = $conn->query("SELECT block_status from Blood_Donation where id = ".$admin_data['blood_id']." AND user_id=".$admin_data['Userid'])) {
                    while($row = $result->fetch_assoc()) {
                          $myArray[] = $row;
                    }
                 }
                 if($myArray[0]['block_status']==0){
                   try{
                         $result=$conn->prepare("UPDATE Blood_Donation set block_status = 1 where id= ? AND user_id=".$admin_data['Userid']);
                         // $resule->bind_param('s', $name);
                         $result->bind_param('s', $admin_data['blood_id']);
                         $result->execute();
                         $result->store_result();
                         $result->close();
                         mysqli_close($conn);
                         $msg = array('success' => 1,'msg'=>'profile will enable');
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
                           $result=$conn->prepare("UPDATE Blood_Donation set block_status = 0 where id= ?  AND user_id=".$admin_data['Userid']);
                           // $resule->bind_param('s', $name);
                           $result->bind_param('s', $admin_data['blood_id']);
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
                      $msg = array('success' => 1,'msg'=>'profile will disable');
                      return $response->withJson($msg);
                 }



                }
});


?>
