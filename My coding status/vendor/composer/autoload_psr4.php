<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'Slim\\Middleware\\' => array($vendorDir . '/dyorg/slim-token-authentication/src', $vendorDir . '/tuupola/slim-jwt-auth/src'),
    'Slim\\Csrf\\' => array($vendorDir . '/slim/csrf/src'),
    'Slim\\' => array($vendorDir . '/slim/slim/Slim'),
    'Silalahi\\Slim\\' => array($vendorDir . '/silalahi/slim-logger/src'),
    'Psr\\Log\\' => array($vendorDir . '/psr/log/Psr/Log'),
    'Psr\\Http\\Message\\' => array($vendorDir . '/psr/http-message/src'),
    'Psr\\Container\\' => array($vendorDir . '/psr/container/src'),
    'Interop\\Container\\' => array($vendorDir . '/container-interop/container-interop/src/Interop/Container'),
    'Firebase\\JWT\\' => array($vendorDir . '/firebase/php-jwt/src'),
    'FastRoute\\' => array($vendorDir . '/nikic/fast-route/src'),
    '' => array($vendorDir . '/bryanjhv/slim-session/src'),
);
