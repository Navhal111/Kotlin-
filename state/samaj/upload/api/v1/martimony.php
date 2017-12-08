<?php
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$app->post('/add_profile_matrimony',function($request,$response,$args){
  $user_data = $request->getParsedBody();
  $files = $request->getUploadedFiles();
       // $file = fopen("matrimony.txt","w");
       // echo fwrite($file,json_encode($user_data));
       // fclose($file);
  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
  $server_path=$cheak['path'];
  $conn = sqlConnection();
  try{

    $resule=$conn->prepare("SELECT RegisterProfileEmail from profile_met where RegisterProfileEmail =? AND status = 1");
    if(!$resule){
      return $response->withJson(array('success'=>0,'msg'=>'somting went wrong'));
          }
    $resule->bind_param('s', $user_data['RegisterProfileEmail']);
    $resule->execute();
    $resule->store_result();
    if($resule->num_rows >= 1 ){
      $resule->close();
      mysqli_close($conn);
      $msg = array('success' => 2,'msg'=>'user allredy exist ');
      return $response->withJson(array('response'=>$msg));
     }

   if(empty($user_data['RegisterProfileImageString']) OR !isset($user_data['RegisterProfileImageString'])){
      $msg = array('success' => 2,'msg'=>'Image not selectd ');
      return $response->withJson(array('response'=>$msg));
   }
    $data = base64_decode($user_data['RegisterProfileImageString']);
    $path =  __DIR__ ."/profile_image/".$user_data['RegisterProfileImageName'];
    file_put_contents($path, $data);
    $path_image = $server_path."profile_image/".$user_data['RegisterProfileImageName'];
    $arr = explode("/", $user_data['RegisterBdate']);
    $arr1[0]=$arr[2];
    $arr1[1]=$arr[0];
    $arr1[2]=$arr[1];
    // $birth_date = implode("-", $arr1);
    // $to   = new DateTime($birth_date);
    $RegisterAge =22;
    // $startdate = date('Y-m-d H:i:s');
    $enddate="2018-01-01 09:55:25";
    $amount ="100";

if($conn->query("INSERT INTO profile_met(gen_id,RegisterProfileFor,RegisterSurname,RegisterSubCast,RegisterMaritalStatus,RegisterGender,RegisterFullname,RegisterFatherName,RegisterFatherMobile,RegisterMotherName,RegisterMotherMobile,RegisterCountry,RegisterState,RegisterCity,RegisterTaluka,RegisterAddress,RegisterPincode,RegisterBdate,RegisterAge,RegisterBtime,RegisterHobby,RegisterHeight,RegisterWeight,RegisterAboutgroom,RegisterEducation,RegisterOccupation,RegisterIncomeDuration,RegisterIncome,RegisterProfileEmail,RegisterProfileMobile,RegisterProfileImageName,RegisterProfileImageString,RegisterRefName,RegisterRefSurname,RegisterRefMobile,RegisterRefVillage,RegisterRefAddress,RegisterLookingMatrial,RegisterLookingEducation,RegisterLookingSubCast,RegisterHoroscope,RegisterAboutPartner,RegisterNRI,RegisterAfterMrgJob,Payment_start,Payment_end,Amount_payment)values (".$user_data['id'].",'".$user_data["RegisterProfileFor"]."','".$user_data['RegisterSurname']."','".$user_data['RegisterSubCast']."','".$user_data['RegisterMaritalStatus']."','".$user_data['RegisterGender']."','".$user_data['RegisterFullname']."','".$user_data['RegisterFatherName']."','".$user_data['RegisterFatherMobile']."','".$user_data['RegisterMotherName']."','".$user_data['RegisterMotherMobile']."',".$user_data['RegisterCountry'].",".$user_data['RegisterState'].",".$user_data['RegisterCity'].",".$user_data['RegisterTaluka'].",'".$user_data['RegisterAddress']."','".$user_data['RegisterPincode']."','".$user_data['RegisterBdate']."',".$RegisterAge.",'".$user_data['RegisterBtime']."','".$user_data['RegisterHobby']."','".$user_data['RegisterHeight']."','".$user_data['RegisterWeight']."','".$user_data['RegisterAboutgroom']."',".$user_data['RegisterEducation'].",".$user_data['RegisterOccupation'].",'".$user_data['RegisterIncomeDuration']."','".$user_data['RegisterIncome']."','".$user_data['RegisterProfileEmail']."','".$user_data['RegisterProfileMobile']."','".$user_data['RegisterProfileImageName']."','".$path_image."','".$user_data['RegisterRefName']."','".$user_data['RegisterRefSurname']."','".$user_data['RegisterRefMobile']."','".$user_data['RegisterRefVillage']."','".$user_data['RegisterRefAddress']."','".$user_data['RegisterLookingMatrial']."','".$user_data['RegisterLookingEducation']."','".$user_data['RegisterLookingSubCast']."','".$user_data['RegisterHoroscope']."','".$user_data['RegisterAboutPartner']."','".$user_data['RegisterNRI']."','".$user_data['RegisterAfterMrgJob']."','".$startdate."','".$enddate."','".$amount."')")){
           $profile_id = $conn->insert_id;
           $i=0;

           if(!empty($user_data['SiblingBrother'])){
             $siblind =json_encode($user_data['SiblingBrother']);
             $siblind = json_decode($user_data['SiblingBrother'],'utf8');
             // return $response->withJson(array('response'=>$siblind[$i]));
             while(isset($siblind[$i])){
if($conn->query("INSERT INTO sibling_met(profile_id,name,married_status,phone_number,gender,country,state,city,elder) values (".$profile_id.",'".$siblind[$i]['Name']."','Male','".$siblind[$i]['Married']."','".$siblind[$i]['Mobile']."',".$siblind[$i]['Country'].",".$siblind[$i]['State'].",".$siblind[$i]['City'].",'".$siblind[$i]['Elder']."')")){
                     $i++;
                 }
           }


           }
         $i=0;
           if(!empty($user_data['SiblingSister'])){
             $siblind =json_encode($user_data['SiblingSister']);
             $siblind = json_decode($user_data['SiblingSister'],'utf8');
              while(isset($siblind[$i])){
if($conn->query("INSERT INTO sister_met(profile_id,name,married_status,phone_number,country,state,city,elder) values (".$profile_id.",'".$siblind[$i]['Name']."','".$siblind[$i]['Married']."','".$siblind[$i]['Mobile']."',".$siblind[$i]['Country'].",".$siblind[$i]['State'].",".$siblind[$i]['City'].",'".$siblind[$i]['Elder']."')")){
                     $i++;
                  }

            }

           }

          mysqli_close($conn);
          $msg = array('success'=>1,'msg'=>'register profile');
          return $response->withJson(array('response'=>$msg));
          }


  }catch(Exception $e){
    mysqli_close($conn);
    return $response->withJson(array('response'=>$e,'msg'=>"excexpotion"));
  }
  mysqli_close($conn);
  $msg = array('success'=>0,'msg'=>'not register profile','sql'=>$sql);
  return $response->withJson(array('response'=>$msg));

});

