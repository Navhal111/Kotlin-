<?php
$app->post('/seva_add', function($request,$response,$args) {
           $admin_data = $request->getParsedBody();
           $files = $request->getUploadedFiles();
          // $sql = "CREATE TABLE Seva_org(id int NOT NULL AUTO_INCREMENT,trust_name text,trust_contect text,trust_email text,trust_website text,logoname text,trusty_name text,trusty_contect text,vicetrust json,cummity json,aboutourwork text,aboutourtrust text,slogan text,trust_address text,country int,state int,district int,status tinyint,block_status tinyint,created_at text, PRIMARY KEY (id))";

          $trust_name =  $admin_data[3]['value'];
          $trust_contact  = $admin_data[4]['value'];
          $trust_email = $admin_data[5]['value'];
          $trust_website  = $admin_data[6]['value'];
          $logoname = $admin_data[7]['value'];
          $trusty_name =$admin_data[8]['value'];
          $trusty_contect = $admin_data[9]['value'];
          $i=10;
          $chek =0;
          while($admin_data[$i]['name'] == "trustname[".$chek."]"){
            $vicetrust[$chek]['trustyname'] = $admin_data[$i]['value'];
            $vicetrust[$chek]['trustcontact'] = $admin_data[$i+1]['value'];
            $chek++;
            $i = $i+2;
          }
          $chek =0;
          while($admin_data[$i]['name'] == "commityname[".$chek."]"){
            $cummity[$chek]["commityname"] = $admin_data[$i]['value'];
            $cummity[$chek]["commitywork"] = $admin_data[$i+1]['value'];
            $cummity[$chek]["commitycontact"] = $admin_data[$i+2]['value'];
            $chek++;
            $i = $i+3;
          }
          $aboutourwork = $admin_data[$i]['value'];$i++;
          $aboutourtrust = $admin_data[$i]['value'];$i++;
          $slogan = $admin_data[$i]['value'];$i++;
          $trust_address = $admin_data[$i]['value'];$i++;
          $country = $admin_data[$i]['value'];$i++;
          $state = $admin_data[$i]['value'];$i++;
          $city = $admin_data[$i]['value'];$i++;
          $district = $admin_data[$i]['value'];$i++;
          $org_pincode =$admin_data[$i]['value'];$i++;
          $editId = $admin_data[$i]['value'];$i = $i +2;
          $timeshedule = $admin_data[$i];


           if(!empty($files["filename"])) {

                 if(!isset($admin_data['id']) AND !isset($admin_data['token'])){
                   $msg = array("success" => 0,'msg'=>'not have permission');
                   return $response->withJson($msg);
                 }

                 $cheak = cheak_token($admin_data['id'],$admin_data['token']);
                  if($cheak['success'] == 0){
                    $msg = array("success" => 0,'msg'=>'unotherise');
                    return $response->withJson($msg);
                  }

                   $tmp= bin2hex(date('H:i:s'));
                   $newfile=$files["seva_file"];

                   if ($newfile->getError() == UPLOAD_ERR_OK) {
                      $uploadFileName = $newfile->getClientFilename();
                      $now_date=date('Y-m-d H:i:s');
                      $type = $newfile->getClientMediaType();
                      $findme = '/';
                      $pos = strpos($type, $findme);
                      $type1 = substr($type,0,$pos);
                      // return $response->withJson(array('type'=>$type1));
                    if($type1 == "image"){
                      $path =  __DIR__ ."/seva_logo/".$tmp."_".$now_date.'_'.$uploadFileName;
                      $newfile->moveTo($path);
                      $file_name = $tmp."_".$now_date.'_'.$uploadFileName;
                      $find = '.';
                      $po = strpos($file_name, $find);
                      $file_name1 = substr($file_name,0,$po);
                      $msg = array("success" => 1,'msg'=>'upload file ','image_name'=>$tmp."_".$now_date.'_'.$uploadFileName,'type_ogg'=>$type,'type'=>$type1);
                      return $response->withJson($msg);
                    }
                  }
                   $msg = array("success" => 2,'msg'=>'file not uplode');
                   return $response->withJson($msg);
           }

          $cheak = cheak_token($admin_data[1]['value'],$admin_data[2]['value']);

           if($cheak['success'] == 0){
             $msg = array("success" => 0,'msg'=>'unotherise');
             return $response->withJson($msg);
           }

           $conn = sqlConnection();
           if($result = $conn->query("SELECT seva_orgnization from module_permition where sub_admin_id = ".$admin_data[1]['value'])) {

                    while($row = $result->fetch_assoc()) {
                      $module_permition[] =$row;
                    }
           }

           if($module_permition[0]['seva_orgnization']==0000){
             $msg =array('success'=>0,'msg'=>'admin have not permission');
             return $response->withJson($msg);

           }
           try{
             if($admin_data[0]['value'] =='add'){
               $resule=$conn->prepare("SELECT trust_email from Seva_org where trust_email =? AND status = 1 AND block_status =1");
               if(!$resule){
                 echo "error";
               }

               $resule->bind_param('s', $admin_data[5]['value']);
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
                            if($data['data'][$CH]==$country){
                              $flag=1;
                            }
                      $CH++;
                     }
                  case 'ST':
                        $CH=0;
                        while(isset($data['data'][$CH])){
                               if($data['data'][$CH]==$state){
                                 $flag=1;
                               }
                               $CH++;
                        }


                  case 'DI':
                    $CH=0;

                      while(isset($data['data'][$CH])){
                             if($data['data'][$CH]==$city){
                               $flag=1;
                             }
                             $CH++;
                      }

                  case 'CT':
                    $CH=0;
                        while(isset($data['data'][$CH])){
                               if($data['data'][$CH]==$district){
                                 $flag=1;
                               }
                             $CH++;
                        }

                }
                // return $response->withJson(array('data'=>$flag));
                if ($flag==0) {

                  return $response->withJson(array('success'=>3,'msg'=>'Admin have not permission of this area'));


                }
                //  if(empty($admin_data[7]['value']) OR $admin_data[7]['value']==null){
                 //
                //    return $response->withJson(array('success'=>2,'msg'=>'Please set logo of your Seva orgnization'));
                 //
                //  }
                if($module_permition[0]['seva_orgnization']==1100 OR $module_permition[0]['seva_orgnization']==1110 OR $module_permition[0]['seva_orgnization']==1101 OR $module_permition[0]['seva_orgnization']==1111){
                  // $sql  ="INSERT INTO Seva_org(admin_id,trust_name,trust_contect,trust_email,trust_website,logoname,trusty_name,trusty_contect,vicetrust,cummity,aboutourwork,aboutourtrust,slogan,trust_address,country,state,city,district)VALUES (".$admin_data[1]['value'].",'".$trust_name."','".$trust_contact."','".$trust_email."','".$trust_website."','".$logoname."','".$trusty_name."','".$trusty_contect."','".json_encode($vicetrust)."','".json_encode($cummity)."','".$aboutourwork."','".$aboutourtrust."','".$slogan."','".$timeshedule."','".$trust_address."',".$country.",".$state.",".$city.",".$district.")";


if($conn->query("INSERT INTO Seva_org(admin_id,trust_name,trust_contect,trust_email,trust_website,logoname,trusty_name,trusty_contect,vicetrust,cummity,aboutourwork,aboutourtrust,slogan,timing,trust_address,pincode,country,state,city,district)VALUES (".$admin_data[1]['value'].",'".$trust_name."','".$trust_contact."','".$trust_email."','".$trust_website."','".$logoname."','".$trusty_name."','".$trusty_contect."','".json_encode($vicetrust)."','".json_encode($cummity)."','".$aboutourwork."','".$aboutourtrust."','".$slogan."','".json_encode($timeshedule)."','".$trust_address."','".$org_pincode."',".$country.",".$state.",".$city.",".$district.")")){

                       mysqli_close($conn);
                       $msg =array('success'=>1,'msg'=>'add data');
                       return $response->withJson($msg);


                      }
                      mysqli_close($conn);
                      $msg =array('success'=>0,'msg'=>'not add');
                      return $response->withJson($msg);
                }

              }


              if($admin_data[0]['value'] == 'edit'){

                $data=area_check($admin_data[1]['value'],$admin_data[2]['value']);
                // return $response->withJson(array('data'=>$data));
                $flag=0;
                switch($data['per']){
                  case 'GO':
                    $flag=1;
                  case 'CO':
                     $CH=0;
                     while(isset($data['data'][$CH])){
                            if($data['data'][$CH]==$country){
                              $flag=1;
                            }
                      $CH++;
                     }
                  case 'ST':
                        $CH=0;
                        while(isset($data['data'][$CH])){
                               if($data['data'][$CH]==$state){
                                 $flag=1;
                               }
                               $CH++;
                        }


                  case 'DI':
                    $CH=0;

                      while(isset($data['data'][$CH])){
                             if($data['data'][$CH]==$city){
                               $flag=1;
                             }
                             $CH++;
                      }

                  case 'CT':
                    $CH=0;
                        while(isset($data['data'][$CH])){
                               if($data['data'][$CH]==$district){
                                 $flag=1;
                               }
                             $CH++;
                        }

                }
                // return $response->withJson(array('data'=>$flag));
                if ($flag==0) {

                  return $response->withJson(array('success'=>3,'msg'=>'Admin have permission of this area'));

                }
                    if($module_permition[0]['seva_orgnization']==1110 OR $module_permition[0]['seva_orgnization']==1011 OR $module_permition[0]['seva_orgnization']==1010 OR $module_permition[0]['seva_orgnization']==1111){

                    //  return $response->withJson(array("dat"=>"UPDATE Seva_org SET trust_name = '".$trust_name."',trust_contect= '".$trusty_contect."',trust_email = '".$trust_email."',trust_website ='".$trust_website."' ,logoname ='".$logoname."' ,trusty_name = '".$trusty_name."' ,trusty_contect = '".$trusty_contect."' ,vicetrust = '".json_encode($vicetrust)."', cummity='".json_encode($cummity)."' ,aboutourwork ='".$aboutourwork."',aboutourtrust='".$aboutourtrust."',slogan='".$slogan."',timing='".json_encode($timeshedule)."',trust_address='".$trust_address."' ,country =".$country." ,state=".$state." ,city=".$city." ,district=".$district." where id =".$editId));
                    $resule=$conn->prepare("SELECT trust_email from Seva_org where trust_email =? AND status = 1 AND id!=".$editId);
                    if(!$resule){
                      echo "error";
                    }
                    $resule->bind_param('s', $trust_email);
                    $resule->execute();
                    $resule->store_result();
                    if($resule->num_rows >= 1 ){
                      $resule->close();
                      mysqli_close($conn);
                      $msg = array('success' => 2,'msg'=>'user allredy existdfgdfgdfg ');
                      return $response->withJson($msg);
                     }
if($conn->query("UPDATE Seva_org SET trust_name = '".$trust_name."',trust_contect= '".$trusty_contect."',trust_email = '".$trust_email."',trust_website ='".$trust_website."' ,logoname ='".$logoname."' ,trusty_name = '".$trusty_name."' ,trusty_contect = '".$trusty_contect."' ,vicetrust = '".json_encode($vicetrust)."', cummity='".json_encode($cummity)."' ,aboutourwork ='".$aboutourwork."',aboutourtrust='".$aboutourtrust."',slogan='".$slogan."',timing='".json_encode($timeshedule)."',trust_address='".$trust_address."',pincode='".$org_pincode."' ,country =".$country." ,state=".$state." ,city=".$city." ,district=".$district." where id =".$editId)){

                            if($conn->affected_rows > 0){
                              $msg =array('success'=>1,'msg'=>"Updated Orignization");
                              return $response->withJson($msg);
                            }else{
                              $msg =array('success'=>2,'msg'=>'not match any data to update');
                              return $response->withJson($msg);
                            }


                        }

                    }
                    mysqli_close($conn);

                    $msg =array('success'=>0,'msg'=>'admin have not permission');
                    return $response->withJson($msg);

            }

           }catch(Exception $e){
                  mysqli_close($conn);
                 return $response->withJson(array('data' => $e));
           }
           mysqli_close($conn);
           $msg =array('success'=>0,'msg'=>'somthing went wrong');
           return $response->withJson($msg);

});

