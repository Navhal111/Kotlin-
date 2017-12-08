<?php
$app->post('/userarealist',function($request,$response,$args){
  $user_data = $request->getParsedBody();
  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
  $conn =sqlConnection();

  if($gpsresult = $conn->query("SELECT id,first_name,last_name,address,lat,lng FROM user WHERE status=1 AND user_block =1 ")){
      $i=0;
       while($gpsrow= $gpsresult->fetch_assoc()){
         $userarea[$i]['id']=$gpsrow['id'];
        $userarea[$i]["Name"]=$gpsrow['last_name']." ".$gpsrow['first_name'];
        $userarea[$i]["address"]=$gpsrow['address'];
        $userarea[$i]["lat"]=$gpsrow['lat'];
        $userarea[$i]["lng"]=$gpsrow['lng'];
        $i++;
       }
       mysqli_close($conn);
       $msg = array('success'=>1,'data'=>$userarea);
       return $response->withJson(array('response'=>$msg));
  }
  mysqli_close($conn);
  $msg = array('success'=>0,'msg'=>'somthing went wrong');
  return $response->withJson($msg);

});

$app->post('/user_location',function($request,$response,$args){
  $user_data = $request->getParsedBody();
  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
  $conn =sqlConnection();

  if($gpsresult = $conn->query("SELECT id,first_name,last_name,address,lat,lng FROM user WHERE status=1 AND user_block =1 AND id = ".$user_data['id'])){
      $i=0;
       while($gpsrow= $gpsresult->fetch_assoc()){
         $userarea[$i]['id']=$gpsrow['id'];
        $userarea[$i]["Name"]=$gpsrow['last_name']." ".$gpsrow['first_name'];
        $userarea[$i]["address"]=$gpsrow['address'];
        $userarea[$i]["lat"]=$gpsrow['lat'];
        $userarea[$i]["lng"]=$gpsrow['lng'];
        $i++;
       }
       mysqli_close($conn);
       $msg = array('success'=>1,'data'=>$userarea);
       return $response->withJson(array('response'=>$msg));
  }
  mysqli_close($conn);
  $msg = array('success'=>0,'msg'=>'somthing went wrong');
  return $response->withJson($msg);

});
$app->post('/user_location_update',function($request,$response,$args){
  $user_data = $request->getParsedBody();
  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
  $conn =sqlConnection();
if($conn->query("UPDATE user SET lat = '".$user_data['lat']."',lng='".$user_data['lng']."' WHERE status=1 AND user_block =1 AND id = ".$user_data['id'])){

  mysqli_close($conn);
  $msg = array('success'=>1,'msg'=>'data updated');
  return $response->withJson(array('response'=>$msg));
}

mysqli_close($conn);
$msg = array('success'=>0,'msg'=>'somthing went wrong');
return $response->withJson($msg);
});
?>
