<?php
$app->post('/add_blood_doner',function($request,$response,$args){
  $user_data = $request->getParsedBody();
  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }

  $conn=sqlConnection();
  if($conn->query('INSERT INTO Blood_Donation(user_id,DonerName,BloodGroup,dob,DonerWeight,Gender,Contact,Contact_2,Email,Address,country,state,district,city) values ('.$user_data['id'].',"'.$user_data['DonerName'].'","'.$user_data['BloodGroup'].'","'.$user_data['dob'].'","'.$user_data['DonerWeight'].'","'.$user_data['Gender'].'","'.$user_data['Contact'].'","'.$user_data['Contact_2'].'","'.$user_data['Email'].'","'.$user_data['Address'].'",'.$user_data['country'].','.$user_data['state'].','.$user_data['distric'].','.$user_data['city'].')')){

    $msg = array("success" => 1,'msg'=>'Added blood donation profile');
    return $response->withJson(array('response'=>$msg));

  }

  $msg = array("success" => 0,'msg'=>'unotherise');
  return $response->withJson(array('response'=>$msg));

});

$app->post('/add_blood_req',function($request,$response,$args){
  $user_data = $request->getParsedBody();

         $file = fopen("add_blood_req.txt","w");
       echo fwrite($file,json_encode($user_data));
       fclose($file);
    $msg = array("success" => 0,'msg'=>'cheack');
    return $response->withJson($msg);


  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }

  $conn=sqlConnection();

  if($conn->query('INSERT INTO Blood_requirment(user_id,ReqName,BloodGroup,age,DonerWeight,Gender,Contact,Contact_2,Address,HospitalName,country,state,district,city) values ('.$user_data['id'].',"'.$user_data['ReqName'].'","'.$user_data['BloodGroup'].'","'.$user_data['dob'].'","'.$user_data['DonerWeight'].'","'.$user_data['Gender'].'","'.$user_data['Contact'].'","'.$user_data['Contact_2'].'","'.$user_data['Address'].'","'.$user_data['HospitalName'].'",'.$user_data['country'].','.$user_data['state'].','.$user_data['distric'].','.$user_data['city'].')')){
     

    if($donner_data=$conn->query('SELECT DonerName,Contact,Contact_2 FROM Blood_Donation WHERE status=1 AND block_status=1 AND BloodGroup ="'.$user_data['BloodGroup'].'" AND country ='.$user_data['country'].' AND state='.$user_data['state'].' AND city ='.$user_data['city'])){
         
         if($donner_data->num_rows <=0){

          $msg = array('success'=>2,'msg'=>'not any match found');
          return $response->withJson(array('response'=>$msg));
         }
            while($donner=$donner_data->fetch_assoc()){
                  $donner_profiles[]=$donner;
            }
            if(!isset($donner_data)){
           $msg = array("success" => 0,'msg'=>'somthing went wrong');
           return $response->withJson(array('response'=>$msg));
            }


    }
    $msg = array("success" => 1,'data'=>$donner_profiles,'msg'=>'Added blood request profile');
    return $response->withJson(array('response'=>$msg));

  }

  $msg = array("success" => 0,'msg'=>'somthing went wrong');
  return $response->withJson(array('response'=>$msg));

 }); 

