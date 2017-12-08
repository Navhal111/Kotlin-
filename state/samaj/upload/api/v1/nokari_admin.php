<?php
$app->post('/admin_nokari_list', function($request,$response,$args) {
       $admin_data = $request->getParsedBody();
      //  $file = fopen("nokari_list_admin.txt","w");
      //  echo fwrite($file,json_encode($admin_data));
      //  fclose($file);
       $cheak = cheak_token($admin_data['id'],$admin_data['token']);

       if($cheak['success'] == 0){
         $msg = array("success" => 0,'msg'=>'unotherise');
         return $response->withJson($msg);
       }
       if(!isset($admin_data['Userid'])){

           return $response->withJson(array('success'=>0,'msg'=>'not get any data'));
       }
       $conn = sqlConnection();
             if($result = $conn->query("SELECT role,noklari from module_permition where sub_admin_id = ".$admin_data['id'])) {
                      while($row = $result->fetch_assoc()) {
                        $module_permition[] =$row;
                      }
             }
             if($module_permition[0]['noklari'] == 00){
                     $result->close;
                     mysqli_close($conn);
                     $msg = array("success" => 0,'mas'=>'admin no permition');
                     return $response->withJson($msg);
                }
          if($nokariresult = $conn->query("SELECT id,Name,Role,contact,Email,block_status FROM Nikari_user where status=1 AND gen_id = ".$admin_data['Userid'])){
                  if($nokariresult->num_rows == 0){

                    $msg=array('success'=>2,'msg'=>'No profile found');
                    return $response->withJson($msg);
                  }

                   while($nokarirow=$nokariresult->fetch_assoc()){
                     $Nokari_profile[]=$nokarirow;
                   }
                  $msg=array('success'=>1,'data'=>$Nokari_profile);
                  return $response->withJson($msg);
                }
$msg=array('success'=>1,'msg'=>'somthing went wrong');
return $response->withJson($msg);

});

