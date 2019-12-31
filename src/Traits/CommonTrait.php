<?php

namespace Achilles\LaravelAlipay\Traits;

use GuzzleHttp\Client;

trait CommonTrait
{
    public $request;
    public $response;

    /**
     * send http request
     * @param $url
     * @param array $body
     * @param string $charset
     * @return mixed
     */
    public function request($url, array $body = [], $charset = 'utf-8')
    {
        $client = new Client();
        $response = $client->get($url);

        $response = $response->getBody()->getContents();

        return json_decode($response, true);
    }

    public function response($request, $params)
    {
        $this->request = $request;
        $this->response = $params;

        return $this;
    }

    public function buildUrl($request, $params)
    {
        return $request->gateway_url.'?'.http_build_query($params);
    }

    /**
     * 返回跳转完整url
     *
     * @param $request
     * @param $params
     * @return string
     */
    public function redirectUrl()
    {
        return $this->request->gateway_url.'?'.http_build_query($this->response);
    }

    /**
     * 返回业务参数包含sign字符串
     *
     * @param $request
     * @param $params
     * @return mixed
     */
    public function redirectBody()
    {
        return $this->response;
    }

    /**
     * 返回签名后订单信息 query url部分
     *
     * @param $request
     * @param $params
     * @return string
     */
    public function redirectQueryUrl()
    {
        $query_url = $this->request->getSignContent($this->response);

        return $query_url;
    }

    /**
     * 返回post form表单支付
     *
     * @param $request
     * @param $params
     * @return string
     */
    public function redirectHtmlForm()
    {
        $sHtml = "<form id='alipay_submit' name='alipay_submit' action='".$this->request->gateway_url."' method='post'>";
        foreach ($this->response as $key => $val) {
            $val = str_replace("'", '&apos;', $val);
            $sHtml .= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
        $sHtml .= "<input type='submit' value='ok' style='display:none;'></form>";
        $sHtml .= "<script>document.forms['alipay_submit'].submit();</script>";

        return $sHtml;
    }

    /**
     * 格式化返回结果
     *
     * @param $response
     * @return mixed
     */
    public function json()
    {
        $respStr = $this->respStr();
        if ($respStr) {
            $result = $this->response[$respStr];
            $result['sign'] = $this->response['sign'];

            return $result;
        }

        return $this->origin();
    }

    /**
     * 返回请求结果原格式数据
     *
     * @param $response
     * @return mixed
     */
    public function origin()
    {
        return $this->response;
    }

    public function __toString()
    {

        return $url = $this->redirectUrl();
    }
}
