<?php
 $app->post('/advertisement_add', function($request,$response,$args) {
   $files = $request->getUploadedFiles();
   $adve_data = $request->getParsedBody();

   if(!empty($files["ad_file"])) {

         if(!isset($adve_data['id']) AND !isset($adve_data['token'])){
           $msg = array("success" => 0,'msg'=>'not have permission');
           return $response->withJson($msg);
         }

         $cheak = cheak_token($adve_data['id'],$adve_data['token']);
          if($cheak['success'] == 0){
            $msg = array("success" => 0,'msg'=>'unotherise');
            return $response->withJson($msg);
          }

           $tmp= bin2hex(date('H:i:s'));
           $newfile=$files["ad_file"];

           if ($newfile->getError() == UPLOAD_ERR_OK) {
              $uploadFileName = $newfile->getClientFilename();
              $now_date=date('Y-m-d H:i:s');
              $type = $newfile->getClientMediaType();
              $findme = '/';
              $pos = strpos($type, $findme);
              $type1 = substr($type,0,$pos);
              // return $response->withJson(array('type'=>$type1));
            if($type1 == "image" OR $type1 == "video" OR $type1 == "audio"){
              $path =  __DIR__ ."/demo_upload/".$tmp."_".$now_date.'_'.$uploadFileName;
              $newfile->moveTo($path);
              $file_name = $tmp."_".$now_date.'_'.$uploadFileName;
              $find = '.';
              $po = strpos($file_name, $find);
              $file_name1 = substr($file_name,0,$po);
              $msg = array("success" => 1,'msg'=>'upload file ','image_name'=>$tmp."_".$now_date.'_'.$uploadFileName,'image_ogg'=>$file_name1,'type_ogg'=>$type,'type'=>$type1);
              return $response->withJson($msg);
            }
          }
           $msg = array("success" => 2,'msg'=>'file not uplode');
           return $response->withJson($msg);
   }


      $cheak = cheak_token($adve_data[1]['value'],$adve_data[2]['value']);
       if($cheak['success'] == 0){
         $msg = array("success" => 0,'msg'=>'unotherise');
         return $response->withJson($msg);
       }

       $conn = sqlConnection();
    try{
       if($result = $conn->query("SELECT addvtisement from module_permition where sub_admin_id = ".$adve_data[1]['value'])) {

                while($row = $result->fetch_assoc()) {
                  $module_permition[] =$row;
                }
      }
        if($module_permition[0]['addvtisement']==0000){
                  $msg =array('success'=>0,'msg'=>'admin have not permission this module');
                  return $response->withJson($msg);
        }
        if($adve_data[0]['value'] =='add'){
          // $file = fopen("adv.txt","w");
          // echo fwrite($file,json_encode($adve_data));
          // fclose($file);
                $resule=$conn->prepare("SELECT email from admin_advtisment where email =? AND status = 1");
                if(!$resule){
                  return $response->withJson(array('error'=>'notadd'));
                }
                $resule->bind_param('s', $adve_data[8]['value']);
                $resule->execute();
                $resule->store_result();
                if($resule->num_rows >= 1 ){
                  $resule->close();
                  mysqli_close($conn);
                  $msg = array('success' => 2,'msg'=>'user allredy exist ');
                  return $response->withJson($msg);
                 }
                 if(empty($adve_data[3]['value'])){
                   $msg = array('success' => 3,'msg'=>'file not selected');
                   return $response->withJson($msg);

                 }
                 if(empty($adve_data[7]['value'])){
                   $msg = array('success' => 4,'msg'=>'discription not added');
                   return $response->withJson($msg);
                 }
                 $data=area_check($adve_data[1]['value'],$adve_data[2]['value']);
                 // return $response->withJson(array('data'=>$data));
                 $flag=0;
                 switch($data['per']){
                   case 'GO':
                     $flag=1;
                   case 'CO':
                      $CH=0;
                      while(isset($data['data'][$CH])){
                             if($data['data'][$CH]==$adve_data[11]['value']){
                               $flag=1;
                             }
                       $CH++;
                      }
                   case 'ST':
                         $CH=0;
                         while(isset($data['data'][$CH])){
                                if($data['data'][$CH]==$adve_data[12]['value']){
                                  $flag=1;
                                }
                                $CH++;
                         }


                   case 'DI':
                     $CH=0;

                       while(isset($data['data'][$CH])){
                              if($data['data'][$CH]==$adve_data[13]['value']){
                                $flag=1;
                              }
                              $CH++;
                       }

                   case 'CT':
                     $CH=0;

                         while(isset($data['data'][$CH])){
                                if($data['data'][$CH]==$adve_data[14]['value']){
                                  $flag=1;
                                }
                              $CH++;
                         }

                 }
                 if ($flag==0) {

                   return $response->withJson(array('success'=>3,'msg'=>'Admin have permission of this area'));

                 }

                if($module_permition[0]['addvtisement']==1100 OR $module_permition[0]['addvtisement']==1110 OR $module_permition[0]['addvtisement']==1101 OR $module_permition[0]['addvtisement']==1111){
                      if($conn->query("INSERT INTO admin_advtisment(admin_id,title,discription,file_name,file_type,country,state,city,distric,module_name,start_time,end_time,email,phone_num,contact_name,area_per) VALUES (".$adve_data[1]['value'].",'".$adve_data[5]['value']."','".$adve_data[7]['value']."','".$adve_data[3]['value']."','".$adve_data[4]['value']."',".$adve_data[11]['value'].",".$adve_data[12]['value'].",".$adve_data[13]['value'].",".$adve_data[14]['value'].",'".$adve_data[15]['value']."','".$adve_data[16]['value']."','".$adve_data[17]['value']."','".$adve_data[8]['value']."',".$adve_data[10]['value'].",'".$adve_data[9]['value']."','".$adve_data[18]['value']."')")){
                       $advs_id=$conn->insert_id;
                       $p=19;
                           switch ($adve_data[18]['value']) {
                                  case 'GO':
                                        if($conn->query("INSERT INTO adv_aria_permition(adv_id,country,state,city,distric) values (".$advs_id.",0,0,0,0)")){

                                        $area_permision='GO';

                                         }
                                         break;
                                  case 'CO':
                                        while($adve_data[$p]['name']=="country_permit"){
                                        if($conn->query("INSERT INTO adv_aria_permition(adv_id,country,state,city,distric) values (".$advs_id.",".$adve_data[$p]['value'].",0,0,0)")){
                                          $area_permision="co";
                                           $p++;
                                         }
                                        }
                                       break;
                                  case 'ST':
                                        $co=$adve_data[$p]['value'];$p++;
                                        while($adve_data[$p]['name']=="state_permit"){
                                        if($conn->query("INSERT INTO adv_aria_permition(adv_id,country,state,city,distric) values (".$advs_id.",".$co.",".$adve_data[$p]['value'].",0,0)")){
                                          $area_permision="st";
                                          $p++;
                                        }
                                       }
                                      break;
                                  case 'DI':

                                        $co=$adve_data[$p]['value'];$p++;
                                        $st=$adve_data[$p]['value'];$p++;
                                        // $sql ="INSERT INTO adv_aria_permition(adv_id,country,state,city,distric) values (".$advs_id.",".$co.",".$st.",".$adve_data[$p]['value'].",0)";
                                        // return $response->withJson(array('sql'=>$sql));
                                        while($adve_data[$p]['name']=="city_permit"){
                                            if($conn->query("INSERT INTO adv_aria_permition(adv_id,country,state,city,distric) values (".$advs_id.",".$co.",".$st.",".$adve_data[$p]['value'].",0)")){
                                              $area_permision="ct";
                                              $p++;

                                            }
                                           }
                                          // $sql = "INSERT INTO sub_aria_permition(area_id,sub_admin_id,country,state,city,distric,sub_distric) values (".$area_permition_id.",".$sub_admin_id.",".$co.",".$st.",".$admin_data[$p]['value'].",0,0)";
                                          // return $response->withJson(array('q'=>$sql));
                                        break;
                                  case 'CT':
                                        $co=$adve_data[$p]['value'];$p++;
                                        $st=$adve_data[$p]['value'];$p++;
                                        $ct=$adve_data[$p]['value'];$p++;
                                        while($adve_data[$p]['name']=="district_permit"){
                                            if($conn->query("INSERT INTO adv_aria_permition(adv_id,country,state,city,distric) values (".$advs_id.",".$co.",".$st.",".$ct.",".$adve_data[$p]['value'].")")){
                                                $area_permision="st";
                                                $p++;
                                              }
                                          }
                                        break;

                           }
                       $path1 =  "demo_upload/".$adve_data[3]['value'];
                       $path2 =  "advetisement/".$adve_data[3]['value'];
                       copy($path1, $path2);
                      //  $files = glob("demo_upload/*");
                      //   foreach($files as $file){
                      //       if(is_file($file))
                      //       unlink($file);
                      //   }
                       mysqli_close($conn);
                       $msg =array('success'=>1,'msg'=>'add data');
                       return $response->withJson($msg);

                      }
                      mysqli_close($conn);
                      $msg =array('success'=>0,'msg'=>'not add');
                      return $response->withJson($msg);
                }
      }
      if($adve_data[0]['value'] =='edit'){
         if($module_permition[0]['addvtisement']==1110 OR $module_permition[0]['addvtisement']==1011 OR $module_permition[0]['addvtisement']==1010 OR $module_permition[0]['addvtisement']==1111){
              if(empty($adve_data[8]['value'])){
                $msg = array('success' => 4,'msg'=>'discription not added');
                return $response->withJson($msg);
              }



              if($result = $conn->query("SELECT file_name from admin_advtisment where status=1 AND id = ".$adve_data[3]['value'])) {
                  $i=0;

                  while($row = $result->fetch_assoc()) {
                          $file[] = $row;
                     }
                  }
                if($adve_data[4]['value']!=$file[0]['file_name']){
                  $old = "advetisement/".$file[0]['file_name'];
                  unlink($old);
                  $path1 =  "demo_upload/".$adve_data[4]['value'];
                  $path2 =  "advetisement/".$adve_data[4]['value'];
                  copy($path1, $path2);
                }
                $data=area_check($adve_data[1]['value'],$adve_data[2]['value']);
                // return $response->withJson(array('data'=>$data));
                $flag=0;
                switch($data['per']){
                  case 'GO':
                    $flag=1;
                  case 'CO':
                     $CH=0;
                     while(isset($data['data'][$CH])){
                            if($data['data'][$CH]==$adve_data[12]['value']){
                              $flag=1;
                            }
                      $CH++;
                     }
                  case 'ST':
                        $CH=0;
                        while(isset($data['data'][$CH])){
                               if($data['data'][$CH]==$adve_data[13]['value']){
                                 $flag=1;
                               }
                               $CH++;
                        }


                  case 'DI':
                    $CH=0;

                      while(isset($data['data'][$CH])){
                             if($data['data'][$CH]==$adve_data[14]['value']){
                               $flag=1;
                             }
                             $CH++;
                      }

                  case 'CT':
                    $CH=0;
                        while(isset($data['data'][$CH])){
                               if($data['data'][$CH]==$adve_data[15]['value']){
                                 $flag=1;
                               }
                             $CH++;
                        }

                }
                // return $response->withJson(array('data'=>$flag));
                if ($flag==0) {

                  return $response->withJson(array('success'=>3,'msg'=>'Admin have not permission of this area'));

                }
                $resule=$conn->prepare("SELECT email from admin_advtisment where email =? AND status = 1 AND id!=".$adve_data[3]['value']);
                if(!$resule){
                  return $response->withJson(array('error'=>'notadd'));
                }
                $resule->bind_param('s', $adve_data[9]['value']);
                $resule->execute();
                $resule->store_result();
                if($resule->num_rows >= 1 ){
                  $resule->close();
                  mysqli_close($conn);
                  $msg = array('success' => 2,'msg'=>'user allredy exist ');
                  return $response->withJson($msg);
                 }
if($conn->query("UPDATE admin_advtisment SET admin_id = ".$adve_data[1]['value'].",title='".$adve_data[6]['value']."',discription='".$adve_data[8]['value']."',file_name='".$adve_data[4]['value']."',file_type='".$adve_data[5]['value']."',country=".$adve_data[12]['value'].",state=".$adve_data[13]['value'].",city=".$adve_data[14]['value'].",distric=".$adve_data[15]['value'].",module_name='".$adve_data[16]['value']."',start_time='".$adve_data[17]['value']."',end_time='".$adve_data[18]['value']."',email='".$adve_data[9]['value']."',phone_num=".$adve_data[11]['value'].",contact_name='".$adve_data[10]['value']."',area_per='".$adve_data[19]['value']."' where id=".$adve_data[3]['value'])){

              $conn->query("DELETE from adv_aria_permition where adv_id=".$adve_data[3]['value']);
              $p=20;
              switch ($adve_data[17]['value']) {
                     case 'GO':
                           if($conn->query("INSERT INTO adv_aria_permition(adv_id,country,state,city,distric) values (".$adve_data[3]['value'].",0,0,0,0)")){

                           $area_permision='GO';

                            }
                            break;
                     case 'CO':
                           while($adve_data[$p]['name']=="country_permit"){
                           if($conn->query("INSERT INTO adv_aria_permition(adv_id,country,state,city,distric) values (".$adve_data[3]['value'].",".$adve_data[$p]['value'].",0,0,0)")){
                             $area_permision="co";
                              $p++;
                            }
                           }
                          break;
                     case 'ST':
                           $co=$adve_data[$p]['value'];$p++;
                           while($adve_data[$p]['name']=="state_permit"){
                           if($conn->query("INSERT INTO adv_aria_permition(adv_id,country,state,city,distric) values (".$adve_data[3]['value'].",".$co.",".$adve_data[$p]['value'].",0,0)")){
                             $area_permision="st";
                             $p++;
                           }
                          }
                         break;
                     case 'DI':

                           $co=$adve_data[$p]['value'];$p++;
                           $st=$adve_data[$p]['value'];$p++;
                           while($adve_data[$p]['name']=="city_permit"){
                               if($conn->query("INSERT INTO adv_aria_permition(adv_id,country,state,city,distric) values (".$adve_data[3]['value'].",".$co.",".$st.",".$adve_data[$p]['value'].",0)")){
                                 $area_permision="ct";
                                 $p++;

                               }
                              }
                           break;
                     case 'CT':
                           $co=$adve_data[$p]['value'];$p++;
                           $st=$adve_data[$p]['value'];$p++;
                           $ct=$adve_data[$p]['value'];$p++;
                           while($adve_data[$p]['name']=="district_permit"){
                               if($conn->query("INSERT INTO adv_aria_permition(adv_id,country,state,city,distric) values (".$adve_data[3]['value'].",".$co.",".$st.",".$ct.",".$adve_data[$p]['value'].")")){
                                   $area_permision="st";
                                   $p++;
                                 }
                             }
                           break;

              }


                $msg =array('success'=>1,'msg'=>'update data');
                return $response->withJson($msg);

             }

         }
      }
    }catch(Exception $e){
      mysqli_close($conn);
      $msg =array('success'=>0,'msg'=>$e);
      return $response->withJson($msg);

    }
    $msg =array('success'=>0,'msg'=>'not add data');
    return $response->withJson($msg);
 });
