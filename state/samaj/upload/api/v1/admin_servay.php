<?php
$app->post('/admin_survey_add', function($request,$response,$args) {
           $admin_data = $request->getParsedBody();
            // $msg =array('success'=>1,'msg'=>'file data');
            // return $response->withJson($msg);
          $cheak = cheak_token($admin_data[1]['value'],$admin_data[2]['value']);
           if($cheak['success'] == 0){
             $msg = array("success" => 0,'msg'=>'unotherise');
             return $response->withJson($msg);
           }

           $conn = sqlConnection();
           if($result = $conn->query("SELECT servay_qus from module_permition where sub_admin_id = ".$admin_data[1]['value'])) {

                    while($row = $result->fetch_assoc()) {
                      $module_permition[] =$row;
                    }
           }
           if($module_permition[0]['servay_qus']==0000){
             $msg =array('success'=>0,'msg'=>'admin have not permission');
             return $response->withJson($msg);

           }
            if($admin_data[0]['value'] =='add'){
              $p=5;
              $i=0;
              while($admin_data[$p]['name']!='response'){
               $option1[$i] =$admin_data[$p]['value'];
               $p++;
               $i++;
              }
              if($admin_data[$p+1]['value']=='Yes'){
                         $area = $admin_data[$p+5]['value'];
                         $a=3;


              }
              if($admin_data[$p+1]['value']=='No'){
                  $area = $admin_data[$p+4]['value'];
                       $a=2;
              }
              // $msg =array('success'=>0,'msg'=>$admin_data[$p+$a+2]['value']);
              // return $response->withJson($msg);
              $options = implode(" # ", $option1);
              if($module_permition[0]['servay_qus']==1100 OR $module_permition[0]['servay_qus']==1110 OR $module_permition[0]['servay_qus']==1101 OR $module_permition[0]['servay_qus']==1111){
                    if($conn->query("INSERT INTO admin_survey(sub_admin_id,question,youtube_link,ans,area_per,ownans,permition,end_date,start_date) VALUES (".$admin_data[1]['value'].",'".$admin_data[3]['value']."','".$admin_data[4]['value']."','".$options."','".$area."','".$admin_data[$p+1]['value']."','".$admin_data[$p]['value']."','".$admin_data[$p+$a+1]['value']."','".$admin_data[$p+$a]['value']."')")){
                        $servay_id=$conn->insert_id;
                        $p=$p+$a+2;
                      switch ($admin_data[$p]['value']) {
                        case 'GO':
                              $p++;
                              if($conn->query("INSERT INTO sub_aria_survey(survey_id,admin_id,country,state,city,distric) values (".$servay_id.",".$admin_data[1]['value'].",0,0,0,0)")){
                                 $success =1;
                               }
                               break;
                        case 'CO':
                             $p++;
                              while($admin_data[$p]['name']=="country_permit"){
                              if($conn->query("INSERT INTO sub_aria_survey(survey_id,sub_admin_id,country,state,city,distric) values (".$servay_id.",".$admin_data[1]['value'].",".$admin_data[$p]['value'].",0,0,0)")){
                                 $p++;
                               }
                              }
                             break;
                        case 'ST':
                              $p++;
                              $co=$admin_data[$p]['value'];$p++;
                              while($admin_data[$p]['name']=="state_permit"){
                              if($conn->query("INSERT INTO sub_aria_survey(survey_id,sub_admin_id,country,state,city,distric) values (".$servay_id.",".$admin_data[1]['value'].",".$co.",".$admin_data[$p]['value'].",0,0)")){

                                $p++;
                              }
                             }
                            break;
                        case 'DI':
                              // return $response->withJson(array('data'=>$p));
                              $p++;
                              $co=$admin_data[$p]['value'];$p++;
                              $st=$admin_data[$p]['value'];$p++;
                              while($admin_data[$p]['name']=="city_permit"){
                                  if($conn->query("INSERT INTO sub_aria_survey(survey_id,sub_admin_id,country,state,city,distric) values (".$servay_id.",".$admin_data[1]['value'].",".$co.",".$st.",".$admin_data[$p]['value'].",0)")){
                                    $p++;

                                  }
                                 }
                              break;
                        case 'CT':

                              $p++;
                              $co=$admin_data[$p]['value'];$p++;
                              $st=$admin_data[$p]['value'];$p++;
                              $ct=$admin_data[$p]['value'];$p++;
                              // $msg =array('success'=>1,'msg'=>'update data');
                              // return $response->withJson($msg);
                              while($admin_data[$p]['name']=="district_permit"){
                                  if($conn->query("INSERT INTO sub_aria_survey(survey_id,sub_admin_id,country,state,city,distric) values (".$servay_id.",".$admin_data[1]['value'].",".$co.",".$st.",".$ct.",".$admin_data[$p]['value'].")")){

                                      $p++;
                                    }
                                }
                              break;

                      }
                     mysqli_close($conn);
                     $msg =array('success'=>1,'msg'=>'add data');
                     return $response->withJson($msg);

                    }
                    $msg =array('success'=>0,'msg'=>'not add');
                    return $response->withJson($msg);
              }
            }

            if($admin_data[0]['value'] =='edit'){
              //  $file = fopen("servay_qus.txt","w");
              //  echo fwrite($file,json_encode($admin_data));
              //  fclose($file);


               if($module_permition[0]['servay_qus']==1110 OR $module_permition[0]['servay_qus']==1011 OR $module_permition[0]['servay_qus']==1010 OR $module_permition[0]['servay_qus']==1111){
                   $p=6;
                   $i=0;
                   while($admin_data[$p]['name']!='response'){
                    $option1[$i] =$admin_data[$p]['value'];
                    $p++;
                    $i++;
                   }
                   if($admin_data[$p+1]['value']=='Yes'){
                              $area = $admin_data[$p+5]['value'];
                              $a=3;

                   }

                   if($admin_data[$p+1]['value']=='No'){
                       $area = $admin_data[$p+4]['value'];
                            $a=2;
                   }
                   $options = implode(" # ", $option1);
                   $servay_id = $admin_data[3]['value'];
if($conn->query("UPDATE admin_survey SET  question= '".$admin_data[4]['value']."',youtube_link = '".$admin_data[5]['value']."',ans='".$options."',area_per='".$area."',permition='".$admin_data[$p]['value']."',ownans='".$admin_data[$p+1]['value']."',end_date='".$admin_data[$p+$a+1]['value']."' , start_date='".$admin_data[$p+$a]['value']."' where id=".$admin_data[3]['value'])){

                      $conn->query("DELETE from sub_aria_survey where survey_id=".$admin_data[3]['value']);
                      $p=$p+$a+2;
                                        //  return $response->withJson(array('mag'=>$admin_data[$p]['value']));
                    switch ($admin_data[$p]['value']) {

                      case 'GO':

                            $p++;
                            if($conn->query("INSERT INTO sub_aria_survey(survey_id,admin_id,country,state,city,distric) values (".$servay_id.",".$admin_data[1]['value'].",0,0,0,0)")){
                               $success =1;
                             }
                             break;
                      case 'CO':
                           $p++;
                            while($admin_data[$p]['name']=="country_permit"){
                            if($conn->query("INSERT INTO sub_aria_survey(survey_id,sub_admin_id,country,state,city,distric) values (".$admin_data[3]['value'].",".$admin_data[1]['value'].",".$admin_data[$p]['value'].",0,0,0)")){

                               $p++;
                             }
                            }
                           break;
                      case 'ST':
                            $p++;
                            $co=$admin_data[$p]['value'];$p++;
                            while($admin_data[$p]['name']=="state_permit"){
                            if($conn->query("INSERT INTO sub_aria_survey(survey_id,sub_admin_id,country,state,city,distric) values (".$admin_data[3]['value'].",".$admin_data[1]['value'].",".$co.",".$admin_data[$p]['value'].",0,0)")){

                              $p++;
                            }
                           }
                          break;
                      case 'DI':
                            $p++;
                            $co=$admin_data[$p]['value'];$p++;
                            $st=$admin_data[$p]['value'];$p++;
                            while($admin_data[$p]['name']=="city_permit"){
                                if($conn->query("INSERT INTO sub_aria_survey(survey_id,sub_admin_id,country,state,city,distric) values (".$admin_data[3]['value'].",".$admin_data[1]['value'].",".$co.",".$st.",".$admin_data[$p]['value'].",0)")){
                                  $p++;

                                }
                               }
                            break;
                      case 'CT':
                            $p++;
                            $co=$admin_data[$p]['value'];$p++;
                            $st=$admin_data[$p]['value'];$p++;
                            $ct=$admin_data[$p]['value'];$p++;
                            while($admin_data[$p]['name']=="district_permit"){
                                if($conn->query("INSERT INTO sub_aria_survey(survey_id,sub_admin_id,country,state,city,distric) values (".$admin_data[3]['value'].",".$admin_data[1]['value'].",".$co.",".$st.",".$ct.",".$admin_data[$p]['value'].")")){

                                    $p++;
                                  }
                              }
                            break;

                    }
                        $msg =array('success'=>1,'msg'=>'update data');
                        return $response->withJson($msg);

                  }
                  $msg =array('success'=>0,'msg'=>'somthing wrong');
                  return $response->withJson($msg);
            }
          }

          $msg =array('success'=>0,'msg'=>'data not updated');
          return $response->withJson($msg);
});

