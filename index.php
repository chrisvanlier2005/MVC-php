<?php
require __DIR__ . "/vendor/autoload.php";
// error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use App\App;
function dd($var){
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    die();
}

/**
 * route helper is hopelijk een tijdelijke oplossing voor het genereren van urls voor op de webserver van het GLR
 */
function route(string $name, array $params = []) : string
{
    // get the url
    return App::$container->get("app")->baseURL . "/" . $name;
}

$app = new App();
//$app = new App();

try {
    // base path is the path to the folder that contains this project.
    $app->basePath = "";
    $app->run();
} catch (Exception $e){
    new Core\Error($e, true);
}
