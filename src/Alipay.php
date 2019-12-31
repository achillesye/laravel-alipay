<?php

namespace Achilles\LaravelAlipay;

use Achilles\LaravelAlipay\Adapters\PayAdapter;
use Achilles\LaravelAlipay\Adapters\TransferAdapter;
use Achilles\LaravelAlipay\Adapters\UnifyTradeAdapter;
use Achilles\LaravelAlipay\Contracts\AlipayRequest;
use Illuminate\Support\Facades\Log;

/**
 * Class Alipay
 *
 * @package Achilles\LaravelAlipay
 */
class Alipay extends AlipayRequest
{

    public function __construct($config)
    {
        parent::__construct($config);
//        $this->setTimestamp('2019-12-26 19:29:56');
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        $this->setParams($name, $arguments[0]);

        return $this;
    }

    public function trade($params)
    {
        $this->setBizContent($params);
        $this->setTimestamp();

        return $this;
    }

    /**
     * pay adapter
     *
     * @param array $data
     * @return PayAdapter
     */
    public function payment(array $params)
    {
        $this->trade($params);

        return new PayAdapter($this);
    }

    /**
     * 统一订单操作接口
     *
     * @param $params
     * @return UnifyTradeAdapter
     */
    public function unify(array $params)
    {
        $this->trade($params);

        return new UnifyTradeAdapter($this);
    }

    /**
     * transfer adapter
     *
     * @param array $params
     * @return TransferAdapter
     */
    public function transfer(array $params)
    {
        $this->trade($params);

        return new TransferAdapter($this);
    }


}