$app->post('/admin_survey_list', function($request,$response,$args) {
           $admin_data = $request->getParsedBody();
          //  $file = fopen("nokariview.txt","w");
          //  echo fwrite($file,json_encode($admin_data));
          //  fclose($file);
          $cheak = cheak_token($admin_data['id'],$admin_data['token']);
           if($cheak['success'] == 0){
             $msg = array("success" => 0,'msg'=>'unotherise');
             return $response->withJson($msg);
           }
           $conn = sqlConnection();
           if($result = $conn->query("SELECT role,servay_qus from module_permition where sub_admin_id = ".$admin_data['id'])) {

                    while($row = $result->fetch_assoc()) {
                      $module_permition[] =$row;
                    }
           }
           if($module_permition[0]['servay_qus']==0000){
             $msg =array('success'=>0,'msg'=>'Admin have not permission');
             return $response->withJson($msg);

           }
           $qu=0;
           if($module_permition[0]['role']==1){
                try{

                  if($result = $conn->query("SELECT * from admin_survey where status =1 ")) {
                        while($row = $result->fetch_assoc()) {
                          $admin_servay[$qu]['id'] =$row['id'];
                          $admin_servay[$qu]['Question'] =$row['question'];
                          $admin_servay[$qu]['Options'] = explode(" # ", $row['ans']);
                          $admin_servay[$qu]['area_per'] =$row['area_per'];
                          $admin_servay[$qu]['block_status'] =$row['block_status'];
                          $admin_servay[$qu]['status'] =$row['status'];
                          $admin_servay[$qu]['start_date'] =$row['start_date'];
                          $admin_servay[$qu]['end_date'] =$row['end_date'];
                          $admin_servay[$qu]['created_at'] =$row['created_at'];
                          $admin_servay[$qu]['permission'] =$row['permition'];
                          $admin_servay[$qu]['ownans'] =$row['ownans'];
                          $admin_servay[$qu]['youtube_link'] = $row["youtube_link"];
                         $co =array();
                         $st =array();
                         $ct =array();
                         $di =array();

                          switch ($row['area_per']) {
                                  case 'CO';
                                      if($resule = $conn->query("SELECT * from sub_aria_survey where status =1 AND survey_id=".$row['id'])) {
                                          $i=0;
                                          while($row1 = $resule->fetch_assoc()) {
                                            $co[$i] = $row1['country'];
                                            $i++;
                                          }

                                          $res=$conn->query("SELECT name from m_area where id IN (" . implode(',', array_map('intval', $co)) . ")");
                                          $i=0;
                                          while($area = $res->fetch_assoc()) {
                                             $admin_servay[$qu]['country'][$i] =$area['name'];
                                             $i++;
                                          }
                                       }
                                      break;
                                  case 'ST';
                                        if($resule = $conn->query("SELECT * from sub_aria_survey where status =1 AND survey_id=".$row['id'])) {
                                            $i=0;
                                            // $st=array();
                                          while($row1 = $resule->fetch_assoc()) {
                                          $co = $row1['country'];
                                          $st[$i]= $row1['state'];
                                          $i++;
                                          }
                                          $res=$conn->query("SELECT name from m_area where id = ".$co);
                                          $country=$res->fetch_assoc();
                                          $admin_servay[$qu]['country'] =$country['name'];
                                          $i=0;
                                          $res=$conn->query("SELECT name from m_area where id IN (" . implode(',', array_map('intval', $st)) . ")");
                                          while($area = $res->fetch_assoc()) {
                                             $admin_servay[$qu]['state'][$i] =$area['name'];
                                             $i++;
                                          }

                                      }

                                      break;
                                  case 'DI';
                                      if($resule = $conn->query("SELECT * from sub_aria_survey where status =1 AND survey_id=".$row['id'])) {
                                        $i=0;
                                        while($row1 = $resule->fetch_assoc()) {
                                        $co = $row1['country'];
                                        $st= $row1['state'];
                                        $ct[$i]=$row1['city'];
                                        $i++;

                                         }
                                         $res=$conn->query("SELECT name from m_area where id = ".$co);
                                         $country=$res->fetch_assoc();
                                         $res=$conn->query("SELECT name from m_area where id = ".$st);
                                         $state=$res->fetch_assoc();
                                         $res=$conn->query("SELECT name from m_area where id IN (" . implode(',', array_map('intval', $ct)) . ")");
                                         $admin_servay[$qu]['country'] =$country['name'];
                                         $admin_servay[$qu]['state'] =$state['name'];
                                         $i=0;
                                         while($area = $res->fetch_assoc()) {
                                            $admin_servay[$qu]['city'][$i] =$area['name'];

                                            $i++;
                                         }

                                      }

                                      break;
                                  case 'CT';
                                        if($resule = $conn->query("SELECT * from sub_aria_survey where status =1 AND survey_id=".$row['id'])) {
                                        $i=0;
                                        while($row1 = $resule->fetch_assoc()) {
                                            $co = $row1['country'];
                                            $st= $row1['state'];
                                            $ct=$row1['city'];
                                            $di[$i]=$row1['distric'];
                                            $i++;
                                         }
                                         $res=$conn->query("SELECT name from m_area where id = ".$co);
                                         $country=$res->fetch_assoc();
                                         $res=$conn->query("SELECT name from m_area where id = ".$st);
                                         $state=$res->fetch_assoc();
                                         $res=$conn->query("SELECT name from m_area where id = ".$ct);
                                         $city=$res->fetch_assoc();
                                         $res=$conn->query("SELECT name from m_area where id IN (" . implode(',', array_map('intval', $di)) . ")");
                                         $admin_servay[$qu]['country'] =$country['name'];
                                         $admin_servay[$qu]['state'] =$state['name'];
                                         $admin_servay[$qu]['city'] =$city['name'];
                                         $i=0;
                                         while($area = $res->fetch_assoc()) {
                                            $admin_servay[$qu]['distric'][$i] =$area['name'];

                                            $i++;
                                         }
                                        }

                                      break;
                          }
                          $qu++;
                        }
                        $msg =array('success'=>1,'data'=>$admin_servay);
                        return $response->withJson($msg);

                  }

                }catch(Exception $e){
                  $msg =array('success'=>0,'msg'=>$e);
                  return $response->withJson($msg);

                }

           }
           if($module_permition[0]['servay_qus']==0000){
             $msg =array('success'=>0,'msg'=>'u have not permition');
             return $response->withJson($msg);
           }

           if($module_permition[0]['role']==2){
                try{
                  if($result = $conn->query("SELECT * from admin_survey where status =1 AND sub_admin_id = ".$admin_data['id'])) {
                        while($row = $result->fetch_assoc()) {
                          $admin_servay[$qu]['id'] =$row['id'];
                          $admin_servay[$qu]['Question'] =$row['question'];
                          $admin_servay[$qu]['Options'] = explode(" # ", $row['ans']);
                          $admin_servay[$qu]['area_per'] =$row['area_per'];
                          $admin_servay[$qu]['block_status'] =$row['block_status'];
                          $admin_servay[$qu]['start_date'] =$row['start_date'];
                          $admin_servay[$qu]['end_date'] =$row['end_date'];
                          $admin_servay[$qu]['status'] =$row['status'];
                          $admin_servay[$qu]['created_at'] =$row['created_at'];
                          $admin_servay[$qu]['permission'] =$row['permition'];
                          $admin_servay[$qu]['ownans'] =$row['ownans'];
                          $admin_servay[$qu]['youtube_link'] = $row["youtube_link"];
                         $co =array();
                         $st =array();
                         $ct =array();
                         $di =array();
                         switch ($row['area_per']) {
                                 case 'CO':
                                     if($resule = $conn->query("SELECT * from sub_aria_survey where status =1 AND survey_id=".$row['id'])) {
                                         $i=0;
                                         while($row1 = $resule->fetch_assoc()) {
                                           $co[$i] = $row1['country'];
                                           $i++;
                                         }
                                         $res=$conn->query("SELECT name from m_area where id IN (" . implode(',', array_map('intval', $co)) . ")");
                                         $i=0;
                                         while($area = $res->fetch_assoc()) {
                                            $admin_servay[$qu]['country'][$i] =$area['name'];
                                            $i++;
                                         }
                                      }
                                     break;
                                 case 'ST':
                                       if($resule = $conn->query("SELECT * from sub_aria_survey where status =1 AND survey_id=".$row['id'])) {
                                           $i=0;
                                           // $st=array();
                                         while($row1 = $resule->fetch_assoc()) {
                                         $co = $row1['country'];
                                         $st[$i]= $row1['state'];
                                         $i++;
                                         }
                                         $res=$conn->query("SELECT name from m_area where id = ".$co);
                                         $country=$res->fetch_assoc();
                                         $admin_servay[$qu]['country'] =$country['name'];
                                         $i=0;
                                         $res=$conn->query("SELECT name from m_area where id IN (" . implode(',', array_map('intval', $st)) . ")");
                                         while($area = $res->fetch_assoc()) {
                                            $admin_servay[$qu]['state'][$i] =$area['name'];
                                            $i++;
                                         }

                                     }

                                     break;
                                 case 'DI':
                                     if($resule = $conn->query("SELECT * from sub_aria_survey where status =1 AND survey_id=".$row['id'])) {
                                       $i=0;
                                      //  return $response->withJson(array('msg'=>"SELECT * from sub_aria_survey where status =1 AND survey_id=".$row['id']));
                                       while($row1 = $resule->fetch_assoc()) {
                                       $co = $row1['country'];
                                       $st= $row1['state'];
                                       $ct[$i]=$row1['city'];
                                       $i++;

                                        }

                                        $res=$conn->query("SELECT name from m_area where id = ".$co);
                                        $country=$res->fetch_assoc();
                                        $res=$conn->query("SELECT name from m_area where id = ".$st);
                                        $state=$res->fetch_assoc();
                                        $res=$conn->query("SELECT name from m_area where id IN (" . implode(',', array_map('intval', $ct)) . ")");
                                        $admin_servay[$qu]['country'] =$country['name'];
                                        $admin_servay[$qu]['state'] =$state['name'];
                                        $i=0;
                                        while($area = $res->fetch_assoc()) {
                                           $admin_servay[$qu]['city'][$i] =$area['name'];

                                           $i++;
                                        }

                                     }

                                     break;
                                 case 'CT':
                                       if($resule = $conn->query("SELECT * from sub_aria_survey where status =1 AND survey_id=".$row['id'])) {
                                       $i=0;
                                       while($row1 = $resule->fetch_assoc()) {
                                           $co = $row1['country'];
                                           $st= $row1['state'];
                                           $ct=$row1['city'];
                                           $di[$i]=$row1['distric'];
                                           $i++;
                                        }
                                        $res=$conn->query("SELECT name from m_area where id = ".$co);
                                        $country=$res->fetch_assoc();
                                        $res=$conn->query("SELECT name from m_area where id = ".$st);
                                        $state=$res->fetch_assoc();
                                        $res=$conn->query("SELECT name from m_area where id = ".$ct);
                                        $city=$res->fetch_assoc();
                                        $res=$conn->query("SELECT name from m_area where id IN (" . implode(',', array_map('intval', $di)) . ")");
                                        $admin_servay[$qu]['country'] =$country['name'];
                                        $admin_servay[$qu]['state'] =$state['name'];
                                        $admin_servay[$qu]['city'] =$city['name'];
                                        $i=0;
                                        while($area = $res->fetch_assoc()) {
                                           $admin_servay[$qu]['distric'][$i] =$area['name'];

                                           $i++;
                                        }
                                       }

                                     break;
                         }
                         $qu++;
                        }
                        $msg =array('success'=>1,'data'=>$admin_servay);
                        return $response->withJson($msg);
                  }

                }catch(Exception $e){
                  $msg =array('success'=>0,'msg'=>$e);
                  return $response->withJson($msg);

                }
           }
           $msg =array('success'=>0,'msg'=>'somthing wrong');
           return $response->withJson($msg);

  });

  $app->post('/admin_servey_block', function($request,$response) {
        $admin_data = $request->getParsedBody();
        $cheak = cheak_token($admin_data['id'],$admin_data['token']);

        if($cheak['success'] == 0){
          $msg = array("success" => 0,'msg'=>'unotherise');
          return $response->withJson($msg);
        }
        $conn = sqlConnection();
        if($result = $conn->query("SELECT servay_qus from module_permition where sub_admin_id = ".$admin_data['id'])) {

                 while($row = $result->fetch_assoc()) {
                   $module_permition[] =$row;
                 }
        }

        if($module_permition[0]['servay_qus'] == 0000){
                $result->close;
                mysqli_close($conn);
                $msg = array("success" => 0,'mas'=>'Admin have no permition');
                return $response->withJson($msg);
          }
          if($module_permition[0]['servay_qus'] == 1001 OR $module_permition[0]['servay_qus'] == 1011 OR $module_permition[0]['servay_qus'] == 1111 OR $module_permition[0]['servay_qus'] == 1101 ){

              if($result = $conn->query("SELECT block_status from admin_survey where id = ".$admin_data['survey_id']." AND sub_admin_id=".$admin_data['id'])) {
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
                       $result=$conn->prepare("UPDATE admin_survey set block_status = 1 where id= ?");
                       // $resule->bind_param('s', $name);
                       $result->bind_param('s', $admin_data['survey_id']);
                       $result->execute();
                       $result->store_result();
                       $result->close();
                       mysqli_close($conn);
                       $msg = array('success' => 1,'msg'=>'survey will enable');
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
                         $result=$conn->prepare("UPDATE admin_survey set block_status = 0 where id= ?");
                         // $resule->bind_param('s', $name);
                         $result->bind_param('s', $admin_data['survey_id']);
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
                    $msg = array('success' => 1,'msg'=>'sur will disable');
                    return $response->withJson($msg);
               }
        }
        mysqli_close($conn);
        $msg = array("success" => 0,'mas'=>'Admin have no permition');
        return $response->withJson($msg);
});

