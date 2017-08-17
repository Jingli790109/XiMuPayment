<?php

namespace XiMu\Alipay;

use XiMu\Alipay\Config\Config;
use XiMu\Alipay\Aop\Request\AlipayOpenAuthTokenAppRequest;
use XiMu\Alipay\Aop\Request\AlipayOpenAuthTokenAppQueryRequest;
use XiMu\Alipay\Aop\Request\AlipaySystemOauthTokenRequest;
use XiMu\Alipay\Aop\Request\AlipayTradeCreateRequest;
use XiMu\Alipay\Aop\Request\AlipayTradeQueryRequest;
use XiMu\Alipay\Aop\Request\AlipayTradeCloseRequest;
use XiMu\Alipay\Aop\Request\AlipayTradeCancelRequest;
use XiMu\Alipay\Aop\Request\AlipayTradePayRequest;
use XiMu\Alipay\Aop\Request\AlipayDataDataserviceBillDownloadurlQueryRequest;
use XiMu\Alipay\Aop\Request\AlipayTradeRefundRequest;
use XiMu\Alipay\Aop\Request\AlipayTradePrecreateRequest;
use XiMu\Alipay\Aop\AopEncrypt;
use XiMu\Alipay\Aop\AopClient;
use XiMu\Alipay\Model\Builder\AlipayAppAuthTokenContentBuilder;
use XiMu\Alipay\Model\Builder\AlipayUserAuthTokenContentBuilder;
use XiMu\Alipay\Model\Builder\AlipayTradeCreateContentBuilder;
use XiMu\Alipay\Model\Builder\AlipayDataserviceBillContentBuilder;
use XiMu\Alipay\Model\Builder\AlipayTradeQueryContentBuilder;
use XiMu\Alipay\Model\Builder\AlipayTradeCloseContentBuilder;
use XiMu\Alipay\Model\Builder\AlipayTradeCancelContentBuilder;
use XiMu\Alipay\Model\Builder\AlipayTradePayContentBuilder;
use XiMu\Alipay\Model\Builder\AlipayRefundContentBuilder;
use XiMu\Alipay\Model\Builder\AlipayTradePreCreateContentBuilder;
use XiMu\Alipay\Model\Builder\ExtendParams;
use XiMu\Alipay\Model\Response\AlipayOpenAuthTokenResponse;
use XiMu\Alipay\Model\Response\AlipayUserAuthTokenResponse;
use XiMu\Alipay\Model\Response\AlipayTradeCreateResponse;
use XiMu\Alipay\Model\Response\AlipayQueryAuthTokenResponse;
use XiMu\Alipay\Model\Response\AlipayTradeQueryResponse;
use XiMu\Alipay\Model\Response\AlipayTradeCloseResponse;
use XiMu\Alipay\Model\Response\AlipayTradeCancelResponse;
use XiMu\Alipay\Model\Response\AlipayTradePayResponse;
use XiMu\Alipay\Model\Response\AlipayDataserviceBillResponse;
use XiMu\Alipay\Model\Response\AlipayTradeRefundResponse;
use XiMu\Alipay\Model\Response\AlipayTradePreCreateResponse;
use XinYing\ConfigBundle\Component\Utils;

class Alipay
{
    private $debug = false;
    private $salt  = 'XiMuTech';
    //支付宝网关地址
    private $gatewayUrl = "https://openapi.alipay.com/gateway.do";
    //异步通知回调地址
    private $notifyUrl;
    //签名类型
    private $signType = 'RSA2';
    //支付宝公钥地址
    private $alipayPublicKey;
    //商户私钥地址
    private $privateKey;
    //应用id
    private $appId;
    private $authToken;
    //服务商返佣标识符
    private $pId;

    //编码格式
    private $charset            = 'UTF-8';
    private $token              = NULL;
    //返回数据格式
    private $format             = 'json';
    private $authUserUrl        = 'https://openauth.alipay.com/oauth2/publicAppAuthorize.htm';
    private $authPartnerUrl     = 'https://openauth.alipay.com/oauth2/appToAppAuth.htm';
    private $testAuthUserUrl    = 'https://openauth.alipaydev.com/oauth2/publicAppAuthorize.htm';
    private $testAuthPartnerUrl = 'https://openauth.alipaydev.com/oauth2/appToAppAuth.htm';
    private $alipay_data_file   = '/share/data/alipay_data/';
    private $preCreateNotifyUrl = '';
    private $maxQueryRetry;
    private $queryDuration;
    private $logFilePath;

