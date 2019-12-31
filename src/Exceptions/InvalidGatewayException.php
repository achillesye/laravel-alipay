<?php

namespace Achilles\LaravelAlipay\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidGatewayException extends HttpException
{

    public function __construct(string $message = null, \Throwable $previous = null, array $headers = [], ?int $code = 0)
    {
        parent::__construct(500, $message, $previous, $headers, $code);
    }

}
