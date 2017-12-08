<?php
$app->post('/admin_pages_add', function($request,$response,$args) {
           $admin_data = $request->getParsedBody();
          $cheak = cheak_token($admin_data['id'],$admin_data['token']);
           if($cheak['success'] == 0){
             $msg = array("success" => 0,'msg'=>'unotherise');
             return $response->withJson($msg);
           }

           $conn = sqlConnection();
           if($result = $conn->query("SELECT pages from module_permition where sub_admin_id = ".$admin_data['id'])) {

                    while($row = $result->fetch_assoc()) {
                      $module_permition[] =$row;
                    }
           }
           if($module_permition[0]['pages']==0000){
             $msg =array('success'=>0,'msg'=>'admin have not permission');
             return $response->withJson($msg);

           }

        try{
                   if(empty($admin_data['description'])){
                     $msg =array('success'=>4);
                     return $response->withJson($msg);

                     }

               if($admin_data['operation'] =='add'){

                 if($module_permition[0]['pages']==1100 OR $module_permition[0]['pages']==1110 OR $module_permition[0]['pages']==1101 OR $module_permition[0]['pages']==1111){
                       if($conn->query("INSERT INTO admin_pages(admin_id,title,discription) VALUES (".$admin_data['id'].",'".$admin_data['title']."','".$admin_data['description']."')")){
                        mysqli_close($conn);
                        $msg =array('success'=>1,'msg'=>'add data');
                        return $response->withJson($msg);

                       }

                       $msg =array('success'=>0,'msg'=>'not add');
                       return $response->withJson($msg);
                 }


               }

              if($admin_data['operation'] == 'edit'){
                      if($module_permition[0]['pages']==1110 OR $module_permition[0]['pages']==1011 OR $module_permition[0]['pages']==1010 OR $module_permition[0]['pages']==1111){
                          if($conn->query("UPDATE admin_pages SET admin_id = ".$admin_data['id'].",title='".$admin_data['title']."',discription='".$admin_data['description']."' where id = ".$admin_data['edit_id'])){

                           $msg =array('success'=>1,'msg'=>'update data');
                           return $response->withJson($msg);

                        }
                      }
                      $msg =array('success'=>0,'msg'=>'admin have not permission');
                      return $response->withJson($msg);
              }

        }catch(Exception $e){
          $msg =array('success'=>0,'msg'=>$e);
          return $response->withJson($msg);

        }
        $msg =array('success'=>0,'msg'=>'somting wrong');
        return $response->withJson($msg);

});

$app->post('/admin_pages_edit', function($request,$response) {

      $admin_data = $request->getParsedBody();
      // $file = fopen("pages.txt","w");
      // echo fwrite($file,json_encode($admin_data));
      // fclose($file);
     $cheak = cheak_token($admin_data['id'],$admin_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }

      $conn = sqlConnection();
      if($result = $conn->query("SELECT pages from module_permition where sub_admin_id = ".$admin_data['id'])) {

               while($row = $result->fetch_assoc()) {
                 $module_permition[] =$row;
               }
      }
      if($module_permition[0]['pages']==0000){
        $msg =array('success'=>0,'msg'=>'admin have not permission');
        return $response->withJson($msg);

      }
      if($module_permition[0]['pages']==1110 OR $module_permition[0]['pages']==1011 OR $module_permition[0]['pages']==1010 OR $module_permition[0]['pages']==1111){

            if($result=$conn->query("SELECT * from admin_pages where id= ".$admin_data['page_id'])){

                     while($row = $result->fetch_assoc()){
                           $page_data[]=$row;

                     }
             return $response->withJson(array('opt'=>'edit','data'=>$page_data));

           }
      }else{
           return $response->withJson(array('success'=>0,'msg'=>'not permission'));
      }
      $msg=array('success'=>0,'data'=>'something went wrong');
      return $response->withJson($msg);


});

$app->post('/admin_pages_list', function($request,$response,$args) {
       $admin_data = $request->getParsedBody();

       $cheak = cheak_token($admin_data['id'],$admin_data['token']);
       if($cheak['success'] == 0){
         $msg = array("success" => 0,'msg'=>'unotherise');
         return $response->withJson($msg);
       }

       $conn = sqlConnection();
             if($result = $conn->query("SELECT role,pages from module_permition where sub_admin_id = ".$admin_data['id'])) {
                      while($row = $result->fetch_assoc()) {
                        $module_permition[] =$row;
                      }
             }
           if($module_permition[0]['pages']==0000){
               $msg =array('success'=>0,'msg'=>'admin have not permission');
               return $response->withJson($msg);
             }

          try{

            if($result = $conn->query("SELECT id,title,discription,block_status,created_at from admin_pages where status =1")) {
                $i=0;
                while($row = $result->fetch_assoc()) {

                  $pages_data[]=$row;
                }

                $msg =array('success'=>1,'data'=>$pages_data);
                return $response->withJson($msg);
              }
          }catch(Exception $e){
            $msg =array('success'=>0,'msg'=>'something wrong');
            return $response->withJson($msg);
          }
          $msg =array('success'=>0,'msg'=>'something wrong with user input');
          return $response->withJson($msg);

});