    public function __construct($config)
    {
        $this->gatewayUrl         = $config->getGatewayUrl();
        $this->appId              = $config->getAppId();
        $this->signType           = $config->getSignType();
        $this->privateKey         = $config->getMerchantPrivateKey();
        $this->alipayPublicKey    = $config->getAlipayPublicKey();
        $this->charset            = $config->getCharset();
        $this->notifyUrl          = $config->getNotifyUrl();
        $this->debug              = $config->getDebug();
        $this->preCreateNotifyUrl = $config->getPreCreateNotifyUrl();
        $this->pId                = $config->getPId();
        $this->maxQueryRetry      = $config->getMaxQueryRetry();
        $this->queryDuration      = $config->getQueryDuration();
        $this->logFilePath        = $config->getLogFile();
        if(empty($this->appId) || trim($this->appId) == '') {
            throw new \Exception("appid should not be NULL!");
        }
        if(empty($this->privateKey) || trim($this->privateKey) == '') {
            throw new \Exception("private_key should not be NULL!");
        }
        if(empty($this->alipayPublicKey) || trim($this->alipayPublicKey) == '') {
            throw new \Exception("alipay_public_key should not be NULL!");
        }
        if(empty($this->charset) || trim($this->charset) == '') {
            throw new \Exception("charset should not be NULL!");
        }
        if(empty($this->gatewayUrl) || trim($this->gatewayUrl) == '') {
            throw new \Exception("gateway_url should not be NULL!");
        }
        if(empty($this->signType) || trim($this->signType) == '') {
            throw new \Exception("sign_type should not be NULL");
        }
    }

    /**
     * 商户授权token.
     */
    public function setAppAuthToken($authToken)
    {
        $this->authToken = $authToken;
        return $this;
    }

    /**
     * 生成商户授权回调URL.
     */
    public function generatePanrtnerAuthUrl($redirectUrl)
    {
        $gatewayUrl  = $this->debug ? $this->testAuthPartnerUrl : $this->authPartnerUrl;
        $redirectUrl = urlencode($redirectUrl);
        $url         = $gatewayUrl . '?app_id=' . $this->appId . '&redirect_uri=' . $redirectUrl;
        return $url;
    }

    /**
     * 生成用户授权回调URL.
     */
    public function generateUserAuthUrl($redirectUrl)
    {
        $gatewayUrl  = $this->debug ? $this->testAuthUserUrl : $this->authUserUrl;
        $redirectUrl = urlencode($redirectUrl);
        $state       = $this->generateUserAuthState();
        $url         = $gatewayUrl . '?app_id=' . $this->appId . '&scope=auth_base&redirect_uri=' . $redirectUrl . '&state=' . $state;
        return $url;
    }

    /**
     * 用户授权自定义字段state，为防止CSRF攻击。
     */
    public function generateUserAuthState()
    {
        return md5(md5($this->appId . $this->salt));
    }

    /**
     * 判断返回state是否一致。
     */
    public function checkUserState($appid)
    {
        $state = $this->generateUserAuthState();
        return md5(md5($appid . $this->salt)) == $state;
    }

    /**
     * @param string $code app_auth_code.
     * 获取商户授权令牌
     */
    public function getAuthToken($code)
    {
        $builder = new AlipayAppAuthTokenContentBuilder();
        $builder->appAuthToken($code);
        $bizContent = $builder->getBizContent();
        if ($this->logFilePath) {
            $this->logFile('get auth token', $bizContent);
        }
        $request = new AlipayOpenAuthTokenAppRequest();
        $request->setBizContent($bizContent);
        $response = $this->aopClientRequestExecute($request);
        $res = new AlipayOpenAuthTokenResponse($request, $response);
        return $res->parse();
    }

