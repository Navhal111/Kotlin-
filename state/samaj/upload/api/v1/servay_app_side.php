<?php

$app->post('/user_survey_display', function($request,$response,$args) {
           $user_data = $request->getParsedBody();


          $cheak = cheak_token_user($user_data['id'],$user_data['token']);
           if($cheak['success'] == 0){
             $msg = array("success" => 0,'msg'=>'unotherise');
             return $response->withJson($msg);
           }
          $conn = sqlConnection();
          $now = date('Y-m-d');

          if($useransweresult= $conn->query("SELECT id,servay_id FROM user_servay WHERE user_id=".$user_data['id'])){
            $answe_index=0;
            while($useranswerrow = $useransweresult->fetch_assoc()){
                $user_answer[$answe_index]=$useranswerrow['servay_id'];

                $answe_index++;
            }
          }
          if(empty($user_answer) OR !isset($user_answer)){
            $user_answer[0]=0;
          }
          // $msg = array("success" => $answe_index,"msg"=>"SELECT * from admin_survey WHERE status=1 AND block_status=1 AND id NOT IN (" . implode(',', array_map('intval', $user_answer)) . ")");
          // return $response->withJson($msg);
          if($resultservay=$conn->query("SELECT * from admin_survey WHERE status=1 AND block_status=1 AND id NOT IN (" . implode(',', array_map('intval', $user_answer)) . ")")){
              if($resultservay->num_rows <=0){
                $msg =array('success'=>2,'msg'=>'No servay on your area');
                return $response->withJson(array('response'=>$msg));
              }
              $i=0;
              while($rowservay = $resultservay->fetch_assoc()){
                if((strtotime($now) < strtotime($rowservay['end_date'])) AND (strtotime($now) >= strtotime($rowservay['start_date']))){

                  $servayApp[$i]['id']=$rowservay['id'];
                  $servayApp[$i]['question']=$rowservay['question'];
                  // $anser = explode(' # ',  $rowservay['ans']);
                  // $servayApp[$i]['anser']=$anser;
                  $servayApp[$i]['start_date']=$rowservay['start_date'];
                  // $servayApp[$i]['ownans']=$rowservay['ownans'];
                  $servayApp[$i]['created_at']=$rowservay['created_at'];
                  $i++;
                }
              }
              if(empty($servayApp)){
                mysqli_close($conn);
                $msg =array('success'=>2,'msg'=>'No servay in your area');
                return $response->withJson(array('response'=>$msg));
              }
              mysqli_close($conn);
              $msg =array('success'=>1,'data'=>$servayApp);
              return $response->withJson(array('response'=>$msg));

          }
          mysqli_close($conn);
          $msg =array('success'=>0,'msg'=>'Somthing went wrong');
          return $response->withJson(array('response'=>$msg));
      });
  $app->post('/user_survey_add', function($request,$response,$args) {
                 $user_data = $request->getParsedBody();

                  $file = fopen("suerveyanswer.txt","w");
                  echo fwrite($file,json_encode($user_data));
                  fclose($file);
                  $msg = array("success" => 0,'msg'=>'somthing went wrong');
                  // return $response->withJson(array('response'=>$msg));

                  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
                   if($cheak['success'] == 0){
                     $msg = array("success" => 0,'msg'=>'unotherise');
                     return $response->withJson(array('response'=>$msg));
                   }
      $conn = sqlConnection();
      if($result_repeat = $conn->query("SELECT * FROM user_servay where user_id =".$user_data['id']." AND servay_id=".$user_data['survay_id'])){
          if($result_repeat->num_rows >= 1 ){
            $msg = array("success" => 2,'msg'=>'Alredy Add This Question');
            return $response->withJson(array('response'=>$msg));
          }
      }
  if(!empty($user_data['survay_id']) OR !empty($user_data['ans'])){
    if($conn->query('INSERT INTO user_servay(user_id,servay_id,ans,answer_text,andtype) VALUES ('.$user_data['id'].','.$user_data['survay_id'].',"'.$user_data['ans'].'","'.$user_data['othertext'].'","'.$user_data['anstype'].'")')){

      $msg = array("success" => 1,'msg'=>'Thanx For the feedback','servay_id'=>$user_data['survay_id']);
      return $response->withJson(array('response'=>$msg));

    }else{
      $msg = array("success" => 0,'msg'=>'INSERT INTO user_servay(user_id,servay_id,ans,answer_text,andtype) VALUES ('.$user_data['id'].','.$user_data['survay_id'].',"'.$user_data['ans'].'","'.$user_data['othertext'].'","'.$user_data['anstype'].'")');
      return $response->withJson(array('response'=>$msg));
    }
  }


  $msg = array("success" => 0,'msg'=>'somthing went wrong');
  return $response->withJson(array('response'=>$msg));

});

