<?php
 $app->post('/advertisement_show_user', function($request,$response,$args) {

$user_data = $request->getParsedBody();

 //  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
 // if($cheak['success'] == 0){
 //   $msg = array("success" => 0,'msg'=>'unotherise');
 //   return $response->withJson($msg);
 // }

 $conn=sqlConnection();
if($data_user= $conn->query("SELECT city from user where id =".$user_data['id'])){
		$city = $data_user->fetch_assoc();
		$user_city = $city['city'];

}
  $now = date('Y-m-d');
 if($result = $conn->query("SELECT id,title,discription,file_name,file_type,start_time,end_time from admin_advtisment where status=1 AND block_status=1")) {
          $i=0;

          while($row = $result->fetch_assoc()) {
            if((strtotime($now) < strtotime($rowservay['end_time'])) AND (strtotime($now) >= strtotime($rowservay['start_time']))){

                   $myArray[$i]['title'] = $row['title'];
                   $myArray[$i]['contact_name'] = $row['discription'];
                   $myArray[$i]['file_name'] = $row['file_name'];
                   $myArray[$i]['file_type'] = $row['file_type'];
                   $myArray[$i]['end_time'] = $row['end_time'];
                   $i++;
          }
        }
        if(empty($myArray)){
          $msg = array("success" => 2,'msg'=>'No Advertise will found');
          return $response->withJson($msg);

        }
          $msg = array("success" => 1,'data'=>$myArray);
          return $response->withJson($msg);

        }

          $msg = array("success" => 0,'msg'=>"somthing wen wrong");
          return $response->withJson($msg);
});

?>
