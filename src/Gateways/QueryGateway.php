<?php

namespace Achilles\LaravelAlipay\Gateways;

use Achilles\LaravelAlipay\Contracts\AlipayRequest;
use Achilles\LaravelAlipay\Contracts\AbstractTradeCateway;

/**
 * 查询订单状态
 *
 * Class QueryGateway
 * @package Achilles\LaravelAlipay\Gateways
 */
class QueryGateway extends AbstractTradeCateway
{

    public function execute(AlipayRequest $request, $params = [])
    {
        $request->setMethod($this->getMethod());
        $common_params = $request->getCommonParams();
        $common_params['biz_content'] = $this->setBizContent($common_params);
        $this->unsetParams($common_params);
        $common_params['sign'] = $request->generateSign($common_params);

        $result = $this->request($this->buildUrl($request, $common_params));

        return $this->response($request, $result)->json();
    }

    public function getMethod()
    {
        return 'alipay.trade.query';
    }

    public function respStr()
    {
        return 'alipay_trade_query_response';
    }
}