$app->post('/admin_nokari_view', function($request,$response,$args) {
         $admin_data = $request->getParsedBody();
        //  $file = fopen("nokari_view_admin.txt","w");
        //  echo fwrite($file,json_encode($admin_data));
        //  fclose($file);
         $cheak = cheak_token($admin_data['id'],$admin_data['token']);
         if($cheak['success'] == 0){
           $msg = array("success" => 0,'msg'=>'unotherise');
           return $response->withJson($msg);
         }

         $conn = sqlConnection();
               if($result = $conn->query("SELECT role,noklari from module_permition where sub_admin_id = ".$admin_data['id'])) {
                        while($row = $result->fetch_assoc()) {
                          $module_permition[] =$row;
                        }
               }
               if($module_permition[0]['noklari'] == 0000){
                       $result->close;
                       mysqli_close($conn);
                       $msg = array("success" => 0,'mas'=>'admin no permition');
                       return $response->withJson($msg);
                   }
              if(!isset($admin_data['nokariid']) OR empty($admin_data['nokariid'])){
                       $msg = array("success" => 0,'mas'=>'admimn have not get data');
                       return $response->withJson($msg);

                   }

      if($profileresult=$conn->query("SELECT id,Name,Email,DOB,Contact,Education,Experience,Role,Description,Adress,Pincode,country,state,city,block_status,sub_cat,main_cat,Payment,End_date,Start_date FROM Nikari_user where status =1 AND id =".$admin_data['nokariid']." AND gen_id = ".$admin_data['Userid'])){

                     if($profileresult->num_rows <= 0){
                       $msg=array('success'=>2,'msg'=>'No profile found');
                       return $response->withJson($msg);
                     }
                  $i=0;
                      while($profilerow=$profileresult->fetch_assoc()){
                        $profilesdata[$i]['id']=$profilerow['id'];
                        $profilesdata[$i]['Name']=$profilerow['Name'];
                        $profilesdata[$i]['Email']=$profilerow['Email'];
                        $profilesdata[$i]['DOB']=$profilerow['DOB'];
                        $profilesdata[$i]['Contact']=$profilerow['Contact'];
                        $profilesdata[$i]['Start_date']=$profilerow['Start_date'];
                        $profilesdata[$i]['End_date']=$profilerow['End_date'];
                        $profilesdata[$i]['Amount_payment']=$profilerow['Payment'];
                        $profilesdata[$i]['Days_left']=(int)((strtotime($profilerow['End_date'])-strtotime($profilerow['Start_date']))/86400);
                        if($Edures=$conn->query("SELECT education from education_met where id = ".$profilerow['Education'])){

                          $Eduname=$Edures->fetch_assoc();
                        }
                        $profilesdata[$i]['Education']=$Eduname['education'];
                        $profilesdata[$i]['Experience']=$profilerow['Experience'];
                        $profilesdata[$i]['Role']=$profilerow['Role'];
                        $mainres=$conn->query("SELECT business_Name from BusinessMain_cat where id = ".$profilerow['main_cat']);
                        $main_cat=$mainres->fetch_assoc();
                        $profilesdata[$i]['main_cat']=$main_cat['business_Name'];
                        $subres=$conn->query("SELECT busness_name from busness_catagery where id = ".$profilerow['sub_cat']);
                        $sub_cat=$subres->fetch_assoc();
                        $profilesdata[$i]['sub_cat']=$sub_cat['busness_name'];
                        $profilesdata[$i]['Description']=$profilerow['Description'];
                        $profilesdata[$i]['Adress']=$profilerow['Adress'];
                        $profilesdata[$i]['Adress']=$profilerow['Adress'];
                        $profilesdata[$i]['Pincode']=$profilerow['Pincode'];
                        $profilesdata[$i]['country']=$profilerow['country'];
                        $profilesdata[$i]['state']=$profilerow['state'];
                        $profilesdata[$i]['city']=$profilerow['city'];
                        $profilesdata[$i]['block_status']=$profilerow['block_status'];
                        $res=$conn->query("SELECT name from a_main where id = ".$profilerow['country']);
                        $country=$res->fetch_assoc();
                        $res=$conn->query("SELECT name from a_main where id = ".$profilerow['state']);
                        $state=$res->fetch_assoc();
                        $res=$conn->query("SELECT name from a_main where id = ".$profilerow['city']);
                        $city=$res->fetch_assoc();
                        $profilesdata[$i]['country']=$country['name'];
                        $profilesdata[$i]['state']=$state['name'];
                        $profilesdata[$i]['city']=$city['name'];
                        $i++;
                      }
                    $msg = array('success'=>1,'data'=>$profilesdata);
                    return $response->withJson($msg);
                   }

                   $msg = array('success'=>2,'data'=>'somting went wrong');
                   return $response->withJson($msg);

});
$app->post('/admin_nokari_block', function($request,$response,$args) {
         $admin_data = $request->getParsedBody();
         $cheak = cheak_token($admin_data['id'],$admin_data['token']);
         if($cheak['success'] == 0){
           $msg = array("success" => 0,'msg'=>'unotherise');
           return $response->withJson($msg);
         }
         $conn = sqlConnection();

               if($result = $conn->query("SELECT role,noklari from module_permition where sub_admin_id = ".$admin_data['id'])) {
                        while($row = $result->fetch_assoc()) {
                          $module_permition[] =$row;
                        }
               }

               if($module_permition[0]['noklari'] == 0000){
                       $result->close;
                       mysqli_close($conn);
                       $msg = array("success" => 0,'msg'=>'admin no permition');
                       return $response->withJson($msg);
                   }

          if($module_permition[0]['noklari'] == 11){
                if($result = $conn->query("SELECT block_status from Nikari_user where id = ".$admin_data['nokariid']." AND gen_id=".$admin_data['Userid'])) {
                    while($row = $result->fetch_assoc()) {
                          $myArray[] = $row;
                    }
                 }

                 if($myArray[0]['block_status']==0){
                   try{
                         $result=$conn->prepare("UPDATE Nikari_user set block_status = 1 where id= ? AND gen_id=".$admin_data['Userid']);
                         $result->bind_param('s', $admin_data['nokariid']);
                         $result->execute();
                         $result->store_result();
                         $result->close();
                         mysqli_close($conn);
                         $msg = array('success' => 1,'msg'=>' Job profile will enable');
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
                           $result=$conn->prepare("UPDATE Nikari_user set block_status = 0 where id= ?  AND gen_id=".$admin_data['Userid']);

                           $result->bind_param('s', $admin_data['nokariid']);
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
                      $msg = array('success' => 1,'msg'=>'job profile will disable');
                      return $response->withJson($msg);
                 }
              }
              mysqli_close($conn);
              $msg = array("success" => 0,'msg'=>'somthing went wrong');
              return $response->withJson($msg);
 });
?>
