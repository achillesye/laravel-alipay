<?php

namespace Yansongda\Pay\Gateways\Alipay;

use Achilles\LaravelAlipay\Contracts\AbstractTradeCateway;
use Achilles\LaravelAlipay\Contracts\AlipayRequest;

/**
 * 统一收单交易退款接口
 *
 * Class RefundGateway
 * https://docs.open.alipay.com/api_1/alipay.trade.refund
 * @package Yansongda\Pay\Gateways\Alipay
 */
class RefundGateway extends AbstractTradeCateway
{
    public function execute(AlipayRequest $request, $params = [])
    {
        $request->setMethod = $this->getMethod();
        $common_params = $request->getCommonParams();
        $common_params['biz_content'] = $this->setBizContent($common_params);
        $common_params['sign'] = $request->generateSign($common_params);

        $url = $request->gateway_url.'?'.$this->getSignContentUrlencode($common_params);
        $result = $this->request($url)->json();

        return $this->response($request, $result);
    }

    public function getMethod()
    {
        return 'alipay.trade.refund';
    }

    public function respStr()
    {
        return 'alipay_trade_refund_response';
    }

}
