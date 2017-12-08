<?php
$app->post('/find_sub',function($request,$response,$args){
	  $user_data = $request->getParsedBody();
		// $file = fopen("find.txt","w");
		// echo fwrite($file,json_encode($user_data));
		// fclose($file);
	  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
	  if($cheak['success'] == 0){
	    $msg = array("success" => 0,'msg'=>'unotherise');
	    return $response->withJson($msg);
	  }
   $conn =sqlConnection();
	 if($businessresult = $conn->query("SELECT Categary_id FROM BusinessCategary_id where business_profile = ".$user_data['business_id'])){
     $i=0;

		 while($businessrow =$businessresult->fetch_assoc()){
         if($subresult = $conn->query("SELECT id,busness_name FROM busness_catagery where id = ".$businessrow['Categary_id'])){
					$subrow = $subresult->fetch_assoc();
					 if($subresult->num_rows >=1){
						 $sub_name[$i]['id']=$subrow['id'];
						 $sub_name[$i]['CompannyName']=$subrow['busness_name'];
						 $i++;
					 }

			 }

		 }
    $msg = array('success' =>1 ,'data'=>$sub_name);
		return $response->withJson(array('response'=>$msg));
	 }

	 $msg = array('success' =>1 ,'msg'=>'somthing went wrong');
	return $response->withJson(array('response'=>$msg));
});

$app->post('/add_JobPost',function($request,$response,$args){
	  $user_data = $request->getParsedBody();
	  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
	  if($cheak['success'] == 0){
	    $msg = array("success" => 0,'msg'=>'unotherise');
	    return $response->withJson($msg);
	  }
		$startdate = date('Y-m-d H:i:s');
		$enddate="2018-01-01 09:55:25";
		$amount ="100";
    $conn = sqlConnection();
    if($conn->query("INSERT INTO JobPost(business_id,user_id,JobTitle,JobType,Role,sub_cat,salary_max,salary_min,vacancy,Expriance_max,Expiriance_min,Discription,Contact,Email,country,state,city,distric,Payment,End_date,Start_date) VALUES (".$user_data['business_id'].",".$user_data['id'].",'".$user_data['JobTitle']."','".$user_data['JobType']."','".$user_data['Role']."','".$user_data['sub_cat']."','".$user_data['salary_max']."','".$user_data['salary_min']."','".$user_data['vacancy']."','".$user_data['Expriance_max']."','".$user_data['Expiriance_min']."','".$user_data['Discription']."','".$user_data['Contact']."','".$user_data['Email']."',".$user_data['country'].",".$user_data['state'].",".$user_data['city'].",".$user_data['distric'].",'".$amount."','".enddate."','".$startdate."') ")){

        mysqli_close($conn);
    		$msg = array("success" => 1,'msg'=>'job posted');
		    return $response->withJson(array('response'=>$msg));
       }
          mysqli_close($conn);
        	$msg = array("success" => 2,'msg'=>'job Not be posted');
		    return $response->withJson(array('response'=>$msg));
});

$app->post('/list_Job',function($request,$response,$args){
	  $user_data = $request->getParsedBody();
	  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
	  if($cheak['success'] == 0){
	    $msg = array("success" => 0,'msg'=>'unotherise');
	    return $response->withJson($msg);
	  }

	$conn= sqlConnection();
	  if($joblist = $conn->query("SELECT * FROM JobPost WHERE user_id = ".$user_data['id'])){

	  if($joblist->num_rows <= 0){
        mysqli_close($conn);
        return $response->withJson(array('response'=>array('success'=>2,'msg'=>'not get any data')));
      }
           $j=0;
          while($job=$joblist->fetch_assoc()){
          	    $busness_name = $conn->query("SELECT CompannyName from business_profile where status=1 and block_status=1 and id = ".$job['business_id']);
          	     $business_profile = $busness_name->fetch_assoc();
          	    $Job_profiles[$j]['busness_name']=$business_profile['CompannyName'];
          	    $Job_profiles[$j]['id']=$job['id'];
                $Job_profiles[$j]['JobTitle']=$job['JobTitle'];
                $Job_profiles[$j]['JobType']=$job['JobType'];
                $Job_profiles[$j]['JobType']=$job['vacancy'];
                $Job_profiles[$j]['Role']=$job['Role'];
                $Job_profiles[$j]['Expiriance_min']=$job['Expiriance_min'];
                $j++;
          }
        mysqli_close($conn);
    		$msg = array("success" => 1,'data'=>$Job_profiles);
		    return $response->withJson(array('response'=>$msg));
	  }
        mysqli_close($conn);
	        $msg = array("success" => 2,'msg'=>'somthind went wrong');
		    return $response->withJson(array('response'=>$msg));
});


