<?php

namespace Achilles\LaravelAlipay;

use Illuminate\Support\ServiceProvider as LumenServiceProvider;
/**
 *
 * Date: 2018/11/6
 * Author: eric <eric@winhu.com>
 */
class AlipayServiceProvider extends LumenServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('alipay.php'),
        ]);
        $source = realpath(__DIR__.'/config/config.php');

        $this->mergeConfigFrom($source, 'alipay');
        $this->app->configure('alipay');
    }

    public function register()
    {

        $this->app->singleton('alipay', function ($app)  {
            return new Alipay(config('alipay'));
        });

    }

    protected function provider()
    {
        return [
            'alipay.web',
            'laipay.app'
        ];
    }
}
