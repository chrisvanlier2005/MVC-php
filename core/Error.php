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
        dd($this->exception->getTrace());
    }


}