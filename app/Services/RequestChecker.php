<?php

namespace App\Services;

class RequestChecker
{
    protected $request;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function check()
    {
        if (($this->request['weight'] ?? 0) < 1) {
            return false;
        }

        if (($this->request['price'] ?? 0) < 1) {
            return false;
        }

        return true;
    }
}
