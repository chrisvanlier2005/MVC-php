<?php
namespace Core\Http;

use Core\Error;

class Request
{
    public function __construct(Error $sub_request)
    {

    }

    public function query(string $key, string|null $default = null) : string|null
    {
        return $_GET[$key] ?? $default;
    }

    public function post(string $key, string|null $default = null) : string|null
    {
        return $_POST[$key] ?? $default;
    }

    public function method() : string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isPost() : bool
    {
        return $this->method() == "POST";
    }

    public function isGet() : bool
    {
        return $this->method() == "GET";
    }

    public function isPut() : bool
    {
        return $this->method() == "PUT";
    }

    public function isDelete() : bool
    {
        return $this->method() == "DELETE";
    }

    public function isPatch() : bool
    {
        return $this->method() == "PATCH";
    }

    public function uri() : string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function path() : string
    {
        $uri = $this->uri();
        $position = strpos($uri, '?');
        if ($position === false) {
            return $uri;
        }
        return substr($uri, 0, $position);
    }

}