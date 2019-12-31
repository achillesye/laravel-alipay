<?php

namespace Achilles\LaravelAlipay\Gateways;

use Achilles\LaravelAlipay\Contracts\AbstractTradeCateway;
use Achilles\LaravelAlipay\Contracts\AlipayRequest;

class CommonQueryGateway extends AbstractTradeCateway
{

    public function execute(AlipayRequest $request, $params = [])
    {
        $request->setMethod($this->getMethod());
        $common_params = $request->getCommonParams();
        $common_params['biz_content'] = $this->setBizContent($common_params);
        $this->unsetParams($common_params);
        $common_params['sign'] = $request->generateSign($common_params);

        return $this->response($request, $common_params)->json();
    }

    public function getMethod()
    {
        return 'alipay.fund.trans.common.query';
    }

    public function getProductCode()
    {
        return 'STD_RED_PACKET';
    }

    public function respStr()
    {
        return 'alipay_fund_trans_common_query_response';
    }
}
