<?php
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$app->post('/add_nokari_profile',function($request,$response,$args){
  $user_data = $request->getParsedBody();
  // $file = fopen("profile.txt","w");
  // echo fwrite($file,json_encode($user_data));
  // fclose($file);
  // $msg =array('success'=>1,'msg'=>$user_data);
  // return $response->withJson($msg);
  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }

  if(empty($user_data['Name']) OR empty($user_data['Email'])){
    $msg = array("success" => 0,'msg'=>'Email not selected');
    return $response->withJson($msg);
   }
   $conn = sqlConnection();
   if($chekresult = $conn->query("SELECT Email FROM Nikari_user where Email = ".$user_data['Email'])){
     if($chekresult->num_rows >= 1){
       $msg = array("success" => 2,'msg'=>'Profile Email use alredy');
       return $response->withJson(array('response'=>$msg));
       }

   }
   $startdate = date('Y-m-d H:i:s');
   $enddate="2018-01-01 09:55:25";
   $amount ="100";
   if(!empty($Education['Ssc']) OR !empty($Education['Hsc']) OR !empty($Education['Graduation']) OR !empty($Education['PGraduation']) OR !empty($Education['Otherdegree'])){
     $msg = array("success" => 0,'msg'=>'Education not selected');
     return $response->withJson($msg);
   }

   if($conn->query('INSERT INTO Nikari_user(gen_id,Name,Email,DOB,Contact,Experience,Role,main_cat,sub_cat,Description,Adress,Pincode,country,state,city,Payment,End_date,Start_date)VALUES ('.$user_data['id'].',"'.$user_data['Name'].'","'.$user_data['Email'].'","'.$user_data['DOB'].'","'.$user_data['Contact'].'","'.$user_data['Experience'].'","'.$user_data['Role'].'","'.$user_data['main_cat'].'","'.$user_data['sub_cat'].'","'.$user_data['Description'].'","'.$user_data['Adress'].'","'.$user_data['Pincode'].'","'.$user_data['country'].'","'.$user_data['state'].'","'.$user_data['city'].'","'.$amount.'","'.$enddate.'","'.$startdate.'")')){

    $profile_id = $conn->insert_id;
    $i=0;

    $Education = json_decode($user_data['Education'],'utf8');
    if(isset($Education[0]['Ssc']) AND !empty($Education[0]['Ssc'])){
      $conn->query('INSERT INTO Job_education(profile_id,degree,Board,year,class,Description) VALUES (  "'.$profile_id.'","'.$Education[0]['Ssc'].'","'.$Education[0]['Sscboard'].'","'.$Education[0]['Sscpassyear'].'","'.$Education[0]['Sscpercentage'].'","'.$Education[0]['Sscdetail'].'")');
    }
    if(isset($Education[0]['Hsc']) AND !empty($Education[0]['Hsc'])){
      $conn->query('INSERT INTO Job_education(profile_id,degree,Board,year,class,Description) VALUES (  "'.$profile_id.'","'.$Education[0]['Hsc'].'","'.$Education[0]['Hscboard'].'","'.$Education[0]['Hscpassyear'].'","'.$Education[0]['Hscpercentage'].'","'.$Education[0]['Hscdetail'].'")');
    }
    if(isset($Education[0]['Graduation']) AND !empty($Education[0]['Graduation']) AND !empty($Education[0]['Graduationpassyear'])){
      $conn->query('INSERT INTO Job_education(profile_id,degree,Board,year,class,Description) VALUES (  "'.$profile_id.'","'.$Education[0]['Graduation'].'","'.$Education[0]['Graduationboard'].'","'.$Education[0]['Graduationpassyear'].'","'.$Education[0]['Graduationpercentage'].'","'.$Education[0]['Graduationdetail'].'")');
    }
    if(isset($Education[0]['PGraduation']) AND !empty($Education[0]['PGraduation']) AND !empty($Education[0]['PGraduationpassyear'])){
      $conn->query('INSERT INTO Job_education(profile_id,degree,Board,year,class,Description) VALUES (  "'.$profile_id.'","'.$Education[0]['PGraduation'].'","'.$Education[0]['PGraduationboard'].'","'.$Education[0]['PGraduationpassyear'].'","'.$Education[0]['PGraduationpercentage'].'","'.$Education[0]['PGraduationdetail'].'")');
    }
    if(isset($Education[0]['Otherdegree']) AND !empty($Education[0]['Otherdegree'])){
      $conn->query('INSERT INTO Job_education(profile_id,degree,Board,year,class,Description) VALUES (  "'.$profile_id.'","'.$Education[0]['Otherdegree'].'","'.$Education[0]['Otherboard'].'","'.$Education[0]['Otherpassyear'].'","'.$Education[0]['Otherpercentage'].'","'.$Education[0]['Otherdetail'].'")');
    }
    mysqli_close($conn);
    $msg=array('success' => 1,'msg'=>'nokarti profile register');
    return $response->withJson(array('response' => $msg));
   }

   mysqli_close($conn);
   $msg = array("success" => 0,'msg'=>'Somthing went wrong');
   return $response->withJson(array('response'=>$msg));

});

