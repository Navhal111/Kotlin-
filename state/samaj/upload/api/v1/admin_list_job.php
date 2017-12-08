<?php
$app->post('/admin_Job_list', function($request,$response,$args) {
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

           if($result=$conn->query("SELECT id,JobTitle,JobType,Role,Email,vacancy,block_status from JobPost where status =1 AND user_id=".$admin_data['Userid'])){

                if($result->num_rows == 0){
                     $msg= array('success'=>3,'msg'=>'not have profile');
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

             $msg = array("success" => 0,'msg'=>'somthing wrong');
             return $response->withJson($msg);
});

$app->post('/admin_job_view', function($request,$response,$args) {
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
                       $msg = array("success" => 0,'mas'=>'admin no permition');
                       return $response->withJson($msg);
               }
               $sql ="SELECT * from JobPost where id=".$admin_data['job_id']." AND status =1 ";
           // return $response->withJson(array('success'=>0,'msg'=>$sql));
          if($job_data=$conn->query("SELECT * from JobPost where id=".$admin_data['job_id']." AND status =1 ")){
            if($job_data->num_rows <= 0 ){
                      $msg = array("success" => 2,'msg'=>'not have data');
                    return $response->withJson($msg);
            }

            while($job_view=$job_data->fetch_assoc()){

            	$busness_name = $conn->query("SELECT CompannyName from business_profile where status=1 and block_status=1 and id = ".$job_view['business_id']);
          	    $business_profile = $busness_name->fetch_assoc();

          	  $job_post_data['busness_name']=$business_profile['CompannyName'];
              $job_post_data['JobTitle']=$job_view['JobTitle'];
              $job_post_data['JobType']=$job_view['JobType'];
              $job_post_data['Role']=$job_view['Role'];
              $job_post_data['Sub_category']=$job_view['sub_cat'];
              $job_post_data['salary_max']=$job_view['salary_max'];
              $job_post_data['salary_min']=$job_view['salary_min'];
              $job_post_data['Expriance_max']=$job_view['Expriance_max'];
              $job_post_data['Expiriance_min']=$job_view['Expiriance_min'];
              $job_post_data['Discription']=$job_view['Discription'];
              $job_post_data['vacancy']=$job_view['vacancy'];
              $job_post_data['Email']=$job_view['Email'];
              $job_post_data['Contact']=$job_view['Contact'];
              $job_post_data['Start_date']=$job_view['Start_date'];
              $job_post_data['End_date']=$job_view['End_date'];
              $job_post_data['Amount_payment']=$job_view['Payment'];
              $job_post_data['Days_left']=(int)((strtotime($job_view['End_date'])-strtotime($job_view['Start_date']))/86400);
                  $res=$conn->query("SELECT name from a_main where id = ".$job_view['country']);
                  $country=$res->fetch_assoc();
                  $res=$conn->query("SELECT name from a_main where id = ".$job_view['state']);
                  $state=$res->fetch_assoc();
                  $res=$conn->query("SELECT name from a_main where id = ".$job_view['city']);
                  $city=$res->fetch_assoc();
                  $res=$conn->query("SELECT name from a_main where id = ".$job_view['distric']);
                  $district=$res->fetch_assoc();
               $job_post_data['district']=$district['name'];
              $job_post_data['country']=$country['name'];
              $job_post_data['state']=$state['name'];
              $job_post_data['city']=$city['name'];
            }

            $msg = array('success'=>1,'data'=>$job_post_data);
            return $response->withJson($msg);
           }

     return $response->withJson(array('success'=>0,'msg'=>'error fetching data'));
});

$app->post('/admin_job_block', function($request,$response,$args) {
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
                       $msg = array("success" => 0,'mas'=>'admin no permition');
                       return $response->withJson($msg);
                   }

          if($module_permition[0]['noklari'] == 11){


                if($result = $conn->query("SELECT block_status from JobPost where id = ".$admin_data['Jobid']." AND user_id=".$admin_data['Userid'])) {
                    while($row = $result->fetch_assoc()) {
                          $myArray[] = $row;
                    }
                 }
                 if(!isset($myArray[0]['block_status'])){
                  mysqli_close($conn);
                 	return $response->withJson(array('success'=>2,'mag'=>'somthing went wrong'));

                 }
                 if($myArray[0]['block_status']==0){
                   try{
                         $result=$conn->prepare("UPDATE JobPost set block_status = 1 where id= ? AND user_id=".$admin_data['Userid']);
                         // $resule->bind_param('s', $name);
                         $result->bind_param('s', $admin_data['Jobid']);
                         $result->execute();
                         $result->store_result();
                         $result->close();
                         mysqli_close($conn);
                         $msg = array('success' => 1,'msg'=>'job post  will enable');
                         return $response->withJson($msg);
                    }catch(Exception $e){
                      $result->close();
                      mysqli_close($conn);
                      $msg =array('success'=>0,'msg'=>$e);
                      return $response->withJson($msg);
                    }
                    $result->close();
                    mysqli_close($conn);
                    $msg = array('success' => 0,'msg'=>'job post to read data');
                    return $response->withJson($msg);

                 }else{
                     try{
                           $result=$conn->prepare("UPDATE JobPost set block_status = 0 where id= ?  AND user_id=".$admin_data['Userid']);
                           // $resule->bind_param('s', $name);
                           $result->bind_param('s', $admin_data['Jobid']);
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
                      $msg = array('success' => 1,'msg'=>'job post will disable');
                      return $response->withJson($msg);
                 }
                 mysql_close($conn);
              }
              $msg = array("success" => 0,'mas'=>'admin no permition');
              return $response->withJson($msg);
 });

?>
