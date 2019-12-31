<?php

namespace Achilles\LaravelAlipay\Contracts;

use Achilles\LaravelAlipay\Traits\CommonTrait;

abstract class AbstractTradeCateway implements GatewayInterface
{
    use CommonTrait;

    abstract public function execute(AlipayRequest $request, $params = []);

    abstract public function getMethod();

    public function setBizContent($params)
    {
        $params['biz_content'] = json_encode($params['biz_content']);

        return $params['biz_content'];
    }

    public function unsetParams(&$params)
    {
        unset($params['return_url']);
        unset($params['notify_url']);

        return $params;
    }

    public function respStr()
    {

    }
}
