<?php

namespace Achilles\LaravelAlipay\Contracts;

use Achilles\LaravelAlipay\Exceptions\InvalidConfigException;
use Achilles\LaravelAlipay\Exceptions\InvalidGatewayException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Class AlipayRequest
 * https://docs.open.alipay.com/api_1/alipay.trade.app.pay/
 * @package Achilles\LaravelAlipay
 */
abstract class AlipayRequest
{
    //支付宝分配给开发者的应用ID
    public $app_id;

    //接口名称
    public $method;

    //仅支持JSON
    public $format = "JSON";

    //请求使用的编码格式，如utf-8,gbk,gb2312等
    public $charset = 'utf-8';

    //商户生成签名字符串所使用的签名算法类型，目前支持RSA2和RSA，推荐使用RSA2
    public $sign_type = 'RSA2';

    //商户请求参数的签名串
    public $sign;

    //发送请求的时间
    public $timestamp;

    //调用的接口版本，固定为：1.0
    public $version = '1.0';

    //支付宝服务器主动通知商户服务器里指定的页面http/https路径
    public $notify_url;

    //HTTP/HTTPS开头字符串
    public $return_url;

    //第三方应用授权
    public $app_auth_token;

    //请求参数的集合，最大长度不限，除公共参数外所有请求参数都必须放在这个参数中传递，具体参照各产品快速接入文档
    public $biz_content;

    //是否使用沙箱环境
    public $sandbox;
    //正式环境
    public $gateway_url = 'https://openapi.alipay.com/gateway.do';
    public $sandbox_gateway_url = 'https://openapi.alipaydev.com/gateway.do';

    //应用私钥
    public $app_private_key;

    //应用公钥
    public $app_public_key;

    //支付宝公钥
    public $alipay_public_key;

    //应用公钥证书
    public $app_public_key_cert;

    //支付宝公钥证书
    public $alipay_public_key_cert;

    //支付宝根证书
    public $alipay_root_cert;

    //接口加密方式 publicKey 公钥，publicKeyCert 公钥证书
    public $key_type;

    public function __construct($config)
    {
        $this->app_id = $config['app_id'];
        $this->notify_url = $config['notify_url'];
        $this->return_url = $config['return_url'];

        $this->key_type = strtolower($config['key_type']);
        if (empty($config['app_private_key'])) {
            throw new InvalidConfigException('Missing Alipay Config -- [private key]');
        }
        $this->app_private_key = $config['app_private_key'];

        if ($this->key_type == 'publickey') {
            if (empty($config['app_public_key'])) {
                throw new InvalidConfigException('Missing Alipay Config -- [private key]');
            }
            $this->alipay_public_key = $config['alipay_public_key'];
        } else {
            if (empty($config['app_public_key_cert']) || empty($config['alipay_public_key_cert']) || empty($config['alipay_root_cert'])) {
                throw new InvalidConfigException('Missing Alipay Config -- [alipay key cert]');
            }
            $this->app_public_key_cert = $config['app_public_key_cert'];
            $this->alipay_public_key_cert = $config['alipay_public_key_cert'];
            $this->alipay_root_cert = $config['alipay_root_cert'];
        }
        $this->sandbox = $config['sandbox'];
        if ($this->sandbox) {
            $this->gateway_url = $this->sandbox_gateway_url;
        }
        $this->setTimestamp();
    }

    protected function setParams($name, $value)
    {
        $name = substr($this->uncamelize($name), 4);
        $this->$name = $value;

        return $this;
    }

    public function setKeyFilePath($file)
    {
        return substr($file, 0, 1) == '/' ? $file : base_path($file);
    }

    public function setTimestamp($value = null)
    {
        if (!$value) {
            $value = date('Y-m-d H:i:s');
        }
        $this->timestamp = $value;

        return $this;
    }

    public function getCommonParams()
    {
        $params = [
            'app_id' => $this->app_id,
            'method' => $this->method,
            'format' => $this->format,
            'charset' => $this->charset,
            'sign_type' => $this->sign_type,
            'timestamp' => $this->timestamp,
            'version' => $this->version,
            'app_auth_token' => $this->app_auth_token,
            'return_url' => $this->return_url,
            'notify_url' => $this->notify_url,
            'biz_content' => $this->biz_content
        ];
        foreach ($params as $k => $v) {
            if (!$v || $k == 'sign') {
                unset($params[$k]);
            }
        }

        return $params;
    }

    /**
     * 获取秘钥字符串
     *
     * @param string $type
     * @return bool|resource
     * @throws InvalidConfigException
     */
    public function getRSAPrivateKey()
    {
        if ($this->key_type == 'publickey') {
            if (Str::endsWith($this->app_private_key, ['.txt'])) {
                $key_str = "-----BEGIN RSA PRIVATE KEY-----\n";
                $file = file_get_contents($this->setKeyFilePath($this->app_private_key));
                $key_str .=  wordwrap($file, 64, "\n", true);
                $key_str .= "\n-----END RSA PRIVATE KEY-----";
            } else {
                $key_str = "-----BEGIN RSA PRIVATE KEY-----\n";
                $key_str .=  wordwrap($this->app_private_key, 64, "\n", true);
                $key_str .= "\n-----END RSA PRIVATE KEY-----";
            }
        } else {
            $file = file_get_contents($this->setKeyFilePath($this->app_private_key));
            $key_str = file_get_contents($file);
        }

        $res_key = openssl_pkey_get_private($key_str);

        return $res_key;
    }

