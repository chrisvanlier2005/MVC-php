<?php
namespace Core;
use Exception;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;

class Container implements ContainerInterface {
    private array $factories = [];

    /**
     * Retrieve an output from the factory registered for the given id in the container.
     * Throws an exception if no factory is registered for the given id.
     * @throws Exception
     */
    public function get(string $id){
        if(!array_key_exists($id, $this->factories)){
            throw new Exception("No factory registered for id: $id");
        }
        // check if the factory is a closure
        if($this->factories[$id] instanceof \Closure){
            // if it is, call it and return the result
            return $this->factories[$id]();
        }
        // check if its an object / instance
        if(is_object($this->factories[$id])){
            // if it is, return it
            return $this->factories[$id];
        }
        // if it is not a closure, it must be a class name
        // so we create a new instance of that class and return it
        return new $this->factories[$id]();
    }

    /**
     * Check if a factory is registered for a given id.
     */
    public function has(string $id): bool
    {
        return isset($this->factories[$id]);
    }

    /**
     * Set a factory for a given id, so the container can create it when requested / needed.
     */
    public function set(string $id, $factory)
    {
        $this->factories[$id] = $factory;
    }

    /**
     * Resolve a class and all of its dependencies.
     * @throws ReflectionException
     * @throws Exception
     */
    public function resolve(string $class)
    {
    }
}