<?php

namespace Achilles\LaravelAlipay\Gateways;

use Achilles\LaravelAlipay\Contracts\AlipayRequest;
use Achilles\LaravelAlipay\Contracts\AbstractPayGateway;

/**
 * app pay
 *
 * Class AppGateway
 * @package Achilles\LaravelAlipay\Gateways
 */
class AppGateway extends AbstractPayGateway
{

    public function execute(AlipayRequest $request, $params = [])
    {
        $request->setMethod($this->getMethod());
        $common_params = $request->getCommonParams();
        $common_params['biz_content'] = $this->setBizContent($common_params);
        $common_params['sign'] = $request->generateSign($common_params);

        return $this->response($request, $common_params);
    }

    public function getMethod()
    {
        return 'alipay.trade.app.pay';
    }

    public function getProductCode()
    {
        return 'QUICK_MSECURITY_PAY';
    }

    public function respStr()
    {
        return 'alipay_trade_app_pay_response';
    }

}
