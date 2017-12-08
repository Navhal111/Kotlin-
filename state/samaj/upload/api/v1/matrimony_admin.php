<?php
$app->post('/admin_matrimony_list', function($request,$response,$args) {
       $admin_data = $request->getParsedBody();
       $cheak = cheak_token($admin_data['id'],$admin_data['token']);

       if($cheak['success'] == 0){
         $msg = array("success" => 0,'msg'=>'unotherise');
         return $response->withJson($msg);
       }
       $conn = sqlConnection();
             if($result = $conn->query("SELECT role,matrimony from module_permition where sub_admin_id = ".$admin_data['id'])) {
                      while($row = $result->fetch_assoc()) {

                        $module_permition[] =$row;
                      }
             }
             if($module_permition[0]['matrimony'] == 0000){
                     $result->close;
                     mysqli_close($conn);
                     $msg = array("success" => 0,'mas'=>'admin no permition');
                     return $response->withJson($msg);
                 }

             $conn =sqlConnection();
             if($result=$conn->query("SELECT id,RegisterFullname,RegisterProfileImageString,RegisterSubCast,RegisterAge,block_status  from profile_met where status = 1 AND gen_id=".$admin_data['Userid'])){
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

  $app->post('/admin_matrimony_view', function($request,$response,$args) {
         $admin_data = $request->getParsedBody();
         $cheak = cheak_token($admin_data['id'],$admin_data['token']);
         if($cheak['success'] == 0){
           $msg = array("success" => 0,'msg'=>'unotherise');
           return $response->withJson($msg);
         }
         $conn = sqlConnection();
               if($result = $conn->query("SELECT role,matrimony from module_permition where sub_admin_id = ".$admin_data['id'])) {
                        while($row = $result->fetch_assoc()) {
                          $module_permition[] =$row;
                        }
               }
               if($module_permition[0]['matrimony'] == 0000){
                       $result->close;
                       mysqli_close($conn);
                       $msg = array("success" => 0,'mas'=>'admin no permition');
                       return $response->withJson($msg);
                   }
                   if($result=$conn->query("SELECT * from profile_met where status=1 AND block_status=1 AND id = ".$admin_data['profileid']." AND gen_id=".$admin_data['Userid'])){
                     $o=0;
                     while($row=$result->fetch_assoc()){
                       $res=$conn->query("SELECT name from a_main where id = ".$row['RegisterCountry']);
                       $country=$res->fetch_assoc();
                       $res=$conn->query("SELECT name from a_main where id = ".$row['RegisterState']);
                       $state=$res->fetch_assoc();
                       $res=$conn->query("SELECT name from a_main where id = ".$row['RegisterCity']);
                       $city=$res->fetch_assoc();
                       $res=$conn->query("SELECT name from a_main where id = ".$row['RegisterTaluka']);
                       $taluka=$res->fetch_assoc();
                       $res=$conn->query("SELECT education from education_met where id = ".$row['RegisterEducation']);
                       $edu=$res->fetch_assoc();
                       $res=$conn->query("SELECT occupation from occupation_met where id = ".$row['RegisterOccupation']);
                       $ocu=$res->fetch_assoc();
                       $profile_match_array[$o]=$row['id'];
                        $pro_seach[$o]['RegisterSurname']=$row['RegisterSurname'];
                        $pro_seach[$o]['RegisterSubCast']=$row['RegisterSubCast'];
                        $pro_seach[$o]['RegisterMaritalStatus']=$row['RegisterMaritalStatus'];
                        $pro_seach[$o]['RegisterProfileFor']=$row['RegisterProfileFor'];
                        $pro_seach[$o]['RegisterGender']=$row['RegisterGender'];
                        $pro_seach[$o]['RegisterFullname']=$row['RegisterFullname'];
                        $pro_seach[$o]['RegisterAddress']=$row['RegisterAddress'];
                        $pro_seach[$o]['RegisterPincode']=$row['RegisterPincode'];
                        $pro_seach[$o]['RegisterBdate']=$row['RegisterBdate'];
                        $pro_seach[$o]['RegisterBtime']=$row['RegisterBtime'];
                                     $arr = explode("/", $row['RegisterBdate']);        
              $arr1[0]=$arr[2];
              $arr1[1]=$arr[0];
              $arr1[2]=$arr[1];
             $birth_date = implode("-", $arr1);  
          $to   = new DateTime('today');
          $from   = new DateTime($birth_date);
          $Age= $from->diff($to)->y;
                        $pro_seach[$o]['RegisterAge']=$Age;
                        $pro_seach[$o]['RegisterHeight']=$row['RegisterHeight'];
                        $pro_seach[$o]['RegisterWeight']=$row['RegisterWeight'];
                        $pro_seach[$o]['RegisterHobby']=$row['RegisterHobby'];
                        $pro_seach[$o]['RegisterEducation']=$edu['education'];
                        $pro_seach[$o]['RegisterOccupation']=$ocu['occupation'];
                        $pro_seach[$o]['RegisterIncomeDuration']=$row['RegisterIncomeDuration'];
                        $pro_seach[$o]['RegisterIncome']=$row['RegisterIncome'];
                        $pro_seach[$o]['RegisterFatherName']=$row['RegisterFatherName'];
                        $pro_seach[$o]['RegisterFatherMobile']=$row['RegisterFatherMobile'];
                        $pro_seach[$o]['RegisterMotherName']=$row['RegisterMotherName'];
                        $pro_seach[$o]['RegisterMotherMobile']=$row['RegisterMotherMobile'];
                        $pro_seach[$o]['RegisterProfileEmail']=$row['RegisterProfileEmail'];
                        $pro_seach[$o]['Start_date']=$row['Payment_start'];
                        $pro_seach[$o]['End_date']=$row['Payment_end'];
                        $pro_seach[$o]['Amount_payment']=$row['Amount_payment'];
                        $pro_seach[$o]['Dyas_left']=(int)((strtotime($row['Payment_end'])-strtotime($row['Payment_start']))/86400);
                        $pro_seach[$o]['RegisterProfileMobile']=$row['RegisterProfileMobile'];
                        $pro_seach[$o]['RegisterProfileImageString']=$row['RegisterProfileImageString'];
                        $pro_seach[$o]['RegisterCountry']=$country['name'];
                        $pro_seach[$o]['RegisterState']=$state['name'];
                        $pro_seach[$o]['RegisterCity']=$city['name'];
                        $pro_seach[$o]['RegisterTaluka']=$taluka['name'];
                        $o++;

                     }
                     mysqli_close($conn);
                     $msg = array("success" => 1,'data'=>$pro_seach);
                     return $response->withJson($msg);

                   }

                   mysqli_close($conn);
                   $msg = array("success" => 0,'msg'=>'not found any match');
                 return $response->withJson($msg);
});

$app->post('/marimony_block', function($request,$response) {
  $admin_data = $request->getParsedBody();
  $cheak = cheak_token($admin_data['id'],$admin_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }

  $conn = sqlConnection();
  if($result = $conn->query("SELECT matrimony from module_permition where sub_admin_id = ".$admin_data['id'])) {
                 while($row = $result->fetch_assoc()) {
                   $module_permition[] =$row;
                 }
        }

        if($module_permition[0]['matrimony'] == 00){
                $result->close;
                mysqli_close($conn);
                $msg = array("success" => 0,'mas'=>'admin no permition');
                return $response->withJson($msg);
            }


            if($module_permition[0]['matrimony'] == 11){


                if($result = $conn->query("SELECT block_status from profile_met where id = ".$admin_data['profileid']." AND gen_id=".$admin_data['Userid'])) {
                    while($row = $result->fetch_assoc()) {
                          $myArray[] = $row;
                    }
                 }
                 if($myArray[0]['block_status']==0){
                   try{
                         $result=$conn->prepare("UPDATE profile_met set block_status = 1 where id= ? AND gen_id=".$admin_data['Userid']);
                         // $resule->bind_param('s', $name);
                         $result->bind_param('s', $admin_data['profileid']);
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
                           $result=$conn->prepare("UPDATE profile_met set block_status = 0 where id= ?  AND gen_id=".$admin_data['Userid']);
                           // $resule->bind_param('s', $name);
                           $result->bind_param('s', $admin_data['profileid']);
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