$app->post('/list_mat',function($request,$response,$args){
  $data = $request->getParsedBody();

  $cheak = cheak_token_user($data['id'],$data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson(array('response'=>$msg));
  }


   $conn =sqlConnection();
   if($result=$conn->query("SELECT id,RegisterFullname,RegisterBdate,RegisterProfileImageString  from profile_met where status = 1 AND block_status=1 AND gen_id=".$data['id'])){
   if($result->num_rows <= 0){
        mysqli_close($conn);
        return $response->withJson(array('response'=>array('success'=>2,'msg'=>'not get any data')));
      }
       while($row=$result->fetch_assoc()){
          $met_data[]=$row;
       }

       $result->close();
       mysqli_close($conn);
       $msg= array('success'=>1,'data'=>$met_data);
       return $response->withJson(array('response'=>$msg));
   }
 mysqli_close($conn);
 $msg = array('success'=>0,'msg'=>'something wrong');
 return $response->withJson(array('response'=>$msg));

});

$app->post('/filter_match_profile',function($request,$response,$args){
  $user_data = $request->getParsedBody();
  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
    $conn = sqlConnection();
    if($result=$conn->query("SELECT block_status from user where status = 1 id =".$user_data['id'])){
      while($row=$result->fetch_assoc()){
         $user_per[]=$row;
      }
      if($user_per[0]['block_status']==0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }

    }
    $Age = "";
    $Height="";
    $married_status ="";
    $SubCast="";
    $Education="";
    $Occupation="";
    if($resultgen=$conn->query("SELECT RegisterGender,Payment_end  from profile_met where id=".$user_data["profileid"])){
      while($rowgen=$resultgen->fetch_assoc()){
         $pro_gen[]=$rowgen;
      }
    }
    $now =date('Y-m-d H:i:s');
    if(strtotime($pro_gen[0]['Payment_end']) < strtotime($now)){
      $msg = array("success" => 0,'msg'=>'Your Subcription is over');
      return $response->withJson(array('response'=>$msg));
    }

    if($pro_gen[0]['RegisterGender']=='Male'){
       $Gender =" AND RegisterGender = 'Female' ";
    }

    if($pro_gen[0]['RegisterGender']=='Female'){
       $Gender =" AND RegisterGender = 'Male' ";
    }

    if(!empty($user_data['StartAge']) AND !empty($user_data['EndAge'])){
       $Age=" AND RegisterAge BETWEEN ".$user_data['StartAge']." AND ".$user_data['EndAge'];;
    }
    if(!empty($user_data['StartHeight']) AND !empty($user_data['EndHeight'])){
      $Height = " AND RegisterHeight BETWEEN ".$user_data['StartHeight']." AND ".$user_data['EndHeight'];
    }
    if(!empty($user_data['MaritalStatus']) AND $user_data['MaritalStatus']!= "Not Selected"){
      $married_status =" AND RegisterMaritalStatus = '".$user_data['MaritalStatus']."'";
    }
    if(!empty($user_data['SubCast']) AND $user_data['SubCast']!= "Not Selected"){
      $SubCast=" AND RegisterSubCast = '".$user_data['SubCast']."'";
    }
    if(!empty($user_data['Education'])  AND $user_data['Education']!= "Not Selected"){
      $res=$conn->query("SELECT id from education_met where education = '".$user_data['Education']."'");
      $edu=$res->fetch_assoc();
      $Education=" AND RegisterEducation = ".$edu['id'];
    }
    if(!empty($user_data['Occupation']) AND $user_data['Occupation']!= "Not Selected"){
      $res=$conn->query("SELECT id from occupation_met where occupation = '".$user_data['Occupation']."'");
      $ocu=$res->fetch_assoc();
      $Occupation=" AND RegisterOccupation = ".$ocu['id'];
    }

    $now =date('Y-m-d H:i:s');
    $sql ="SELECT id,RegisterFullname,RegisterBdate,RegisterProfileImageString,Payment_end from profile_met where status = 1 AND gen_id != ".$user_data['id'].$Gender.$Age.$Height.$married_status.$SubCast.$Education.$Occupation." AND status =1 AND block_status =1";
    // $msg = array("success" => 0,'data'=>$sql);
    // return $response->withJson(array('response'=>$msg));
      if($result=$conn->query($sql)){
        $pro=0;
        while($row=$result->fetch_assoc()){

            if(strtotime($row['Payment_end']) > strtotime($now)){
            $met_data[$pro]=$row;
            $profile_id_match[$pro] = $row['id'];
            $pro++;
          }
    }

      }


        if(!empty($user_data['StartAge']) AND !empty($user_data['EndAge'])){
           $Age=" OR RegisterAge BETWEEN ".$user_data['StartAge']." AND ".$user_data['EndAge'];;
        }
        if(!empty($user_data['StartHeight']) AND !empty($user_data['EndHeight'])){
          $Height = " OR RegisterHeight BETWEEN ".$user_data['StartHeight']." AND ".$user_data['EndHeight'];
        }
        if(!empty($user_data['MaritalStatus']) AND $user_data['MaritalStatus']!= "Not Selected"){
          $married_status =" OR RegisterMaritalStatus = '".$user_data['MaritalStatus']."'";
        }
        if(!empty($user_data['SubCast']) AND $user_data['SubCast']!= "Not Selected"){
          $SubCast=" OR RegisterSubCast = '".$user_data['SubCast']."'";
        }
        if(!empty($user_data['Education']) AND $user_data['Education']!= "Not Selected"){
          $res=$conn->query("SELECT id from education_met where education = '".$user_data['Education']."'");
          $edu=$res->fetch_assoc();
          $Education=" OR RegisterEducation = ".$edu['id'];
        }
        if(!empty($user_data['Occupation']) AND $user_data['Occupation']!= "Not Selected"){
          $res=$conn->query("SELECT id from occupation_met where occupation = '".$user_data['Occupation']."'");
          $ocu=$res->fetch_assoc();
          $Occupation=" OR RegisterOccupation = ".$ocu['id'];
        }
        $adnARRAy="";
        if(!empty($met_data)){
          $adnARRAy = " AND id NOT IN (" . implode(',', array_map('intval', $profile_id_match)) . ")";
        }


        $sql ="SELECT id,RegisterFullname,RegisterBdate,RegisterProfileImageString,Payment_end  from profile_met where status = 1 AND block_status =1 AND gen_id != ".$user_data['id']." AND (status = 0 ".$Gender.$Age.$Height.$married_status.$SubCast.$Education.$Occupation.")".$adnARRAy;
        // $msg = array("success" => 0,'data'=>$sql);
        // return $response->withJson(array('response'=>$msg));
          if($result=$conn->query($sql)){
            while($row=$result->fetch_assoc()){
              if(strtotime($row['Payment_end']) > strtotime($now)){
              $met_data[$pro]=$row;
              $profile_id_match[$pro] = $row['id'];
              $pro++;
            }
            }
        }
        if(empty($met_data)){
          $msg = array("success" => 2,'msg'=>'not found any match');
          return $response->withJson(array('response'=>$msg));
        }
        $msg = array("success" => 1,'data'=>$met_data);
        return $response->withJson(array('response'=>$msg));
        // echo $from->diff($to)->y;



    $msg = array("success" => 0,'data'=>'somthing wrong');
    return $response->withJson(array('response'=>$msg));

});