$app->post('/seva_list', function($request,$response,$args) {
       $admin_data = $request->getParsedBody();
       $cheak = cheak_token($admin_data['id'],$admin_data['token']);
       if($cheak['success'] == 0){
         $msg = array("success" => 0,'msg'=>'unotherise');
         return $response->withJson($msg);
       }

       $conn = sqlConnection();
             if($result = $conn->query("SELECT role,seva_orgnization from module_permition where sub_admin_id = ".$admin_data['id'])) {
                      while($row = $result->fetch_assoc()) {

                        $module_permition[] =$row;
                      }
             }
             if($module_permition[0]["role"] == 1){
                       try{

                         if($result = $conn->query("SELECT id,trust_name,trust_contect,trusty_name,trusty_contect,block_status,status from Seva_org where status =1")) {
                             $i=0;
                             while($row = $result->fetch_assoc()) {
                                     $myArray[$i]['id'] = $row['id'];
                                     $myArray[$i]['trust_name'] = $row['trust_name'];
                                     $myArray[$i]['trust_contect'] = $row['trust_contect'];
                                     $myArray[$i]['trusty_name'] = $row['trusty_name'];
                                     $myArray[$i]['trusty_contect'] = $row['trusty_contect'];
                                     $myArray[$i]['status']=$row['status'];
                                     $myArray[$i]['block_status']=$row['block_status'];

                                    //  $myArray[$i]['created_at'] = $row['created_at'];
                                $i++;
                             }
                             mysqli_close($conn);

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
        if($module_permition[0]['seva_orgnization'] == 00){
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
                  if($result = $conn->query("SELECT id,trust_name,trust_contect,trusty_name,trusty_contect,block_status from Seva_org where status =1")) {
                      $i=0;
                      while($row = $result->fetch_assoc()) {
                        $myArray[$i]['id'] = $row['id'];
                        $myArray[$i]['trust_name'] = $row['trust_name'];
                        $myArray[$i]['trust_contect'] = $row['trust_contect'];
                        $myArray[$i]['trusty_name'] = $row['trusty_name'];
                        $myArray[$i]['trusty_contect'] = $row['trusty_contect'];
                        $myArray[$i]['status']=$row['status'];
                        $myArray[$i]['block_status']=$row['block_status'];

                             //  $myArray[$i]['created_at'] = $row['created_at'];
                         $i++;
                      }
                      mysqli_close($conn);

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
                       if($result = $conn->query("SELECT id,trust_name,trust_contect,trusty_name,trusty_contect,block_status from Seva_org where status =1 AND country IN (" . implode(',', array_map('intval', $area)) . ")")) {
                               $i=0;
                               while($row = $result->fetch_assoc()) {
                                 $myArray[$i]['id'] = $row['id'];
                                 $myArray[$i]['trust_name'] = $row['trust_name'];
                                 $myArray[$i]['trust_contect'] = $row['trust_contect'];
                                 $myArray[$i]['trusty_name'] = $row['trusty_name'];
                                 $myArray[$i]['trusty_contect'] = $row['trusty_contect'];
                                 $myArray[$i]['status']=$row['status'];
                                 $myArray[$i]['block_status']=$row['block_status'];

                                  $i++;
                               }
                               mysqli_close($conn);

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
                       if($result = $conn->query("SELECT id,trust_name,trust_contect,trusty_name,trusty_contect,block_status from Seva_org where status =1 AND state IN (" . implode(',', array_map('intval', $area)) . ")")) {
                               $i=0;
                               while($row = $result->fetch_assoc()) {
                                 $myArray[$i]['id'] = $row['id'];
                                 $myArray[$i]['trust_name'] = $row['trust_name'];
                                 $myArray[$i]['trust_contect'] = $row['trust_contect'];
                                 $myArray[$i]['trusty_name'] = $row['trusty_name'];
                                 $myArray[$i]['trusty_contect'] = $row['trusty_contect'];
                                 $myArray[$i]['status']=$row['status'];
                                 $myArray[$i]['block_status']=$row['block_status'];

                                      //  $myArray[$i]['created_at'] = $row['created_at'];
                                  $i++;
                               }
                               mysqli_close($conn);

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

                       if($result = $conn->query("SELECT id,trust_name,trust_contect,trusty_name,trusty_contect,block_status from Seva_org where status =1 AND distric IN (" . implode(',', array_map('intval', $area)) . ")")) {
                               $i=0;

                               while($row = $result->fetch_assoc()) {
                                 $myArray[$i]['id'] = $row['id'];
                                 $myArray[$i]['trust_name'] = $row['trust_name'];
                                 $myArray[$i]['trust_contect'] = $row['trust_contect'];
                                 $myArray[$i]['trusty_name'] = $row['trusty_name'];
                                 $myArray[$i]['trusty_contect'] = $row['trusty_contect'];
                                 $myArray[$i]['status']=$row['status'];
                                 $myArray[$i]['block_status']=$row['block_status'];

                                      //  $myArray[$i]['created_at'] = $row['created_at'];
                                  $i++;
                               }

                               mysqli_close($conn);
                              //  $data = array("status"=>1, "data"=>$myArray);
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
                       if($result = $conn->query("SELECT id,trust_name,trust_contect,trusty_name,trusty_contect,block_status from Seva_org where status =1 AND city IN (" . implode(',', array_map('intval', $area)) . ")")) {
                               $i=0;
                               while($row = $result->fetch_assoc()) {
                                 $myArray[$i]['id'] = $row['id'];
                                 $myArray[$i]['trust_name'] = $row['trust_name'];
                                 $myArray[$i]['trust_contect'] = $row['trust_contect'];
                                 $myArray[$i]['trusty_name'] = $row['trusty_name'];
                                 $myArray[$i]['trusty_contect'] = $row['trusty_contect'];
                                 $myArray[$i]['status']=$row['status'];
                                 $myArray[$i]['block_status']=$row['block_status'];

                                      //  $myArray[$i]['created_at'] = $row['created_at'];
                                  $i++;
                               }
                               mysqli_close($conn);

                               $data = array("status"=>1, "data"=>$myArray);
                               return $response->withJson($myArray);
                       }
              }
            }
  }catch(Exception $e){
    $msg = array("success" => 0,'mag'=>$e);
    return $response->withJson(array('response'=>$msg));
  }
  mysqli_close($conn);
 return $response->withJson(array('success'=>0,'msg'=>'something went wrong'));
}

});
$app->post('/seva_view', function($request,$response) {

     $admin_data = $request->getParsedBody();
     $cheak = cheak_token($admin_data['id'],$admin_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }

      $conn = sqlConnection();
      if($result = $conn->query("SELECT seva_orgnization from module_permition where sub_admin_id = ".$admin_data['id'])) {

               while($row = $result->fetch_assoc()) {
                 $module_permition[] =$row;
               }
      }
      if($module_permition[0]['seva_orgnization'] == 00){
              $result->close;
              mysqli_close($conn);
              $msg = array("success" => 0,'mas'=>'admin no permition');
              return $response->withJson($msg);
        }
              //  return $response->withJson(array('error'=>"SELECT id,org_name,owner_name,onwe_contact,owner_email,org_cat,discription,or_add,website,email,country,state,distric,city,pincode,block_status,created_at from Seva_org where status =1 AND id =".$admin_data['seva_id']));
try{
          if($result = $conn->query("SELECT * from Seva_org where status =1 AND id =".$admin_data['seva_id'])) {
            $i=0;
            while($row = $result->fetch_assoc()) {
                   $res=$conn->query("SELECT name from m_area where id = ".$row['country']);
                   $country=$res->fetch_assoc();
                   $res=$conn->query("SELECT name from m_area where id = ".$row['state']);
                   $state=$res->fetch_assoc();
                   $res=$conn->query("SELECT name from m_area where id = ".$row['city']);
                   $city=$res->fetch_assoc();
                   $res=$conn->query("SELECT name from m_area where id = ".$row['district']);
                   $distric=$res->fetch_assoc();
                    $myArray[$i]['id'] = $row['id'];
                    $myArray[$i]['trust_name'] = $row['trust_name'];
                    $myArray[$i]['trust_contect'] = $row['trust_contect'];
                    $myArray[$i]['trust_email'] = $row['trust_email'];
                    $myArray[$i]['trust_website'] = $row['trust_website'];
                    $myArray[$i]['logoname'] = $row['logoname'];
                    $myArray[$i]['trusty_name'] = $row['trusty_name'];
                    $myArray[$i]['trusty_contect'] = $row['trusty_contect'];
                    $myArray[$i]['vicetrust']=$row['vicetrust'];
                    $myArray[$i]['cummity'] = $row['cummity'];
                    $myArray[$i]['aboutourwork'] = $row['aboutourwork'];
                    $myArray[$i]['aboutourtrust'] = $row['aboutourtrust'];
                    $myArray[$i]['slogan'] = $row['slogan'];
                    $myArray[$i]['timing'] = $row['timing'];
                    $myArray[$i]['trust_address'] = $row['trust_address'];
                    $myArray[$i]['country'] = $country['name'];
                    $myArray[$i]['state'] = $state['name'];
                    $myArray[$i]['ciry'] = $city['name'];
                    $myArray[$i]['district'] = $distric['name'];
                    $myArray[$i]['pincode'] = $row['pincode'];
                    $myArray[$i]['created_at'] = $row['created_at'];
               $i++;
            }
            mysqli_close($conn);
            return $response->withJson(array('success' =>1 ,'data'=>$myArray ));
        }

      }catch(Exception $e){
        $msg = array("success" => 0,'mas'=>$e);
        return $response->withJson(array('response'=>$msg));
      }
     mysqli_close($conn);
     return $response->withJson(array('success'=>0,'mag'=>'something went wrong'));

});
$app->post('/seva_remove', function($request,$response) {
      $admin_data = $request->getParsedBody();
      $cheak = cheak_token($admin_data['id'],$admin_data['token']);

      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
      $conn = sqlConnection();
      if($result = $conn->query("SELECT seva_orgnization from module_permition where sub_admin_id = ".$admin_data['id'])) {

               while($row = $result->fetch_assoc()) {
                 $module_permition[] =$row;
               }
      }

      if($module_permition[0]['seva_orgnization'] == 1001 OR $module_permition[0]['seva_orgnization'] == 1011 OR $module_permition[0]['seva_orgnization'] == 1111 OR $module_permition[0]['seva_orgnization'] == 1101 ){

        try{
              $result=$conn->prepare("UPDATE Seva_org set status = 0 where id= ?");
              // $resule->bind_param('s', $name);
              $result->bind_param('s', $admin_data['seva_id']);
              $result->execute();
              $result->store_result();
              $result->close();
              mysqli_close($conn);
              $msg = array('success' => 1,'msg'=>'orgnisation will delete');
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
      $msg = array('success' => 0,'msg'=>'Somthing went wrong');
      return $response->withJson($msg);
});
$app->post('/seva_block', function($request,$response) {
      $admin_data = $request->getParsedBody();
      $cheak = cheak_token($admin_data['id'],$admin_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
      $conn = sqlConnection();
      if($result = $conn->query("SELECT seva_orgnization from module_permition where sub_admin_id = ".$admin_data['id'])) {

               while($row = $result->fetch_assoc()) {
                 $module_permition[] =$row;
               }

      }

      if($module_permition[0]['seva_orgnization'] == 1001 OR $module_permition[0]['seva_orgnization'] == 1011 OR $module_permition[0]['seva_orgnization'] == 1111 OR $module_permition[0]['seva_orgnization'] == 1101 ){

          if($result = $conn->query("SELECT block_status from Seva_org where id = ".$admin_data['seva_id'])) {
              while($row = $result->fetch_assoc()) {
                    $myArray[] = $row;
              }
           }
           if($myArray[0]['block_status']==0){
             try{
                   $result=$conn->prepare("UPDATE Seva_org set block_status = 1 where id= ?");
                   // $resule->bind_param('s', $name);
                   $result->bind_param('s', $admin_data['seva_id']);
                   $result->execute();
                   $result->store_result();
                   $result->close();
                   mysqli_close($conn);
                   $msg = array('success' => 1,'msg'=>'seva_orgnization will enable');
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
                     $result=$conn->prepare("UPDATE Seva_org set block_status = 0 where id= ?");
                     $result->bind_param('s', $admin_data['seva_id']);
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
                $msg = array('success' => 1,'msg'=>'seva_orgnization will disable');
                return $response->withJson($msg);
           }
           }

      mysqli_close($conn);
      $msg = array("success" => 0,'msg'=>'Somthing went wrong');
      return $response->withJson($msg);

});
$app->post('/seva_edit', function($request,$response) {
      $admin_data = $request->getParsedBody();
      $cheak = cheak_token($admin_data['id'],$admin_data['token']);

      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
      $conn = sqlConnection();
      if($result = $conn->query("SELECT seva_orgnization from module_permition where sub_admin_id = ".$admin_data['id'])) {

               while($row = $result->fetch_assoc()) {
                 $module_permition[] =$row;
               }
      }
      if($module_permition[0]['seva_orgnization']==1110 OR $module_permition[0]['seva_orgnization']==1011 OR $module_permition[0]['seva_orgnization']==1010 OR $module_permition[0]['seva_orgnization']==1111){

            if($result=$conn->query("SELECT * from Seva_org where id= ".$admin_data['seva_id'])){

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
  $app->get('/seva_list_open', function($request,$response,$args) {
    $conn =  sqlConnection();
    if($result = $conn->query("SELECT * from Seva_org where status =1 AND block_status=1")) {
        $i=0;
        while($row = $result->fetch_assoc()) {
          $res=$conn->query("SELECT name from m_area where id = ".$row['country']);
          $country=$res->fetch_assoc();
          $res=$conn->query("SELECT name from m_area where id = ".$row['state']);
          $state=$res->fetch_assoc();
          $res=$conn->query("SELECT name from m_area where id = ".$row['city']);
          $city=$res->fetch_assoc();
          $res=$conn->query("SELECT name from m_area where id = ".$row['district']);
          $distric=$res->fetch_assoc();
           $myArray[$i]['id'] = $row['id'];
           $myArray[$i]['trust_name'] = $row['trust_name'];
           $myArray[$i]['trust_contect'] = $row['trust_contect'];
           $myArray[$i]['trust_email'] = $row['trust_email'];
           $myArray[$i]['trust_website'] = $row['trust_website'];
           $myArray[$i]['logoname'] = $row['logoname'];
           $myArray[$i]['trusty_name'] = $row['trusty_name'];
           $myArray[$i]['trusty_contect'] = $row['trusty_contect'];
           $myArray[$i]['vicetrust']=$row['vicetrust'];
           $myArray[$i]['cummity'] = $row['cummity'];
           $myArray[$i]['aboutourwork'] = $row['aboutourwork'];
           $myArray[$i]['aboutourtrust'] = $row['aboutourtrust'];
           $myArray[$i]['slogan'] = $row['slogan'];
           $myArray[$i]['timing'] = $row['timing'];
           $myArray[$i]['trust_address'] = $row['trust_address'];
           $myArray[$i]['country'] = $country['name'];
           $myArray[$i]['state'] = $state['name'];
           $myArray[$i]['ciry'] = $city['name'];
           $myArray[$i]['district'] = $distric['name'];
           $myArray[$i]['created_at'] = $row['created_at'];

               //  $myArray[$i]['created_at'] = $row['created_at'];
           $i++;
        }
        mysqli_close($conn);

        $data = array("status"=>1, "data"=>$myArray);
        return $response->withJson($myArray);
     }


  });
?>
