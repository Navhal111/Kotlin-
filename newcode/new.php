<?php
require_once 'vendor/autoload.php';

$app = new \Slim\App();

$app->get('/', function() {
    echo 'It works!';
});

$app->get('/hello', function() {

    echo 'Hello world!';
});

$app->run();

?>
