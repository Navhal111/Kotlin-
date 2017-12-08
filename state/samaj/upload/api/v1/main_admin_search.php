<?php
$app->post('/Search_all_profile',function($request,$response,$args){

  $admin_data = $request->getParsedBody();
  $cheak = cheak_token($admin_data['id'],$admin_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
  $conn=  sqlConnection();
   if($result = $conn->query("SELECT * from module_permition where sub_admin_id = ".$admin_data['id'])) {

            $row = $result->fetch_assoc();
             $module_permition[] =$row;
  }

$u=0;
if(empty($module_permition[0]['role'])){
  mysqli_close($conn);
	$msg = array("success" => 0,'msg'=>'Somting went wrong');
    return $response->withJson($msg);
}

if($module_permition[0]['register_user']==00){
   mysqli_close($conn);
	 $msg = array("success" => 0,'msg'=>'Not permition to read');
    return $response->withJson($msg);

}
if($module_permition[0]['register_user']!=00){

		if($userRusult = $conn->query('SELECT id,first_name,last_name,email,phone_num,user_block FROM user WHERE status = 1 AND (first_name LIKE "%'.$admin_data['SearchValue'].'%" OR last_name LIKE "%'.$admin_data['SearchValue'].'%" OR email LIKE "%'.$admin_data['SearchValue'].'%" OR phone_num LIKE "%'.$admin_data['SearchValue'].'%" ) LIMIT 30')){

				while($userrow = $userRusult->fetch_assoc()) {
				    $SearchValue[$u]['id']=$userrow['id'];
					$SearchValue[$u]['Fullname']=$userrow['first_name']." ".$userrow['last_name'];
					$SearchValue[$u]['Email']=$userrow['email'];
					$SearchValue[$u]['Contact']=$userrow['phone_num'];
          $SearchValue[$u]['Status']=$userrow['user_block'];
					$SearchValue[$u]['Module']="MainUser";
					$u++;
				}

		}

}

if($module_permition[0]['matrimony']!=00){

		if( $mertyresult=$conn->query('SELECT id,RegisterFullname,RegisterProfileEmail,RegisterProfileMobile,block_status FROM profile_met WHERE status=1 AND (RegisterFullname LIKE "'.$admin_data['SearchValue'].'" OR RegisterProfileEmail LIKE "%'.$admin_data['SearchValue'].'%" OR RegisterProfileMobile LIKE "%'.$admin_data['SearchValue'].'%") LIMIT 30')){
			while ($martyrow=$mertyresult->fetch_assoc()) {
				    $SearchValue[$u]['id']=$martyrow['id'];
			      	$SearchValue[$u]['Fullname']=$martyrow['RegisterFullname'];
					$SearchValue[$u]['Email']=$martyrow['RegisterProfileEmail'];
					$SearchValue[$u]['Contact']=$martyrow['RegisterProfileMobile'];
          $SearchValue[$u]['Status']=$martyrow['block_status'];
					$SearchValue[$u]['Module']="Matrimonial Profile";
					$u++;
			}

		}

}
if($module_permition[0]['blood_doner']!=00){

		if( $bloodresult=$conn->query('SELECT id,DonerName,BloodGroup,Email,Contact_2,Contact,block_status FROM Blood_Donation WHERE status=1 AND (DonerName LIKE "%'.$admin_data['SearchValue'].'%" OR Email LIKE "%'.$admin_data['SearchValue'].'%" OR Contact LIKE "%'.$admin_data['SearchValue'].'%" OR Contact_2 LIKE "%'.$admin_data['SearchValue'].'%"  ) LIMIT 30')){

			while ($bloodirow=$bloodresult->fetch_assoc()) {
                   $SearchValue[$u]['id']=$bloodirow['id'];
			      	$SearchValue[$u]['Fullname']=$bloodirow['DonerName']."(BloodGroup = ".$bloodirow['BloodGroup'].")";
					$SearchValue[$u]['Email']=$bloodirow['Email'];
					$SearchValue[$u]['Contact']=$bloodirow['Contact']."/".$bloodirow['Contact_2'];
          $SearchValue[$u]['Status']=$bloodirow['block_status'];
					$SearchValue[$u]['Module']="Blood Profile";
					$u++;
			}

		}


}
if($module_permition[0]['nokari_user']!=00){
  if($nokarires =  $conn->query("SELECT id,Name,Email,Contact,block_status FROM Nikari_user where status =1 AND (Name LIKE '%".$admin_data['SearchValue']."%' OR Email LIKE '%".$admin_data['SearchValue']."%' OR Contact LIKE '%".$admin_data['SearchValue']."%') LIMIT 30")){
      while($noakrirow = $nokarires->fetch_assoc()){
        $SearchValue[$u]['id']=$noakrirow['id'];
        $SearchValue[$u]['Fullname']=$noakrirow['Name'];
        $SearchValue[$u]['Email']=$noakrirow['Email'];
        $SearchValue[$u]['Status']=$noakrirow['block_status'];
        $SearchValue[$u]['Contact']=$noakrirow['Contact'];
        $SearchValue[$u]['Module']="Nokari Profile";
        $u++;
      }

  }

}
if($module_permition[0]['noklari']!=00){
  if($jobres = $conn->query("SELECT id,JobTitle,Email,Contact,block_status FROM JobPost WHERE status=1 AND (JobTitle LIKE '%".$admin_data['SearchValue']."%' OR Email LIKE '%".$admin_data['SearchValue']."%' OR Contact LIKE '%".$admin_data['SearchValue']."%') LIMIT 30")){

    while($jobrow = $jobres->fetch_assoc()){
      $SearchValue[$u]['id']=$jobrow['id'];
      $SearchValue[$u]['Fullname']=$jobrow['JobTitle'];
      $SearchValue[$u]['Email']=$jobrow['Email'];
      $SearchValue[$u]['Contact']=$jobrow['Contact'];
      $SearchValue[$u]['Status']=$jobrow['block_status'];
      $SearchValue[$u]['Module']="Job Post";
      $u++;

    }
  }

}
if($module_permition[0]['bissness']!=00){

		if( $businessresult=$conn->query('SELECT id,CompannyName,OwnerName,Contact,contact_2,BusinessLink,block_status FROM business_profile where status=1 AND (CompannyName LIKE "%'.$admin_data['SearchValue'].'%" OR OwnerName LIKE "%'.$admin_data['SearchValue'].'%" OR Contact LIKE "%'.$admin_data['SearchValue'].'%" OR contact_2 LIKE "%'.$admin_data['SearchValue'].'%" OR BusinessLink LIKE "%'.$admin_data['SearchValue'].'%") LIMIT 40')){

			while ($busirow=$businessresult->fetch_assoc()) {
                    $SearchValue[$u]['id']=$busirow['id'];
			      	$SearchValue[$u]['Fullname']=$busirow['CompannyName']."(Owner :- ".$busirow['OwnerName'].")";
					$SearchValue[$u]['Email']=$busirow['BusinessLink'];
					$SearchValue[$u]['Contact']=$busirow['Contact']."/".$busirow['contact_2'];
          $SearchValue[$u]['Status']=$busirow['block_status'];
					$SearchValue[$u]['Module']="Business Profile";
					$u++;
			}

		}
mysqli_close($conn);
return $response->withJson(array('success'=>1,'data'=>$SearchValue));

}
    if(isset($SearchValue[0]) OR !empty($SearchValue[0])){
       mysqli_close($conn);
       return $response->withJson(array('success'=>2,'msg'=>'not find any match'));
    }
mysqli_close($conn);
return $response->withJson(array('success'=>1,'msg'=>'somthing went wrong'));
});

$app->post('/mail_module_vise',function($request,$response,$args){

  $admin_data = $request->getParsedBody();

      //   $file = fopen("add.txt","w");
      //  echo fwrite($file,json_encode($admin_data));
      //  fclose($file);
  $cheak = cheak_token($admin_data[1]['value'],$admin_data[2]['value']);

  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
  if(empty($admin_data[6]['value']) OR !isset($admin_data[6]['value'])){
    $msg = array('success'=>0,'mag'=>'Not get any data');
    return $response->withJson($msg);

  }

  $conn=  sqlConnection();
   if($result = $conn->query("SELECT * from module_permition where sub_admin_id = ".$admin_data[1]['value'])) {

            $row = $result->fetch_assoc();
             $module_permition[] =$row;
  }

  if($admin_data[0]['value']=="mail"){

      // return $response->withJson(array('data' => 'in'));
    $set=7;
    $i=0;
      while($admin_data[$set]['name']=='mail_group'){

      	$setmodule[$i]=$admin_data[$set]['value'];
      	$i++;
      	$set++;
      }
     $j=0;
    $mail='YES';

    $i=0;
    $setnew=$set;
    while (isset($setmodule[$j])) {

      switch ($setmodule[$j]) {

    	  	case 'Register User':
    	  	  $set=$setnew;
              switch ($admin_data[$set]['value']) {
              	case 'ST':

              	  $set++;
              	  $set++;
              	  $a=0;
                   unset($area);
    				while($admin_data[$set]['name']=='state_permit'){
    			        $area[$a]=$admin_data[$set]['value'];
    			        $set++;
    					$a++;
    				}
    		 			  if($module_permition[0]['register_user']!=00){

    				         if($stateresult=$conn->query("SELECT id,email FROM user where  status=1 AND block_status=1 AND state IN (" . implode(',', array_map('intval', $area)) . ")")){

    				          while($staterow = $stateresult->fetch_assoc()){
    				               $minadata[$i]['email']=$staterow['email'];
    				               $i++;
    				          }

    				         }

    					  }
              		break;
              	case 'DI':
                  $set++;
              	  $set++;
              	  $set++;
              	  $a=0;
    				while($admin_data[$set]['name']=='city_permit'){
    			        $area[$a]=$admin_data[$set]['value'];
    			        $set++;
    					$a++;
    				}

    		 			  if($module_permition[0]['register_user']!=00){

    				         if($stateresult=$conn->query("SELECT id,email FROM user where  status=1 AND user_block=1 AND city IN (" . implode(',', array_map('intval', $area)) . ")")){

    				          while($staterow = $stateresult->fetch_assoc()){
    				               $minadata[$i]['email']=$staterow['email'];
    				               $i++;
    				          }

    				         }

    					  }
              		break;
              	case 'CT':
                  $set++;
              	  $set++;
              	  $set++;
              	  $set++;
              	  $a=0;
              	   unset($area);
    				while($admin_data[$set]['name']=='district_permit'){
    			        $area[$a]=$admin_data[$set]['value'];
    			        $set++;
    					$a++;
    				}
    		 			  if($module_permition[0]['register_user']!=00){

    				         if($stateresult=$conn->query("SELECT id,email FROM user where  status=1 AND block_status=1 AND distric IN (" . implode(',', array_map('intval', $area)) . ")")){

    				          while($staterow = $stateresult->fetch_assoc()){
    				               $minadata[$i]['email']=$staterow['email'];
    				               $i++;
    				          }

    				         }

    					  }
              		break;

              }
        		break;
     		case 'Matrimonial Profile':
     		  $set=$setnew;
              switch ($admin_data[$set]['value']) {
              	case 'ST':

              	  $set++;
              	  $set++;
              	  $a=0;
                   unset($area);
    				while($admin_data[$set]['name']=='state_permit'){
    			        $area[$a]=$admin_data[$set]['value'];
    			        $set++;
    					$a++;
    				}
    		 			  if($module_permition[0]['matrimony']!=00){

    				         if($stateresult=$conn->query("SELECT id,RegisterProfileEmail FROM profile_met where  status=1 AND block_status=1 AND RegisterState IN (" . implode(',', array_map('intval', $area)) . ")")){

    				          while($staterow = $stateresult->fetch_assoc()){
    				               $minadata[$i]['email']=$staterow['RegisterProfileEmail'];
    				               $i++;
    				          }

    				}


    					  }
              		break;
              	case 'DI':
                  $set++;
              	  $set++;
              	  $set++;
              	  $a=0;
              	  unset($area);
    				while($admin_data[$set]['name']=='city_permit'){
    			        $area[$a]=$admin_data[$set]['value'];
    			        $set++;
    					$a++;
    				}

    		 			  if($module_permition[0]['register_user']!=00){

    				         if($stateresult=$conn->query("SELECT id,RegisterProfileEmail FROM profile_met where  status=1 AND block_status=1 AND RegisterCity IN (" . implode(',', array_map('intval', $area)) . ")")){

    				          while($staterow = $stateresult->fetch_assoc()){
    				               $minadata[$i]['email']=$staterow['RegisterProfileEmail'];
    				               $i++;
    				          }

    				         }

    					  }
              		break;
              	case 'CT':
                  $set++;
              	  $set++;
              	  $set++;
              	  $set++;
              	  $a=0;
              	   unset($area);
    				while($admin_data[$set]['name']=='district_permit'){
    			        $area[$a]=$admin_data[$set]['value'];
    			        $set++;
    					$a++;
    				}
    		 			  if($module_permition[0]['register_user']!=00){

    				         if($stateresult=$conn->query("SELECT id,RegisterProfileEmail FROM profile_met where  status=1 AND block_status=1 AND RegisterTaluka IN (" . implode(',', array_map('intval', $area)) . ")")){

    				          while($staterow = $stateresult->fetch_assoc()){
    				               $minadata[$i]['email']=$staterow['RegisterProfileEmail'];
    				               $i++;
    				          }

    				         }


    					  }
              		break;

              }
        		break;

      		case 'Blood Profile':
     		  $set=$setnew;
              switch ($admin_data[$set]['value']) {
              	case 'ST':

              	  $set++;
              	  $set++;
              	  $a=0;
                   unset($area);
    				while($admin_data[$set]['name']=='state_permit'){
    			        $area[$a]=$admin_data[$set]['value'];
    			        $set++;
    					$a++;
    				}
    		 			  if($module_permition[0]['matrimony']!=00){

    				         if($stateresult=$conn->query("SELECT id,Email FROM Blood_Donation where  status=1 AND block_status=1 AND state IN (" . implode(',', array_map('intval', $area)) . ")")){

    				          while($staterow = $stateresult->fetch_assoc()){
    				               $minadata[$i]['email']=$staterow['RegisterProfileEmail'];
    				               $i++;
    				          }

    				         }


    					  }
              		break;
              	case 'DI':
                  $set++;
              	  $set++;
              	  $set++;
              	  $a=0;
              	  unset($area);
    				while($admin_data[$set]['name']=='city_permit'){
    			        $area[$a]=$admin_data[$set]['value'];
    			        $set++;
    					$a++;
    				}

    		 			  if($module_permition[0]['register_user']!=00){

    				         if($stateresult=$conn->query("SELECT id,Email FROM Blood_Donation where  status=1 AND block_status=1 AND district IN (" . implode(',', array_map('intval', $area)) . ")")){

    				          while($staterow = $stateresult->fetch_assoc()){
    				               $minadata[$i]['email']=$staterow['RegisterProfileEmail'];
    				               $i++;
    				          }

    				         }

    					  }
              		break;
              	case 'CT':
                  $set++;
              	  $set++;
              	  $set++;
              	  $set++;
              	  $a=0;
              	   unset($area);
    				while($admin_data[$set]['name']=='district_permit'){
    			        $area[$a]=$admin_data[$set]['value'];
    			        $set++;
    					$a++;
    				}
    		 			  if($module_permition[0]['register_user']!=00){

    				         if($stateresult=$conn->query("SELECT id,Email FROM Blood_Donation where  status=1 AND block_status=1 AND city IN (" . implode(',', array_map('intval', $area)) . ")")){

    				          while($staterow = $stateresult->fetch_assoc()){
    				               $minadata[$i]['email']=$staterow['RegisterProfileEmail'];
    				               $i++;
    				          }

    				         }


    					  }
              		break;

              }
        		break;

            case 'Nokari Profile':
       		  $set=$setnew;
                switch ($admin_data[$set]['value']) {
                	case 'ST':

                	  $set++;
                	  $set++;
                	  $a=0;
                     unset($area);
      				while($admin_data[$set]['name']=='state_permit'){
      			        $area[$a]=$admin_data[$set]['value'];
      			        $set++;
      					$a++;
      				}
      		 			  if($module_permition[0]['nokari_user']!=00){

      				         if($stateresult=$conn->query("SELECT id,Email FROM Nikari_user where  status=1 AND block_status=1 AND state IN (" . implode(',', array_map('intval', $area)) . ")")){

      				          while($staterow = $stateresult->fetch_assoc()){
      				               $minadata[$i]['email']=$staterow['RegisterProfileEmail'];
      				               $i++;
      				          }

      				         }


      					  }
                		break;
                	case 'DI':
                    $set++;
                	  $set++;
                	  $set++;
                	  $a=0;
                	  unset($area);
      				while($admin_data[$set]['name']=='city_permit'){
      			        $area[$a]=$admin_data[$set]['value'];
      			        $set++;
      					$a++;
      				}

      		 			  if($module_permition[0]['nokari_user']!=00){

      				         if($stateresult=$conn->query("SELECT id,Email FROM Nikari_user where  status=1 AND block_status=1 AND district IN (" . implode(',', array_map('intval', $area)) . ")")){

      				          while($staterow = $stateresult->fetch_assoc()){
      				               $minadata[$i]['email']=$staterow['RegisterProfileEmail'];
      				               $i++;
      				          }

      				         }

      					  }
                		break;
                	case 'CT':
                    $set++;
                	  $set++;
                	  $set++;
                	  $set++;
                	  $a=0;
                	   unset($area);
      				while($admin_data[$set]['name']=='district_permit'){
      			        $area[$a]=$admin_data[$set]['value'];
      			        $set++;
      					$a++;
      				}
      		 			  if($module_permition[0]['nokari_user']!=00){

      				         if($stateresult=$conn->query("SELECT id,Email FROM Nikari_user where  status=1 AND block_status=1 AND city IN (" . implode(',', array_map('intval', $area)) . ")")){

      				          while($staterow = $stateresult->fetch_assoc()){
      				               $minadata[$i]['email']=$staterow['RegisterProfileEmail'];
      				               $i++;
      				          }

      				         }


      					  }
                		break;

                }
          		break;
    	   }


    $j++;
    }
  }



 $modules = implode(" <br> ",$setmodule);
 $send =$mail;

 if($conn->query('INSERT INTO Msg_send(admin_id,MsgTitle,MsgSubject,MagBody,MsgGroup,area,Persions,send) values('.$admin_data[1]['value'].',"'.$admin_data[4]['value'].'","'.$admin_data[3]['value'].'","'.$admin_data[6]['value'].'" ,"'.$modules.'","'.$admin_data[$setnew]['value'].'","'.$i.'","'.$send.'" )')){

    return $response->withJson(array('success'=>1,'msg'=>'send mssage to all match '));
 }

    mysqli_close($conn);
    $msg = array('success'=>2,'mag'=>'Somthing went wrong');
    return $response->withJson($msg);

});

$app->post('/list_send_msg',function($request,$response,$args){

  $admin_data = $request->getParsedBody();
  $cheak = cheak_token($admin_data['id'],$admin_data['token']);

  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }

  $conn=  sqlConnection();
   if($result = $conn->query("SELECT role from module_permition where sub_admin_id = ".$admin_data['id'])) {

            $row = $result->fetch_assoc();
             $module_permition[] =$row;
   }
  if($module_permition[0]['role']==1){
		  if($msgresult=$conn->query("SELECT * FROM Msg_send where  status=1 ")){

             while($msgrow=$msgresult->fetch_assoc()){
                    $allmsg[]=$msgrow;

             }
             mysqli_close($conn);
             return $response->withJson(array('success' =>1,'data'=>$allmsg));
		  }

  }
     mysqli_close($conn);
     $msg = array("success" => 0,'msg'=>'something went wrong');
    return $response->withJson($msg);

 });


