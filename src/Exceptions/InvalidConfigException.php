<?php
namespace Achilles\LaravelAlipay\Exceptions;

use HttpException;
use Throwable;

class InvalidConfigException extends HttpException
{

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
