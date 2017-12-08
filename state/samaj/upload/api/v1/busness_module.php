<?php
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$app->post('/add_profile_bussness',function($request,$response,$args){
 $user_data = $request->getParsedBody();
  $user_data = $request->getParsedBody();
   $numArray = substr($user_data['CompanyCategory'],1,-1);
   $categry = explode(", ", $numArray);

  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
  if(empty($user_data['city'])){
    $user_data['city']=0;
  }
  $server_path=$cheak['path'];
  $startdate = date('Y-m-d H:i:s');
  $enddate="2018-01-01 09:55:25";
  $amount ="100";
  $conn = sqlConnection();
  $i=0;
  if(!isset($user_data['CompanylogoString']) OR empty($user_data['CompanylogoString'])){

  mysqli_close($conn);
  return $response->withJson(array('response'=>array('success'=>2,'msg'=>'not get the image')));
  }
  $c=0;
  while(isset($categry[$c])){
    if($result = $conn->query("SELECT id FROM busness_catagery where busness_name = '".$categry[$c]."'")){
        $rowsub = $result->fetch_assoc();
        $subcat[$c]=$rowsub['id'];
    }
    $c++;
    // return $response->withJson(array('cat'=>"SELECT id FROM busness_catagery where busness_name = '".$categry[$c]."'",'main'=>$categry));
  }

    $data = base64_decode($user_data['CompanylogoString']);
    $path =  __DIR__ ."/bussness_image/".$user_data['CompanylogoName'];
    file_put_contents($path, $data);
    $path_image = $server_path."bussness_image/".$user_data['CompanylogoName'];


  if($conn->query('INSERT INTO  business_profile(user_id,CompannyName,OwnerName,Address,BusinessDiscription,CompnnaylogoName,CompanylogoString,Website,Email,business_main,Contact,contact_2,BusinessLink,country,state,city,payment,End_date,Start_date)values('.$user_data['id'].',"'.$user_data['CompanyName'].'","'.$user_data['OwnerName'].'","'.$user_data['Address'].'","'.$user_data['Discription'].'","'.$user_data['CompanylogoName'].'","'.$path_image.'","'.$user_data['Website'].'","'.$user_data['Email'].'",'.$user_data['busines_main'].',"'.$user_data['Contact'].'","'.$user_data['Contact_2'].'","'.$user_data['BusinessLink'].'",'.$user_data['country'].','.$user_data['state'].','.$user_data['city'].',"'.$amount.'","'.$enddate.'","'.$startdate.'")')){

    $Busness_id=$conn->insert_id;
    $b=0;
    while(isset($subcat[$b])){

      $conn->query("INSERT INTO BusinessCategary_id(business_profile,Categary_id)VALUES(".$Busness_id.",".$subcat[$b].")");
       $b++;
    }
    if(!empty($user_data['sunday'] OR isset($user_data['sunday']))){
      $conn->query("INSERT INTO Business_time(user_id,business_id,sun,mon,tue,wed,thu,fri,sat,break) VALUES(".$user_data['id'].",".$Busness_id.",'".$user_data['sunday']."','".$user_data['monday']."','".$user_data['tuesday']."','".$user_data['wednesday']."','".$user_data['thursday']."','".$user_data['friday']."','".$user_data['saturday']."','".$user_data['lunch']."')");

    }

    mysqli_close($conn);
    $msg = array("success" => 1,'msg'=>'Busness profile created');
    return $response->withJson(array('response'=>$msg));


  }

  mysqli_close($conn);
  $msg = array("success" => 0,'msg'=>'Somthing went wrong');
  return $response->withJson(array('response'=>$msg));

});