$app->post('/list_nokari_profile',function($request,$response,$args){
  $user_data = $request->getParsedBody();
  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
  $conn = sqlConnection();
  if($nokariresult = $conn->query("SELECT id,Name,Email,Role FROM Nikari_user where status=1 and block_status=1 AND gen_id= ".$user_data['id'])){
    if($nokariresult->num_rows == 0){

      $msg=array('success'=>2,'msg'=>'No profile found');
      return $response->withJson(array('response'=>$msg));
    }

     while($nokarirow=$nokariresult->fetch_assoc()){
       $Nokari_profile[]=$nokarirow;
     }
    mysqli_close($conn);
    $msg=array('success'=>1,'data'=>$Nokari_profile);
    return $response->withJson(array('response'=>$msg));
  }
mysqli_close($conn);
  $msg =  array('success'=>0,'msg'=>"somthing wrong");
  return $response->withJson(array('response'=>$msg));
});

$app->post('/view_nokari_profile',function($request,$response,$args){
   $user_data = $request->getParsedBody();
  //  $file = fopen("nokariview.txt","w");
  //  echo fwrite($file,json_encode($user_data));
  //  fclose($file);
  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
  $conn = sqlConnection();

  if($profileresult=$conn->query("SELECT id,Name,Email,DOB,Contact,Experience,Role,Description,Adress,Pincode,country,state,city,sub_cat,main_cat FROM Nikari_user where status =1 And block_status=1 AND gen_id = ".$user_data['id']." AND id=".$user_data['profileid'])){

    if($profileresult->num_rows <= 0){
      mysqli_close($conn);
      $msg=array('success'=>2,'msg'=>'No profile found');
      return $response->withJson(array('response'=>$msg));
    }
 $i=0;
     while($profilerow=$profileresult->fetch_assoc()){
       $profilesdata[$i]['id']=$profilerow['id'];
       $profilesdata[$i]['Name']=$profilerow['Name'];
       $profilesdata[$i]['Email']=$profilerow['Email'];
       $profilesdata[$i]['DOB']=$profilerow['DOB'];
       $profilesdata[$i]['Contact']=$profilerow['Contact'];
       if($Edures=$conn->query("SELECT education from education_met where id = ".$profilerow['Education'])){
         $Eduname=$Edures->fetch_assoc();
       }
       $profilesdata[$i]['Education']=$Eduname['education'];
       $profilesdata[$i]['Experience']=$profilerow['Experience'];
       $profilesdata[$i]['Role']=$profilerow['Role'];
       $mainres=$conn->query("SELECT business_Name from BusinessMain_cat where id = ".$profilerow['main_cat']);
       $main_cat=$mainres->fetch_assoc();
       $profilesdata[$i]['main_cat']=$main_cat['business_Name'];
       $subres=$conn->query("SELECT busness_name from busness_catagery where id = ".$profilerow['sub_cat']);
       $sub_cat=$subres->fetch_assoc();
       $profilesdata[$i]['sub_cat']=$sub_cat['busness_name'];
       $profilesdata[$i]['Description']=$profilerow['Description'];
       $profilesdata[$i]['Adress']=$profilerow['Adress'];
       $profilesdata[$i]['Pincode']=$profilerow['Pincode'];
       $res=$conn->query("SELECT name from a_main where id = ".$profilerow['country']);
       $country=$res->fetch_assoc();
       $res=$conn->query("SELECT name from a_main where id = ".$profilerow['state']);
       $state=$res->fetch_assoc();
       $res=$conn->query("SELECT name from a_main where id = ".$profilerow['city']);
       $city=$res->fetch_assoc();
       $profilesdata[$i]['country']=$country['name'];
       $profilesdata[$i]['state']=$state['name'];
       $profilesdata[$i]['city']=$city['name'];
    //          $msg = array("success" => 0,'msg'=>"SELECT * FROM Job_education where profileid = ".$user_data['profileid']);
    // return $response->withJson($msg);
       if($Educationresulrt =$conn->query("SELECT * FROM Job_education where profileid = ".$user_data['profileid'])){
           $j=0;
          while($ducationrow=$Educationresulrt->fetch_assoc()){

            $profilesdata[$i]['Education'][$j]['Degree']=$ducationrow['degree'];
            $profilesdata[$i]['Education'][$j]['year']=$ducationrow['year'];
            $profilesdata[$i]['Education'][$j]['per']=$ducationrow['per'];
            $profilesdata[$i]['Education'][$j]['Board']=$ducationrow['Board'];
              $j++;
          }
       }

     }
    mysqli_close($conn);
   $msg = array('success'=>1,'data'=>$profilesdata);
   return $response->withJson(array('response'=>$msg));
  }
  mysqli_close($conn);
  $msg =  array('success'=>0,'msg'=>'somthing went wrong');
  return $response->withJson(array('response'=>$msg));
});

