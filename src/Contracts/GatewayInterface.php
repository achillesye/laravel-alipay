<?php

namespace Achilles\LaravelAlipay\Contracts;

interface GatewayInterface
{
    public function execute(AlipayRequest $request, $params = []);

    public function getMethod();

}
