<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'config.php';
use Slim\LogFileWriter;
use Slim\Middleware\SessionCookie;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Tracy\Debugger;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');


$app = new \Slim\App;

$app->add(new Silalahi\Slim\Logger(
  [
  'path' => 'log/',
  'name' => 'app_logger_',
  'name_format' => 'Y-m-d',
  'extension' => '.txt',
  'message_format' => '[%label%] %date% %message%'
]
));
$app->add(new \Slim\Middleware\Session());

$app->get('/', function($request, $response, $args){

  $msg = array('success' => 1);
  return $response->withJson($msg);

});

require_once 'user.php';
require_once 'admin.php';
require_once 'other.php';


$app->run();
?>
