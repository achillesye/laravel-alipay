<?php

namespace Achilles\LaravelAlipay\Gateways;

use Achilles\LaravelAlipay\Contracts\AlipayRequest;
use Achilles\LaravelAlipay\Contracts\AbstractPayGateway;

/**
 * 统一收单下单并支付页面接口
 * https://docs.open.alipay.com/api_1/alipay.trade.page.pay
 * Class WebGateway
 * @package Achilles\LaravelAlipay\Gateways
 */
class WebGateway extends AbstractPayGateway
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
        return 'alipay.trade.page.pay';
    }

    public function getProductCode()
    {
        return 'FAST_INSTANT_TRADE_PAY';
    }

    public function respStr()
    {
        return 'alipay_trade_page_pay_response';
    }

}