$app->post('/addvtisement_list', function($request,$response){
      $admin_data = $request->getParsedBody();
      $cheak = cheak_token($admin_data['id'],$admin_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);

      }
        $conn = sqlConnection();
        if($result = $conn->query("SELECT role,addvtisement from module_permition where sub_admin_id = ".$admin_data['id'])) {
                 while($row = $result->fetch_assoc()) {

                   $module_permition[] =$row;
                 }
        }

        if($module_permition[0]["role"] == 1){
          try{
            if($result = $conn->query("SELECT id,title,email,phone_num,contact_name,created_at,block_status from admin_advtisment where status=1")) {

                while($row = $result->fetch_assoc()) {

                        $myArray[] = $row;
                }
                mysqli_close($conn);
                return $response->withJson($myArray);
             }

          }catch(Exception $e){
                return $response->withJson(array('msg'=>$e));
            }
        }

        if($module_permition[0]["role"] == 2){

          if($module_permition[0]['addvtisement'] == 00){
            mysqli_close($conn);
            $msg = array("success" => 0,'mas'=>'admin no permition');
            return $response->withJson($msg);
          }

         if($result = $conn->query("SELECT permition_type from aria_permition where sub_admin_id = " .$admin_data['id'])) {
                   $permition=$result->fetch_assoc();
                   switch ($permition['permition_type']) {
                             case 'GO':
                             if($result = $conn->query("SELECT id,title,email,phone_num,contact_name,created_at,block_status from admin_advtisment where status=1")) {

                                 while($row = $result->fetch_assoc()) {

                                         $myArray[] = $row;
                                 }
                               }
                                 return $response->withJson($myArray);
                                 break;
                              case 'CO':
                              if($result = $conn->query("SELECT country from sub_aria_permition where sub_admin_id = ".$admin_data['id'])) {
                                     $m=0;
                                     while($row = $result->fetch_assoc()) {
                                       $area[$m]=$row['country'];
                                       $m++;
                                     }

                               }
                               if($result = $conn->query("SELECT id,title,email,phone_num,contact_name,created_at,block_status from admin_advtisment where status=1 AND country IN (" . implode(',', array_map('intval', $area)) . ")")) {

                                   while($row = $result->fetch_assoc()) {

                                           $myArray[] = $row;
                                   }
                                 }
                                 return $response->withJson($myArray);
                                 break;
                               case 'ST':
                               if($result = $conn->query("SELECT state from sub_aria_permition where sub_admin_id = ".$admin_data['id'])) {
                                      $m=0;
                                      while($row = $result->fetch_assoc()) {
                                        $area[$m]=$row['state'];
                                        $m++;
                                      }

                                }
                                if($result = $conn->query("SELECT id,title,email,phone_num,contact_name,created_at,block_status from admin_advtisment where status=1 AND state IN (" . implode(',', array_map('intval', $area)) . ")")) {

                                    while($row = $result->fetch_assoc()) {

                                            $myArray[] = $row;
                                    }
                                  }
                                  return $response->withJson($myArray);
                                  break;

                                case 'DI':
                                  if($result = $conn->query("SELECT city from sub_aria_permition where sub_admin_id = ".$admin_data['id'])) {
                                         $m=0;
                                         while($row = $result->fetch_assoc()) {
                                           $area[$m]=$row['city'];
                                           $m++;
                                         }

                                   }

                                   if($result = $conn->query("SELECT id,title,email,phone_num,contact_name,created_at,block_status from admin_advtisment where status=1 AND city IN (" . implode(',', array_map('intval', $area)) . ")")) {

                                       while($row = $result->fetch_assoc()) {

                                               $myArray[] = $row;
                                       }
                                     }
                                     mysqli_close($conn);
                                     return $response->withJson($myArray);
                                     break;

                                  case 'CT':

                                    if($result = $conn->query("SELECT distric from sub_aria_permition where sub_admin_id = ".$admin_data['id'])) {
                                            $m=0;
                                            while($row = $result->fetch_assoc()) {
                                            $area[$m]=$row['distric'];
                                            $m++;
                                          }

                                        }
                                        if($result = $conn->query("SELECT id,title,email,phone_num,contact_name,created_at,block_status from admin_advtisment where status=1 AND distric IN (" . implode(',', array_map('intval', $area)) . ")")) {

                                            while($row = $result->fetch_assoc()) {

                                                    $myArray[] = $row;
                                            }
                                          }
                                          mysqli_close($conn);
                                          return $response->withJson($myArray);
                                          break;

            }
    }

        }
        mysqli_close($conn);
        $data = array("status"=>0, "msg"=>'not send list');
        return $response->withJson($data);


});


