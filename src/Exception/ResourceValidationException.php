<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;



class ResourceValidationException extends HttpException
{
    public function __construct(string $message = null, \Throwable $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct(400, $message, $previous, $headers, $code);
    }
}
