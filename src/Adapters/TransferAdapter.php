<?php


namespace Achilles\LaravelAlipay\Adapters;

use Achilles\LaravelAlipay\Contracts\AbstractAdapter;

class TransferAdapter extends AbstractAdapter
{

    public function pay()
    {
        return $this->getGateway('transfer');
    }

}
