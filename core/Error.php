<?php
namespace Core;

class Error extends Controller {
    private \Exception $exception;
    private bool $debug;

    public function __construct(\Exception $exception, bool $debug = false)
    {
        $this->exception = $exception;
        $this->debug = $debug;
        $this->handle();
    }

    /**
     * @throws \Exception
     */
    private function handle(): void
    {
        Router::NotFound(function (){
            return false;
        });

        $this->debug ? $this->debug() : $this->production();
    }

    /**
     * @throws \Exception
     */
    private function debug(): void
    {
        $this->view("errors.exception", ["error" => $this->exception]);
    }

    /**
     * @throws \Exception
     */
    private function production(): void
    {
        // return a 500 error
        http_response_code(500);
        $this->view("errors.500");
    }


}