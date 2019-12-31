<?php

namespace Achilles\LaravelAlipay\Facade;

use Illuminate\Support\Facades\Facade;

class Alipay extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'alipay';
    }

}
