<?php

$app->post('/city', function($request,$response){

  $state_data = $request->getParsedBody();
  $conn = sqlConnection();
  $resule=$conn->query("SELECT id,city from city where state_id = ".$state_data['id'] ." AND status = 1 ");

  $i=0;
   while($row = $resule->fetch_assoc()){
     $country_data[$i]['id'] = $row['id'];
     $country_data[$i]['text']= $row['city'];
     $i++;
   }

  $msg=array('status'=>1,'data'=>$country_data);
  $resule->close();
  mysqli_close($conn);
  return $response->withJson($msg);
   //return $user_data["state"];
});
$app->post('/distic', function($request,$response){

  $city_data = $request->getParsedBody();
  $conn = sqlConnection();
  $resule=$conn->query("SELECT id,distric from distric where city_id = ".$city_data['id'] ." AND status = 1 ");
  $i=0;
   while($row = $resule->fetch_assoc()){
     $country_data[$i]['id'] = $row['id'];
     $country_data[$i]['text']= $row['distric'];
     $i++;
   }
  $mag=array('status'=>1,'data'=>$country_data);
  $resule->close();
  mysqli_close($conn);
  return $response->withJson($mag);
   //return $user_data["state"];
});
$app->post('/sub_distic', function($request,$response){

  $distic_data = $request->getParsedBody();
  $conn = sqlConnection();
  $resule=$conn->query("SELECT id,sub_distric from sub_distric where country_id = ".$distic_data['id'] ." AND state_id=".$distic_data['state_id']." AND city_id=".$distic_data['city_id']." AND distric_id=".$distic_data['distric_id']."  AND status = 1 ");

  $i=0;
   while($row = $resule->fetch_assoc()){
     $country_data[$i]['id'] = $row['id'];
     $country_data[$i]['text']= $row['sub_distric'];
     $i++;
   }
  $mag=array('status'=>1,'data'=>$country_data);
  $resule->close();
  mysqli_close($conn);
  return $response->withJson($mag);
   //return $user_data["state"];
});
$app->get('/country', function($request,$response){

  $conn = sqlConnection();
      if($resule=$conn->query("SELECT id,country_name from country where status = 1 ")){
          $i=0;
           while($row = $resule->fetch_assoc()){
             $country_data[$i]['id'] = $row['id'];
             $country_data[$i]['text']= $row['country_name'];
             $i++;
           }

      }else{
        $mag=array('status'=>0);
        $resule->close();
        mysqli_close($conn);
        return $respons->withJson($mag);
      }
  $resule->close();
  mysqli_close($conn);
  $mag=array('status'=>1,'data'=>$country_data);
  return $response->withJson(array('data'=>$country_data));
});

$app->post('/state', function($request,$response){
  $country_id = $request->getParsedBody();
  $conn = sqlConnection();
  $resule=$conn->query("SELECT id,state from state where country_id =".$country_id['id']." AND status = 1 ");

  $i=0;
   while($row = $resule->fetch_assoc()){
     $country_data[$i]['id'] = $row['id'];
     $country_data[$i]['text']= $row['state'];
     $i++;
   }

  $mag=array('status'=>1,'data'=>$country_data);
  $resule->close();
  mysqli_close($conn);
  return $response->withJson($mag);

});

$app->get('/occp',function($request,$response) use ($app){
    $conn =sqlConnection();
    $user_data = $request->getParsedBody();
    // $user_data1 = $request->getQueryParams();
    //  $method = $request->getMethod();
    //  $file = fopen("occu.txt","w");
    //  echo fwrite($file,json_encode($user_data));
    //  fclose($file);
    try{
      if($result = $conn->query("SELECT id,occupation from occupation_met where status =1")) {

          while($row = $result->fetch_assoc()) {
                $myArray[] = $row;
          }
       }
          return $response->withJson(array('data'=>$myArray));
          // $resule->insert_id;
    }catch(Exception $e){
          $msg = array("success"=>0,"msg" => $e);
          return $response->withJson($msg);
      }
      $result->close();
      mysqli_close();
});

