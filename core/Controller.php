<?php
namespace Core;
class Controller {
    /**
     * @throws \Exception
     */
    protected function view(string $viewName, array $properties = []) : bool
    {
        $path = str_replace(".", '/', $viewName) . ".php";
        foreach ($properties as $property => $value) {
            $$property = $value;
        }
        $path = __DIR__ . '/../App/views/' . $path;
        if (!file_exists($path)){
            throw new \Exception("View {$viewName} does not exist in {$path}");
        }
        return require $path;
    }

    protected function redirect(string $url) : bool
    {
        header("Location: {$url}");
        return true;
    }
}