$app->post('/view_Job',function($request,$response,$args){
	  $user_data = $request->getParsedBody();
		//  $file = fopen("viewjob_admin.txt","w");
		//  echo fwrite($file,json_encode($user_data));
		//  fclose($file);
	  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
	  if($cheak['success'] == 0){
	    $msg = array("success" => 0,'msg'=>'unotherise');
	    return $response->withJson($msg);
	  }
		 if(!isset($user_data['job_id']) OR empty($user_data['job_id'])){
		    $msg = array("success" => 0,'msg'=>'unotherise');
		    return $response->withJson($msg);
		 }
	$conn= sqlConnection();
		  if($joblist = $conn->query("SELECT * FROM JobPost WHERE id = ".$user_data['job_id'])){

			  if($joblist->num_rows <= 0){
		        mysqli_close($conn);
		        return $response->withJson(array('response'=>array('success'=>2,'msg'=>'not get any data')));
		      }

          	    $jo=0;
                while($job = $joblist->fetch_assoc()){
                $busness_name = $conn->query("SELECT CompannyName from business_profile where status=1 and block_status=1 and id = ".$job['business_id']);
          	    $business_profile = $busness_name->fetch_assoc();
                $Job_profiles[$jo]['busness_name']=$business_profile['CompannyName'];
                $Job_profiles[$jo]['id']=$job['id'];
                $Job_profiles[$jo]['Role']=$job['Role'];
                $Job_profiles[$jo]['JobType']=$job['JobType'];
                $Job_profiles[$jo]['JobTitle']=$job['JobTitle'];
                $Job_profiles[$jo]['Role']=$job['Role'];

								$subres=$conn->query("SELECT busness_name from busness_catagery where id = ".$job['sub_cat']);
								$sub_cat=$subres->fetch_assoc();
								$Job_profiles[$jo]['sub_cat']=$sub_cat['busness_name'];

                $Job_profiles[$jo]['Expiriance_min']=$job['Expiriance_min'];
                $Job_profiles[$jo]['Expriance_max']=$job['Expriance_max'];
                $Job_profiles[$jo]['Discription']=$job['Discription'];
                $Job_profiles[$jo]['salary_min']=$job['salary_min'];
                $Job_profiles[$jo]['salary_max']=$job['salary_max'];
                $Job_profiles[$jo]['vacancy']=$job['vacancy'];
                $Job_profiles[$jo]['Email']=$job['Email'];
                $Job_profiles[$jo]['Contact']=$job['Contact'];

								$res=$conn->query("SELECT name from a_main where id = ".$job['country']);
								$country=$res->fetch_assoc();
								$res=$conn->query("SELECT name from a_main where id = ".$job['state']);
								$state=$res->fetch_assoc();
								$res=$conn->query("SELECT name from a_main where id = ".$job['city']);
								$city=$res->fetch_assoc();
								$res=$conn->query("SELECT name from a_main where id = ".$job['distric']);
								$district=$res->fetch_assoc();
								$Job_profiles[$jo]['distric']=$district['name'];
								$Job_profiles[$jo]['country']=$country['name'];
								$Job_profiles[$jo]['state']=$state['name'];
								$Job_profiles[$jo]['city']=$city['name'];
                $jo++;

                }
								mysqli_close($conn);
                $msg =array('success'=>1,'data'=>$Job_profiles);
                return $response->withJson(array('response'=>$msg));

      }
        mysqli_close($conn);
      	    $msg = array("success" => 0,'msg'=>'unotherise');
		    return $response->withJson($msg);
});


