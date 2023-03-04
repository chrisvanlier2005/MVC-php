<?php
namespace App;

use Core\Container;
use Core\Router;

class App implements \Core\AppInterface
{
    public static Container $container;
    public string $baseURL = "";
    public string $basePath = "";
    public function run()
    {
        // URL plaatsen, zodat de route() helper kan werken op de andere adressen
        $this->baseURL = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . $this->basePath;
        self::$container = new Container();
        self::$container->set("app", $this);
        Router::singleton();
        require __DIR__ . "/../web/routes.php";
        Router::run();
    }
}