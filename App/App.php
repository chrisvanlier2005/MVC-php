<?php
namespace App;

use Core\Container;
use Core\Router;
use Core\ViewProcessor;

class App implements \Core\AppInterface
{
    private Container $container;
    public function run()
    {
        $this->container = new Container();

        Router::singleton();
        require __DIR__ . "/../web/routes.php";
        Router::run();
    }
}