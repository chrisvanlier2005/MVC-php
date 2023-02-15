<?php
require __DIR__ . "/vendor/autoload.php";

use App\App;
use Core\Http\Request;
use Core\Router;

function dd($var){
    var_dump($var);
    die();
}

$app = new App();

try {
    $app->run();
} catch (Exception $e){
    new Core\Error($e, true);
}
