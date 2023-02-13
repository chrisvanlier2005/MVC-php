<?php

use Core\Router;

require __DIR__ . "/vendor/autoload.php";


function dd($var){
    header('Content-Type: application/json');
    echo json_encode($var);
    die();
}

// top level catch
try {
    require "web/routes.php";
    Router::run();
} catch (Exception $e){
    $errorHandler = new \Core\Error($e);
}
