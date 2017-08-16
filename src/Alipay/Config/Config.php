<?php

namespace XiMu\Alipay\Config;

class Config
{
    private $signType           = 'RSA2';
    private $alipayPublicKey    = '';
    private $merchantPrivateKey = '';
    private $charset            = 'UTF-8';
    private $gatewayUrl         = 'https://openapi.alipay.com/gateway.do';
    private $appId              = '';
    private $pId                = '';
    private $notifyUrl          = '';
    private $maxQueryRetry      = 10;
    private $queryDuration      = 3;
    private $debug              = true;
    private $testGatewayUrl     = 'https://openapi.alipaydev.com/gateway.do';
    private $preCreateNotifyUrl;

    public function setQueryDuration($queryDuration)
    {
        $this->queryDuration = $queryDuration;
        return $this;
    }

    public function getQueryDuration()
    {
        return $this->queryDuration;
    }

    public function setMaxQueryRetry($maxQueryRetry)
    {
        $this->maxQueryRetry = $maxQueryRetry;
        return $this;
    }

    public function getMaxQueryRetry()
    {
        return $this->maxQueryRetry;
    }

    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
        return $this;
    }

    public function getNotifyUrl()
    {
        return $this->notifyUrl;
    }

    public function setAppId($appId)
    {
        $this->appId = $appId;
        return $this;
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getGatewayUrl()
    {
        if ($this->debug) {
            return $this->testGatewayUrl;
        } else {
            return $this->gatewayUrl;
        }
    }

    public function setCharset($charset)
    {
        $this->charset = $charset;
        return $this;
    }

    public function getCharset()
    {
        return $this->charset;
    }

    public function setMerchantPrivateKey($merchantPrivateKey)
    {
        $this->merchantPrivateKey = $merchantPrivateKey;
        return $this;
    }

    public function getMerchantPrivateKey()
    {
        return $this->merchantPrivateKey;
    }

    public function setAlipayPublicKey($alipayPublicKey)
    {
        $this->alipayPublicKey = $alipayPublicKey;
        return $this;
    }

    public function getAlipayPublicKey()
    {
        return $this->alipayPublicKey;
    }

    public function setSignType($signType)
    {
        $this->signType = $signType;
        return $this;
    }

    public function getSignType()
    {
        return $this->signType;
    }

    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    public function getDebug()
    {
        return $this->debug;
    }

    public function setPId($pId)
    {
        $this->pId = $pId;
        return $this;
    }

    public function getPId()
    {
        return $this->pId;
    }

    public function setPreCreateNotifyUrl($preCreateNotifyUrl)
    {
        $this->preCreateNotifyUrl = $preCreateNotifyUrl;
        return $this;
    }

    public function getPreCreateNotifyUrl()
    {
        return $this->preCreateNotifyUrl;
    }
}
