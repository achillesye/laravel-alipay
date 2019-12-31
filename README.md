#### laravel alipay
基于laravel/lumen alipay扩展包
### 运行环境
- laravel/lumen >= 5.6
### 安装
composer require achilles/laravel-alipay
- lumen
  - $app->configure('alipay');
  - $app->register(\Achilles\LaravelAlipay\AlipayServiceProvider::class);
- laravel

### 支持方法
| 方法名 | 描述 |
| :----:  | :----: |
| web     | pc支付 | 
| wap     | 手机网站支付 |
| app     | app 支付 |
| query   | 支付单查询 |

### 使用说明
- 拷贝配置文件 alipay.php
```php
[
    app_id => '',
    key_type => '',
    app_private_key => '', //应用私钥
    app_public_key => '' //应用公钥
]
```
- 支付调用
```php
use Achilles\LaravelAlipay\Facade\Alipay;
//默认会读取配置文件设置参数，也可以通过下面这种形式设置参数
//payment()方法是setBizContent()别名

$order = [
    'out_trade_no' => '2021001101688901',
    'total_amount' => 1,
    'subject' => '支付测试'
];
$result = Alipay::setReturnUrl($return_url)
    ->setNotifyUrl($notify_url)
    ->payment($order)->app();
    
/*
格式化返回结果，可自由选择返回数据格式
redirectUrl() 返回完整跳转链接
redirectQueryUrl() 返回url参数部分
redirectBody() 返回订单详情包含sign签名
redirectHtmlForm() 返回post提交的html form表单
*/
$result = $result->redirectUrl();

//统一收单线下交易查询
$order = [
    'out_trade_no' => '2021001101688901',
    'trade_no' => '',
    'org_pid' => '',
//   'query_options' => []
];

$result = Alipay::unify($order)->query();

```
- 回调处理
```php
//$body 回调参数
//$verify 签名校验true/false
return Alipay::notifyVerify(function ($body, $verify) {
    if (!$verify) {
        return 'error';
    }
    return 'success';
});
```

### TODO
- 转账功能