$app->post('/user_survey_answer_display', function($request,$response,$args) {
                 $user_data = $request->getParsedBody();

                  // $file = fopen("suerveyanswer.txt","w");
                  // echo fwrite($file,json_encode($user_data));
                  // fclose($file);
                  // $msg = array("success" => 0,'msg'=>'somthing went wrong');
                  // return $response->withJson($msg);
                  $cheak = cheak_token_user($user_data['id'],$user_data['token']);
                   if($cheak['success'] == 0){
                     $msg = array("success" => 0,'msg'=>'unotherise');
                     return $response->withJson($msg);
                   }

            $conn =sqlConnection();
                      $now = date('Y-m-d');

                      if($userresult = $conn->query("SELECT id FROM user where user_block AND status = 1")){
                        $users_count=0;
                        while($userrow=$userresult->fetch_assoc()){

                        $users_count++;
                        }

                      }

            if($resultservay=$conn->query("SELECT * from admin_survey WHERE status=1 AND block_status=1 AND id =".$user_data["servay_id"])){

                if($resultservay->num_rows <=0){
                  $msg =array('success'=>2,'msg'=>'No servay on your area');
                  return $response->withJson(array('response'=>$msg));
                }
                $i=0;

                while($rowservay = $resultservay->fetch_assoc()){
                  if((strtotime($now) < strtotime($rowservay['end_date'])) AND (strtotime($now) >= strtotime($rowservay['start_date']))){
                    $anser = explode(' # ',  $rowservay['ans']);
                    $user_ans_count=0;
                    while(isset($anser[$user_ans_count])){
                      $user_answer_servay[$user_ans_count]=0;
                      $user_ans_count++;
                    }

                    if($answerresult = $conn->query("SELECT * FROM user_servay where servay_id=".$rowservay['id'])){
                      $j=0;
                      while($answerrow=$answerresult->fetch_assoc()){
                            $check_ans=0;
                            while(isset($anser[$check_ans])){
                                $anser_check = explode(' # ',  $answerrow['ans']);
                                $set_check_option=0;
                                while(isset($anser_check[$set_check_option])){
                                  if($anser[$check_ans]==$anser_check[$set_check_option]){
                                    $user_answer_servay[$check_ans]=$user_answer_servay[$check_ans]+1;
                                  }
                                  $set_check_option++;
                                }
                                $check_ans++;
                            }
                      $j++;
                      }

                    }

                    $persantage=0;

                    // $persantage = (100*$j)/$user_count;
                    // $servayApp[$i]['user_count']=$j;
                    // $servayApp[$i]['ratio']=$persantage."%";
                    $servayApp['id']=$rowservay['id'];
                    $servayApp['question']=$rowservay['question'];
                    $servayApp['permition']=$rowservay['permition'];
                    $servayApp["youtube_link"]=$rowservay["youtube_link"];
                    $servayApp['ownans']=$rowservay['ownans'];
                    $servayApp['created_at']=$rowservay['created_at'];
                    $count_pers=0;

                    while(isset($user_answer_servay[$count_pers])){
                      $prer_anser =(100*$user_answer_servay[$count_pers])/$j;
                      $servayApp['anser'][$count_pers]['ans']=$anser[$count_pers];
                      $servayApp['anser'][$count_pers]['ratio']=$prer_anser."";
                      $count_pers++;
                    }
                    $i++;
                  }
                }

                mysqli_close($conn);
                $msg =array('success'=>1,'data'=>$servayApp);
                return $response->withJson(array('response'=>$msg));

              }

              mysqli_close($conn);
              $msg =array('success'=>0,'msg'=>'Somthing went wrong');
              return $response->withJson(array('response'=>$msg));
  });
?>
