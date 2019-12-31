<?php
/**
 * Created by PhpStorm.
 * User: winhu
 * Date: 2019-12-28
 * Time: 17:55
 */

namespace Achilles\LaravelAlipay\Gateways;


use Achilles\LaravelAlipay\Contracts\AbstractTradeCateway;
use Achilles\LaravelAlipay\Contracts\AlipayRequest;

class TransferGateway extends AbstractTradeCateway
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
        return 'alipay.fund.trans.uni.transfer';
    }

    public function getProductCode()
    {
        return 'STD_RED_PACKET';
    }

    public function respStr()
    {
        return 'alipay_fund_trans_uni_transfer_response';
    }

}
