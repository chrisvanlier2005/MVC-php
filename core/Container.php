<?php
namespace Core;
use Psr\Container\ContainerInterface;
class Container implements ContainerInterface {
    private array $factories = [];
    public function get(string $id){
        return $this->factories[$id]();
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->factories);
    }

    public function set(string $id, $factory)
    {
        $this->factories[$id] = $factory;
    }

    public function resolve(callable $method)
    {

    }

}