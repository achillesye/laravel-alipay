<?php

namespace Achilles\LaravelAlipay\Gateways;

use Achilles\LaravelAlipay\Contracts\AbstractTradeCateway;
use Achilles\LaravelAlipay\Contracts\AlipayRequest;

class CloseGateway extends AbstractTradeCateway
{

    public function execute(AlipayRequest $request, $params = [])
    {
        $request->setMethod($this->getMethod());
        $common_params = $request->getCommonParams();
        $common_params['biz_content'] = $this->setBizContent($common_params);
        $this->unsetParams($common_params);
        $common_params['sign'] = $request->generateSign($common_params);

        $url = $request->gateway_url.'?'.$request->getSignContentUrlencode($common_params);
        $result = $this->request($url);

        return $this->response($request, $result)->json();
    }

    public function getMethod()
    {
        return 'alipay.trade.close';
    }

    public function respStr()
    {
        return 'alipay_trade_close_response';
    }

}