$app->post('/admin_pages_block', function($request,$response) {

      $admin_data = $request->getParsedBody();
     $cheak = cheak_token($admin_data['id'],$admin_data['token']);
      if($cheak['success'] == 0){
        $msg = array("success" => 0,'msg'=>'unotherise');
        return $response->withJson($msg);
      }

      $conn = sqlConnection();
      if($result = $conn->query("SELECT pages from module_permition where sub_admin_id = ".$admin_data['id'])) {

               while($row = $result->fetch_assoc()) {
                 $module_permition[] =$row;
               }
      }
      if($module_permition[0]['pages']==0000){
        $msg =array('success'=>0,'msg'=>'admin have not permission');
        return $response->withJson($msg);
      }

      if($module_permition[0]['pages'] == 1001 OR $module_permition[0]['pages'] == 1011 OR $module_permition[0]['pages'] == 1111 OR $module_permition[0]['pages'] == 1101 ){
              if($result = $conn->query("SELECT block_status from admin_pages where id = ".$admin_data['page_id'])) {
                  while($row = $result->fetch_assoc()) {
                        $myArray[] = $row;
                  }
               }
            if($myArray[0]['block_status']==0){

                      try{
                      $result=$conn->prepare("UPDATE admin_pages set block_status = 1 where id= ?");
                      // $resule->bind_param('s', $name);
                      $result->bind_param('s', $admin_data['page_id']);
                      $result->execute();
                      $result->store_result();
                      $result->close();
                      mysqli_close($conn);
                      $msg = array('success' => 1,'msg'=>'corporater will enable');
                      return $response->withJson($msg);
                       }catch(Exception $e){
                         $result->close();
                         mysqli_close($conn);
                         $msg =array('success'=>0,'msg'=>$e);
                         return $response->withJson($msg);
                       }
                       $result->close();
                       mysqli_close($conn);
                       $msg = array('success' => 0,'msg'=>'enable to page');
                       return $response->withJson($msg);

            }else{
              try{
                    $result=$conn->prepare("UPDATE admin_pages set block_status = 0 where id= ?");
                    // $resule->bind_param('s', $name);
                    $result->bind_param('s', $admin_data['page_id']);
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
               $msg = array('success' => 1,'msg'=>'page will disable');
               return $response->withJson($msg);
            }
      }
      $msg = array("success" => 0,'msg'=>'not permission to remove');
      return $response->withJson($msg);
  });
  $app->post('/admin_pages_remove', function($request,$response) {

        $admin_data = $request->getParsedBody();
       $cheak = cheak_token($admin_data['id'],$admin_data['token']);
        if($cheak['success'] == 0){
          $msg = array("success" => 0,'msg'=>'unotherise');
          return $response->withJson($msg);
        }

        $conn = sqlConnection();
        if($result = $conn->query("SELECT pages from module_permition where sub_admin_id = ".$admin_data['id'])) {

                 while($row = $result->fetch_assoc()) {
                   $module_permition[] =$row;
                 }
        }
        if($module_permition[0]['pages']==0000){
          $msg =array('success'=>0,'msg'=>'admin have not permission');
          return $response->withJson($msg);
        }

        if($module_permition[0]['pages'] == 1001 OR $module_permition[0]['pages'] == 1011 OR $module_permition[0]['pages'] == 1111 OR $module_permition[0]['pages'] == 1101 ){

                                try{
                                $result=$conn->prepare("UPDATE admin_pages set status = 1 where id= ?");
                                // $resule->bind_param('s', $name);
                                $result->bind_param('s', $admin_data['page_id']);
                                $result->execute();
                                $result->store_result();
                                $result->close();
                                mysqli_close($conn);
                                $msg = array('success' => 1,'msg'=>'corporater will remove');
                                return $response->withJson($msg);
                                 }catch(Exception $e){
                                   $result->close();
                                   mysqli_close($conn);
                                   $msg =array('success'=>0,'msg'=>$e);
                                   return $response->withJson($msg);
                                 }
                                 $result->close();
                                 mysqli_close($conn);
                                 $msg = array('success' => 0,'msg'=>'enable to page');
                                 return $response->withJson($msg);

        }
        $result->close();
        mysqli_close($conn);
        $msg = array('success' => 0,'msg'=>'enable to page');
        return $response->withJson($msg);

});
?>