$app->post('/profile_detail_privacy',function($request,$response,$args){
  $user_data = $request->getParsedBody();
  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
    $conn = sqlConnection();
    if($result=$conn->query("SELECT user_block from user where status = 1 AND id =".$user_data['id'])){
      while($row=$result->fetch_assoc()){
         $user_per[]=$row;
      }
      if($user_per[0]['user_block']==0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }

    }

    if($result=$conn->query("SELECT * from profile_met where status=1 AND block_status=1 AND id = ".$user_data['Profileid'])){
      $o=0;
      while($row=$result->fetch_assoc()){
        $res=$conn->query("SELECT name from a_main where id = ".$row['RegisterCountry']);
        $country=$res->fetch_assoc();
        $res=$conn->query("SELECT name from a_main where id = ".$row['RegisterState']);
        $state=$res->fetch_assoc();
        $res=$conn->query("SELECT name from a_main where id = ".$row['RegisterCity']);
        $city=$res->fetch_assoc();
        $res=$conn->query("SELECT name from a_main where id = ".$row['RegisterTaluka']);
        $taluka=$res->fetch_assoc();
        $res=$conn->query("SELECT education from education_met where id = ".$row['RegisterEducation']);
        $edu=$res->fetch_assoc();
        $res=$conn->query("SELECT occupation from occupation_met where id = ".$row['RegisterOccupation']);
        $ocu=$res->fetch_assoc();
        // $res=$conn->query("SELECT occupation from occupation_met where id = ".$row['RegisterRefOccupationid']);
        // $ocuref=$res->fetch_assoc();
        $profile_match_array[$o]=$row['id'];
         $pro_seach[$o]['RegisterSurname']=$row['RegisterSurname'];
         $pro_seach[$o]['RegisterSubCast']=$row['RegisterSubCast'];
         $pro_seach[$o]['RegisterMaritalStatus']=$row['RegisterMaritalStatus'];
         $pro_seach[$o]['RegisterProfileFor']=$row['RegisterProfileFor'];
         $pro_seach[$o]['RegisterGender']=$row['RegisterGender'];
         $pro_seach[$o]['RegisterFullname']=$row['RegisterFullname'];
         $pro_seach[$o]['RegisterAddress']=$row['RegisterAddress'];
         $pro_seach[$o]['RegisterPincode']=$row['RegisterPincode'];
         $pro_seach[$o]['RegisterBdate']=$row['RegisterBdate'];
         $pro_seach[$o]['RegisterBtime']=$row['RegisterBtime'];
             $arr = explode("/", $row['RegisterBdate']);
              $arr1[0]=$arr[2];
              $arr1[1]=$arr[0];
              $arr1[2]=$arr[1];
             $birth_date = implode("-", $arr1);
          $to   = new DateTime('today');
          $from   = new DateTime($birth_date);
          $Age= $from->diff($to)->y;
         $pro_seach[$o]['RegisterAge']=$Age;
         $pro_seach[$o]['RegisterHeight']=$row['RegisterHeight'];
         $pro_seach[$o]['RegisterWeight']=$row['RegisterWeight'];
         $pro_seach[$o]['RegisterHobby']=$row['RegisterHobby'];
         $pro_seach[$o]['RegisterEducation']=$edu['education'];
         $pro_seach[$o]['RegisterOccupation']=$ocu['occupation'];
         $pro_seach[$o]['RegisterIncomeDuration']=$row['RegisterIncomeDuration'];
         $pro_seach[$o]['RegisterIncome']=$row['RegisterIncome'];
         $pro_seach[$o]['RegisterRefName']=$row['RegisterRefName'];
         $pro_seach[$o]['RegisterRefSurname']=$row['RegisterRefSurname'];
         $pro_seach[$o]['RegisterRefOccupation']=$ocuref['occupation'];
         $pro_seach[$o]['RegisterFatherName']=$row['RegisterFatherName'];
         $pro_seach[$o]['RegisterMotherName']=$row['RegisterMotherName'];
         $pro_seach[$o]['RegisterProfileImageString']=$row['RegisterProfileImageString'];
         $pro_seach[$o]['RegisterCountry']=$country['name'];
         $pro_seach[$o]['RegisterState']=$state['name'];
         $pro_seach[$o]['RegisterCity']=$city['name'];
         $pro_seach[$o]['RegisterTaluka']=$taluka['name'];
         $o++;

      }
      mysqli_close($conn);
      $msg = array("success" => 1,'data'=>$pro_seach);
      return $response->withJson(array('response'=>$msg));

    }
    mysqli_close($conn);
    $msg = array("success" => 0,'msg'=>'not found any match');
  return $response->withJson(array('response'=>$msg));

  });


$app->post('/search_by_name_profile',function($request,$response,$args){
    $user_data = $request->getParsedBody();
    $cheak = cheak_token_user($user_data['id'],$user_data['token']);
    if($cheak['success'] == 0){
      $msg = array("success" => 0,'msg'=>'unotherise');
      return $response->withJson($msg);
    }


      $conn = sqlConnection();
      if($result=$conn->query("SELECT block_status from user where status = 1 id =".$user_data['id'])){
        while($row=$result->fetch_assoc()){
           $user_per[]=$row;
        }
        if($user_per[0]['block_status']==0){
          $msg = array("success" => 0,'msg'=>'unotherise');
          return $response->withJson($msg);
        }
      }
      if($resultgen=$conn->query("SELECT Payment_end  from profile_met where id=".$user_data["ProfileId"])){
        while($rowgen=$resultgen->fetch_assoc()){
           $pro_gen[]=$rowgen;
        }
      }
      $now =date('Y-m-d H:i:s');
      if(strtotime($pro_gen[0]['Payment_end']) < strtotime($now)){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson(array('response'=>$msg));
      }

    if($result=$conn->query("SELECT RegisterGender  from profile_met where id=".$user_data["ProfileId"])){
      while($row=$result->fetch_assoc()){
         $pro_gen[]=$row;
      }
    }

    if($pro_gen[0]['RegisterGender']=='Male'){
       $Gender =" AND RegisterGender = 'Female' ";
    }
    if($pro_gen[0]['RegisterGender']=='Female'){
       $Gender =" AND RegisterGender = 'Male' ";
    }

      if($result=$conn->query("SELECT id,RegisterFullname,RegisterBdate,RegisterProfileImageString  from profile_met where gen_id!=".$user_data['id'].$Gender." AND status = 1 AND RegisterFullname like('%".$user_data['SearchName']."%')")){
        while($row=$result->fetch_assoc()){
           $pro_seach[]=$row;
        }

        mysqli_close($conn);
        $msg = array("success" => 1,'data'=>$pro_seach);
      return $response->withJson(array('response'=>$msg));
      }

      mysqli_close($conn);
      $msg = array("success" => 0,'msg'=>'unotherise');
      return $response->withJson(array('response'=>$msg));
    });