$app->get('/edu',function($request,$response){

    $conn =sqlConnection();
    try{
      if ($result = $conn->query("SELECT id,education from education_met where status =1")) {

          while($row = $result->fetch_assoc()) {
                $myArray[] = $row;
          }
       }
          return $response->withJson(array('data'=>$myArray));
          // $resule->insert_id;
    }catch(Exception $e){
          $msg = array("success"=>0,"msg" => $e);
          return $response->withJson($msg);
      }
      $result->close();
      mysqli_close();
});

$app->get('/reg_country_data', function($request,$response) {

      // $url="http://192.168.0.3:8080/api/v1/area.json";
      // $output = file_get_contents('area.json');
      // $json = json_decode($output, true);
      // return $response->withJson($json);
      // return $response->withJson($json[0]['items'][0]);

       $conn = sqlConnection();
       $res=$conn->query("SELECT id,parent_id,name FROM m_area where status=1");
       while($row=$res->fetch_assoc()){
           $categoryIdList[]=$row;
         }

      $categoryArrangeArray = array();
      foreach($categoryIdList as $row) {
          $categoryArrangeArray[] = $row;
      }
      $regionArray = array();
      foreach ($categoryArrangeArray as $a) {
          $regionArray[$a['parent_id']][] = $a;
      }
     $treeRegion = createTree($regionArray, array($categoryArrangeArray[0]));
    // $s=0;
    //  while(isset($treeRegion[0]['items'][$s])){
    //    $country[$s]['name']=$treeRegion[0]['items'][$s]['name'];
    //    $country[$s]['id']=$treeRegion[0]['items'][$s]['id'];
    //   $s++;
    //  }
    //  return $response->withJson($country);
    //  return $response->withJson($treeRegion[0]['items'][0]['name']);
    return $response->withJson($treeRegion);

});