$app->post('/Job_post_update',function($request,$response,$args){
	  $user_data = $request->getParsedBody();
	  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
	  if($cheak['success'] == 0){
	    $msg = array("success" => 0,'msg'=>'unotherise');
	    return $response->withJson($msg);
	  }
	  if(empty($user_data['JobId']) OR !isset($user_data['JobId'])){
         return $response->withJson(array('success'=>0,'msg'=>'not find any profile'));
	  }
      $conn = sqlConnection();


      $now = date('Y-m-d H:i:s');
      if($chack = $conn->query("SELECT * FROM JobPost WHERE status=1 AND  id= ".$user_data['JobId']." AND user_id= ".$user_data['id'])){
        if($chack->num_rows <= 0){
	       $msg = array("success" => 2,'msg'=>'not get any data');
	       return $response->withJson($msg);


        }
      $ckrow = $chack->fetch_assoc();
				if(strtotime($chrow['End_date']) < strtotime($now) ){
					$msg = array("success" => 2,'msg'=>'Your Subcription is over');
				  return $response->withJson($msg);
						}
     if($conn->query('UPDATE JobPost SET JobTitle="'.$user_data['JobTitle'].'",JobType ="'.$user_data['JobType'].'",sub_cat="'.$user_data['sub_cat'].'",Role = "'.$user_data['Role'].'",salary_max="'.$user_data['salary_max'].'",salary_min="'.$user_data['salary_min'].'",vacancy="'.$user_data['vacancy'].'",Expiriance_min = "'.$user_data['Expiriance_min'].'",Expriance_max="'.$user_data['Expriance_max'].'",Discription="'.$user_data['Discription'].'",Contact="'.$user_data['Contact'].'",Email="'.$user_data['Email'].'",country='.$user_data['country'].',state='.$user_data['state'].',city = '.$user_data['city'].' where id = '.$user_data['JobId'])){
                mysqli_close($conn);
                $msg =array('success'=>1,'msg'=>'update job post data');
                return $response->withJson(array('response'=>$msg));

        }
        		mysqli_close($conn);
                $msg =array('success'=>0,'msg'=>'data not be updated');
                return $response->withJson(array('response'=>$msg));

      }

      			mysqli_close($conn);
                $msg =array('success'=>0,'msg'=>'somthing went wrong');
                return $response->withJson(array('response'=>$msg));

});

$app->post('/JobPost_Delete', function($request,$response) {

        $admin_data = $request->getParsedBody();
        $cheak = cheak_token_user($admin_data['id'],$admin_data['token']);

        if($cheak['success'] == 0){
          $msg = array("success" => 0,'msg'=>'unotherise');
          return $response->withJson($msg);
        }

        $conn = sqlConnection();
        $profile = $conn->query("SELECT id FROM JobPost WHERE id = ".$admin_data['JobId']." AND user_id=".$admin_data['id']);
        if($profile->num_rows == 1){
                 try{

                      $result=$conn->prepare("UPDATE JobPost set status = 0 where id= ? AND user_id= ?");

                      $result->bind_param('ss', $admin_data['JobId'],$admin_data['id']);
                      $result->execute();
                      $result->store_result();
                      $result->close();
                      mysqli_close($conn);
                      $msg = array('success' => 1,'msg'=>'JobPost will deleted');
                      return $response->withJson(array('response'=>$msg));

                 }catch(Exception $e){
                   $result->close();
                   mysqli_close($conn);
                   $msg =array('success'=>0,'msg'=>$e);
                   return $response->withJson(array('response'=>$msg));
                 }

        }


             $result->close();
             mysqli_close($conn);
             $msg = array('success' => 0,'msg'=>'enable to read data');
             return $response->withJson($msg);
});