$app->post('/advertisement_view', function($request,$response) {

     $admin_data = $request->getParsedBody();
     $cheak = cheak_token($admin_data['id'],$admin_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
   $conn =sqlConnection();

   if($result = $conn->query("SELECT addvtisement from module_permition where sub_admin_id = ".$admin_data['id'])) {

            while($row = $result->fetch_assoc()) {
              $module_permition[] =$row;
            }
   }
   if($module_permition[0]['addvtisement'] == 00){
           $result->close;
           mysqli_close($conn);
           $msg = array("success" => 0,'mas'=>'admin no permition');
           return $response->withJson($msg);
     }

    try{
      if($result = $conn->query("SELECT id,title,discription,email,phone_num,contact_name,created_at,country,state,city,distric,sub_distric,file_name,file_type,module_name,start_time,end_time from admin_advtisment where status=1 AND id = ".$admin_data['adv_id'])) {
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
                   $myArray[$i]['title'] = $row['title'];
                   $myArray[$i]['discription'] = $row['discription'];
                   $myArray[$i]['email'] = $row['email'];
                   $myArray[$i]['phone_num'] = $row['phone_num'];
                   $myArray[$i]['contact_name'] = $row['contact_name'];
                   $myArray[$i]['created_at'] = $row['created_at'];
                   $myArray[$i]['file_name'] = $row['file_name'];
                   $myArray[$i]['file_type'] = $row['file_type'];
                   $myArray[$i]['module_name'] = $row['module_name'];
                   $myArray[$i]['start_time'] = $row['start_time'];
                   $myArray[$i]['end_time'] = $row['end_time'];
                   $myArray[$i]['country'] = $country['name'];
                   $myArray[$i]['state'] = $state['name'];
                   $myArray[$i]['city'] = $city['name'];
                   $myArray[$i]['distric'] = $distric['name'];
                   $i++;
          }

          $msg = array("success" => 1,'data'=>$myArray);
          return $response->withJson($msg);

        }

    }catch(Exception $e){
      $msg = array("success" => 0,'mas'=>$e);
      return $response->withJson(array('response'=>$msg));

    }
    $msg = array("success" => 0,'mas'=>'ddg');
    return $response->withJson($msg);

});