$app->post('/send_request_profile',function($request,$response,$args){
      $user_data = $request->getParsedBody();
      $cheak = cheak_token_user($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }

        $conn = sqlConnection();
        if(empty($user_data['ProfileSenderId']) OR empty($user_data['ProfileReceverId'])){
          mysqli_close($conn);
          $msg = array("success" => 0,'msg'=>'somthing wrong');
          return $response->withJson(array('response'=>$msg));
        }

    if($chek_result = $conn->query("SELECT id FROM request_met WHERE ProfileSenderId = ".$user_data['ProfileSenderId']." AND ProfileReceverId=".$user_data['ProfileReceverId'])){
        if($chek_result->num_rows > 0){
          mysqli_close($conn);
         $msg = array("success" => 2,'msg'=>'Request Allredy Send');
         return $response->withJson(array('response'=>$msg));
        }
     }

     if($chek_result = $conn->query("SELECT id FROM request_met WHERE ProfileSenderId = ".$user_data['ProfileReceverId']." AND ProfileReceverId=".$user_data['ProfileSenderId'])){
         if($chek_result->num_rows > 0){
           mysqli_close($conn);
          $msg = array("success" => 2,'msg'=>'Request Allredy Send by other pertion');
          return $response->withJson(array('response'=>$msg));
         }
      }

     if($conn->query("INSERT INTO request_met(RegisterUserId,ProfileSenderId,ProfileReceverId,status) values (".$user_data['id'].",".$user_data['ProfileSenderId'].",".$user_data['ProfileReceverId'].",0)")){
           mysqli_close($conn);
          $msg = array("success" => 1,'msg'=>'Request Send');
          return $response->withJson(array('response'=>$msg));
     }

         mysqli_close($conn);
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson(array('response'=>$msg));
});


