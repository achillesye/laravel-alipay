<?php

namespace Achilles\LaravelAlipay\Contracts;

use Achilles\LaravelAlipay\Traits\CommonTrait;

abstract class AbstractPayGateway implements GatewayInterface
{
    use CommonTrait;

    abstract public function execute(AlipayRequest $request, $params = []);

    abstract public function getMethod();

    abstract public function getProductCode();

    public function setBizContent($params)
    {
        if ($this->getProductCode()) {
            $params['biz_content']['product_code'] = $this->getProductCode();
        }
        $params['biz_content'] = json_encode($params['biz_content']);

        return $params['biz_content'];
    }

    public function respStr()
    {

    }
}