$app->post('/list_Blood_Doner',function($request,$response,$args){
      $user_data = $request->getParsedBody();
      $cheak = cheak_token_user($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
      $conn =sqlConnection();
      
      if($donner_data=$conn->query("SELECT id,DonerName,Gender,BloodGroup from Blood_Donation where status =1 AND block_status =1 AND user_id=".$user_data['id'])){
           
          
            while($donner=$donner_data->fetch_assoc()){
                  $donner_profiles[]=$donner;
            }
            if(empty($donner_profiles)){
            $msg = array("success" => 2,'mag'=>'Not have any profile');
            return $response->withJson(array('response'=>$msg));
            }
            $msg = array("success" => 1,'data'=>$donner_profiles);
            return $response->withJson(array('response'=>$msg));
      }
      $msg = array("success" => 0,'msg'=>'not search any match');
      return $response->withJson(array('response'=>$msg));
  });

$app->post('/delete_profile_blood',function($request,$response,$args){
    $user_data = $request->getParsedBody();
    $cheak = cheak_token_user($user_data['id'],$user_data['token']);
    if($cheak['success'] == 0){
      $msg = array("success" => 0,'msg'=>'unotherise');
      return $response->withJson(array('response'=>$msg));
    }
    
       // $file = fopen("companydel.txt","w");
       // echo fwrite($file,json_encode($user_data));
       // fclose($file);

    $conn=sqlConnection();
        $profile = $conn->query("SELECT id FROM Blood_Donation WHERE id = ".$user_data['profileid']." AND user_id=".$user_data['id']);
        if($profile->num_rows == 1){
                 try{

                      $result=$conn->prepare("UPDATE Blood_Donation set status = 0 where id= ? AND user_id= ?");
                      $result->bind_param('ss', $user_data['profileid'],$user_data['id']);
                      $result->execute();
                      $result->store_result();
                      $result->close();
                      mysqli_close($conn);
                      $msg = array('success' => 1,'msg'=>'business will deleted');
                      return $response->withJson(array('response'=>$msg));

                 }catch(Exception $e){
                   $result->close();
                   mysqli_close($conn);
                   $msg =array('success'=>0,'msg'=>$e);
                   return $response->withJson(array('response'=>$msg));
                 }

        }
    $msg = array("success" => 0,'msg'=>'not search any match');
    return $response->withJson(array('respons'=>$msg));
});

$app->post('/view_profile_blood',function($request,$response,$args){
      $user_data = $request->getParsedBody();
       $cheak = cheak_token_user($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }

      $conn=sqlConnection();
      if(empty($user_data['blood_id']) OR !isset($user_data['blood_id'])){
            return $response->withJson(array('success'=>2,'mag'=>'serch not found'));
      }

      if($blood_data=$conn->query("SELECT * from Blood_Donation where id=".$user_data['blood_id']." AND status =1 AND block_status =1 AND user_id=".$user_data['id'])){
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
               $blood_view[$i]['Email']=$blood['Email'];
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
             mysqli_close($conn);
             $msg = array('success'=>1,'data'=>$blood_view);
             return $response->withJson(array('response'=>$msg));         
          }
                  
                  mysqli_close($conn);
                  $msg = array("success" => 0,'msg'=>'somthing wrong');
                 return $response->withJson($msg);

});
$app->post('/Edit_profile_blood',function($request,$response,$args){
      $user_data = $request->getParsedBody();
       $cheak = cheak_token_user($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }

      if(!isset($user_data['blood_id']) OR empty($user_data['blood_id'])){
         
         $msg = array("success" => 2,'msg'=>'not get id');
        return $response->withJson($msg);
      }
      $conn=sqlConnection();
      if($conn->query("UPDATE Blood_Donation SET DonerName = '".$user_data['DonerName']."' , BloodGroup='".$user_data['BloodGroup']."', Gender='".$user_data['Gender']."', dob='".$user_data['dob']."', DonerWeight='".$user_data['DonerWeight']."', Contact='".$user_data['Contact']."', Contact_2='".$user_data['Contact_2']."',Email='".$user_data['Email']."',country=".$user_data['country'].", state=".$user_data['state'].", city=".$user_data['city'].",district=".$user_data['distric']." WHERE id=".$user_data['blood_id'])){

          mysqli_close($conn);
         $msg = array("success" => 1,'msg'=>'BloodDonation profile updated');
        return $response->withJson(array('response'=>$msg));
     
      }
         mysqli_close($conn);
         $msg = array("success" => 0,'msg'=>'not update id');
        return $response->withJson($msg);
 });        

?>