$app->post('/edit_nokari_profile',function($request,$response,$args){
  $user_data = $request->getParsedBody();
  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
  $conn = sqlConnection();
  if($chekresult=$conn->query("SELECT * FROM Nikari_user WHERE status =1 AND block_status=1 AND gen_id = ".$user_data['id']." AND id = ".$user_data['profileid'])){

    if($chekresult->num_rows <=0){
      $msg = array("success" => 2,'msg'=>'Not a main user profile');
      return $response->withJson(array('response'=>$msg));
    }
  }
if($conn->query('UPDATE Nikari_user SET Name = "'.$user_data['Name'].'",sub_cat="'.$user_data['sub_cat'].'",main_cat="'.$user_data['main_cat'].'",Email="'.$user_data['Email'].'",DOB = "'.$user_data['DOB'].'",Contact= "'.$user_data['Contact'].'",Experience = "'.$user_data['Experience'].'",Role = "'.$user_data['Role'].'",Description= "'.$user_data['Description'].'",Adress= "'.$user_data['Adress'].'",Pincode= "'.$user_data['Pincode'].'",country= "'.$user_data['country'].'",state= "'.$user_data['state'].'",city= "'.$user_data['city'].'" where id='.$user_data['profileid'])){
   $conn->query("DELETE FROM Job_education WHERE id =".$user_data['profileid']);

  $Education = json_decode($user_data['Education'],'utf8');
  if(isset($Education['Ssc']) OR !empty($Education['Ssc'])){
    $conn->query('INSERT INTO Job_education(profile_id,degree,Board,year,class,Description) VALUES (  "'.$profile_id.'","'.$Education['Ssc'].'","'.$Education['Sscboard'].'","'.$Education['Sscpassyear'].'","'.$Education['Sscpercentage'].'","'.$Education['Sscdetail'].'")');
  }
  if(isset($Education['Hsc']) OR !empty($Education['Hsc'])){
    $conn->query('INSERT INTO Job_education(profile_id,degree,Board,year,class,Description) VALUES (  "'.$profile_id.'","'.$Education['Hsc'].'","'.$Education['Hscboard'].'","'.$Education['Hscpassyear'].'","'.$Education['Hscpercentage'].'","'.$Education['Hscdetail'].'")');
  }
  if(isset($Education['Graduation']) OR !empty($Education['Graduation'])){
    $conn->query('INSERT INTO Job_education(profile_id,degree,Board,year,class,Description) VALUES (  "'.$profile_id.'","'.$Education['Graduation'].'","'.$Education['Graduationboard'].'","'.$Education['Graduationpassyear'].'","'.$Education['Graduationpercentage'].'","'.$Education['Graduationdetail'].'")');
  }
  if(isset($Education['PGraduation']) OR !empty($Education['PGraduation'])){
    $conn->query('INSERT INTO Job_education(profile_id,degree,Board,year,class,Description) VALUES (  "'.$profile_id.'","'.$Education['PGraduation'].'","'.$Education['PGraduationboard'].'","'.$Education['PGraduationpassyear'].'","'.$Education['PGraduationpercentage'].'","'.$Education['PGraduationdetail'].'")');
  }
  if(isset($Education['Otherdegree']) OR !empty($Education['Otherdegree'])){
    $conn->query('INSERT INTO Job_education(profile_id,degree,Board,year,class,Description) VALUES (  "'.$profile_id.'","'.$Education['Otherdegree'].'","'.$Education['Otherboard'].'","'.$Education['Otherpassyear'].'","'.$Education['Otherpercentage'].'","'.$Education['Otherdetail'].'")');
  }

    mysqli_close($conn);
    $msg = array("success" => 1,'msg'=>'data updated');
    return $response->withJson(array('response'=>$msg));

  }
  mysqli_close($conn);
  $msg = array("success" => 0,'msg'=>'somthing went wrong');
  return $response->withJson(array('response'=>$msg));


});

