<?php

namespace Achilles\LaravelAlipay\Gateways;

use Achilles\LaravelAlipay\Contracts\AbstractPayGateway;
use Achilles\LaravelAlipay\Contracts\AlipayRequest;

class FaceToFaceGateway extends AbstractPayGateway
{

    public function execute(AlipayRequest $request, $params = [])
    {
        $request->setMethod = $this->getMethod();
        $common_params = $request->getCommonParams();
        $common_params['biz_content'] = $this->setBizContent($common_params);
        $common_params['sign'] = $request->generateSign($common_params);

        return $request->gateway_url.'?'.$request->getSignContentUrlencode($common_params);
    }

    public function getMethod()
    {
        return 'alipay.trade.pay';
    }

    public function getProductCode()
    {
        return 'FACE_TO_FACE_PAYMENT';
    }

    public function respStr()
    {
        return 'alipay_trade_pay_response';
    }
}