$app->get('/area_reg_country', function($request,$response) {

      //  $url="http://192.168.0.3:8080/api/v1/area.json";
      //  $output = file_get_contents('area.json');
      //  $json = json_decode($output, true);
      //  return $response->withJson(array('data'=>$json));
      //  return $response->withJson(array('data'=>$json[0]['items'][0]));
      //  $s=0;
      //   while(isset($json[0]['items'][$s])){
      //     $country[$s]['name']=$json[0]['items'][$s]['name'];
      //     $country[$s]['id']=$json[0]['items'][$s]['id'];
      //    $s++;
      //   }
      // return $response->withJson($country);
      //  $s=1;
      //   while(isset($json[0]['items'][$s])){
      //     if($json[0]['items'][$s]['id']=='17277'){
      //       $sta=0;
      //       while(isset($json[0]['items'][$s]['items'][$sta])){
       //
      //         $state[$sta]['id']=$json[0]['items'][$s]['items'][$sta]['id'];
      //         $state[$sta]['name']=$json[0]['items'][$s]['items'][$sta]['name'];
      //         $sta++;
      //       }
      //      }
      //     $s++;
      //    }
      //    return $response->withJson($state);
        //  $s=0;
        //  while(isset($json[0]['items'][$s])){
        //    if($json[0]['items'][$s]['id']=='17277'){
        //      $sta=0;
        //      while(isset($json[0]['items'][$s]['items'][$sta]['id'])){
        //       if($json[0]['items'][$s]['items'][$sta]['id']=='46739'){
        //         $ci=0;
        //         while(isset($json[0]['items'][$s]['items'][$sta]['items'][$ci])){
        //           $city[$ci]['id']=$json[0]['items'][$s]['items'][$sta]['items'][$ci]['id'];
        //           $city[$ci]['name']=$json[0]['items'][$s]['items'][$sta]['items'][$ci]['name'];
        //           $ci++;
        //         }
         //
        //       }
        //        $sta++;
        //      }
        //     }
        //    $s++;
        //   }
        //   return $response->withJson($city);

          // $s=0;
          // while(isset($json[0]['items'][$s])){
          //   if($json[0]['items'][$s]['id']=='17277'){
          //     $sta=0;
          //     while(isset($json[0]['items'][$s]['items'][$sta]['id'])){
          //      if($json[0]['items'][$s]['items'][$sta]['id']=='46739'){
          //        $ci=0;
          //        while(isset($json[0]['items'][$s]['items'][$sta]['items'][$ci])){
          //          if($json[0]['items'][$s]['items'][$sta]['items'][$ci]['id']=='53428'){
          //            $di=0;
          //            while(isset($json[0]['items'][$s]['items'][$sta]['items'][$ci]['items'][$di])){
          //              $city[$di]['id']=$json[0]['items'][$s]['items'][$sta]['items'][$ci]['items'][$di]['id'];
          //              $city[$di]['name']=$json[0]['items'][$s]['items'][$sta]['items'][$ci]['items'][$di]['name'];
          //              $di++;
          //            }
          //
          //          }
          //
          //          $ci++;
          //        }
          //
          //      }
          //       $sta++;
          //     }
          //    }
          //   $s++;
          //  }

      //     $conn = sqlConnection();
      //     $res=$conn->query("SELECT id,parent_id,name FROM m_area where status=1");
      //     while($row=$res->fetch_assoc()){
      //         $categoryIdList[]=$row;
      //       }
       //
      //    $categoryArrangeArray = array();
      //    foreach($categoryIdList as $row) {
      //        $categoryArrangeArray[] = $row;
      //    }
      //    $regionArray = array();
      //    foreach ($categoryArrangeArray as $a) {
      //        $regionArray[$a['parent_id']][] = $a;
      //    }
      //   $treeRegion = createTree($regionArray, array($categoryArrangeArray[0]));
      //  return $response->withJson(array('data'=>$treeRegion));
     //
       $conn=sqlConnection();
       $res=$conn->query("SELECT id,parent_id,name FROM a_main");
       while($row=$res->fetch_assoc()){
           $categoryIdList[]=$row;
         }

      $categoryArrangeArray = array();
      foreach($categoryIdList as $row) {
          $categoryArrangeArray[] = $row;
      }
      $regionArray = array();
      foreach ($categoryArrangeArray as $a) {
          $regionArray[$a['parent_id']][] = $a;
      }

     $treeRegion = createTree($regionArray, array($categoryArrangeArray[0]));
     return $response->withJson(array('data'=>$treeRegion));
});

$app->get('/busness_catagery_data', function($request,$response) {

$conn =sqlConnection();
if($main = $conn->query("SELECT * FROM BusinessMain_cat")){

      while($main_catagery = $main->fetch_assoc()){
          $Business_main[] = $main_catagery;
      }
      // mysqli_close($conn);
      // $msg =array('success'=>1,'data'=>$Business_main);
      mysqli_close($conn);
      return $response->withJson(array('data'=>$Business_main));
      // return $response->withJson(array('response'=>$msg));
}
  mysqli_close($conn);
  $msg = array('success'=>0,'msg'=>'Not found any data');
  return $response->withJson(array('respons'=>$msg));

});

$app->post('/Business_Sub', function($request,$response) {
    $data = $request->getParsedBody();
    $conn=sqlConnection();


    if($sub=$conn->query("SELECT id,busness_name FROM busness_catagery WHERE parent=".$data['Business_Id'])){

        while($sub_catagery=$sub->fetch_assoc()){
          $Business_sub[] =$sub_catagery;

        }
       if(empty($Business_sub)){
          mysqli_close($conn);
          $msg = array('success'=>2,'msg'=>'Not found any data');
          return $response->withJson(array('respons'=>$msg));

       }
      mysqli_close($conn);

        $msg = array('success'=>1,'data'=>$Business_sub);
        return $response->withJson(array('response'=>$msg));


    }
  mysqli_close($conn);
  $msg = array('success'=>0,'msg'=>'Not found any data');
  return $response->withJson(array('response'=>$msg));

});

?>