$app->post('/delete_nokari_profile',function($request,$response,$args){

  $user_data = $request->getParsedBody();
  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }

 $conn = sqlConnection();
 if($chekresult=$conn->query("SELECT * FROM Nikari_user WHERE status =1 AND block_status=1 AND gen_id = ".$user_data['id']." AND id = ".$user_data['profileid'])){

   if($chekresult->num_rows <=0){
     mysqli_close($conn);
     $msg = array("success" => 2,'msg'=>'Not match admin');
     return $response->withJson(array('response'=>$msg));
   }

 if($conn->query("UPDATE Nikari_user SET status = 0 where block_status AND id =".$user_data['profileid'])){
   mysqli_close($conn);
   $msg = array('success'=>1,'mag'=>'Deleted profile');
   return $response->withJson(array('response'=>$msg));
 }

}
mysqli_close($conn);
 $msg = array('success'=>0,'mag'=>'somthing went wrong');
 return $response->withJson(array('response'=>$msg));
});


$app->post('/find_nokari_for_profile',function($request,$response,$args){

  $user_data = $request->getParsedBody();
  // $file = fopen("nokari_find.txt","w");
  // echo fwrite($file,json_encode($user_data));
  // fclose($file);
  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
  $now =date('Y-m-d H:i:s');
  $conn =sqlConnection();
  if($proresult = $conn->query("SELECT sub_cat,End_date FROM Nikari_user WHERE status=1 AND block_status =1 AND id=".$user_data['nokariid'])){
    if($proresult->num_rows >= 1){
        $prorow  = $proresult->fetch_assoc();
        if(strtotime($prorow['End_date']) < strtotime($now)){
          $msg = array("success" => 2,'msg'=>'Your Subcription is over');
          return $response->withJson(array('response'=>$msg));
        }

        $now =date('Y-m-d H:i:s');
        if($resultJob = $conn->query("SELECT id,business_id,JobTitle,Role,JobType,Email,End_date FROM JobPost where status=1 AND block_status=1 AND sub_cat=".$prorow['sub_cat'])){
            $i=0;
            if($resultJob->num_rows <=0 ){
              mysqli_close($conn);
              $msg =  array('success'=>2,'msg'=>'Not any Job for u');
              return $response->withJson(array('response'=>$msg));
            }

            while($rowjob = $resultJob->fetch_assoc()){
              if(strtotime($rowjob['End_date']) > strtotime($now)){
                $findjob[$i]['id']=$rowjob['id'];
                 $findjob[$i]['JobTitle']=$rowjob['JobTitle'];
                 $findjob[$i]['Role']=$rowjob['Role'];
                 $findjob[$i]['JobType']=$rowjob['JobType'];
                 $findjob[$i]['Email']=$rowjob['Email'];
                 if($busimgres = $conn->query("SELECT CompanylogoString FROM business_profile WHERE id = ".$rowjob['business_id'])){
                    $rowbusinimg = $busimgres->fetch_assoc();
                    $findjob[$i]['Business_image']=$rowbusinimg['CompanylogoString'];
                 }
                $i++;
              }
            }


        }
      mysqli_close($conn);
      $msg =  array('success'=>1,'data'=>$findjob);
      return $response->withJson(array('response'=>$msg));
    }else{
      mysqli_close($conn);
      $msg =  array('success'=>2,'data'=>'user not found');
      return $response->withJson(array('response'=>$msg));
    }
  }
  mysqli_close($conn);
  $msg =  array('success'=>0,'msg'=>'somthing went wrong');
  return $response->withJson(array('response'=>$msg));
});
$app->post('/profile_job_share_pdf',function($request,$response,$args){
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

      $now =date('Y-m-d H:i:s');
      if($jobresult=$conn->query("SELECT * from Nikari_user where status=1 AND block_status=1 AND id = ".$user_data['JobProfileid'])){

        if($jobresult->num_rows != 0){
           while($jobrow=$jobresult->fetch_assoc()){
             if(strtotime($jobrow['End_date']) < strtotime($now)){
               $msg = array("success" => 2,'msg'=>'Your Subcription is over');
               return $response->withJson(array('response'=>$msg));
             }

             $jobprofile['Name']=$jobrow['Name'];
             $jobprofile['Email']=$jobrow['Email'];
             $jobprofile['DOB']=$jobrow['DOB'];
             $jobprofile['Contact']=$jobrow['Contact'];
             $jobprofile['Experience']=$jobrow['Experience'];
             $jobprofile['Role']=$jobrow['Role'];

             $mainres=$conn->query("SELECT business_Name from BusinessMain_cat where id = ".$jobrow['main_cat']);
             $main_cat=$mainres->fetch_assoc();
              $jobprofile['main_cat']=$main_cat['business_Name'];;

             $subres=$conn->query("SELECT busness_name from busness_catagery where id = ".$jobrow['sub_cat']);
             $sub_cat=$subres->fetch_assoc();
             $jobprofile['sub_cat']=$sub_cat['busness_name'];

             $jobprofile['Description']=$jobrow['Description'];
             $jobprofile['Adress']=$jobrow['Adress'];
             $jobprofile['Pincode']=$jobrow['Pincode'];

             $res=$conn->query("SELECT name from a_main where id = ".$jobrow['country']);
             $country=$res->fetch_assoc();
             $res=$conn->query("SELECT name from a_main where id = ".$jobrow['state']);
             $state=$res->fetch_assoc();
             $res=$conn->query("SELECT name from a_main where id = ".$jobrow['city']);
             $city=$res->fetch_assoc();
             $jobprofile['country']=$country['name'];
             $jobprofile['state']=$state['name'];
             $jobprofile['city']=$city['name'];
           }
           $i=0;
           $EducationString =" ";
           if($education = $conn->query("SELECT * FROM Job_education where profile_id = ".$user_data['JobProfileid'])){
             while($educationdata=$education->fetch_assoc()){
               $NokariEducation[$i]['degree'] =$educationdata['degree'];
               $NokariEducation[$i]['year'] =$educationdata['year'];
               $NokariEducation[$i]['Board'] =$educationdata['Board'];
               $NokariEducation[$i]['per'] =$educationdata['per'];
               $NokariEducation[$i]['class'] =$educationdata['class'];
               $NokariEducation[$i]['Description'] =$educationdata['Description'];
               $str  ="<tr>
                      <td>".$educationdata['degree']."</td>
                      <td>".$educationdata['year']."</td>
                      <td>".$educationdata['Board']."</td>
                      <td>".$educationdata['class']."</td>
                      <td>".$educationdata['Description']."</td>
                     </tr>";
               $EducationString .= $str;
                  $i++;
             }

           }
          //  $msg = array("success" => $NokariEducation,'msg'=>$EducationString);
          //  return $response->withJson(array('response'=>$msg));
           $html = '<!DOCTYPE html>
           <html>
             <head>
             <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
         <link rel="stylesheet" href="http://192.168.0.3:8080/api/v1/panel.css" type="text/css"/>
             </head>
             <body>
             <div class="bg-container">
             <div class="container">
           <div class="jumbotron" align="center">
             <h1>Resume</h1>
           </div>
         </div>
             <div class="container">
               <div class="bs-example">
                   <div class="panel panel-primary">
                       <div class="panel-heading" style="background-color:black;">
                           <h3 class="panel-title">Name & Address</h3>
                       </div>
                       <div class="panel-body">
                         <p><i class="fa fa-user-o" aria-hidden="true"></i>
                         <strong>Name :- </strong>'.$jobprofile['Name'].'</p>
                         <p><strong>Email:-</strong> '.$jobprofile['Email'].'</p>
                         <p><i class="fa fa-address-book-o" aria-hidden="true"></i><strong>Address:-</strong>'.$jobprofile['Adress'].'<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$jobprofile['Pincode'].' - '.$jobprofile['city'].'<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$jobprofile['state'].'</p>
                         </div>

                   </div>
               </div>
             </div>
             <div class="container">
               <div class="bs-example">
                   <div class="panel panel-primary">
                       <div class="panel-heading" style="background-color:black;">
                           <h3 class="panel-title">Key Skill</h3>
                       </div>
                       <div class="panel-body">
                         <p><strong>Role:-</strong>'.$jobprofile['Role'].'</p>
                         <p><strong>Experience:-</strong>'.$jobprofile['Experience'].' year</p></div>
                   </div>
               </div>
             </div>
             <div class="container">
               <div class="bs-example">
                   <div class="panel panel-primary">
                       <div class="panel-heading" style="background-color:black;">
                           <h3 class="panel-title">Education</h3>
                       </div>
                       <div class="panel-body">
                        <table>
                          <tr>
                            <th>Degree</th>
                            <th>Year</th>
                            <th>Board</th>
                            <th>Gread</th>
                            <th>Description</th>
                          </tr>
                            '.$EducationString.'
                        </table>
                   </div>
               </div>
             </div>
             <div class="container">
               <div class="bs-example">
                   <div class="panel panel-primary">
                       <div class="panel-heading" style="background-color:black;">
                           <h3 class="panel-title">About</h3>
                       </div>
                       <div class="panel-body">
                         <p><i class="fa fa-user-o" aria-hidden="true"></i>
                         <strong>Description :-</strong> '.$jobprofile['Description'].'</p>
                         <p><strong>DOB:-</strong>'.$jobprofile['DOB'].'</p>
                         <p><strong>Contact:-</strong>'.$jobprofile['Contact'].'</p>
                         <p><strong>Intrested:-</strong>'.$jobprofile['main_cat'].'</p></div>
                   </div>
               </div>
             </div>
             </body>
           </html>
         ';
         $dompdf = new Dompdf();
         $dompdf->load_html($html);
         $dompdf->render();

         $output = $dompdf->output(['isRemoteEnabled' => true]);
         $msg = array('success'=>1,'msg'=>'sete');
         file_put_contents("resume/".$jobprofile['Email'].".pdf", $output);
          $link="http://192.168.0.3:8080/api/v1/resume/".$jobprofile['Email'].".pdf";

          $msg = array('success'=>1,'link'=>$link);
          return $response->withJson(array('response'=>$msg));
        }

      }
      $msg =  array('success'=>0,'msg'=>'somthing went wrong');
      return $response->withJson(array('response'=>$msg));
  });

?>