$app->post('/approve_request_profile',function($request,$response,$args){
      $user_data = $request->getParsedBody();
      $cheak = cheak_token_user($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
      if($resultgen=$conn->query("SELECT Payment_end  from profile_met where id=".$user_data["ProfileId"])){
        while($rowgen=$resultgen->fetch_assoc()){
           $pro_gen[]=$rowgen;
        }
      }
      $now =date('Y-m-d H:i:s');
      if(strtotime($pro_gen[0]['Payment_end']) < strtotime($now)){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson(array('response'=>$msg));
      }

      $conn= sqlConnection();
      if($res=$conn->query("SELECT id FROM profile_met where gen_id = ".$user_data['id']." AND id = ".$user_data['ProfileId'])){
          if($res->num_rows > 0){

               if($conn->query("UPDATE request_met SET status = 1 WHERE ProfileSenderId = ".$user_data['SenderId']." AND ProfileReceverId= ".$user_data['ProfileId'])){
                 mysqli_close($conn);
                 $msg = array("success" => 1,'msg'=>'Aprove reqvest');
                 return $response->withJson(array('response'=>$msg));
               }else{
                 mysqli_close($conn);
                 $msg = array("success" => 2,'msg'=>'Not send request');
                 return $response->withJson($msg);
               }

          }
          mysqli_close($conn);
          $msg = array("success" => 2,'msg'=>'somthing wrong');
          return $response->withJson($msg);
      }
      mysqli_close($conn);
      $msg = array("success" => 2,'msg'=>'somthing wrong');
      return $response->withJson($msg);
  });
$app->post('/panding_request_profile',function($request,$response,$args){
        $user_data = $request->getParsedBody();

        $cheak = cheak_token_user($user_data['id'],$user_data['token']);
        if($cheak['success'] == 0){
          $msg = array("success" => 0,'msg'=>'unotherise');
          return $response->withJson($msg);
        }


        $conn= sqlConnection();
        if($resultgen=$conn->query("SELECT Payment_end  from profile_met where id=".$user_data["profileid"])){
          while($rowgen=$resultgen->fetch_assoc()){
             $pro_gen[]=$rowgen;
          }
        }
        $now =date('Y-m-d H:i:s');
        if(strtotime($pro_gen[0]['Payment_end']) < strtotime($now)){
          $msg = array("success" => 0,'msg'=>'unotherise');
          return $response->withJson(array('response'=>$msg));
        }
          if($panding=$conn->query("SELECT ProfileSenderId FROM request_met where ProfileReceverId = ".$user_data['profileid']." AND status = 0")){
           $pad=0;
            while($panding_id=$panding->fetch_assoc()){

               if($panding_profile = $conn->query("SELECT id,RegisterFullname,RegisterBdate,RegisterProfileImageString  from profile_met where status = 1 AND block_status =1 AND id = ".$panding_id['ProfileSenderId'])){
                    $panding_full_profile=$panding_profile->fetch_assoc();
                    $profile_panding[$pad]=$panding_full_profile;
                    $pad++;
               }
             }
             mysqli_close($conn);
             $msg = array("success" => 1,'data'=>$profile_panding);
             return $response->withJson(array('response'=>$msg));

          }
          mysqli_close($conn);
          $msg = array("success" => 2,'msg'=>'somthing wrong');
          return $response->withJson(array('response'=>$msg));
});

$app->post('/accept_request_profile',function($request,$response,$args){

        $user_data = $request->getParsedBody();
        $cheak = cheak_token_user($user_data['id'],$user_data['token']);
        if($cheak['success'] == 0){
          $msg = array("success" => 0,'msg'=>'unotherise');
          return $response->withJson($msg);
        }

        $conn= sqlConnection();
        if($resultgen=$conn->query("SELECT Payment_end  from profile_met where id=".$user_data["profileid"])){
          while($rowgen=$resultgen->fetch_assoc()){
             $pro_gen[]=$rowgen;
          }
        }
        $now =date('Y-m-d H:i:s');
        if(strtotime($pro_gen[0]['Payment_end']) < strtotime($now)){
          $msg = array("success" => 0,'msg'=>'unotherise');
          return $response->withJson(array('response'=>$msg));
        }
        $pad=0;
          if($panding=$conn->query("SELECT ProfileSenderId FROM request_met where ProfileReceverId = ".$user_data['profileid']." AND status = 1")){

            while($panding_id=$panding->fetch_assoc()){

               if($panding_profile = $conn->query("SELECT id,RegisterFullname,RegisterBdate,RegisterProfileImageString  from profile_met where status = 1 AND block_status =1 AND id = ".$panding_id['ProfileSenderId'])){
                    $panding_full_profile=$panding_profile->fetch_assoc();
                    $profile_panding[$pad]=$panding_full_profile;
                    $pad++;
               }
             }

          }
          if($panding=$conn->query("SELECT ProfileReceverId FROM request_met where ProfileSenderId = ".$user_data['profileid']." AND status = 1")){

            while($panding_id=$panding->fetch_assoc()){

               if($panding_profile = $conn->query("SELECT id,RegisterFullname,RegisterBdate,RegisterProfileImageString  from profile_met where status = 1 AND block_status =1 AND id = ".$panding_id['ProfileReceverId'])){
                    $panding_full_profile=$panding_profile->fetch_assoc();
                    $profile_panding[$pad]=$panding_full_profile;
                    $pad++;
               }
             }
             if(!empty($profile_panding)){
               mysqli_close($conn);
               $msg = array("success" => 1,'data'=>$profile_panding);
               return $response->withJson(array('response'=>$msg));

             }

          }

          mysqli_close($conn);
          $msg = array("success" => 2,'msg'=>'somthing wrong');
          return $response->withJson(array('response'=>$msg));
});
$app->post('/profile_detail_accepts',function($request,$response,$args){
  $user_data = $request->getParsedBody();
      // $file = fopen("matimony.txt","w");
      //   echo fwrite($file,json_encode($user_data));
      //   fclose($file);
  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
    $conn = sqlConnection();
    if($result=$conn->query("SELECT user_block from user where status = 1 AND block_status =1 AND id =".$user_data['id'])){
      while($row=$result->fetch_assoc()){
         $user_per[]=$row;
      }
      if($user_per[0]['user_block']==0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }

    }

    if($result=$conn->query("SELECT * from profile_met where status=1 AND block_status=1 AND id = ".$user_data['Profileid'])){
      $o=0;
      while($row=$result->fetch_assoc()){
        $res=$conn->query("SELECT name from a_main where id = ".$row['RegisterCountry']);
        $country=$res->fetch_assoc();
        $res=$conn->query("SELECT name from a_main where id = ".$row['RegisterState']);
        $state=$res->fetch_assoc();
        $res=$conn->query("SELECT name from a_main where id = ".$row['RegisterCity']);
        $city=$res->fetch_assoc();
        $res=$conn->query("SELECT name from a_main where id = ".$row['RegisterTaluka']);
        $taluka=$res->fetch_assoc();
        $res=$conn->query("SELECT education from education_met where id = ".$row['RegisterEducation']);
        $edu=$res->fetch_assoc();
        $res=$conn->query("SELECT occupation from occupation_met where id = ".$row['RegisterOccupation']);
        $ocu=$res->fetch_assoc();
      $res=$conn->query("SELECT occupation from occupation_met where id = ".$row['RegisterOccupation']);
        $ocuref=$res->fetch_assoc();
        $profile_match_array[$o]=$row['id'];
         $pro_seach[$o]['RegisterSurname']=$row['RegisterSurname'];
         $pro_seach[$o]['RegisterSubCast']=$row['RegisterSubCast'];
         $pro_seach[$o]['RegisterMaritalStatus']=$row['RegisterMaritalStatus'];
         $pro_seach[$o]['RegisterProfileFor']=$row['RegisterProfileFor'];
         $pro_seach[$o]['RegisterGender']=$row['RegisterGender'];
         $pro_seach[$o]['RegisterFullname']=$row['RegisterFullname'];
         $pro_seach[$o]['RegisterAddress']=$row['RegisterAddress'];
         $pro_seach[$o]['RegisterPincode']=$row['RegisterPincode'];
         $pro_seach[$o]['RegisterBdate']=$row['RegisterBdate'];
         $pro_seach[$o]['RegisterBtime']=$row['RegisterBtime'];
                      $arr = explode("/", $row['RegisterBdate']);
              $arr1[0]=$arr[2];
              $arr1[1]=$arr[0];
              $arr1[2]=$arr[1];
             $birth_date = implode("-", $arr1);
          $to   = new DateTime('today');
          $from   = new DateTime($birth_date);
          $Age= $from->diff($to)->y;
         $pro_seach[$o]['RegisterAge']=$Age;
         $pro_seach[$o]['RegisterHeight']=$row['RegisterHeight'];
         $pro_seach[$o]['RegisterWeight']=$row['RegisterWeight'];
         $pro_seach[$o]['RegisterHobby']=$row['RegisterHobby'];
         $pro_seach[$o]['RegisterEducation']=$edu['education'];
         $pro_seach[$o]['RegisterOccupation']=$ocu['occupation'];
         $pro_seach[$o]['RegisterIncomeDuration']=$row['RegisterIncomeDuration'];
         $pro_seach[$o]['RegisterIncome']=$row['RegisterIncome'];
         $pro_seach[$o]['RegisterFatherName']=$row['RegisterFatherName'];
         $pro_seach[$o]['RegisterFatherMobile']=$row['RegisterFatherMobile'];
         $pro_seach[$o]['RegisterMotherName']=$row['RegisterMotherName'];
         $pro_seach[$o]['RegisterMotherMobile']=$row['RegisterMotherMobile'];
         $pro_seach[$o]['RegisterRefName']=$row['RegisterRefName'];
         $pro_seach[$o]['RegisterRefSurname']=$row['RegisterRefSurname'];
         $pro_seach[$o]['RegisterRefOccupation']=$ocuref['occupation'];
         $pro_seach[$o]['RegisterRefMobile']=$row['RegisterRefMobile'];
         $pro_seach[$o]['RegisterProfileEmail']=$row['RegisterProfileEmail'];
         $pro_seach[$o]['RegisterProfileMobile']=$row['RegisterProfileMobile'];
         $pro_seach[$o]['RegisterProfileImageString']=$row['RegisterProfileImageString'];
         $pro_seach[$o]['RegisterCountry']=$country['name'];
         $pro_seach[$o]['RegisterState']=$state['name'];
         $pro_seach[$o]['RegisterCity']=$city['name'];
         $pro_seach[$o]['RegisterTaluka']=$taluka['name'];
         $o++;

      }
      mysqli_close($conn);
      $msg = array("success" => 1,'data'=>$pro_seach);
      return $response->withJson(array('response'=>$msg));

    }
    mysqli_close($conn);
    $msg = array("success" => 0,'msg'=>'not found any match');
  return $response->withJson(array('response'=>$msg));

  });

$app->post('/profile_shear_pdf',function($request,$response,$args){
    $user_data = $request->getParsedBody();

    $cheak = cheak_token_user($user_data['id'],$user_data['token']);
    if($cheak['success'] == 0){
      $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson(array('response'=>$msg));
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
      if($cheak_profile=$conn->query("SELECT id from profile_met where status = 1 AND block_status =1 AND gen_id =".$user_data['id'])){

        if($result=$conn->query("SELECT * from profile_met where status=1 AND block_status=1 AND id = ".$user_data['Profileid'])){

          if($cheak_profile->num_rows > 0){
                  while($row=$result->fetch_assoc()){
                    if(strtotime($row['Payment_end']) < strtotime($now) ){

                      $msg = array("success" => 2,'msg'=>'Your Subcription is over');
                      return $response->withJson(array('response'=>$msg));

                    }
                    $res=$conn->query("SELECT name from a_main where id = ".$row['RegisterCountry']);
                    $country=$res->fetch_assoc();
                    $res=$conn->query("SELECT name from a_main where id = ".$row['RegisterState']);
                    $state=$res->fetch_assoc();
                    $res=$conn->query("SELECT name from a_main where id = ".$row['RegisterCity']);
                    $city=$res->fetch_assoc();
                    $res=$conn->query("SELECT name from a_main where id = ".$row['RegisterTaluka']);
                    $taluka=$res->fetch_assoc();
                    $res=$conn->query("SELECT education from education_met where id = ".$row['RegisterEducation']);
                    $edu=$res->fetch_assoc();
                    $res=$conn->query("SELECT occupation from occupation_met where id = ".$row['RegisterOccupation']);
                    $ocu=$res->fetch_assoc();
                    $profile_match_array[$o]=$row['id'];
                     $pro_seach['RegisterSurname']=$row['RegisterSurname'];
                     $pro_seach['RegisterSubCast']=$row['RegisterSubCast'];
                     $pro_seach['RegisterMaritalStatus']=$row['RegisterMaritalStatus'];
                     $pro_seach['RegisterProfileFor']=$row['RegisterProfileFor'];
                     $pro_seach['RegisterGender']=$row['RegisterGender'];
                     $pro_seach['RegisterFullname']=$row['RegisterFullname'];
                     $pro_seach['RegisterAddress']=$row['RegisterAddress'];
                     $pro_seach['RegisterPincode']=$row['RegisterPincode'];
                     $pro_seach['RegisterBdate']=$row['RegisterBdate'];
                     $pro_seach['RegisterBtime']=$row['RegisterBtime'];
           $arr = explode("/", $row['RegisterBdate']);
              $arr1[0]=$arr[2];
              $arr1[1]=$arr[0];
              $arr1[2]=$arr[1];
             $birth_date = implode("-", $arr1);
          $to   = new DateTime('today');
          $from   = new DateTime($birth_date);
          $Age= $from->diff($to)->y;
                     $pro_seach['RegisterAge']=$Age;
                     $pro_seach['RegisterHeight']=$row['RegisterHeight'];
                     $pro_seach['RegisterWeight']=$row['RegisterWeight'];
                     $pro_seach['RegisterHobby']=$row['RegisterHobby'];
                     $pro_seach['RegisterEducation']=$edu['education'];
                     $pro_seach['RegisterOccupation']=$ocu['occupation'];
                     $pro_seach['RegisterIncomeDuration']=$row['RegisterIncomeDuration'];
                     $pro_seach['RegisterIncome']=$row['RegisterIncome'];
                     $pro_seach['RegisterFatherName']=$row['RegisterFatherName'];
                     $pro_seach['RegisterFatherMobile']=$row['RegisterFatherMobile'];
                     $pro_seach['RegisterMotherName']=$row['RegisterMotherName'];
                     $pro_seach['RegisterMotherMobile']=$row['RegisterMotherMobile'];
                     $pro_seach['RegisterProfileEmail']=$row['RegisterProfileEmail'];
                     $pro_seach['RegisterProfileMobile']=$row['RegisterProfileMobile'];
                     $pro_seach['RegisterProfileImageString']=$row['RegisterProfileImageString'];
                     $pro_seach['RegisterProfileImageName']=$row['RegisterProfileImageName'];
                     $pro_seach['RegisterCountry']=$country['name'];
                     $pro_seach['RegisterState']=$state['name'];
                     $pro_seach['RegisterCity']=$taluka['name'];
                     $pro_seach['RegisterTaluka']=$city['name'];
                   }

                 }
                 $image ="";
                 $image="<img src='profile_image/".$pro_seach['RegisterProfileImageName']."'  class='img-circle'/>";
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
                               <th class="myclass" colspan="2">Personal Details</th>
                             </tr>
                           </thead>
                           <tbody>
                             <tr>
                               <td><b>Fullname</b></td>
                               <td>'.$pro_seach['RegisterFullname'].'</td>
                             </tr>
                             <tr>
                               <td><b>Gender</b></td>
                               <td>'.$pro_seach['RegisterGender'].'</td>
                             </tr>
                             <tr>
                               <td><b>Birth Date</b></td>
                               <td>'.$pro_seach['RegisterBdate'].'</td>
                             </tr>
                             <tr>
                               <td><b>Birth Time</b></td>
                               <td>'.$pro_seach['RegisterBtime'].'</td>
                             </tr>
                             <tr>
                               <td><b>Sub-Cast</b></td>
                               <td>'.$pro_seach['RegisterSurname'].'</td>
                             </tr>
                             <tr>
                               <td><b>Marital Status</b></td>
                               <td>'.$pro_seach['RegisterMaritalStatus'].'</td>
                             </tr>
                             <tr>
                               <td><b>Age</b></td>
                               <td>'.$pro_seach['RegisterAge'].'</td>
                             </tr>
                             <tr>
                               <td><b>Height</b></td>
                               <td>'.$pro_seach['RegisterHeight'].'</td>
                             </tr>
                             <tr>
                               <td><b>Weight</b></td>
                               <td>'.$pro_seach['RegisterHeight'].'</td>
                             </tr>
                             <tr>
                               <td><b>Hobbies</b></td>
                               <td>'.$pro_seach['RegisterWeight'].'</td>
                             </tr>
                           </tbody>
                           </table>
                           <br>
                           <table align="center" width="70%" class="table-striped">
                             <tr>
                               <th class="myclass" colspan="2">Qualification</th>
                             </tr>+
                             <tr>
                               <td><b>Education</b></td>
                               <td align="left">'.$pro_seach['RegisterEducation'].'</td>
                             </tr>
                           </table>
                           <br>
                           <table align="center" width="70%" class="table-striped">
                             <tr>
                               <th class="myclass" colspan="2">Occupation Information</th>
                             </tr>
                             <tr>
                               <td><b>Occupation</b></td>
                               <td>'.$pro_seach['RegisterOccupation'].'</td>
                             </tr>
                             <tr>
                               <td><b>Income</b></td>
                               <td>'.$pro_seach['RegisterIncome'].' ('.$pro_seach['RegisterIncomeDuration'].')</td>
                           </table>
                           <br>
                           <table align="center" width="70%" class="table-striped">
                             <tr>
                               <th class="myclass" colspan="2">Family Details</th>
                             </tr>
                             <tr>
                               <td><b>Father Name</b></td>
                               <td>'.$pro_seach['RegisterFatherName'].'</td>
                             </tr>
                             <tr>
                               <td><b>Mother Name</b></td>
                               <td>'.$pro_seach['RegisterMotherName'].'</td>
                             </tr>
                           </table>
                           <br>
                           <table align="center" width="70%" class="table-striped">
                             <tr>
                               <th class="myclass" colspan="2">Resident & Contact Information</th>
                             </tr>
                             <tr>
                               <td><b>Mobile</b></td>
                               <td>'.$pro_seach['RegisterMotherMobile'].'</td>
                             </tr>
                             <tr>
                               <td><b>Email</b></td>
                               <td>'.$pro_seach['RegisterProfileEmail'].'</td>
                             </tr>
                             <tr>
                               <td><b>Address</b></td>
                               <td>'.$pro_seach['RegisterAddress'].'</td>
                             </tr>
                             <tr>
                               <td><b>Country</b></td>
                               <td>'.$pro_seach['RegisterCountry'].'</td>
                             </tr>
                             <tr>
                               <td><b>State</b></td>
                               <td>'.$pro_seach['RegisterState'].'</td>
                             </tr>
                             <tr>
                               <td><b>Taluka</b></td>
                               <td>'.$pro_seach['RegisterTaluka'].'</td>
                             </tr>
                           </table>
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
             file_put_contents("profile_pdf/".$pro_seach['RegisterProfileEmail'].".pdf", $output);
             $link="http://192.168.0.3:8080/api/v1/profile_pdf/".$pro_seach['RegisterProfileEmail'].".pdf";
             $msg = array("success" => 1,'msg'=>'genrate__pdf','link'=>$link);
             return $response->withJson(array('response'=>$msg));

          }else{
            $msg = array("success" => 0,'msg'=>'unotherise');
              return $response->withJson(array('response'=>$msg));
          }
          $msg = array("success" => 0,'msg'=>'woong');
            return $response->withJson(array('response'=>$msg));
      }
      $msg = array("success" => 0,'msg'=>'woong');
      return $response->withJson(array('response'=>$msg));

  });

$app->post('/profile_sibling_privacy',function($request,$response,$args){
    $user_data = $request->getParsedBody();
    $cheak = cheak_token_user($user_data['id'],$user_data['token']);
    if($cheak['success'] == 0){
      $msg = array("success" => 0,'msg'=>'unotherise');
      return $response->withJson($msg);
    }
      $conn = sqlConnection();
      if($result=$conn->query("SELECT user_block from user where status = 1 AND id =".$user_data['id'])){
        while($row=$result->fetch_assoc()){
           $user_per[]=$row;
        }

        if($user_per[0]['user_block']==0){
          $msg = array("success" => 0,'msg'=>'unotherise');
          return $response->withJson(array('response'=>$msg));
        }

        if($ressibling=$conn->query("SELECT * from sibling_met where status=1 AND profile_id = ".$user_data['Profileid'])){
             $sibl = 0;
             while($row2=$ressibling->fetch_assoc()){
                 $pro_seach[$sibl]['SiblingId']= $row2['id'];
                 $pro_seach[$sibl]['SiblingName']= $row2['name'];
                 $pro_seach[$sibl]['SiblingGender']= 'Male';
                 $pro_seach[$sibl]['SiblingMobile']= $row2['phone_number'];
                 $pro_seach[$sibl]['SiblingMaritalStatus']= $row2['married_status'];
                 $pro_seach[$sibl]['SiblingElder']= $row2['elder'];
                 $res=$conn->query("SELECT name from a_main where id = ".$row2['country']);
                 $country=$res->fetch_assoc();
                 $res=$conn->query("SELECT name from a_main where id = ".$row2['state']);
                 $state=$res->fetch_assoc();
                 $res=$conn->query("SELECT name from a_main where id = ".$row2['city']);
                 $city=$res->fetch_assoc();
                 $pro_seach[$sibl]['SiblingCountry']= $country['name'];
                 $pro_seach[$sibl]['SiblingState']= $state['name'];
                 $pro_seach[$sibl]['SiblingCity']= $city['name'];
                 $sibl++;

             }
        }
                if($ressibling=$conn->query("SELECT * from sister_met where status=1 AND profile_id = ".$user_data['Profileid'])){
             while($row2=$ressibling->fetch_assoc()){
                 $pro_seach[$sibl]['SiblingId']= $row2['id'];
                 $pro_seach[$sibl]['SiblingName']= $row2['name'];
                 $pro_seach[$sibl]['SiblingGender']= 'Female';
                 $pro_seach[$sibl]['SiblingMobile']= $row2['phone_number'];
                 $pro_seach[$sibl]['SiblingMaritalStatus']= $row2['married_status'];
                 $pro_seach[$sibl]['SiblingElder']= $row2['elder'];
                 $res=$conn->query("SELECT name from a_main where id = ".$row2['country']);
                 $country=$res->fetch_assoc();
                 $res=$conn->query("SELECT name from a_main where id = ".$row2['state']);
                 $state=$res->fetch_assoc();
                 $res=$conn->query("SELECT name from a_main where id = ".$row2['city']);
                 $city=$res->fetch_assoc();
                 $pro_seach[$sibl]['SiblingCountry']= $country['name'];
                 $pro_seach[$sibl]['SiblingState']= $state['name'];
                 $pro_seach[$sibl]['SiblingCity']= $city['name'];
                 $sibl++;

             }

             $msg = array("success" => 1,'data'=>$pro_seach);
             return $response->withJson(array('response'=>$msg));
        }

      }
      $msg = array("success" => 0,'msg'=>'somthing worng');
      return $response->withJson(array('response'=>$msg));
});

$app->post('/profile_upade_image',function($request,$response,$args){
  $user_data = $request->getParsedBody();
  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
    $server_path=$cheak['path'];
    $conn = sqlConnection();
    if($result=$conn->query("SELECT user_block from user where status = 1 AND id =".$user_data['id'])){
      while($row=$result->fetch_assoc()){
         $user_per[]=$row;
      }

      if($user_per[0]['user_block']==0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson(array('response'=>$msg));
      }
  }
    if($cheak_profile=$conn->query("SELECT id from profile_met where status = 1 AND block_status =1 AND gen_id =".$user_data['id'])){
       if($cheak_profile->num_rows > 0){
      $data = base64_decode($user_data['RegisterProfileImageString']);
      $path =  __DIR__ ."/profile_image/".$user_data['RegisterProfileImageName'];
      file_put_contents($path, $data);
      $path_image = $server_path."profile_image/".$user_data['RegisterProfileImageName'];

              if($conn->query("UPDATE profile_met SET RegisterProfileImageString = '".$path_image."' , RegisterProfileImageName = '".$user_data['RegisterProfileImageName']."' where id= ".$user_data['Profileid'])){
                $msg = array("success" => 1,'msg'=>'image uploded');
                return $response->withJson(array('response'=>$msg));

              }

              $msg = array("success" => 2,'msg'=>'wrong');
              return $response->withJson(array('response'=>$msg));
            }
    }
    $msg = array("success" => 2,'msg'=>'wrong');
    return $response->withJson(array('response'=>$msg));

});

$app->post('/profile_upadte_persnal_data',function($request,$response,$args){
  $user_data = $request->getParsedBody();
  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
    $server_path=$cheak['path'];
    $conn = sqlConnection();
    if($result=$conn->query("SELECT user_block from user where status = 1 AND id =".$user_data['id'])){
      while($row=$result->fetch_assoc()){
         $user_per[]=$row;
      }

      if($user_per[0]['user_block']==0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson(array('response'=>$msg));
      }
  }
  if($cheak_profile=$conn->query("SELECT id from profile_met where status = 1 AND block_status =1 AND gen_id =".$user_data['id'])){
     if($cheak_profile->num_rows > 0){
       $arr = explode("/", $user_data['RegisterBdate']);
       $arr1[0]=$arr[2];
       $arr1[1]=$arr[0];
       $arr1[2]=$arr[1];
       $birth_date = implode("-", $arr1);
       $to   = new DateTime($birth_date);
       $RegisterAge =date_diff($to, date_create('today'))->y;
if($conn->query("UPDATE profile_met SET RegisterFullname = '".$user_data['RegisterFullname']."' , RegisterSurname = '".$user_data['RegisterSurname']."', RegisterFatherName = '".$user_data['RegisterFatherName']."', RegisterMotherName = '".$user_data['RegisterMotherName']."', RegisterHeight = '".$user_data['RegisterHeight']."', RegisterWeight = '".$user_data['RegisterWeight']."', RegisterBdate = '".$user_data['RegisterBdate']."', RegisterAge = ".$RegisterAge.", RegisterMaritalStatus = '".$user_data['RegisterMaritalStatus']."',RegisterBtime='".$user_data['RegisterBtime']."',RegisterGender = '".$user_data['RegisterGender']."' where id= ".$user_data['Profileid'])){

              $msg = array("success" => 1,'msg'=>'data uploded','sql'=>$sql);
              return $response->withJson(array('response'=>$msg));

            }
            mysqli_close($conn);
            $msg = array("success" => 2,'msg'=>'wrong');
            return $response->withJson(array('response'=>$msg));
          }
  }
  mysqli_close($conn);
  $msg = array("success" => 2,'msg'=>'wrong');
  return $response->withJson(array('response'=>$msg));

});

$app->post('/profile_upadte_education_data',function($request,$response,$args){
      $user_data = $request->getParsedBody();
      $cheak = cheak_token_user($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        mysqli_close($conn);
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
        $server_path=$cheak['path'];
        $conn = sqlConnection();
        if($result=$conn->query("SELECT user_block from user where status = 1 AND id =".$user_data['id'])){
          while($row=$result->fetch_assoc()){
             $user_per[]=$row;
          }

          if($user_per[0]['user_block']==0){
            mysqli_close($conn);
            $msg = array("success" => 0,'msg'=>'unotherise');
            return $response->withJson(array('response'=>$msg));
          }
      }
      if($cheak_profile=$conn->query("SELECT id from profile_met where status = 1 AND block_status =1 AND gen_id =".$user_data['id'])){
           if($cheak_profile->num_rows > 0){
                     if($conn->query("UPDATE profile_met SET RegisterEducation = '".$user_data['RegisterEducation']."' , RegisterOccupation = '".$user_data['RegisterOccupation']."', RegisterHobby = '".$user_data['RegisterHobby']."', RegisterIncomeDuration = '".$user_data['RegisterIncomeDuration']."', RegisterIncome = '".$user_data['RegisterIncome']."'  where id= ".$user_data['Profileid'])){
                       mysqli_close($conn);
                       $msg = array("success" => 1,'msg'=>'data updated');
                       return $response->withJson(array('response'=>$msg));

                    }
                    mysqli_close($conn);
                    $msg = array("success" => 2,'msg'=>'wrong');
                    return $response->withJson(array('response'=>$msg));
           }
         }
         mysqli_close($conn);
         $msg = array("success" => 2,'msg'=>'wrong');
         return $response->withJson(array('response'=>$msg));
});

$app->post('/profile_upadte_background',function($request,$response,$args){

      $user_data = $request->getParsedBody();
      $cheak = cheak_token_user($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        mysqli_close($conn);
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
        $server_path=$cheak['path'];
        $conn = sqlConnection();
        if($result=$conn->query("SELECT user_block from user where status = 1 AND id =".$user_data['id'])){
          while($row=$result->fetch_assoc()){
             $user_per[]=$row;
          }

          if($user_per[0]['user_block']==0){
            mysqli_close($conn);
            $msg = array("success" => 0,'msg'=>'unotherise');
            return $response->withJson(array('response'=>$msg));
          }
      }
      if($cheak_profile=$conn->query("SELECT id from profile_met where status = 1 AND block_status =1 AND gen_id =".$user_data['id'])){
           if($cheak_profile->num_rows > 0){
                     if($conn->query("UPDATE profile_met SET RegisterAddress = '".$user_data['RegisterAddress']."', RegisterSubCast = '".$user_data['RegisterSubCast']."' , RegisterPincode = '".$user_data['RegisterPincode']."', RegisterCountry = ".$user_data['RegisterCountry'].", RegisterState = ".$user_data['RegisterState'].", RegisterCity =".$user_data['RegisterCity']." where id= ".$user_data['Profileid'])){
                       mysqli_close($conn);
                       $msg = array("success" => 1,'msg'=>'data updated');
                       return $response->withJson(array('response'=>$msg));
                    }
                    mysqli_close($conn);
                    $msg = array("success" => 2,'msg'=>'wrong');
                    return $response->withJson(array('response'=>$msg));
           }
         }
         mysqli_close($conn);
         $msg = array("success" => 2,'msg'=>'wrong');
         return $response->withJson(array('response'=>$msg));
});
$app->post('/profile_upadte_contact',function($request,$response,$args){

      $user_data = $request->getParsedBody();
      $cheak = cheak_token_user($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        mysqli_close($conn);
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
        $server_path=$cheak['path'];
        $conn = sqlConnection();
        if($result=$conn->query("SELECT user_block from user where status = 1 AND id =".$user_data['id'])){
          while($row=$result->fetch_assoc()){
             $user_per[]=$row;
          }

          if($user_per[0]['user_block']==0){
            mysqli_close($conn);
            $msg = array("success" => 0,'msg'=>'unotherise');
            return $response->withJson(array('response'=>$msg));
          }
      }
      if($cheak_profile=$conn->query("SELECT id from profile_met where status = 1 AND block_status =1 AND gen_id =".$user_data['id'])){
           if($cheak_profile->num_rows > 0){
                     if($conn->query("UPDATE profile_met SET RegisterProfileMobile = '".$user_data['RegisterProfileMobile']."', RegisterProfileEmail = '".$user_data['RegisterProfileEmail']."' , RegisterFatherMobile = '".$user_data['RegisterFatherMobile']."', RegisterMotherMobile = '".$user_data['RegisterMotherMobile']."' where id= ".$user_data['Profileid'])){
                       mysqli_close($conn);
                       $msg = array("success" => 1,'msg'=>'data updated');
                       return $response->withJson(array('response'=>$msg));
                    }
                    mysqli_close($conn);
                    $msg = array("success" => 2,'msg'=>'wrong');
                    return $response->withJson(array('response'=>$msg));
           }
         }
         mysqli_close($conn);
         $msg = array("success" => 2,'msg'=>'wrong');
         return $response->withJson(array('response'=>$msg));
});

$app->post('/profile_upadte_sibling',function($request,$response,$args){

      $user_data = $request->getParsedBody();
      $cheak = cheak_token_user($user_data['id'],$user_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }
        $conn = sqlConnection();
        if($result=$conn->query("SELECT user_block from user where status = 1 AND id =".$user_data['id'])){
          while($row=$result->fetch_assoc()){
             $user_per[]=$row;
          }

          if($user_per[0]['user_block']==0){
            mysqli_close($conn);
            $msg = array("success" => 0,'msg'=>'unotherise');
            return $response->withJson(array('response'=>$msg));
          }
      }

      if($cheak_profile=$conn->query("SELECT id from sibling_met where status = 1 AND profile_id =".$user_data['Profileid'])){
           if($cheak_profile->num_rows > 0){
if($conn->query("UPDATE sibling_met SET name = '".$user_data['Siblingname']."', married_status = '".$user_data['SiblingMaritalStatus']."',elder='".$user_data['elder']."' ,phone_number = '".$user_data['SiblingMobile']."',country=".$user_data['Siblingcountry'].",state=".$user_data['Siblingstate'].",city=".$user_data['Siblingcity']." where id= ".$user_data['Siblingid'])){
                       mysqli_close($conn);
                       $msg = array("success" => 1,'msg'=>'data updated');
                       return $response->withJson(array('response'=>$msg));
                    }
                    mysqli_close($conn);
                    $msg = array("success" => 2,'msg'=>'wrong');
                    return $response->withJson(array('response'=>$msg));
           }
         }
         mysqli_close($conn);
         $msg = array("success" => 2,'msg'=>'wrong');
         return $response->withJson(array('response'=>$msg));

});

$app->post('/matrimony_Delete', function($request,$response) {

        $admin_data = $request->getParsedBody();
        $cheak = cheak_token_user($admin_data['id'],$admin_data['token']);

        if($cheak['success'] == 0){
          $msg = array("success" => 0,'msg'=>'unotherise');
          return $response->withJson($msg);
        }
        $conn = sqlConnection();

        $profile = $conn->query("SELECT id FROM profile_met WHERE id = ".$admin_data['profileid']." AND gen_id=".$admin_data['id']);
        if($profile->num_rows == 1){
                 try{

                      $result=$conn->prepare("UPDATE profile_met set status = 0 where id= ? AND gen_id= ?");
                      // $resule->bind_param('s', $name);
                      $result->bind_param('ss', $admin_data['profileid'],$admin_data['id']);
                      $result->execute();
                      $result->store_result();
                      mysqli_close($conn);
                      $msg = array('success' => 1,'msg'=>'matrimony profile deleted will deleted');
                      return $response->withJson($msg);

                 }catch(Exception $e){
                   mysqli_close($conn);
                   $msg =array('success'=>0,'msg'=>$e);
                   return $response->withJson(array('response'=>$msg));
                 }

        }

             mysqli_close($conn);
             $msg = array('success' => 0,'msg'=>'enable to read data');
             return $response->withJson(array('response'=>$msg));

});
?>