$app->post('/admin_servey_edit', function($request,$response) {
      $admin_data = $request->getParsedBody();
      $cheak = cheak_token($admin_data['id'],$admin_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
      $conn = sqlConnection();
      if($result = $conn->query("SELECT servay_qus from module_permition where sub_admin_id = ".$admin_data['id'])) {

               while($row = $result->fetch_assoc()) {
                 $module_permition[] =$row;
               }
      }

      if($module_permition[0]['servay_qus'] == 0000){
              $result->close;
              mysqli_close($conn);
              $msg = array("success" => 0,'mas'=>'Admin have no permition');
              return $response->withJson($msg);
        }

        if($module_permition[0]['servay_qus']==1110 OR $module_permition[0]['servay_qus']==1011 OR $module_permition[0]['servay_qus']==1010 OR $module_permition[0]['servay_qus']==1111){

              if($result=$conn->query("SELECT id,sub_admin_id,question,ans,area_per,ownans,permition,end_date,start_date,status,block_status from admin_survey where status = 1 AND id= ".$admin_data['survey_id'])){

                       while($row = $result->fetch_assoc()){
                             $admin_advtisment[]=$row;
                       }

                       if(!isset($admin_advtisment[0]['area_per'])){
                         $msg = array("success" => 3,'mas'=>'not a spacific survey');
                         return $response->withJson($msg);
                       }
                      //  $msg = array("success" => 0,'msg'=>$admin_advtisment[0]['area_per']);
                      //  return $response->withJson($msg);
                switch ($admin_advtisment[0]['area_per']) {
                       case 'GO':
                           mysqli_close($conn);
                           return $response->withJson(array('opt'=>'edit','data'=>$admin_advtisment,'type'=>'GO'));
                           break;
                        case 'CO':
                            if($result = $conn->query("SELECT * from sub_aria_survey where status =1 AND survey_id=".$admin_advtisment[0]['id'])) {
                                $i=0;
                                while($row = $result->fetch_assoc()) {
                                  $co[$i] = $row['country'];
                                  $i++;
                                }
                             }
                            return $response->withJson(array('opt'=>'edit','data'=>$admin_advtisment,'type'=>'CO','country'=>$co));
                            break;
                        case 'ST':
                              if($result = $conn->query("SELECT * from sub_aria_survey where status =1 AND survey_id=".$admin_advtisment[0]['id'])) {
                                  $i=0;
                                while($row = $result->fetch_assoc()) {
                                $co = $row['country'];
                                $st[$i]= $row['state'];
                                $i++;
                                }
                            }
                            return $response->withJson(array('opt'=>'edit','data'=>$admin_advtisment,'type'=>'ST','country'=>$co,'state'=>$st));
                            break;
                        case 'DI':
                            if($result = $conn->query("SELECT * from sub_aria_survey where status =1 AND survey_id=".$admin_advtisment[0]['id'])) {
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
                        case 'CT':
                              if($result = $conn->query("SELECT * from sub_aria_survey where status =1 AND survey_id=".$admin_advtisment[0]['id'])) {
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

  $app->post('/admin_servey_remove', function($request,$response) {
        $admin_data = $request->getParsedBody();
        $cheak = cheak_token($admin_data['id'],$admin_data['token']);

        if($cheak['success'] == 0){
          $msg = array("success" => 0,'msg'=>'unotherise');
          return $response->withJson($msg);
        }
        $conn = sqlConnection();
        if($result = $conn->query("SELECT servay_qus from module_permition where sub_admin_id = ".$admin_data['id'])) {

                 while($row = $result->fetch_assoc()) {
                   $module_permition[] =$row;
                 }
        }

        if($module_permition[0]['servay_qus'] == 0000){
                $result->close;
                mysqli_close($conn);
                $msg = array("success" => 0,'mas'=>'Admin have no permition');
                return $response->withJson($msg);
          }

          if($module_permition[0]['servay_qus']==1110 OR $module_permition[0]['servay_qus']==1011 OR $module_permition[0]['servay_qus']==1010 OR $module_permition[0]['servay_qus']==1111){

            try{
                  $result=$conn->prepare("UPDATE admin_survey set status = 0 where id= ?");
                  // $resule->bind_param('s', $name);
                  $result->bind_param('s', $admin_data['survey_id']);
                  $result->execute();
                  $result->store_result();
                  $result->close();
                  mysqli_close($conn);
                  $msg = array('success' => 1,'msg'=>'survey will deleted');
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
});
$app->post('/admin_servey_ans_list', function($request,$response) {
      $admin_data = $request->getParsedBody();
      $cheak = cheak_token($admin_data['id'],$admin_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
      $conn = sqlConnection();
      if($result = $conn->query("SELECT role,servay_ans from module_permition where sub_admin_id = ".$admin_data['id'])) {
               while($row = $result->fetch_assoc()) {
                 $module_permition[] =$row;
               }
      }

      if($module_permition[0]['servay_ans'] == 0000){
              $result->close;
              mysqli_close($conn);
              $msg = array("success" => 0,'mas'=>'Admin have no permition');
              return $response->withJson($msg);
        }
      if($module_permition[0]['role']==1){
        if($useransresult=$conn->query("SELECT * from user_servay WHERE status=1")){
          $i=0;
          while($useransrow =$useransresult->fetch_assoc()){
              $servayans[$i]['ans']=$useransrow['ans'];
              $servayans[$i]['user_id']=$useransrow['ans'];
              $servayans[$i]['servay_id']=$useransrow['ans'];
              $servayans[$i]['answer_text']=$useransrow['ans'];
              $i++;

          }

          $msg = array("success" => 1,'data'=>$servayans);
          return $response->withJson($msg);

        }

        $msg = array("success" => 1,'msg'=>'Somthing went wrong');
        return $response->withJson($msg);
      }

      $msg = array("success" => 1,'msg'=>'You have not permission');
      return $response->withJson($msg);
});
?>
