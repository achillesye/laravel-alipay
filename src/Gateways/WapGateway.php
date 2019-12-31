<?php

namespace Achilles\LaravelAlipay\Gateways;

/**
 * wap pay
 *
 * Class WapGateway
 * @package Achilles\LaravelAlipay\Gateways
 */
class WapGateway extends WebGateway
{

    public function getMethod()
    {
        return 'alipay.trade.wap.pay';
    }

    public function getProductCode()
    {
        return 'QUICK_WAP_WAY';
    }

    public function respStr()
    {
        return 'alipay_trade_wap_pay_response';
    }
}