$app->post('/send_msg_del',function($request,$response,$args){

  $admin_data = $request->getParsedBody();
  $cheak = cheak_token($admin_data['id'],$admin_data['token']);

  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }

  $conn=  sqlConnection();
   if($result = $conn->query("SELECT role from module_permition where sub_admin_id = ".$admin_data['id'])) {

            $row = $result->fetch_assoc();
             $module_permition[] =$row;
   }

  if($module_permition[0]['role']==1){

  	if($conn->query("UPDATE Msg_send SET status=0 where id=".$admin_data['MsgId'])){
       mysqli_close($conn);
       return $response->withJson(array('success' => 1,'mag'=>'Msg will deleted'));

  	}
  	mysqli_close($conn);
       $msg = array("success" => 0,'msg'=>'not match');
    return $response->withJson($msg);
  }
    mysqli_close($conn);
         $msg = array("success" => 0,'msg'=>'somthing went wrong ');
    return $response->withJson($msg);
});

$app->post('/occuvise_send',function($request,$response,$args){

  $admin_data = $request->getParsedBody();
  $cheak = cheak_token($admin_data['id'],$admin_data['token']);

  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }

  $conn=  sqlConnection();
   if($result = $conn->query("SELECT role from module_permition where sub_admin_id = ".$admin_data['id'])) {

            $row = $result->fetch_assoc();
             $module_permition[] =$row;
   }
 $i=0;
   while ($admin_data['occupation'][$i]) {

   	$occu[$i]=$admin_data['occupation'][$i];
   	$i++;
   }
   $i=0;
  if($module_permition[0]['role']==1){

   		if($conn->query("SELECT * FROM profile_met where status =1 AND block_status= 1 AND RegisterRefOccupationid=".$admin_data['occupation'])){}

  }else{

    mysqli_close($conn);
  	return $response->withJson(array('success' =>0 ,'msg'=>'Nots have permition'));
  }

  mysql_close($conn);
  return $response->withJson(array('success' =>0 ,'msg'=>'somthing went wrong' ));
});
$app->post('/msg_module_vise',function($request,$response,$args){

  $admin_data = $request->getParsedBody();

      //   $file = fopen("msg_add.txt","w");
      //  echo fwrite($file,json_encode($admin_data));
      //  fclose($file);
  $cheak = cheak_token($admin_data[1]['value'],$admin_data[2]['value']);

  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
  if(empty($admin_data[0]['value'])){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
  $conn=  sqlConnection();
   if($result = $conn->query("SELECT * from module_permition where sub_admin_id = ".$admin_data[1]['value'])) {

            $row = $result->fetch_assoc();
             $module_permition[] =$row;
  }
  if($admin_data[0]['value']=="message"){
    $set=5;
    $i=0;
    $j=0;
    $mail='YES';
    while (isset($setmodule[$j])) {
      switch ($setmodule[$j]) {

    	  	case 'Register User':
    	  	  $set=$setnew;
              switch ($admin_data[$set]['value']) {
              	case 'ST':

              	  $set++;
              	  $set++;
              	  $a=0;
                   unset($area);
    				while($admin_data[$set]['name']=='state_permit'){
    			        $area[$a]=$admin_data[$set]['value'];
    			        $set++;
    					$a++;
    				}
    		 			  if($module_permition[0]['register_user']!=00){

    				         if($stateresult=$conn->query("SELECT id,phone_num FROM user where  status=1 AND block_status=1 AND state IN (" . implode(',', array_map('intval', $area)) . ")")){

    				          while($staterow = $stateresult->fetch_assoc()){
    				               $minadata[$i]['Contact']=$staterow['phone_num'];
    				               $i++;
    				          }

    				         }

    					  }
              		break;
              	case 'DI':
                  $set++;
              	  $set++;
              	  $set++;
              	  $a=0;
    				while($admin_data[$set]['name']=='city_permit'){
    			        $area[$a]=$admin_data[$set]['value'];
    			        $set++;
    					$a++;
    				}

    		 			  if($module_permition[0]['register_user']!=00){

    				         if($stateresult=$conn->query("SELECT id,phone_num FROM user where  status=1 AND user_block=1 AND city IN (" . implode(',', array_map('intval', $area)) . ")")){

    				          while($staterow = $stateresult->fetch_assoc()){
    				               $minadata[$i]['Contact']=$staterow['phone_num'];
    				               $i++;
    				          }

    				         }

    					  }
              		break;
              	case 'CT':
                  $set++;
              	  $set++;
              	  $set++;
              	  $set++;
              	  $a=0;
              	   unset($area);
    				while($admin_data[$set]['name']=='district_permit'){
    			        $area[$a]=$admin_data[$set]['value'];
    			        $set++;
    					$a++;
    				}
    		 			  if($module_permition[0]['register_user']!=00){

    				         if($stateresult=$conn->query("SELECT id,phone_num FROM user where  status=1 AND block_status=1 AND distric IN (" . implode(',', array_map('intval', $area)) . ")")){

    				          while($staterow = $stateresult->fetch_assoc()){
    				               $minadata[$i]['Contact']=$staterow['phone_num'];
    				               $i++;
    				          }

    				         }

    					  }
              		break;

              }
        		break;
     		case 'Matrimonial Profile':
     		  $set=$setnew;
              switch ($admin_data[$set]['value']) {
              	case 'ST':

              	  $set++;
              	  $set++;
              	  $a=0;
                   unset($area);
    				while($admin_data[$set]['name']=='state_permit'){
    			        $area[$a]=$admin_data[$set]['value'];
    			        $set++;
    					$a++;
    				}
    		 			  if($module_permition[0]['matrimony']!=00){

    				         if($stateresult=$conn->query("SELECT id,RegisterProfileMobile FROM profile_met where  status=1 AND block_status=1 AND RegisterState IN (" . implode(',', array_map('intval', $area)) . ")")){

    				          while($staterow = $stateresult->fetch_assoc()){
    				               $minadata[$i]['Contact']=$staterow['RegisterProfileMobile'];
    				               $i++;
    				          }

    				}


    					  }
              		break;
              	case 'DI':
                  $set++;
              	  $set++;
              	  $set++;
              	  $a=0;
              	  unset($area);
    				while($admin_data[$set]['name']=='city_permit'){
    			        $area[$a]=$admin_data[$set]['value'];
    			        $set++;
    					$a++;
    				}

    		 			  if($module_permition[0]['register_user']!=00){

    				         if($stateresult=$conn->query("SELECT id,RegisterProfileMobile FROM profile_met where  status=1 AND block_status=1 AND RegisterCity IN (" . implode(',', array_map('intval', $area)) . ")")){

    				          while($staterow = $stateresult->fetch_assoc()){
    				               $minadata[$i]['Conatct']=$staterow['RegisterProfileMobile'];
    				               $i++;
    				          }

    				         }

    					  }
              		break;
              	case 'CT':
                  $set++;
              	  $set++;
              	  $set++;
              	  $set++;
              	  $a=0;
              	   unset($area);
    				while($admin_data[$set]['name']=='district_permit'){
    			        $area[$a]=$admin_data[$set]['value'];
    			        $set++;
    					$a++;
    				}
    		 			  if($module_permition[0]['register_user']!=00){

    				         if($stateresult=$conn->query("SELECT id,RegisterProfileMobile FROM profile_met where  status=1 AND block_status=1 AND RegisterTaluka IN (" . implode(',', array_map('intval', $area)) . ")")){

    				          while($staterow = $stateresult->fetch_assoc()){
    				               $minadata[$i]['Contact']=$staterow['RegisterProfileMobile'];
    				               $i++;
    				          }

    				         }


    					  }
              		break;

              }
        		break;

      		case 'Blood Profile':
     		  $set=$setnew;
              switch ($admin_data[$set]['value']) {
              	case 'ST':

              	  $set++;
              	  $set++;
              	  $a=0;
                   unset($area);
    				while($admin_data[$set]['name']=='state_permit'){
    			        $area[$a]=$admin_data[$set]['value'];
    			        $set++;
    					$a++;
    				}
    		 			  if($module_permition[0]['matrimony']!=00){

    				         if($stateresult=$conn->query("SELECT id,Contact FROM Blood_Donation where  status=1 AND block_status=1 AND state IN (" . implode(',', array_map('intval', $area)) . ")")){

    				          while($staterow = $stateresult->fetch_assoc()){
    				               $minadata[$i]['Conatct']=$staterow['Contact'];
    				               $i++;
    				          }

    				         }


    					  }
              		break;
              	case 'DI':
                  $set++;
              	  $set++;
              	  $set++;
              	  $a=0;
              	  unset($area);
    				while($admin_data[$set]['name']=='city_permit'){
    			        $area[$a]=$admin_data[$set]['value'];
    			        $set++;
    					$a++;
    				}

    		 			  if($module_permition[0]['register_user']!=00){

    				         if($stateresult=$conn->query("SELECT id,Contact FROM Blood_Donation where  status=1 AND block_status=1 AND district IN (" . implode(',', array_map('intval', $area)) . ")")){

    				          while($staterow = $stateresult->fetch_assoc()){
    				               $minadata[$i]['Contact']=$staterow['Contact'];
    				               $i++;
    				          }

    				         }

    					  }
              		break;
              	case 'CT':
                  $set++;
              	  $set++;
              	  $set++;
              	  $set++;
              	  $a=0;
              	   unset($area);
    				while($admin_data[$set]['name']=='district_permit'){
    			        $area[$a]=$admin_data[$set]['value'];
    			        $set++;
    					$a++;
    				}
    		 			  if($module_permition[0]['register_user']!=00){

    				         if($stateresult=$conn->query("SELECT id,Contact FROM Blood_Donation where  status=1 AND block_status=1 AND city IN (" . implode(',', array_map('intval', $area)) . ")")){

    				          while($staterow = $stateresult->fetch_assoc()){
    				               $minadata[$i]['Contact']=$staterow['Contact'];
    				               $i++;
    				          }

    				         }


    					  }
              		break;

              }
        		break;

            case 'Nokari Profile':
       		  $set=$setnew;
                switch ($admin_data[$set]['value']) {
                	case 'ST':

                	  $set++;
                	  $set++;
                	  $a=0;
                     unset($area);
      				while($admin_data[$set]['name']=='state_permit'){
      			        $area[$a]=$admin_data[$set]['value'];
      			        $set++;
      					$a++;
      				}
      		 			  if($module_permition[0]['nokari_user']!=00){

      				         if($stateresult=$conn->query("SELECT id,Contact FROM Nikari_user where  status=1 AND block_status=1 AND state IN (" . implode(',', array_map('intval', $area)) . ")")){

      				          while($staterow = $stateresult->fetch_assoc()){
      				               $minadata[$i]['Contact']=$staterow['Contact'];
      				               $i++;
      				          }

      				         }


      					  }
                		break;
                	case 'DI':
                    $set++;
                	  $set++;
                	  $set++;
                	  $a=0;
                	  unset($area);
      				while($admin_data[$set]['name']=='city_permit'){
      			        $area[$a]=$admin_data[$set]['value'];
      			        $set++;
      					$a++;
      				}

      		 			  if($module_permition[0]['nokari_user']!=00){

      				         if($stateresult=$conn->query("SELECT id,Contact FROM Nikari_user where  status=1 AND block_status=1 AND district IN (" . implode(',', array_map('intval', $area)) . ")")){

      				          while($staterow = $stateresult->fetch_assoc()){
      				               $minadata[$i]['Contact']=$staterow['Contact'];
      				               $i++;
      				          }

      				         }

      					  }
                		break;
                	case 'CT':
                    $set++;
                	  $set++;
                	  $set++;
                	  $set++;
                	  $a=0;
                	   unset($area);
      				while($admin_data[$set]['name']=='district_permit'){
      			        $area[$a]=$admin_data[$set]['value'];
      			        $set++;
      					$a++;
      				}
      		 			  if($module_permition[0]['nokari_user']!=00){

      				         if($stateresult=$conn->query("SELECT id,Contact FROM Nikari_user where  status=1 AND block_status=1 AND city IN (" . implode(',', array_map('intval', $area)) . ")")){

      				          while($staterow = $stateresult->fetch_assoc()){
      				               $minadata[$i]['Contact']=$staterow['Contact'];
      				               $i++;
      				          }

      				         }


      					  }
                		break;

                }
          		break;
    	   }
     $j++;
    }

    if($conn->query("INSERT INTO msg_send (admin_id,Title,Massage,Body) values (".$admin_data[1]['value'].",'".$admin_data[3]['value']."','".$admin_data[4]['value']."','".$admin_data[5]['value']."')")){
      $msg=array('success'=>1,'data'=>'msg send to all','data'=>$minadata);
      return $response->withJson($msg);
    }


  }
  $msg=array('success'=>1,'data'=>'somthing went wrong');
  return $response->withJson($msg);
});


?>
