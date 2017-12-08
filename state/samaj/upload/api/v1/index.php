<?php
session_start();
error_reporting();
ini_set('display_errors', 'On');
// set_error_handler("var_dump");
// extension=php_mbstring.dll
require_once 'vendor/autoload.php';
require_once 'config.php';
use Slim\LogFileWriter;
use Slim\Middleware\SessionCookie;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Tracy\Debugger;
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
date_default_timezone_set("Asia/Kolkata");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');


$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

$app->add(new Silalahi\Slim\Logger(
  [
  'path' => 'log/'
  ]
));
$app->add(new \Slim\Middleware\Session());

$app->get('/', function($request, $response, $args){
  $tokenExpiration = date('Y-m-d');
  $session =date('Y-m-d');
  $enddate = "10/25/2017";
  $day = date("l", strtotime($tokenExpiration));
  // $json = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=1004,%20White%20Orchid,%20near%20Shell%20petrol%20pump,L.P%20Savani%20road%20,Adajan,Surat,%20Gujarat%20395009%20&key=AIzaSyDFtLiPr8vHgsFqeOuhuVw_XNeJE7WPT7Y');
  // $obj = json_decode($json,true);
  // $obj["results"][0]["geometry"]["location"]
  $diff=strtotime($enddate)-strtotime($session);
  $main= $diff/86400;
  $msg = array('success' => 1,'day'=>$day,'Time'=>$tokenExpiration,"days left"=>$main);
  return $response->withJson($msg);

});
$app->post('/check_login_admin', function($request, $response, $args){

  $admin_data = $request->getParsedBody();
  $cheak = cheak_token($admin_data['id'],$admin_data['token']);
  if($cheak['success'] == 0){
    $msg = array("success" => 0,'msg'=>'unotherise');
    return $response->withJson($msg);
  }
  $msg = array("success" => 1,'msg'=>'success login');
  return $response->withJson($msg);
});

// $app->get('/forget_pass', function($request, $response, $args){
//
//    $email = 'y123light@gmail.com';
//    $mail = new PHPMailer(true);
//
//    $mail->IsSMTP();
//    $mail->isHTML(true);
//    $mail->SMTPDebug  = 0;
//    $mail->SMTPAuth   = true;
//    $mail->SMTPSecure = "tls";
//    $mail->Host       = "smtp.gmail.com";
//    $mail->Port       = 587;
//    $mail->AddAddress("ylight528@gmail.com");
//    $mail->Username   ="milanabdul0@gmail.com";
//    $mail->Password   ="abmi1234";
//    $mail->SetFrom('ylight528@gmail.com','msg2');
//    $mail->AddReplyTo("ylight528@gmail.com","msg2");
//    $mail->Subject    = "Cube";
//    $arrRtn= bin2hex(openssl_random_pseudo_bytes(8));
//    $mail->Body    = "<h1>Hello Ritesh</h1>";
//    $mail->AltBody    = "user send details";
//    $s='failes';
//   //  $email ="ylight528@gmail.com";
//    if ($mail->validateAddress($email) ){
//      return $response->withJson(array('msg'=>'email exist'));
//
//    }
//
//
//   if($seo=$mail->Send()){
//     $s="send";
//
//   }
//
// });

// /etc/php/7.0/apache2/php.ini
$server_path ="http://192.168.0.3:8080/api/v1/";
require_once 'user.php';
require_once 'admin.php';
require_once 'other.php';
require_once 'corporate.php';
require_once 'martimony.php';
require_once 'advedtisement.php';
require_once 'admin_pages.php';
require_once 'admin_servay.php';
require_once 'matrimony_admin.php';
require_once 'busness_module.php';
require_once 'business_admin.php';
require_once 'Blood_Donation.php';
require_once 'blood_admin.php';
require_once 'job.php';
require_once 'admin_list_job.php';
require_once 'adv_show.php';
require_once 'main_admin_search.php';
require_once 'nokari_user.php';
require_once 'nokari_admin.php';
require_once 'seva_orgnaisation.php';
require_once 'seva_appside.php';
require_once 'gpsmap.php';
require_once 'servay_app_side.php';

$app->run();
?>
