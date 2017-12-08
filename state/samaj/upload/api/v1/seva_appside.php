<?php
$app->post('/list_seva_app',function($request,$response,$args){
      $user_data = $request->getParsedBody();
      $cheak = cheak_token_user($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
   $conn = sqlConnection();

   if($sevares=$conn->query("SELECT * FROM Seva_org WHERE status=1 AND block_status=1")){
      $i=0;
      if($sevares->num_rows <= 0){
        mysqli_close($conn);
        $msg =  array('success'=>2,'msg'=>'No list found');
        return $response->withJson(array('response'=>$msg));
      }
      while($sevarow=$sevares->fetch_assoc()){

        $myArray[$i]['id'] = $sevarow['id'];
        $myArray[$i]['org_name'] = $sevarow['trust_name'];
        $myArray[$i]['owner_name'] = $sevarow['trusty_name'];
        $myArray[$i]['owner_email'] = $sevarow['trust_address'];
        $myArray[$i]['website'] = $sevarow['trust_website'];
        $myArray[$i]['String_img'] = $sevarow['logoname'];
         $i++;
      }
      mysqli_close($conn);
     $msg =  array('success'=>1,'data'=>$myArray);
     return $response->withJson(array('response'=>$msg));
   }
   mysqli_close($conn);

   $msg =  array('success'=>0,'msg'=>'Somthing went wrong');
   return $response->withJson(array('response'=>$msg));

});

$app->post('/view_seva_app',function($request,$response,$args){
      $user_data = $request->getParsedBody();

       $cheak = cheak_token_user($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }


      $conn=sqlConnection();
      if($res_seva_view = $conn->query("SELECT * from Seva_org WHERE status=1 AND block_status=1 AND id =".$user_data['seva_id'])){
        $s=0;
        if($res_seva_view->num_rows <= 0){

            $msg = array("success" => 2,'msg'=>'No data found');
            return $response->withJson($msg);
        }
        $i=0;
        while($row=$res_seva_view->fetch_assoc()){
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
           $myArray[$i]['city'] = $city['name'];
           $myArray[$i]['district'] = $distric['name'];
           $myArray[$i]['pincode'] = $row['pincode'];
           $myArray[$i]['created_at'] = $row['created_at'];
          //   $res=$conn->query("SELECT name from m_area where id = ".$row_seva['country']);
          //   $country=$res->fetch_assoc();
          //   $res=$conn->query("SELECT name from m_area where id = ".$row_seva['state']);
          //   $state=$res->fetch_assoc();
          //   $res=$conn->query("SELECT name from m_area where id = ".$row_seva['city']);
          //   $city=$res->fetch_assoc();
          //   $res=$conn->query("SELECT name from m_area where id = ".$row_seva['distric']);
          //   $distric=$res->fetch_assoc();
          //  $myArray[$s]['org_name'] = $row_seva['org_name'];
          //  $myArray[$s]['owner_name'] = $row_seva['owner_name'];
          //  $myArray[$s]['owner_contact'] = $row_seva['onwe_contact'];
          //  $myArray[$s]['owner_email'] = $row_seva['owner_email'];
          //  $myArray[$s]['country'] = $country['name'];
          //  $myArray[$s]['state'] = $state['name'];
          //  $myArray[$s]['city'] = $city['name'];
          //  $myArray[$s]['distric'] = $distric['name'];
          //  $myArray[$s]['String_img'] = $row_seva['String_img'];
          //
          // //  $myArray[$s]['org_cat'] = $row_seva['org_cat'];
          //
          //  $temp = explode(" # ",$row_seva['org_cat']);
          //  $c=0;
          //  while(isset($temp[$c])){
          //    $myArray[$s]['cat_name'][$c]=$temp[$c];
          //    $c++;
          //  }
          //
          //  $myArray[$s]['discription'] = $row_seva['discription'];
          //  $myArray[$s]['or_add'] = $row_seva['or_add'];
          //  $myArray[$s]['website'] = $row_seva['website'];
          //  $myArray[$s]['email'] = $row_seva['email'];
          //  $myArray[$s]['pincode'] = $row_seva['pincode'];
          //  $myArray[$s]['created_at']=$row_seva["created_at"];
      $i++;
        }
        mysqli_close($conn);
       $msg =  array('success'=>1,'data'=>$myArray);
       return $response->withJson(array('response'=>$msg));
      }
      mysqli_close($conn);
      $msg =  array('success'=>0,'msg'=>'Somthing went wrong');
      return $response->withJson(array('response'=>$msg));
});

?>
