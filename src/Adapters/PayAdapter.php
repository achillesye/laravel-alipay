<?php

namespace Achilles\LaravelAlipay\Adapters;

use Achilles\LaravelAlipay\Contracts\AbstractAdapter;

class PayAdapter extends AbstractAdapter
{
    /**
     * 电脑网站支付
     * @param array $params
     * @return mixed
     */
    public function webPay($params = [])
    {
        return $this->getGateway('web', $params);
    }

    /**
     * 手机网站支付
     * @param array $params
     * @return mixed
     */
    public function wapPay($params = [])
    {
        return $this->getGateway('wap', $params);
    }

    /**
     * app支付
     * @param array $params
     * @return mixed
     */
    public function appPay($params = [])
    {
        return $this->getGateway('app', $params);
    }

    /**
     * 刷脸付
     * @return mixed
     */
    public function f2fPay($params = [])
    {
        return $this->getGateway('faceToFace', $params);
    }

}
