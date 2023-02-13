<?php

use Core\Router;

require __DIR__ . "/vendor/autoload.php";


function dd($var){
    header('Content-Type: application/json');
    echo json_encode($var);
    die();
}

/*
 * Running the Router
*/
try {
    require "web/routes.php";
    Router::run();
} catch (Exception $e){
    new Core\Error($e);
}