    public function getRSAPublicKey()
    {
        if ($this->key_type == 'publickey') {
            if (Str::endsWith($this->alipay_public_key, ['.txt'])) {
                $key_str = "-----BEGIN PUBLIC KEY-----\n";
                $file = file_get_contents($this->setKeyFilePath($this->alipay_public_key));
                $key_str .=  wordwrap($file, 64, "\n", true);
                $key_str .= "\n-----END PUBLIC KEY-----";
            } else {
                $key_str = "-----BEGIN PUBLIC KEY-----\n";
                $key_str .=  wordwrap($this->alipay_public_key, 64, "\n", true);
                $key_str .= "\n-----END PUBLIC KEY-----";
            }
        } else {
            $file = file_get_contents($this->setKeyFilePath($this->alipay_public_key));
            $key_str = file_get_contents($file);
        }

        $res_key = openssl_get_publickey($key_str);

        return $res_key;
    }

    /**
     * 生成sign
     *
     * @param array $data
     * @return string
     * @throws InvalidConfigException
     */
    public function generateSign($data = [])
    {
        $private_key = $this->getRSAPrivateKey();
        $data = $this->getSignContent($data);

        if ($this->sign_type == 'RSA2') {
            $this->sign = openssl_sign($data, $signature, $private_key, OPENSSL_ALGO_SHA256);
        } else {
            $this->sign = openssl_sign($data, $signature, $private_key, OPENSSL_ALGO_SHA1);
        }

        openssl_free_key($private_key);
        $signature = base64_encode($signature);

        return $signature;
    }

    public function encodeValue(array $data)
    {
        foreach ($data as $key => $value) {
            $data[$key] = urlencode($value);
        }

        return $data;
    }

    /**
     * 异步通知校验
     *
     * @param $body
     * @param \Closure $callback
     * @return mixed
     * @throws InvalidConfigException
     */
    public function notifyVerify(\Closure $callback)
    {
        $body = $_POST ? : $_GET;

        if (empty($body['sign']) || empty($body['sign_type'])) {
            return $callback($body, false);
        }

        if (!$this->verifySign($body, $body['sign'], $body['sign_type'])) {
            return $callback($body, false);
        }

        return $callback($body, true);
    }

    /**
     * 支付宝回调结果验签
     *
     * @param $params
     * @param $sign
     * @param $sign_type
     * @return int
     * @throws InvalidConfigException
     */
    public function verifySign($params, $sign, $sign_type = '')
    {
        unset($params['sign']);
        unset($params['sign_type']);

        if ($sign_type) {
            $this->setSignType($sign_type);
        }
        $params = $this->getSignContent($params);
        $get_key = $this->getRSAPublicKey('public');

        if ($this->sign_type == 'RSA2') {
            $is_verify = openssl_verify($params, base64_decode($sign), $get_key, OPENSSL_ALGO_SHA256);
        } else {
            $is_verify = openssl_verify($params, base64_decode($sign), $get_key, OPENSSL_ALGO_SHA1);
        }

        openssl_free_key($get_key);

        return $is_verify;
    }

    protected function uncamelize($camelCaps, $separator='_')
     {
         $camelCaps = trim($camelCaps);
         return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
     }

    /**
     * 切换字符集
     *
     * @param $data
     * @param $targetCharset
     * @return bool|false|string|string[]|null
     */
    function characet($data, $targetCharset)
    {

        if (!empty($data)) {
            $fileType = $this->charset;
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
            }
        }

        return $data;
    }

    /**
     * 拼接url参数
     *
     * @param $params
     * @return string
     */
    public function getSignContent($params) {
        ksort($params);

        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            // 转换成目标字符集
            $v = $this->characet($v, $this->charset);

            if ($i == 0) {
                $stringToBeSigned .= "$k" . "=" . "$v";
            } else {
                $stringToBeSigned .= "&" . "$k" . "=" . "$v";
            }
            $i++;
        }

        return $stringToBeSigned;
    }

    /**
     * 拼接url参数并urlencode转码
     *
     * @param $params
     * @return string
     */
    public function getSignContentUrlencode($params) {
        ksort($params);

        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            // 转换成目标字符集
            $v = $this->characet($v, $this->charset);

            if ($i == 0) {
                $stringToBeSigned .= "$k" . "=" . urlencode($v);
            } else {
                $stringToBeSigned .= "&" . "$k" . "=" . urlencode($v);
            }
            $i++;
        }

        return $stringToBeSigned;
    }

    public function getGateway($name, array $params = [])
    {

        $gateway = 'Achilles\LaravelAlipay\Gateways\\'.ucfirst($name).'Gateway';

        if (!class_exists($gateway)) {
            throw new InvalidGatewayException("Pay Gateway [{$name}] not exists");
        }
        $gateway = new $gateway();

        return $gateway->execute($this, $params);
    }

}