    /**
     * @param string $token app_auth_token.
     * 查询商户授权令牌信息
     */
    public function queryAuthToken($token)
    {
        $builder = new AlipayAppAuthTokenContentBuilder();
        $builder->queryAuthToken($token);
        $bizContent = $builder->getBizContent();
        if ($this->logFilePath) {
            $this->logFile('query auth token', $bizContent);
        }
        $request = new AlipayOpenAuthTokenAppQueryRequest();
        $request->setBizContent($bizContent);
        $response = $this->aopClientRequestExecute($request);
        $res      = new AlipayQueryAuthTokenResponse($request, $response);
        return $res->parse();
    }

    /**
     * @param string 刷新token.
     * 刷新alipay授权token.
     */
    public function refreshAuthToken($refreshToken)
    {
        $builder = new AlipayAppAuthTokenContentBuilder();
        $builder->setGrantType('refresh_token');
        $builder->setRefreshToken($refreshToken);
        $bizContent = $builder->getBizContent();
        if ($this->logFilePath) {
            $this->logFile('refresh auth token', $bizContent);
        }
        $request = new AlipayOpenAuthTokenAppRequest();
        $request->setBizContent($bizContent);
        $response = $this->aopClientRequestExecute($request);
        $res = new AlipayOpenAuthTokenResponse($request, $response);
        return $res->parse();
    }

    /**
     * 使用SDK执行提交页面接口请求
     * @param object $request
     * @param string $token
     * @param string $appAuthToken
     * @return string $result
     */
    private function aopClientRequestExecute($request, $token = NULL, $appAuthToken = NULL)
    {
        $aop = new AopClient();
        $aop->gatewayUrl         = $this->gatewayUrl;
        $aop->appId              = $this->appId;
        $aop->signType           = $this->signType;
        $aop->rsaPrivateKey      = $this->privateKey;
        $aop->alipayrsaPublicKey = $this->alipayPublicKey;
        $aop->apiVersion         = "1.0";
        $aop->postCharset        = $this->charset;
        $aop->format             = $this->format;
        // 开启页面信息输出
        $aop->debugInfo          = true;
        $result = $aop->execute($request, $token, $appAuthToken);

        //打开后，将url形式请求报文写入log文件
        //Utils::logFile('alipay', __FILE__, 'response from alipay', var_export($result, true));
        return $result;
    }

    /**
     * @param string $code app_auth_code.
     * 获取用户授权令牌，用户ID.
     */
    public function getUserAuthToken($code)
    {
        Utils::logFile('alipay', __FILE__, 'user auth token', $code);
        $request = new AlipaySystemOauthTokenRequest();
        $request->setCode($code)
            ->setGrantType('authorization_code')
        ;
        $response = $this->aopClientRequestExecute($request);
        $res      = new AlipayUserAuthTokenResponse($request, $response);
        return $res->parse();
    }

    /**
     * 支付宝统一下单接口.
     * @param float $totalAmount.
     * @param string $userid.
     * @param string $partnerId.
     * @param string $orderId.
     */
    public function alipayTradeCreate($totalAmount, $userid, $partnerTitle, $orderId, $storeId)
    {
        $builder = new AlipayTradeCreateContentBuilder();
        $builder->setOutTradeNo($orderId)
            ->setTotalAmount($totalAmount)
            ->setSubject($partnerTitle)
            ->setBuyerId($userid)
            ->setTimeoutExpress('10m')
            ->setStoreId($storeId)
        ;
        if ($this->pId) {
            $extendParams = new ExtendParams();
            $extendParams->setSysServiceProviderId(Config::getPId());
            $extendParamsArr = $extendParams->getExtendParams();
            $builder->setExtendParams($extendParamsArr);
        }
        $content = $builder->getBizContent();
        if ($this->logFilePath) {
            $this->logFile('trade create order', $content);
        }
        $request = new AlipayTradeCreateRequest();
        $request->setBizContent($content);
        $res      = $this->aopClientRequestExecute($request, null, $this->authToken);
        $response = new AlipayTradeCreateResponse($request, $res);
        return $response->parse();
    }