$app->post('/list_profile_bussness',function($request,$response,$args){
      $user_data = $request->getParsedBody();
      $cheak = cheak_token_user($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
    $conn=sqlConnection();
    if($business_data=$conn->query("SELECT id,CompannyName,CompanylogoString from business_profile where status =1 AND block_status=1 AND user_id=".$user_data['id'])){
      if($business_data->num_rows <= 0){
        mysqli_close($conn);
        return $response->withJson(array('response'=>array('success'=>2,'msg'=>'not get any data')));
      }
          while($business=$business_data->fetch_assoc()){
                $bussness_profiles[]=$business;
          }
          mysqli_close($conn);
          $msg = array("success" => 1,'data'=>$bussness_profiles);
          return $response->withJson(array('response'=>$msg));
    }
    mysqli_close($conn);
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson(array('response'=>$msg));

});

$app->post('/find_profile_bussness',function($request,$response,$args){
       $user_data = $request->getParsedBody();

      $cheak = cheak_token_user($user_data['id'],$user_data['token']);

      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
      if(!isset($user_data['SearchId'])){

        $msg = array("success" => 0,'msg'=>'Seacrch id not match');
        return $response->withJson($msg);

      }
      $now =date('Y-m-d H:i:s');
      $conn=sqlConnection();
      if($categery_data=$conn->query("SELECT id,business_profile from BusinessCategary_id where Categary_id=".$user_data['SearchId'])){
           $pro=0;
            while($row=$categery_data->fetch_assoc()){
                  $match=$conn->query("SELECT id,CompannyName,CompanylogoString,BusinessLink,OwnerName,End_date FROM business_profile where status =1 AND block_status=1 AND id=".$row['business_profile']);
                  $match_business_profile=$match->fetch_assoc();

                  if(strtotime($match_business_profile['End_date']) > strtotime($now)){
                  $bussness_profiles_match[$i]=$match_business_profile;
                  $pro++;
                }
            }
            if(empty($bussness_profiles_match)){
              return $response->withJson(array('response'=>array('success'=>2,'msg'=>'seacrh not found')));
            }
            mysqli_close($conn);
            $msg = array("success" => 1,'data'=>$bussness_profiles_match);
            return $response->withJson(array('response'=>$msg));
      }
      mysqli_close($conn);
      $msg = array("success" => 0,'msg'=>'unotherise');
      return $response->withJson(array('response'=>$msg));
});

$app->post('/view_profile_bussness',function($request,$response,$args){
      $user_data = $request->getParsedBody();
       $cheak = cheak_token_user($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }

      $conn=sqlConnection();
      if(empty($user_data['business_id']) OR !isset($user_data['business_id'])){
            return $response->withJson(array('success'=>2,'mag'=>'serch not found'));
      }
      if($business_data=$conn->query("SELECT * from business_profile where id=".$user_data['business_id']." AND status =1 AND block_status =1")){
            $i=0;
            if($business_data->num_rows <= 0 ){
                      mysqli_close($conn);
                      $msg = array("success" => 2,'msg'=>'not');
                    return $response->withJson($msg);
            }
            while($business=$business_data->fetch_assoc()){
                  $bussness_profiles[$i]['CompannyName']=$business['CompannyName'];
                  $bussness_profiles[$i]['OwnerName']=$business['OwnerName'];
                  $bussness_profiles[$i]['Contact']=$business['Contact'];
                  $bussness_profiles[$i]['Contact_2']=$business['Contact_2'];
                  $bussness_profiles[$i]['Address']=$business['Address'];
                  $bussness_profiles[$i]['CompanylogoString']=$business['CompanylogoString'];
                  $bussness_profiles[$i]['Website']=$business['Website'];
                  $bussness_profiles[$i]['Email']=$business['Email'];
                  $bussness_profiles[$i]['BusinessLink']=$business['BusinessLink'];
                  $bussness_profiles[$i]['Discription']=$business['BusinessDiscription'];
                  $bussness_profiles[$i]['contact_2']=$business['contact_2'];
                  $res=$conn->query("SELECT name from a_main where id = ".$business['country']);
                  $country=$res->fetch_assoc();
                  $res=$conn->query("SELECT name from a_main where id = ".$business['state']);
                  $state=$res->fetch_assoc();
                  $res=$conn->query("SELECT name from a_main where id = ".$business['city']);
                  $city=$res->fetch_assoc();
                  $bussness_profiles[$i]['Country']=$country['name'];
                  $bussness_profiles[$i]['State']=$state['name'];
                  $bussness_profiles[$i]['City']=$city['name'];
                  $bussness_profiles[$i]['District']=$city['name'];


                  if($categery_main=$conn->query("SELECT business_Name from BusinessMain_cat where id=".$business['business_main'])){
                     $main = $categery_main->fetch_assoc();
                     $bussness_profiles[$i]['Business_main']=$main['business_Name'];

                  }                        $bussness_profiles[$i]['busness_catagery'][0]=" ";
                  if($categery_data=$conn->query("SELECT * from BusinessCategary_id where business_profile=".$business['id'])){
                     $d=0;
                    while($row2=$categery_data->fetch_assoc()){

                      $catageryname = $conn->query("SELECT busness_name from busness_catagery where id=".$row2['Categary_id']);
                      $row3=$catageryname->fetch_assoc();
                      $bussness_profiles[$i]['busness_catagery'][$d]=$row3['busness_name'];
                      $d++;
                    }
                  }

              if($time=$conn->query("SELECT * FROM Business_time WHERE business_id = ".$user_data['business_id'])){
                  $timerow=$time->fetch_assoc();
                  $bussness_profiles[$i]['sun']=$timerow['sun'];
                  $bussness_profiles[$i]['mon']=$timerow['mon'];
                  $bussness_profiles[$i]['tue']=$timerow['tue'];
                  $bussness_profiles[$i]['wed']=$timerow['wed'];
                  $bussness_profiles[$i]['thu']=$timerow['thu'];
                  $bussness_profiles[$i]['fri']=$timerow['fri'];
                  $bussness_profiles[$i]['sat']=$timerow['sat'];
                  $bussness_profiles[$i]['lunch']=$timerow['break'];

                  }
                $i++;
            }
            mysqli_close($conn);
            $msg = array('success' => 1,'data'=>$bussness_profiles);
            return $response->withJson(array('response'=>$msg));

      }
      mysqli_close($conn);
      $msg = array('success' => 0,'msg'=>'unotherise');
      return $response->withJson(array('response'=>$msg));
});
$app->post('/search_profile_bussness',function($request,$response,$args){

      $user_data = $request->getParsedBody();

     if(empty($user_data['SearchName'])){

            $msg = array("success" => 2,'msg'=>'not found any company');
            return $response->withJson(array('response'=>$msg));
     }
// state = '.$user_data['state'].'AND city = '.$user_data['city'].'AND
       $now =date('Y-m-d H:i:s');
       $conn=sqlConnection();
      if($business_data=$conn->query('SELECT id,CompannyName,CompanylogoString,BusinessLink,OwnerName,End_date from business_profile where status =1 AND block_status =1 AND CompannyName like "%'.$user_data['SearchName'].'%"')){
         while($row2=$business_data->fetch_assoc()){
           if(strtotime($row2['End_date']) > strtotime($now)){
               $company[]=$row2;
            }
         }
           if(empty($company)){
            $msg = array("success" => 2,'msg'=>'not found any company');
            return $response->withJson(array('response'=>$msg));
           }
             mysqli_close($conn);
            $msg = array("success" => 1,'data'=>$company);
            return $response->withJson(array('response'=>$msg));
           }


      mysqli_close($conn);
      $msg = array("success" => 0,'msg'=>'unotherise');
      return $response->withJson(array('response'=>$msg));

});

$app->post('/Edit_profile_bussness',function($request,$response,$args){
      $user_data = $request->getParsedBody();
       $cheak = cheak_token_user($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }

$server_path=$cheak['path'];
      $conn=sqlConnection();
if(empty($user_data['CompanylogoString'])){
    $numArray = substr($user_data['CompanyCategory'],1,-1);
    $categry = explode(",", $numArray);
    $data = base64_decode($user_data['CompanylogoString']);
    $sql="UPDATE business_profile SET CompannyName='".$user_data['CompanyName']."',OwnerName='".$user_data['OwnerName']."',Contact='".$user_data['Contact']."',Contact_2='".$user_data['Contact_2']."',BusinessLink='".$user_data['BusinessLink']."',Address ='".$user_data['Address']."',Website='".$user_data['Website']."',Email='".$user_data['Email']."',business_main=".$user_data['busines_main'].",country=".$user_data['country'].",state=".$user_data['state'].",city=".$user_data['city'].",BusinessDiscription='".$user_data['Discription']."' where id=".$user_data['Business_id'];

      if($conn->query($sql)){
        $c=0;
        while(isset($categry[$c])){
          if($result = $conn->query("SELECT id FROM busness_catagery where busness_name = '".$categry[$c]."'")){
              $rowsub = $result->fetch_assoc();
              $subcat[$c]=$rowsub['id'];
          }
          $c++;
          // return $response->withJson(array('cat'=>"SELECT id FROM busness_catagery where busness_name = '".$categry[$c]."'",'main'=>$categry));
        }

      $b=0;
      if(!empty($subcat[$b])){
      $conn->query("DELETE FROM BusinessCategary_id where business_profile = ".$user_data['Business_id']);
      }

      while(isset($categry[$b])){
        $conn->query("INSERT INTO BusinessCategary_id(business_profile,Categary_id)VALUES(".$user_data['Business_id'].",".$subcat[$b].")");
         $b++;
        }

        if(!isset($user_data['monday']) OR empty($user_data['monday'])){
     mysqli_close($conn);
            $msg = array("success" => 0,'msg'=>"time not been updated");
            return $response->withJson(array('response'=>$msg));
        }


        $conn->query("UPDATE Business_time SET sun='".$user_data['sunday']."',mon='".$user_data['monday']."',tue='".$user_data['tuesday']."',wed='".$user_data['wednesday']."',thu='".$user_data['thursday']."',fri='".$user_data['friday']."',sat='".$user_data['saturday']."',break='".$user_data['lunch']."' where business_id = ".$user_data['Business_id']);
mysqli_close($conn);
            $msg = array("success" => 1,'msg'=>" success update");
            return $response->withJson(array('response'=>$msg));
      }

}else{

  $numArray = substr($user_data['CompanyCategory'],1,-1);
    $categry = explode(",", $numArray);
    $data = base64_decode($user_data['CompanylogoString']);
    $path =  __DIR__ ."/bussness_image/".$user_data['CompanylogoName'];
    file_put_contents($path, $data);

    $path_image = $server_path."bussness_image/".$user_data['CompanylogoName'];

    $sql="UPDATE business_profile SET CompannyName='".$user_data['CompanyName']."',OwnerName='".$user_data['OwnerName']."',Contact='".$user_data['Contact']."',Contact_2='".$user_data['Contact_2']."',BusinessLink='".$user_data['BusinessLink']."',Address ='".$user_data['Address']."',CompnnaylogoName='".$user_data['CompanylogoName']."',CompanylogoString='".$path_image."',Website='".$user_data['Website']."',business_main=".$user_data['busines_main'].",country=".$user_data['country'].",state=".$user_data['state'].",city=".$user_data['city']." where id=".$user_data['Business_id'];

      if($conn->query($sql)){
        $c=0;
        while(isset($categry[$c])){
          if($result = $conn->query("SELECT id FROM busness_catagery where busness_name = '".$categry[$c]."'")){
              $rowsub = $result->fetch_assoc();
              $subcat[$c]=$rowsub['id'];
          }
          $c++;
          // return $response->withJson(array('cat'=>"SELECT id FROM busness_catagery where busness_name = '".$categry[$c]."'",'main'=>$categry));
        }
      $b=0;
      if(!empty($subcat[$b]) ){
        $conn->query("DELETE FROM BusinessCategary_id where business_profile = ".$user_data['Business_id']);
      }
      while(isset($categry[$b])){
        $conn->query("INSERT INTO BusinessCategary_id(business_profile,Categary_id)VALUES(".$user_data['Business_id'].",".$subcat[$b].")");
         $b++;
        }

            if(!isset($user_data['monday']) OR empty($user_data['monday'])){
             mysqli_close($conn);
            $msg = array("success" => 0,'msg'=>"time not been updated");
            return $response->withJson(array('response'=>$msg));
           }

        $conn->query("UPDATE Business_time SET sun='".$user_data['sunday']."',mon='".$user_data['monday']."',tue='".$user_data['tuesday']."',wed='".$user_data['wednesday']."',thu='".$user_data['thursday']."',fri='".$user_data['friday']."',sat='".$user_data['saturday']."',break='".$user_data['lunch']."' where business_id = ".$user_data['Business_id']);

          mysqli_close($conn);
            $msg = array("success" => 1,'msg'=>"update");
            return $response->withJson(array('response'=>$msg));
      }

}
            mysqli_close($conn);
      $msg = array("success" => 2,'msg'=>'wrong');
      return $response->withJson(array('response'=>$msg));

});

$app->post('/business_Delete', function($request,$response) {

        $admin_data = $request->getParsedBody();
        $cheak = cheak_token_user($admin_data['id'],$admin_data['token']);

        if($cheak['success'] == 0){
          $msg = array("success" => 0,'msg'=>'unotherise');
          return $response->withJson($msg);
        }

       if(!isset($user_data['Profileid']) OR empty($user_data['Profileid'])){

          return $response->withJson(array('success'=>0,'msg'=>'Not find any data'));
       }
        $conn = sqlConnection();
        $profile = $conn->query("SELECT id FROM business_profile WHERE id = ".$admin_data['profileid']." AND user_id=".$admin_data['id']);
        if($profile->num_rows == 1){
                 try{

                   $result=$conn->prepare("UPDATE business_profile set status = 0 where id= ? AND user_id= ?");
                      $result->bind_param('ss', $admin_data['profileid'],$admin_data['id']);
                      $result->execute();
                      $result->store_result();
                      $result->close();
                      mysqli_close($conn);
                      $msg = array('success' => 1,'msg'=>'business will deleted');
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

$app->post('/business_pdf', function($request,$response) {

        $admin_data = $request->getParsedBody();
        $cheak = cheak_token_user($admin_data['id'],$admin_data['token']);

        if($cheak['success'] == 0){
          $msg = array("success" => 0,'msg'=>'unotherise');
          return $response->withJson($msg);
        }

      $conn = sqlConnection();
      if($result=$conn->query("SELECT user_block from user where id =".$user_data['id'])){
        while($row=$result->fetch_assoc()){
           $user_per[]=$row;
        }
        if($user_per[0]['user_block']==0){
          $msg = array("success" => 0,'msg'=>'unotherise');
            return $response->withJson(array('response'=>$msg));
        }

      }
    $now =date('Y-m-d H:i:s');
    if($result=$conn->query("SELECT * from business_profile where status=1 AND block_status=1 AND id = ".$admin_data['Profileid'])){

    $bussness_profiles = $result->fetch_assoc();
    if(strtotime($bussness_profiles['End_date']) < strtotime($now)){
       $msg = array('success'=>2,'msg'=>'Your Subcription is over');
       return $response->withJson(array('response'=>$msg));
    }
    $catageryname = $conn->query("SELECT * FROM BusinessCategary_id where business_profile = ".$bussness_profiles['id']);
    $setname ="";

    while($cat = $catageryname->fetch_assoc()){
      $catname=$conn->query("SELECT * FROM busness_catagery where id = ".$cat['Categary_id']);
      $catname2 = $catname->fetch_assoc();
      $setname .= $catname2['busness_name']."<br>";
      }
                    $res=$conn->query("SELECT name from a_main where id = ".$bussness_profiles['country']);
                    $country=$res->fetch_assoc();
                    $res=$conn->query("SELECT name from a_main where id = ".$bussness_profiles['state']);
                    $state=$res->fetch_assoc();
                    $res=$conn->query("SELECT name from a_main where id = ".$bussness_profiles['city']);
                    $city=$res->fetch_assoc();
                     $image="<img src='bussness_image/".$bussness_profiles['CompnnaylogoName']."'  class='img-circle'/>";
                 $image1='<img src="profile_image/logo1_2.png" class="img-circle"/>';
                 $html = '<!DOCTYPE html>
                 <html>
                 <head>
                 <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                 <link rel="stylesheet" href="http://192.168.0.3:8080/api/v1/bootstrap.css" type="text/css"/>
                 <link rel="stylesheet" href="http://192.168.0.3:8080/api/v1/bio.css" type="text/css"/>
                 </head>
                   <body>
                     <meta charset="utf-8">
                     <div id="watermark">
                      '.$image1.'
                    </div>
                     <div class="container">
                       <div class="row">
                         <div class="col-sm-3">

                         </div>
                         <div class="col-sm-6">
                          <h3 align="center">Lohana Samaj<h3>
                           <br><br>
                           <table align="center">
                             <tr>
                               <th class="imagecenter"> '.$image.' </th>
                             </tr>
                           </table>
                           <br><br>
                           <table align="center" width="70%" class="table-striped" >
                           <thead>
                             <tr>
                               <th class="myclass" colspan="2">Business Details</th>
                             </tr>
                           </thead>
                           <tbody>
                             <tr>
                               <td><b>CompanyName</b></td>
                               <td>'.$bussness_profiles['CompannyName'].'</td>
                             </tr>
                             <tr>
                               <td><b>OwnerName</b></td>
                               <td>'.$bussness_profiles['OwnerName'].'</td>
                             </tr>
                             <tr>
                               <td><b>BusinessLink</b></td>
                               <td>'.$bussness_profiles['BusinessLink'].'</td>
                             </tr>
                             <tr>
                               <td><b>Website</b></td>
                               <td>'.$bussness_profiles['Website'].'</td>
                             </tr>
                             <tr>
                               <td><b>Business Discription</b></td>
                               <td>'.$bussness_profiles['BusinessDiscription'].'</td>
                             </tr>
                             <tr>
                               <td><b>Business Categary</b></td>
                               <td>'.$setname.'</td>
                             </tr>
                             <tr>
                               <td><b>Address</b></td>
                               <td>'.$bussness_profiles['Address'].'</td>
                             </tr>
                             <tr>
                               <td><b>Contact</b></td>
                               <td>'.$bussness_profiles['Contact'].'</td>
                             </tr>
                             <tr>
                               <td><b>Other Contact</b></td>
                               <td>'.$bussness_profiles['Contact_2'].'</td>
                             </tr>
                             <tr>
                               <td><b>Country</b></td>
                               <td>'.$country['name'].'</td>
                             </tr>
                             <tr>
                               <td><b>State</b></td>
                               <td>'.$state['name'].'</td>
                             </tr>
                             </tr>
                             <tr>
                               <td><b>City</b></td>
                               <td>'.$city['name'].'</td>
                             </tr>
                           </tbody>
                           </table>
                           <br>
                         </div>
                         <div class="col-sm-3">
                         </div>
                       </div>
                     </div>
                   </body>
                 </html>
                ';
             $dompdf = new Dompdf();
             $dompdf->load_html($html);
             $dompdf->render();
             // $dompdf->stream();
             $output = $dompdf->output(['isRemoteEnabled' => true]);
             file_put_contents("Business_pdf/".$bussness_profiles['CompnnaylogoName'].".pdf", $output);
             $link="http://192.168.0.3:8080/api/v1/Business_pdf/".$bussness_profiles['CompnnaylogoName'].".pdf";
             mysqli_close($conn);
             $msg = array("success" => 1,'msg'=>'genrate__pdf','link'=>$link);
             return $response->withJson(array('response'=>$msg));

        }
mysqli_close($conn);
             $msg = array("success" => 0,'msg'=>'nor genrated');
             return $response->withJson(array('response'=>$msg));
});

?>
