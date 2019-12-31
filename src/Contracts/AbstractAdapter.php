<?php

namespace Achilles\LaravelAlipay\Contracts;

use Achilles\LaravelAlipay\Exceptions\InvalidGatewayException;
use Achilles\LaravelAlipay\Traits\CommonTrait;

abstract class AbstractAdapter
{
    use CommonTrait;

    public $alipay;
    public $params;

    public function __construct($alipay)
    {
        $this->alipay = $alipay;
    }

    public function __call($name, $arguments)
    {
        return $this->getGateway($name);
    }

    public function getGateway($name, array $params = [])
    {

        $gateway = 'Achilles\LaravelAlipay\Gateways\\'.ucfirst($name).'Gateway';

        if (!class_exists($gateway)) {
            throw new InvalidGatewayException("Pay Gateway [{$name}] not exists");
        }
        $gateway = new $gateway();

        return $gateway->execute($this->alipay, $params);
    }
}