    /**
     * 支付
     */
    public function alipayTradePay($authCode, $totalAmount, $partnerTitle, $orderId, $storeId, $terminalId = '')
    {
        $builder = new AlipayTradePayContentBuilder();
        $builder->setOutTradeNo($orderId)
            ->setTotalAmount($totalAmount)
            ->setSubject($partnerTitle)
            ->setTimeoutExpress('10m')
            ->setStoreId($storeId)
            ->setAuthCode($authCode)
            ->setScene('bar_code')
        ;
        if ($this->pId) {
            $extendParams = new ExtendParams();
            $extendParams->setSysServiceProviderId(Config::getPId());
            $extendParamsArr = $extendParams->getExtendParams();
            $builder->setExtendParams($extendParamsArr);
        }
        if ($terminalId) {
            $builder->setTerminalId($terminalId);
        }
        $content = $builder->getBizContent();
        if ($this->logFilePath) {
            $this->logFile('trade pay order', $content);
        }
        $request = new AlipayTradePayRequest();
        $request->setBizContent($content);
        $res      = $this->aopClientRequestExecute($request, null, $this->authToken);
        $response = new AlipayTradePayResponse($request, $res);
        return $response->parse();
    }

    /**
     * 交易预创建
     */
    public function alipayTradePreCreate($orderId, $totalAmount, $subject)
    {
        $builder = new AlipayTradePreCreateContentBuilder();
        $builder->setOutTradeNo($orderId)
            ->setTotalAmount($totalAmount)
            ->setSubject($subject)
             ->setTimeoutExpress('5m')
        ;
        if ($this->pId) {
            $extendParams = new ExtendParams();
            $extendParams->setSysServiceProviderId($this->pId);
            $extendParamsArr = $extendParams->getExtendParams();
            $builder->setExtendParams($extendParamsArr);
        }
        $content = $builder->getBizContent();
        if ($this->logFilePath) {
            $this->logFile('trade precreate order', $content);
        }
        $request = new AlipayTradePrecreateRequest();
        $request->setBizContent($content);
        $request->setNotifyUrl($this->preCreateNotifyUrl);
        $res      = $this->aopClientRequestExecute($request, null, $this->authToken);
        $response = new AlipayTradePreCreateResponse($request, $res);
        return $response->parse();
    }

    /**
     * 统一收单线下交易查询.
     */
    public function alipayTradeQuery($tradeNo, $outTradeNo)
    {
        $builder = new AlipayTradeQueryContentBuilder();
        $builder->setTradeNo($tradeNo);
        $builder->setOutTradeNo($outTradeNo);
        $content = $builder->getBizContent();
        if ($this->logFilePath) {
            $this->logFile('trade query order', $content);
        }
        $request = new AlipayTradeQueryRequest();
        $request->setBizContent($content);
        $res      = $this->aopClientRequestExecute($request, null, $this->authToken);
        $response = new AlipayTradeQueryResponse($request, $res);
        return $response->parse();
    }

    /**
     * 统一收单交易关闭接口
     */
    public function alipayTradeClose($tradeNo)
    {
        $builder = new AlipayTradeCloseContentBuilder();
        $builder->setTradeNo($tradeNo);
        $content = $builder->getBizContent();
        if ($this->logFilePath) {
            $this->logFile('trade close order', $content);
        }
        $request = new AlipayTradeCloseRequest();
        $request->setBizContent($content);
        $res      = $this->aopClientRequestExecute($request, null, $this->authToken);
        $response = new AlipayTradeCloseResponse($request, $res);
        return $response->parse();
    }

    /**
     * 账单下载，获取账单地址
     */
    public function getbilldownloadurl($date)
    {
        $builder = new AlipayDataserviceBillContentBuilder();
        $builder->setBillType('trade')
            ->setBillDate($date)
        ;
        $content = $builder->getBizContent();
        if ($this->logFilePath) {
            $this->logFile('download bill', $content);
        }
        $request = new AlipayDataDataserviceBillDownloadurlQueryRequest();
        $request->setBizContent($content);
        $res      = $this->aopClientRequestExecute($request, null, $this->authToken);
        $response = new AlipayDataserviceBillResponse($request, $res);
        $url      = urldecode($response->parse()->getBillDownloadUrl());
        if(!$url) {
            return ['status' => false, 'text' => $response->parse()->getSubMsg()];
        }
        return ['status' => true, 'text' => $url];
    }

