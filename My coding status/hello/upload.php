<?php

$app->post('/upload', function ($request, $response, $args) {
    $files = $request->getUploadedFiles();
    if (empty($files['newfile'])) {
        throw new Exception('Expected a newfile');
    }

        $newfile = $files['newfile'];
    if ($newfile->getError() == UPLOAD_ERR_OK) {
       $uploadFileName = $newfile->getClientFilename();
       $path =  __DIR__ ."/advetisement/".$uploadFileName;
      //  $newfile->moveTo("__DIR__ ./advetisement/$uploadFileName");
       $newfile->moveTo($path);
       // move_uploaded_file($newfile, "adv/$uploadFileName");
       $msg = array("success" => 1,'msg'=>'fiole added');
       return $response->withJson($msg);
   }
});
?>
