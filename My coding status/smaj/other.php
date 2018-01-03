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

  $mag=array('status'=>1,'data'=>$country_data);
  $resule->close();
  mysqli_close($conn);
  return $response->withJson($mag);
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
        return $response->withJson($mag);
      }
  $mag=array('status'=>1,'data'=>$country_data);
  $resule->close();
  mysqli_close($conn);
  return $response->withJson($mag);
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

$app->get('/edu',function(ServerRequestInterface $request, ResponseInterface $response){
    $conn =sqlConnection();
    try{
      if ($result = $conn->query("SELECT education from education_met where status =1")) {

          while($row = $result->fetch_array()) {
                $myArray[] = $row;
          }
       }
          return $response->withJson($myArray);
          // $resule->insert_id;
    }catch(Exception $e){
          return $e;
      }
      $result->close();
      mysqli_close();
});

$app->get('/occp',function(ServerRequestInterface $request, ResponseInterface $response){
    $conn =sqlConnection();
    try{
      if ($result = $conn->query("SELECT occupation from occupation_met where status =1")) {

          while($row = $result->fetch_array()) {
                $myArray[] = $row;
          }
       }
          return $response->withJson($myArray);
          // $resule->insert_id;
    }catch(Exception $e){
          $msg = array("success"=>0,"msg" => $e);
          return $response->withJson($msg);
      }
      $result->close();
      mysqli_close();
});

?>
