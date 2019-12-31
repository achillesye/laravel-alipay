<?php

return [
    'app_id' => env('ALIPAY_APP_ID', ''),  //app id

    'sandbox' => env('ALIPAY_SANDBOX', true),

    'key_type' => 'publicKey', //接口加密方式 publicKey 公钥，publicKeyCert 公钥证书
    'app_private_key' => env('ALIPAY_APP_PRIVATE_KEY', ''), //应用私钥
    'app_public_key' => env('ALIPAY_APP_PUBLIC_KEY', ''), //应用公钥
    'alipay_public_key' => env('ALIPAY_PUBLIC_KEY', ''), //支付宝公钥

    'app_public_key_cert' => env('ALIPAY_APP_PUBLIC_KEY_CERT', ''), //应用公钥证书
    'alipay_public_key_cert' => env('ALIPAY_PUBLIC_KEY_CERT', ''), //支付宝公钥证书
    'alipay_root_cert' => env('ALIPAY_ROOT_CERT', ''), //支付宝根证书


    'return_url' => env('ALIPAY_RETURN_URL', ''), //支付成功前台页面跳转地址
    'notify_url' => env('ALIPAY_NOTIFY_URL', ''), //支付异步消息通知

];
