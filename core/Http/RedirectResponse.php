<?php
namespace Core\Http;

class RedirectResponse
{
    private string $uri;
    private bool $killOnRedirect = false;
    private array $beforeCallbacks = [];
    private array $afterCallbacks = [];
    public function construct()
    {

    }

    public function to(string $uri) : static
    {
        $this->uri = $uri;
        return $this;
    }

    public function withQuery(array $parameters) : static
    {
        $parameters = http_build_query($parameters);
        $this->uri .= "?{$parameters}";
        return $this;
    }

    public function before(callable $callback) : static
    {
        $this->beforeCallbacks[] = $callback;
        return $this;
    }
    public function after(callable $callback) : static
    {
        $this->afterCallbacks[] = $callback;
        return $this;
    }

    public function killOnRedirect() : static
    {
        $this->killOnRedirect = true;
        return $this;
    }

    public function __destruct()
    {
        call_user_func_array($this->beforeCallbacks, []);
        header("Location: {$this->uri}");
        call_user_func_array($this->afterCallbacks, []);

        if ($this->killOnRedirect) {
            die();
        }
    }
}