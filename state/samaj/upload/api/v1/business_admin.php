<?php
$app->post('/admin_business_list', function($request,$response,$args) {
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
             if($result = $conn->query("SELECT role,bissness from module_permition where sub_admin_id = ".$admin_data['id'])) {
                      while($row = $result->fetch_assoc()) {

                        $module_permition[] =$row;
                      }
             }
             if($module_permition[0]['bissness'] == 00){
                     $result->close;
                     mysqli_close($conn);
                     $msg = array("success" => 0,'mas'=>'admin no permition');
                     return $response->withJson($msg);
                 }

             if($result=$conn->query("SELECT id,CompannyName,CompanylogoString,Contact,Address,OwnerName,block_status,BusinessDiscription from business_profile where status =1 AND user_id=".$admin_data['Userid'])){

                   if($result->num_rows == 0){
                     $msg= array('success'=>3,'mag'=>'not have profile');
                     return $response->withJson($msg);
                   }

                 while($row=$result->fetch_assoc()){
                    $met_profile_data[]=$row;
                 }

                 $result->close();
                 mysqli_close($conn);
                 $msg= array('success'=>1,'data'=>$met_profile_data);
                 return $response->withJson($msg);
             }

             $msg = array("success" => 0,'mas'=>'admin no permition');
             return $response->withJson($msg);
});

$app->post('/admin_business_view', function($request,$response,$args) {
         $admin_data = $request->getParsedBody();
        //  $file = fopen("jobview.txt","w");
        //  echo fwrite($file,json_encode($admin_data));
        //  fclose($file);
         $cheak = cheak_token($admin_data['id'],$admin_data['token']);
         if($cheak['success'] == 0){
           $msg = array("success" => 0,'msg'=>'unotherise');
           return $response->withJson($msg);
         }
         $conn = sqlConnection();
               if($result = $conn->query("SELECT role,bissness from module_permition where sub_admin_id = ".$admin_data['id'])) {
                        while($row = $result->fetch_assoc()) {
                          $module_permition[] =$row;
                        }
               }
               if($module_permition[0]['bissness'] == 0000){
                       $result->close;
                       mysqli_close($conn);
                       $msg = array("success" => 0,'mas'=>'admin no permition');
                       return $response->withJson($msg);
                   }
                   if(!isset($admin_data['businessid']) OR empty($admin_data['businessid'])){
                       $msg = array("success" => 0,'mas'=>'admimn have not get data');
                       return $response->withJson($msg);

                   }
                   if($business_data=$conn->query("SELECT * from business_profile where id=".$admin_data['businessid']." AND status =1 ")){

            $i=0;
            if($business_data->num_rows <= 0 ){
                      $msg = array("success" => 2,'msg'=>'not');
                      return $response->withJson($msg);
            }

            while($business=$business_data->fetch_assoc()){
            	  $bussness_profiles[$i]['id']=$business['id'];
                  $bussness_profiles[$i]['CompannyName']=$business['CompannyName'];
                  $bussness_profiles[$i]['OwnerName']=$business['OwnerName'];
                  $bussness_profiles[$i]['Contact']=$business['Contact'];
                  $bussness_profiles[$i]['Address']=$business['Address'];
                  $bussness_profiles[$i]['CompanylogoString']=$business['CompanylogoString'];
                  $bussness_profiles[$i]['Website']=$business['Website'];
                  $bussness_profiles[$i]['BusinessLink']=$business['BusinessLink'];
                  $bussness_profiles[$i]['BusinessDiscription']=$business['BusinessDiscription'];
                  $bussness_profiles[$i]['block_status']=$business['block_status'];
                  $bussness_profiles[$i]['contact_2']=$business['contact_2'];
                  $bussness_profiles[$i]['created_at']=$business['created_at'];
                  $res=$conn->query("SELECT name from a_main where id = ".$business['country']);
                  $country=$res->fetch_assoc();
                  $res=$conn->query("SELECT name from a_main where id = ".$business['state']);
                  $state=$res->fetch_assoc();
                  $res=$conn->query("SELECT name from a_main where id = ".$business['city']);
                  $city=$res->fetch_assoc();
                  $bussness_profiles[$i]['Country']=$country['name'];
                  $bussness_profiles[$i]['State']=$state['name'];
                  $bussness_profiles[$i]['City']=$city['name'];
                  $bussness_profiles[$i]['Start_date']=$business['Start_date'];
                  $bussness_profiles[$i]['End_date']=$business['End_date'];
                  $bussness_profiles[$i]['Amount_payment']=$business['payment'];
                  $bussness_profiles[$i]['Days_left']=(int)((strtotime($business['End_date'])-strtotime($business['Start_date']))/86400);
                  if($categery_main=$conn->query("SELECT business_Name from BusinessMain_cat where id=".$business['business_main'])){
                     $main = $categery_main->fetch_assoc();
                     $bussness_profiles[$i]['Business_main']=$main['business_Name'];

                  }

                  if($categery_data=$conn->query("SELECT * from BusinessCategary_id where business_profile=".$business['id'])){
                     $d=0;

                    while($row2=$categery_data->fetch_assoc()){

                      $catageryname = $conn->query("SELECT busness_name from busness_catagery where id=".$row2['Categary_id']);
                      $row3=$catageryname->fetch_assoc();
                      $bussness_profiles[$i]['busness_catagery'][$d]=$row3['busness_name'];
                      $d++;
                    }
                  }
                $i++;
            }
            mysqli_close($conn);
            return $response->withJson(array('success' => 1,'data'=>$bussness_profiles));

       }
                   mysqli_close($conn);
            $msg = array('success' => 0,'msg'=>'not found');
            return $response->withJson($msg);
});

$app->post('/admin_business_block', function($request,$response,$args) {
         $admin_data = $request->getParsedBody();
         $cheak = cheak_token($admin_data['id'],$admin_data['token']);
         if($cheak['success'] == 0){
           $msg = array("success" => 0,'msg'=>'unotherise');
           return $response->withJson($msg);
         }
         $conn = sqlConnection();

               if($result = $conn->query("SELECT role,bissness from module_permition where sub_admin_id = ".$admin_data['id'])) {
                        while($row = $result->fetch_assoc()) {
                          $module_permition[] =$row;
                        }
               }

               if($module_permition[0]['bissness'] == 0000){
                       $result->close;
                       mysqli_close($conn);
                       $msg = array("success" => 0,'mas'=>'admin no permition');
                       return $response->withJson($msg);
                   }

          if($module_permition[0]['bissness'] == 11){


                if($result = $conn->query("SELECT block_status from business_profile where id = ".$admin_data['businessid']." AND user_id=".$admin_data['Userid'])) {
                    while($row = $result->fetch_assoc()) {
                          $myArray[] = $row;
                    }
                 }
                 if($myArray[0]['block_status']==0){
                   try{
                         $result=$conn->prepare("UPDATE business_profile set block_status = 1 where id= ? AND user_id=".$admin_data['Userid']);
                         // $resule->bind_param('s', $name);
                         $result->bind_param('s', $admin_data['businessid']);
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
                           $result=$conn->prepare("UPDATE business_profile set block_status = 0 where id= ?  AND user_id=".$admin_data['Userid']);
                           // $resule->bind_param('s', $name);
                           $result->bind_param('s', $admin_data['businessid']);
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

              $msg = array("success" => 0,'mas'=>'admin no permition');
              return $response->withJson($msg);
 });
?>