$app->post('/addvtisement_block', function($request,$response) {
      $admin_data = $request->getParsedBody();
      $cheak = cheak_token($admin_data['id'],$admin_data['token']);

      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
      $conn = sqlConnection();
      if($result = $conn->query("SELECT addvtisement from module_permition where sub_admin_id = ".$admin_data['id'])) {

               while($row = $result->fetch_assoc()) {
                 $module_permition[] =$row;
               }
      }

      if($module_permition[0]['addvtisement'] == 0000){
              $result->close;
              mysqli_close($conn);
              $msg = array("success" => 0,'mas'=>'admin have no permition');
              return $response->withJson($msg);
        }
        if($module_permition[0]['addvtisement'] == 1001 OR $module_permition[0]['addvtisement'] == 1011 OR $module_permition[0]['addvtisement'] == 1111 OR $module_permition[0]['addvtisement'] == 1101 ){

            if($result = $conn->query("SELECT block_status from admin_advtisment where id = ".$admin_data['adv_id'])) {
                while($row = $result->fetch_assoc()) {
                      $myArray[] = $row;
                }
             }
             if(!isset($myArray[0]['block_status'])){
               $msg = array("success" => 3,'mas'=>'not a spacific addvtisement');
               return $response->withJson($msg);
             }

             if($myArray[0]['block_status']==0){
               try{
                     $result=$conn->prepare("UPDATE admin_advtisment set block_status = 1 where id= ?");
                     // $resule->bind_param('s', $name);
                     $result->bind_param('s', $admin_data['adv_id']);
                     $result->execute();
                     $result->store_result();
                     $result->close();
                     mysqli_close($conn);
                     $msg = array('success' => 1,'msg'=>'advertisement will enable');
                     return $response->withJson($msg);
                }catch(Exception $e){
                  $result->close();
                  mysqli_close($conn);
                  $msg = array("success" => 0,'mas'=>$e);
                  return $response->withJson(array('response'=>$msg));
                }
                $result->close();
                mysqli_close($conn);
                $msg = array('success' => 0,'msg'=>'enable to read data');
                return $response->withJson($msg);

             }else{
                 try{
                       $result=$conn->prepare("UPDATE admin_advtisment set block_status = 0 where id= ?");
                       // $resule->bind_param('s', $name);
                       $result->bind_param('s', $admin_data['adv_id']);
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
                  $msg = array('success' => 1,'msg'=>'advertisement will disable');
                  return $response->withJson($msg);
             }
             }

        $msg = array("success" => 0,'msg'=>'not permission to remove');
        return $response->withJson($msg);


});
$app->post('/advertisement_edit', function($request,$response) {

      $admin_data = $request->getParsedBody();
      $cheak = cheak_token($admin_data['id'],$admin_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }

      $conn =sqlConnection();
      if($result = $conn->query("SELECT addvtisement from module_permition where sub_admin_id = ".$admin_data['id'])) {

               while($row = $result->fetch_assoc()) {
                 $module_permition[] =$row;
               }
      }
      if($module_permition[0]['addvtisement'] == 0000){
              $result->close;
              mysqli_close($conn);
              $msg = array("success" => 0,'mas'=>'admin have no permition');
              return $response->withJson($msg);
        }
        if($module_permition[0]['addvtisement']==1110 OR $module_permition[0]['addvtisement']==1011 OR $module_permition[0]['addvtisement']==1010 OR $module_permition[0]['addvtisement']==1111){

              if($result=$conn->query("SELECT * from admin_advtisment where status = 1 AND id= ".$admin_data['adv_id'])){

                       while($row = $result->fetch_assoc()){
                             $admin_advtisment[]=$row;
                       }
                       if(!isset($admin_advtisment[0]['block_status'])){
                         $msg = array("success" => 3,'mas'=>'not a spacific addvtisement');
                         return $response->withJson($msg);
                       }
                switch ($admin_advtisment[0]['area_per']) {
                       case 'GO':
                           mysqli_close($conn);
                           return $response->withJson(array('opt'=>'edit','data'=>$admin_advtisment,'type'=>'GO'));
                           break;
                        case 'CO';
                            if($result = $conn->query("SELECT * from adv_aria_permition where status =1 AND adv_id=".$admin_advtisment[0]['id'])) {
                                $i=0;
                                while($row = $result->fetch_assoc()) {
                                  $co[$i] = $row['country'];
                                  $i++;
                                }
                             }
                            return $response->withJson(array('opt'=>'edit','data'=>$admin_advtisment,'type'=>'CO','country'=>$co));
                            break;
                        case 'ST';
                              if($result = $conn->query("SELECT * from adv_aria_permition where status =1 AND adv_id=".$admin_advtisment[0]['id'])) {
                                  $i=0;
                                while($row = $result->fetch_assoc()) {
                                $co = $row['country'];
                                $st[$i]= $row['state'];
                                $i++;
                                }
                            }
                            return $response->withJson(array('opt'=>'edit','data'=>$admin_advtisment,'type'=>'ST','country'=>$co,'state'=>$st));
                            break;
                        case 'DI';
                            if($result = $conn->query("SELECT * from adv_aria_permition where status =1 AND adv_id=".$admin_advtisment[0]['id'])) {
                              $i=0;
                              while($row = $result->fetch_assoc()) {
                              $co = $row['country'];
                              $st= $row['state'];
                              $ct[$i]=$row['city'];
                              $i++;
                               }
                            }
                            return $response->withJson(array('opt'=>'edit','data'=>$admin_advtisment,'type'=>'DI','country'=>$co,'state'=>$st,'city'=>$ct));
                            break;
                        case 'CT';
                              if($result = $conn->query("SELECT * from adv_aria_permition where status =1 AND adv_id=".$admin_advtisment[0]['id'])) {
                              $i=0;
                              while($row = $result->fetch_assoc()) {
                                  $co = $row['country'];
                                  $st= $row['state'];
                                  $ct=$row['city'];
                                  $di[$i]=$row['distric'];
                                  $i++;
                               }
                              }
                            return $response->withJson(array('opt'=>'edit','data'=>$admin_advtisment,'type'=>'CT','country'=>$co,'state'=>$st,'city'=>$ct,'distric'=>$di));
                            break;

                }
             }
        }else{
             mysqli_close($conn);
             return $response->withJson(array('success'=>0,'msg'=>'not permission'));
        }

    mysqli_close($conn);
    $msg=array('success'=>0,'data'=>'something went wrong');
    return $response->withJson($msg);
  });

  $app->post('/advertisement_show', function($request,$response) {

        $user_data = $request->getParsedBody();
        $cheak = cheak_token_user($user_data['id'],$user_data['token']);
        if($cheak['success'] == 0){
          $msg = array("success" => 0,'msg'=>'unotherise');
          return $response->withJson($msg);
        }
        $conn = sqlConnection();
        if($result = $conn->query("SELECT country,state,city,distric from user where id = ".$user_data['id'])) {

                 while($row = $result->fetch_assoc()) {
                   $user_area[] =$row;
                 }

        }
        if($result = $conn->query("SELECT title,discription,file_name,file_type,area_per from admin_advtisment where status = 1 AND block_status = 1 AND area_per = 'GO'")) {

                 while($row = $result->fetch_assoc()) {
                   $GO_advetisement[] =$row;
                 }

        }
        $addvertise[]=NULL;
        if($result = $conn->query("SELECT id,title,discription,file_name,file_type,area_per from admin_advtisment where status = 1 AND block_status = 1 AND area_per != 'GO'")) {
                  $s=0;

                 while($row = $result->fetch_assoc()) {
                  //  $res = $conn->query("SELECT city from adv_aria_permition where status = 1 AND adv_id=".$row['id']);
                  //  $row2 = $res->fetch_assoc();
                  // $msg = "SELECT city from adv_aria_permition where status = 1 AND adv_id=".$row['id']." AND city = ".$user_area[0]['city'];

                   switch ($row['area_per']) {
                          case 'CT':
                          if($res = $conn->query("SELECT city from adv_aria_permition where status = 1 AND adv_id=".$row['id']." AND city = ".$user_area[0]['city'])){
                            //  $row2 = $res->fetch_assoc();
                             while($row2 = $res->fetch_assoc()){
                               $addvertise[$s]['title']=$row['title'];
                               $addvertise[$s]['discription']=$row['discription'];
                               $addvertise[$s]['file_name']=$row['file_name'];
                               $addvertise[$s]['file_type']=$row['file_type'];
                               $s++;

                             }

                          }
                          break;
                          case 'ST':
                          if($res = $conn->query("SELECT state from adv_aria_permition where status = 1 AND adv_id=".$row['id']." AND state = ".$user_area[0]['state'])){
                            //  $row2 = $res->fetch_assoc();
                             while($row2 = $res->fetch_assoc()){
                               $addvertise[$s]['title']=$row['title'];
                               $addvertise[$s]['discription']=$row['discription'];
                               $addvertise[$s]['file_name']=$row['file_name'];
                               $addvertise[$s]['file_type']=$row['file_type'];
                               $s++;

                             }

                          }
                          break;

                          case 'CO':
                          if($res = $conn->query("SELECT country from adv_aria_permition where status = 1 AND adv_id=".$row['id']." AND country = ".$user_area[0]['country'])){
                            //  $row2 = $res->fetch_assoc();
                             while($row2 = $res->fetch_assoc()){
                               $addvertise[$s]['title']=$row['title'];
                               $addvertise[$s]['discription']=$row['discription'];
                               $addvertise[$s]['file_name']=$row['file_name'];
                               $addvertise[$s]['file_type']=$row['file_type'];
                               $s++;

                             }

                          }
                          break;
                          case 'DI':
                          if($res = $conn->query("SELECT distric from adv_aria_permition where status = 1 AND adv_id=".$row['id']." AND distric = ".$user_area[0]['distric'])){
                            //  $row2 = $res->fetch_assoc();
                             while($row2 = $res->fetch_assoc()){
                               $addvertise[$s]['title']=$row['title'];
                               $addvertise[$s]['discription']=$row['discription'];
                               $addvertise[$s]['file_name']=$row['file_name'];
                               $addvertise[$s]['file_type']=$row['file_type'];
                               $s++;

                             }

                          }
                          break;

                   }
                 }
                 mysqli_close($conn);
                 $msg = array("success" => 1,'all_advertise'=>$addvertise,'globle'=>$GO_advetisement);
                 return $response->withJson($msg);



        }
        mysqli_close($conn);
        $msg = array("success" => 0,'msg'=>$addvertise);
        return $response->withJson($msg);

  });

  $app->post('/addvtisement_remove', function($request,$response) {
        $admin_data = $request->getParsedBody();
        $cheak = cheak_token($admin_data['id'],$admin_data['token']);

        if($cheak['success'] == 0){
          $msg = array("success" => 0,'msg'=>'unotherise');
          return $response->withJson($msg);
        }
        $conn = sqlConnection();
        if($result = $conn->query("SELECT addvtisement from module_permition where sub_admin_id = ".$admin_data['id'])) {

                 while($row = $result->fetch_assoc()) {
                   $module_permition[] =$row;
                 }
        }

        if($module_permition[0]['addvtisement'] == 0000){
                $result->close;
                mysqli_close($conn);
                $msg = array("success" => 0,'mas'=>'admin have no permition');
                return $response->withJson($msg);
          }
          if($module_permition[0]['addvtisement'] == 1001 OR $module_permition[0]['addvtisement'] == 1011 OR $module_permition[0]['addvtisement'] == 1111 OR $module_permition[0]['addvtisement'] == 1101 ){
            try{
                  $result=$conn->prepare("UPDATE admin_advtisment set status = 0 where id= ?");
                  // $resule->bind_param('s', $name);
                  $result->bind_param('s', $admin_data['adv_id']);
                  $result->execute();
                  $result->store_result();
                  $result->close();
                  mysqli_close($conn);
                  $msg = array('success' => 1,'msg'=>'advertisement will deleted');
                  return $response->withJson($msg);
             }catch(Exception $e){
               $result->close();
               mysqli_close($conn);
               $msg = array("success" => 0,'mas'=>$e);
               return $response->withJson(array('response'=>$msg));
             }
             $result->close();
             mysqli_close($conn);
             $msg = array('success' => 0,'msg'=>'enable to read data');
             return $response->withJson($msg);

           }
           mysqli_close($conn);
           $msg = array('success' => 0,'msg'=>'enable to read data');
           return $response->withJson($msg);

});
?>
