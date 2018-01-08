<?php

require "vendor/autoload.php";
use Slim\LogFileWriter;
use Slim\Middleware\SessionCookie;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Tracy\Debugger;

date_default_timezone_set("Asia/Jakarta");
$app = new \Slim\App;
$app->add(new Silalahi\Slim\Logger());

$app->get('/', function ($request, $response, $args) {
  return $response->withJson("Hello, World!");
});

$app->run();
?>