$app->post('/view_Search',function($request,$response,$args){
	  $user_data = $request->getParsedBody();
	  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
	  if($cheak['success'] == 0){
	    $msg = array("success" => 0,'msg'=>'unotherise');
	    return $response->withJson($msg);
	  }

      if(!isset($user_data['Search_key']) OR empty($user_data['Search_key'])){

       return $response->withJson(array('success'=>2,'mas'=>'key not found'));

      }
      $conn = sqlConnection();
 if($filter_job = $conn->query(`SELECT * from JobPost where status =1 And block_status=1 And Role Like %`.$user_data['Search_key'].`% `)){

    $j=0;
 	while($job_list=$filter_job->fetch_assoc()){

        $job_filter[$j]['id']=$job_list['id'];
        $job_filter[$j]['JobTitle'] = $job_list['JobTitle'];
        $job_filter[$j]['Role'] = $job_list['Role'];
        $job_filter[$j]['vacancy'] = $job_list['vacancy'];
        $job_filter[$j]['Discription'] = $job_list['Discription'];

        $j++;

 	}
	 mysqli_close($conn);
 	             $msg =array('success'=>1,'data'=>$job_filter);
                return $response->withJson(array('response'=>$msg));
 }
 				mysqli_close($conn);
 	             $msg =array('success'=>0,'msg'=>'somthing went wrong');
                return $response->withJson(array('response'=>$msg));
});

$app->post('/Job_view_find',function($request,$response,$args){
	  $user_data = $request->getParsedBody();
		 $file = fopen("jobview.txt","w");
		 echo fwrite($file,json_encode($user_data));
		 fclose($file);
	  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
	  if($cheak['success'] == 0){
	    $msg = array("success" => 0,'msg'=>'unotherise');
	    return $response->withJson($msg);
	  }

		$conn=sqlConnection();
		if($resultview = $conn->query("SELECT * From JobPost where status=1 AND block_status=1 AND id= ".$user_data['JobId'])){
       $jo=0;
			while($jobrow = $resultview->fetch_assoc()){
				$viewjob[$jo]['id']=$jobrow['id'];
				$viewjob[$jo]['JobTitle']=$jobrow['JobTitle'];
				$viewjob[$jo]['JobType']=$jobrow['JobType'];
				$viewjob[$jo]['Role']=$jobrow['Role'];
				$viewjob[$jo]['salary_max']=$jobrow['salary_max'];
				$viewjob[$jo]['salary_min']=$jobrow['salary_min'];
				$viewjob[$jo]['Expriance_max']=$jobrow['Expriance_max'];
				$viewjob[$jo]['Expiriance_min']=$jobrow['Expiriance_min'];
				$viewjob[$jo]['Discription']=$jobrow['Discription'];
				$viewjob[$jo]['Contact']=$jobrow['Contact'];
				$viewjob[$jo]['Email']=$jobrow['Email'];
				$viewjob[$jo]['vacancy']=$jobrow['vacancy'];
				$res=$conn->query("SELECT name from a_main where id = ".$jobrow['country']);
				$country=$res->fetch_assoc();
				$res=$conn->query("SELECT name from a_main where id = ".$jobrow['state']);
				$state=$res->fetch_assoc();
				$res=$conn->query("SELECT name from a_main where id = ".$jobrow['city']);
				$city=$res->fetch_assoc();
				$viewjob[$jo]['country']=$country['name'];
				$viewjob[$jo]['state']=$state['name'];
				$viewjob[$jo]['city']=$city['name'];
					if($comres = $conn->query("SELECT * FROM business_profile WHERE status=1 AND id=".$jobrow['business_id'])){
	            $comrow = $comres->fetch_assoc();
							$viewjob[$jo]['CompannyName']=$comrow['CompannyName'];
							$viewjob[$jo]['BusinessLink']=$comrow['BusinessLink'];
							$viewjob[$jo]['Website']=$comrow['Website'];
							$viewjob[$jo]['Address']=$comrow['Address'];
							$viewjob[$jo]['CompanylogoString']=$comrow['CompanylogoString'];
							$viewjob[$jo]['OwnerName']=$comrow['OwnerName'];
							if($maincatres = $conn->query("SELECT * FROM BusinessMain_cat where id =".$comrow['business_main'])){

								$mainrow=$maincatres->fetch_assoc(	);
								$viewjob[$jo]['business_Cat']=$mainrow['business_Name'];
							}

					}else{
						$msg = array('success'=>2,'mag'=>'company not fount or block');
						return $response->withJson(array('response' => $msg));
					}
				$jo++;
			}
			$msg = array('success'=>1,'data'=>$viewjob);
			return $response->withJson(array('response' => $msg));


		}

		$msg = array('success'=>0,'mag'=>'somthing went wrong');
		return $response->withJson(array('response' => $msg));

	});

?>
