<?php

namespace App\Controller;

use Symfony\Component\ErrorHandler\Exception\FlattenException;

class ErrorController
{
    public function showError(FlattenException $exception)
    {
        echo "ERROR" . $exception->getMessage();
    }
}