    /**
     * 下载对账单.
     * /
    public function billDownload($date, $tokenid)
    {
        if(!$date || !$tokenid) {
            return array('status' => 'false', "msg" => 'DO NOT REVICE DATE OR TOKENID');
        }

        $alipay_data_file   = $this->alipay_data_file;
        $filename           = $date.'_'.$tokenid.'.zip';
        $tempfile           = 'temp.zip';

        if(file_exists($alipay_data_file.$filename)) {
            return array('status' => 'false', "msg" => 'DATA ALREADY EXISTS');
        }

        $result = $this->getbilldownloadurl($date);
        if ($result['status']) {
            $content = file_get_contents($result['text']);
            file_put_contents($alipay_file.$filename, $content);
        } else {
            return $result;
        }

        return array('status' => 'true','msg' => 'DATA SAVE SUCCEED','url' => $alipay_data_file.$filename);
    }

    /**
     * 退款
     */
    public function alipayTradeRefund($outTradeNo, $tradeNo, $price, $reason = "正常退款")
    {
        $builder = new AlipayRefundContentBuilder();
        $builder->setOutTradeNo($outTradeNo)
                ->setPrice($price)
                ->setReason($reason)
                ->setTradeNo($tradeNo)
        ;
        $content = $builder->getBizContent();
        if ($this->logFilePath) {
            $this->logFile('trade refund', $content);
        }
        $request = new AlipayTradeRefundRequest();
        $request->setBizContent($content);
        $res      = $this->aopClientRequestExecute($request, null, $this->authToken);
        $response = new AlipayTradeRefundResponse($request, $res);
        $res      = $response->parse();
        return (
            ($res->getCode() == '10000') &&
            ($res->getMsg() == 'Success')
        );
    }

    /**
     * 撤销
     */
    public function alipayTradeCancel($tradeNo, $outTradeNo)
    {
        $builder = new AlipayTradeCancelContentBuilder();
        $builder->setTradeNo($tradeNo);
        $builder->setOutTradeNo($outTradeNo);
        $content = $builder->getBizContent();
        if ($this->logFilePath) {
            $this->logFile('trade cancel order', $content);
        }
        $request = new AlipayTradeCancelRequest();
        $request->setBizContent($content);
        $res      = $this->aopClientRequestExecute($request, null, $this->authToken);
        $response = new AlipayTradeCancelResponse($request, $res);
        return $response->parse();
    }

    /**
     * 轮询查询订单支付结果
     */
	protected function loopQueryResult($tradeNo, $outTradeNo)
    {
		$queryResult   = NULL;
        $maxQueryRetry = $this->getMaxQueryRetry();
        $queryDuration = $this->getQueryDuration();
		for ($i = 1; $i < $maxQueryRetry; $i++) {
			try {
				sleep($queryDuration);
			} catch (\Exception $e) {
				print_r($e->getMessage());
			}
			$queryResponse = $this->alipayTradeQuery($tradeNo, $outTradeNo);
			if(!empty($queryResponse)) {
				if($this->stopQuery($queryResponse)) {
					return $queryResponse;
				}
				$queryResult = $queryResponse;
			}
		}
		return $queryResult;
	}

    private function getMaxQueryRetry()
    {
        $maxQueryRetry = $this->maxQueryRetry;
        return $maxQueryRetry ? $maxQueryRetry : 10;
    }

    private function getQueryDuration()
    {
        $queryDuration = $this->queryDuration;
        return ($queryDuration ? $queryDuration : 3);
    }

	/**
     * 判断是否停止查询
     */
	protected function stopQuery($response)
    {
		if('10000' == $response->getCode()) {
			if('TRADE_FINISHED' == $response->getTradeStatus()||
				'TRADE_SUCCESS' == $response->getTradeStatus()||
				'TRADE_CLOSED'  == $response->getTradeStatus()) {
				return true;
			}
		}
		return false;
	}

    public function logFile($title, $content)
    {
        $folder = $this->logFilePath;
        if (!file_exists($folder)) {
            return;
        }
        $filename = $folder . '/alipay_' . date("Y-m-d") . '.php';
        $filename = str_replace('//', '/', $filename);
        if (!file_exists($filename)) {
            file_put_contents($filename, '<?php exit(); ?>' . "\n");
        }
        file_put_contents(
            $filename,
            date("Y-m-d H:i:s") . "\n" . $title . "\n" . $content
            . "\n-------------------------------------\n",
            FILE_APPEND | LOCK_EX
        );
    }
}
