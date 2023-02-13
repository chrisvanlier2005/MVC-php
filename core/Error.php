<?php
namespace Core;

class Error extends Controller {
    private \Exception $exception;

    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
        $this->handle();
    }

    private function handle(): void
    {
        Router::NotFound(function (){
            return false;
        });

        $this->view("errors.exception", ["error" => $this->exception]);
    }


}