<?php

namespace Core;

use ReflectionMethod;
use ReflectionClass;

class Router
{
    private static Router $routerInstance;
    public array $registeredRoutes = [];
    private array $prefixStack = [];
    private array $notFoundHandler = [];
    private bool $matched = false;

    public function __construct()
    {
        self::$routerInstance = $this;
        self::$routerInstance->notFoundHandler[0] = function(){
          throw new \Exception("404 not found");
        };
    }

    protected function register($url, $callback, $method)
    {
        $url = $this->getPrefixUrl() . $url;
        $parameters = $this->extractParameters($url);
        $this->registeredRoutes[] = ['url' => $url, 'callback' => $callback, 'method' => $method, 'checked' => false, 'params' => $parameters];
    }

    protected function getPrefixUrl()
    {
        $url = "";
        if (is_array($this->prefixStack) && count($this->prefixStack) <= 0) {
            return $url;
        }
        foreach ($this->prefixStack as $prefix) {
            $url .= $prefix;
        }
        return $url;
    }

    public function extractParameters($url): array
    {
        $parameters = [];
        $url = explode("/", $url);
        foreach ($url as $key => $value) {
            if (str_contains($value, "{") && str_contains($value, "}")) {
                $parameters[substr($value, 1, -1)] = $key;
            }
        }
        return $parameters;
    }

    /**
     * @throws \ReflectionException
     */
    public function execute() : bool
    {
        /*
        if ($this->matched) {
            return false;
        }
        */
        $url = "/";
        if (isset($_GET['q'])) {
            $url .= $_GET['q'];
        }
        if ($url == "") {
            $url = "/";
        }
        if (!str_ends_with($url, "/")) {
            $url .= "/";
        }

        foreach ($this->registeredRoutes as $key => $route) {
            if (!str_ends_with($route['url'], "/")) {
                $route['url'] .= "/";
            }
            if ($this->urlParameterMatch($route["url"], $url, $key)[0] && $route['method'] == $this->getTrueRequestMethod()) {
                $params = $this->urlParameterMatch($route["url"], $url, $key)[1];
                if ($params == null) $params = [];
                $callback = $route['callback'];
                // check if the callback is an Controller method. [UserController::class, 'index']
                if (is_array($callback)) {
                    $controller = new $callback[0]();
                    $reflection = new ReflectionMethod($controller, $callback[1]);
                    $parameters = $reflection->getParameters();
                    $result = $controller->{$callback[1]}(...$params);
                } else {
                    $result = $callback(...$params);
                }
                $this->matched = true;
                $this->registeredRoutes[$key]["checked"] = true;
                return $result;
            }
        }
        return true;
    }

    public function urlParameterMatch($registeredUrl, $requestedUrl, $key): array
    {
        $requestedUrl = explode('/', $requestedUrl);
        array_shift($requestedUrl);
        $registeredUrl = explode('/', $registeredUrl);
        array_shift($registeredUrl);

        //remove last / from both arrays
        array_pop($requestedUrl);
        array_pop($registeredUrl);
        $matches = true;
        $matchesFalsifiedAt = "";
        $requestParams = [];
        if (count($requestedUrl) != count($registeredUrl)) {
            $matches = false;
            $matchesFalsifiedAt = "Count does not match";
        }
        if (!$matches) {
            return [false];
        }
        for ($i = 0; $i < count($registeredUrl); $i++) {
            $parameterMatch = false;
            if (str_contains($registeredUrl[$i], "{") && str_contains($registeredUrl[$i], "}")) {
                // request part is a parameter
                $parameter = substr($registeredUrl[$i], 1, -1);
                // checken of de parameter in de geristreerde parameters staat.
                // get the parameters for the registered url
                $parameters = $this->registeredRoutes[$key]['params'];
                $value = $requestedUrl[$i];
                $requestParams[$parameter] = $value;
                $parameterMatch = true;
                continue;
            }
            if ($registeredUrl[$i] == $requestedUrl[$i]) {
                continue;
            }
            $matches = false;

        }
        return [$matches, $requestParams];

    }

    /**
     * Retrieve the actual request method, for example a FORM could have the _method put.
     * If the request is not post it will default to GET and if the field is not present it will default to POST.
     */
    protected function getTrueRequestMethod() : string
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["_method"])) {
                return $_POST["_method"];
            }
            return "POST";
        }
        return $_SERVER["REQUEST_METHOD"];
    }

    /**
     * Register the standard Controller methods
     * [index, create, show, edit, store, update, destroy]
     * @param string $url
     * @param string $controller
     * @return void
     */
    public static function resource(string $url, string $controller): void
    {
        if (!str_ends_with($url, "/")) {
            $url .= "/";
        }
        self::prefix($url, function () use ($controller) {
            self::get("", [$controller, "index"]);
            self::get("create", [$controller, "create"]);
            self::get("{id}", [$controller, "show"]);
            self::get("{id}/edit", [$controller, "edit"]);
            self::post("", [$controller, "store"]);
            self::put("{id}", [$controller, "update"]);
            self::delete("{id}", [$controller, "destroy"]);
        });
    }

    /**
     * Register an action to execute when the Route does not match any registered routes.
     */
    public static function NotFound(callable|array $callback) : void
    {
        if (self::$routerInstance->matched) {
            return;
        }
        if (is_array($callback) && count($callback) === 2) {
            $controller = new $callback[0]();
            self::$routerInstance->notFoundHandler[0] = $controller->{$callback[1]};
        }
        self::$routerInstance->notFoundHandler[0] = $callback;
    }

    /**
     * Register a new GET route
     */
    public static function get(string $url, callable|array $callback): void
    {
        self::$routerInstance->register($url, $callback, "GET");
    }
    /**
     * Register a new POST route
     */
    public static function post(string $url, callable|array $callback) : void
    {
        self::$routerInstance->register($url, $callback, "POST");
    }
    /**
     * Register a new PUT route
    */
    public static function put(string $url, callable|array $callback) : void
    {
        self::$routerInstance->register($url, $callback, "PUT");
    }

    /**
     * Register a new Delete Route
     */
    public static function delete(string $url, callable|array $callback): void
    {
        self::$routerInstance->register($url, $callback, "DELETE");
    }

    /**
     * Set the Router prefix to a certain string
     * E.g. Router::prefix("/user") ..., routes in the prefix must start with /
     * @param string $prefix
     * @param callable $callback
     * @return void
     */
    public static function prefix(string $prefix, callable $callback): void
    {
        self::$routerInstance->addPrefix($prefix);
        $callback();
        self::$routerInstance->removePrefix();
    }

    /**
     * Run the Router instance (singleton)
    */
    public static function run() : void
    {
        self::$routerInstance->execute();
    }

    /**
     * Add a new prefix to the stack
     */
    public function addPrefix(string $prefix): void
    {
        $this->prefixStack[] = $prefix;
    }

    /**
     * Removes the last added prefix from the prefix "stack" array
     */
    public function removePrefix(): void
    {
        array_pop($this->prefixStack);
    }

    public static function singleton() : static
    {
        // return the Router Singleton;
        if(isset(self::$routerInstance)){
            return self::$routerInstance;
        }
        return new static();
    }


    /**
     * Finalize the router, (notFound etc...)
     */
    public function __destruct()
    {
        if (!$this->matched){
            call_user_func($this->notFoundHandler[0]);
        }
    }

}
