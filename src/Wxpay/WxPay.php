<?php

namespace XiMu\Wxpay;

use XiMu\Wxpay\Common\WxPayConfig;
use XiMu\Wxpay\Common\WxPayApi;
use XiMu\Wxpay\Model\WxPayUnifiedOrder;
use XiMu\Wxpay\Model\WxPayGoodsDetail;
use XiMu\Wxpay\Model\WxPayJsApiPay;
use XiMu\Wxpay\Model\WxPayOrderQuery;
use XiMu\Wxpay\Model\WxPayCloseOrder;
use XiMu\Wxpay\Model\WxPayMicroPay;
use XiMu\Wxpay\Model\WxPayReverse;
use XiMu\Wxpay\Model\WxPayRefund;
use XiMu\Wxpay\Model\WxPayDownloadBill;
use XinYing\ConfigBundle\Component\Utils;

/**
 *
 * JSAPI支付实现类
 * 该类实现了从微信公众平台获取code、通过code获取openid和access_token、
 * 生成jsapi支付js接口所需的参数、生成获取共享收货地址所需的参数
 *
 * 该类是微信支付提供的样例程序，商户可根据自己的需求修改，或者使用lib中的api自行开发
 *
 * @author widy
 */
class WxPay
{
    private $maxQueryRetry = 6;
    private $queryDuration = 5;
    private $data = null;
    private $appId;
    private $secret;
    private $key;
    private $salt         = 'XiMuTech';
    private $notifyUrl    = 'http://xypay.andmall.com';
    private $wx_data_file = '/share/data/weixin_data/';

    public function __construct()
    {
        $this->appId = WxPayConfig::APPID;
        //$this->notifyUrl = WxPayConfig::NotifyUrl;
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

    public function getUserAuthUrl($storeid)
    {
        $redirectUrl = $this->notifyUrl . '/payment/wxpay/user/auth/' . $storeid;
        $state       = $this->generateUserAuthState();
        $url         = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' .
            $this->appId . '&redirect_uri=' . urlencode($redirectUrl) .
            '&response_type=code&scope=snsapi_base&state=' . $state . '#wechat_redirect'
        ;
        return $url;
    }

    /**
     *
     * 通过跳转获取用户的openid，跳转流程如下：
     * 1、设置自己需要调回的url及其其他参数，跳转到微信服务器https://open.weixin.qq.com/connect/oauth2/authorize
     * 2、微信服务处理完成之后会跳转回用户redirect_uri地址，此时会带上一些参数，如：code
     *
     * @return 用户的openid
     */
    public function getOpenid($code)
    {
        //获取code码，以获取openid
        $openid = $this->getOpenidFromMp($code);
        return $openid;
    }

    /**
     * 微信统一下单
     */
    public function tradeCreate($payid, $openId, $store, $total, $subMchId, $tradeType = 'JSAPI')
    {
        $body  = new WxPayGoodsDetail();
        $body->setCostPrice($total * 100);
        $body->setGoodsId($store['id']);
        $body->setGoodsName($store['title']);
        $body->setQuantity(1);
        $body->setPrice($total * 100);
        $detail = $body->getContent();

        $input = new WxPayUnifiedOrder();
        $input->SetBody($store['title']);
        $input->SetDetail($detail);
        $input->SetAttach($store['id']);
        $input->SetOut_trade_no($payid);
        $input->SetTotal_fee($total * 100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", (time() + 600)));
        $input->SetGoods_tag('test');
        $input->SetNotify_url(WxPayConfig::NotifyUrl);
        $input->SetTrade_type($tradeType);
        if ($tradeType == 'JSAPI') {
            $input->SetOpenid($openId);
        }
        if ($tradeType == 'NATIVE') {
            $input->SetProduct_id($payid);
        }
        $input->SetSubMchId($subMchId);
        $input->SetDevice_info($store['id']);
        Utils::logFile('weixinpay', __FILE__, 'create trade ', serialize($input));
        $result = WxPayApi::unifiedOrder($input);
        Utils::logFile('weixinpay', __FILE__, 'create trade result: ', serialize($result));
        return $result;
    }

    /**
     *
     * 获取jsapi支付的参数
     * @param array $UnifiedOrderResult 统一支付接口返回的数据
     * @throws WxPayException
     *
     * @return json数据，可直接填入js函数作为参数
     */
    public function getJsApiParameters($UnifiedOrderResult)
    {
        if(!array_key_exists("appid", $UnifiedOrderResult)
        || !array_key_exists("prepay_id", $UnifiedOrderResult)
        || $UnifiedOrderResult['prepay_id'] == "")
        {
            throw new WxPayException("参数错误");
        }
        $jsapi = new WxPayJsApiPay();
        $jsapi->SetAppid($UnifiedOrderResult["appid"]);
        $timeStamp = time();
        $jsapi->SetTimeStamp("$timeStamp");
        $jsapi->SetNonceStr(WxPayApi::getNonceStr());
        $jsapi->SetPackage("prepay_id=" . $UnifiedOrderResult['prepay_id']);
        $jsapi->SetSignType("MD5");
        $jsapi->SetPaySign($jsapi->MakeSign());
        return $jsapi->GetValues();
    }

    /**
     * 查询
     */
    public function tradeQuery($outTradeNo, $subMchId)
    {
        $input = new WxPayOrderQuery();
        $input->SetOut_trade_no($outTradeNo);
        $input->SetSubMchId($subMchId);
        $result = WxPayApi::orderQuery($input);
        Utils::logFile('weixinpay', __FILE__, 'query trade ', serialize($result));
        return $result;
    }

    /**
     * 关闭
     */
    public function tradeClose($outTradeNo, $subMchId)
    {
        $input = new WxPayCloseOrder();
        $input->SetOut_trade_no($outTradeNo);
        $input->SetSubMchId($subMchId);
        $result = WxPayApi::closeOrder($input);
        Utils::logFile('weixinpay', __FILE__, 'close trade ', serialize($result));
        return $result;
    }

    /**
     * 条码支付下单
     */
    public function tradeMicroPay($payid, $authCode, $store, $total, $subMchId, $deviceId = '')
    {
        $body  = new WxPayGoodsDetail();
        $body->setCostPrice($total * 100);
        $body->setGoodsId($store['id']);
        $body->setGoodsName($store['title']);
        $body->setQuantity(1);
        $body->setPrice($total * 100);
        $detail = $body->getContent();

        $input = new WxPayMicroPay();
        $input->SetBody($store['title']);
        $input->SetDetail($detail);
        $input->SetAttach($store['id']);
        $input->SetOut_trade_no($payid);
        $input->SetTotal_fee($total * 100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", (time() + 600)));
        $input->SetGoods_tag('test');
        $input->SetSubMchId($subMchId);
        $input->SetDevice_info($store['id']);
        $input->SetAuth_code($authCode);
        Utils::logFile('weixinpay', __FILE__, 'create trade micropay', serialize($input));
        $result = WxPayApi::micropay($input);
        Utils::logFile('weixinpay', __FILE__, 'create trade micropay result: ', serialize($result));
        return $result;
    }

    /**
     * 账单地址
     */
    public function billDownloadbyurl($date, $subMchId)
    {
        $input = new WxPayDownloadBill();
        $input->SetBill_date($date);
        $input->SetSubMchId($subMchId);
        $input->SetBill_type('ALL');
        $contents =  WxPayApi::downloadBill($input);
        return $contents;
    }

    /**
     * 下载对账单
     */
    public function downloadBill($date, $tokenid, $subMchId)
    {
        $downloadPath = $this->wx_data_file;
        $filename     = 'wxpay_'.$date.'_'.$tokenid.'.csv';
        $durl         = $downloadPath.$filename;

        if(!$date || !$tokenid) {
             return array('status' => 'false','msg' => 'DO NOT RECEIVE DATE OR TOKENID',);
        }
        $contents =  $this->billDownloadbyurl($date,$subMchId);
        $string = '';
        $arr = explode('\n',$contents);
        foreach($arr as $k=>$v){
            $data = explode('`',$v);
            foreach($data as $kk => $vv) {
                $string .= $vv;
            }
        }

        //Start Encrypt
        // $AopEncrypt = new AopEncrypt();
        // $string = $AopEncrypt->encrypt($string);
        //End Encrypt

        file_put_contents($durl,$string);
        return array('status' => 'true','msg' => 'DATA SAVE SUCCEED','url' => $durl);
    }

    /**
	 * 撤销订单，如果失败会重复调用10次
	 * @param string $outTradeNo
	 * @param 调用深度 $depth
	 */
	public function cancelOrder($outTradeNo, $subMchId, $depth = 0)
	{
		if($depth > 5){
			return false;
		}
		$clostOrder = new WxPayReverse();
		$clostOrder->SetOut_trade_no($outTradeNo);
        $clostOrder->SetSub_Mch_id($subMchId);
        Utils::logFile('weixinpay', __FILE__, 'cancel trade', serialize($clostOrder));
		$result = WxPayApi::reverse($clostOrder);
		Utils::logFile('weixinpay', __FILE__, 'cancel trade result: ', serialize($result));
		//接口调用失败
		if($result['return_code'] != 'SUCCESS') {
			return false;
		}

		//如果结果为success且不需要重新调用撤销，则表示撤销成功
		if($result['result_code'] == 'SUCCESS'
			&& $result['recall'] == 'N') {
			return true;
		} else if($result['recall'] == 'Y') {
			return $this->cancelOrder($outTradeNo, $subMchId, ++$depth);
		}
		return false;
	}

    /**
     *
     * 通过code从工作平台获取openid机器access_token
     * @param string $code 微信跳转回来带上的code
     * @return openid
     */
    public function getOpenidFromMp($code)
    {
        $url = $this->__CreateOauthUrlForOpenid($code);
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if(WxPayConfig::CURL_PROXY_HOST != "0.0.0.0"
            && WxPayConfig::CURL_PROXY_PORT != 0) {
            curl_setopt($ch,CURLOPT_PROXY, WxPayConfig::CURL_PROXY_HOST);
            curl_setopt($ch,CURLOPT_PROXYPORT, WxPayConfig::CURL_PROXY_PORT);
        }
        //运行curl，结果以jason形式返回
        $res = curl_exec($ch);
        curl_close($ch);
        //取出openid
        $data = json_decode($res, true);
        $this->data = $data;
        $openid = $data['openid'];
        return $openid;
    }

    /**
     *
     * 拼接签名字符串
     * @param array $urlObj
     * @return 返回已经拼接好的字符串
     */
    private function toUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign"){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 获取地址js参数
     * @return 获取共享收货地址js函数需要的参数，json格式可以直接做参数使用
     */
    public function getEditAddressParameters()
    {
        $getData = $this->data;
        $data = array();
        $data["appid"] = WxPayConfig::APPID;
        $data["url"] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $time = time();
        $data["timestamp"] = "$time";
        $data["noncestr"] = "1234568";
        $data["accesstoken"] = $getData["access_token"];
        ksort($data);
        $params = $this->ToUrlParams($data);
        $addrSign = sha1($params);

        $afterData = array(
            "addrSign" => $addrSign,
            "signType" => "sha1",
            "scope" => "jsapi_address",
            "appId" => WxPayConfig::APPID,
            "timeStamp" => $data["timestamp"],
            "nonceStr" => $data["noncestr"]
        );
        $parameters = json_encode($afterData);
        return $parameters;
    }

    /**
     * 构造获取code的url连接
     * @param string $redirectUrl 微信服务器回跳的url，需要url编码
     * @return 返回构造好的url
     */
    private function __CreateOauthUrlForCode($redirectUrl)
    {
        $urlObj["appid"] = WxPayConfig::APPID;
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_base";
        $urlObj["state"] = "STATE"."#wechat_redirect";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
    }

    /**
     * 构造获取open和access_toke的url地址
     * @param string $code，微信跳转带回的code
     * @return 请求的url
     */
    private function __CreateOauthUrlForOpenid($code)
    {
        $urlObj["appid"] = WxPayConfig::APPID;
        $urlObj["secret"] = WxPayConfig::APPSECRET;
        $urlObj["code"] = $code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString = $this->ToUrlParams ($urlObj);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
    }

    public function SendWechatPriceMessage($price,$openid)
    {
        $data["touser"] = $openid;
        $data["template_id"] = "kx--t_QBV4DSR2NTA6ehEYZi9G9zlISR9oyIHCL_k5o";
        $data["url"] = "#";
        $data["data"] = array(
            "first" => array(
                "value" => "您有一笔资金到账",
                "color" => "#173177",
            ),
            "keyword1" => array(
                "value" => "20165843756832854",
                "color" => "#173177",
            ),
            "keyword2" => array(
                "value" => "￥105.00元",
                "color" => "#173177",
            ),
            "keyword3" => array(
                "value" => "支付宝",
                "color" => "#173177",
            ),
            "remark" => array(
                "value" => "如对该资金有疑问请尽快与客服联系",
                "color" => "#173177",
            ),
        );
        if($this->SendWechatMessage($data)){
          return true;
        }
    }
    /*
     *  PreSendWechatMessage(string $openid,array $array)
     *  $array[touser][url][template_id][data]
     */
    private function SendWechatMessage($data)
    {
        $token =  $this->GetAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$token;
        $data = json_encode($data,true);
        $return = $this->curlpost($url,$data);
        var_dump($return);exit();
    }


    private function curlpost($url,$data)
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    private function curlget($url)
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * 获取access token
     */
    private function GetAccessToken()
    {
        $secret = WxPayConfig::APPSECRET;
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appId."&secret=".$secret;
        $curl = $this->curlget($url);
        $curl = json_decode($curl,true);
        return $curl["access_token"];
    }

    /**
     * 退款
     */
    public function tradeRefund($transaction_id, $out_trade_no, $out_refund_no, $total, $refundfee)
    {
        $total     = $total * 100;
        $refundfee = $refundfee * 100;
        $refund    = new WxPayRefund();
        $refund->SetTransaction_id($transaction_id);
        $refund->SetOut_trade_no($out_trade_no);
        $refund->SetOut_refund_no($out_refund_no);
        $refund->SetTotal_fee($total);
        $refund->SetRefund_fee($refundfee);
        $refund->SetOp_user_id(WxPayConfig::MCHID);
        // TODO:: 微信退款，返回码解析。
        return WxPayApi::refund($refund);
    }

    /**
     * 轮循
     */
    public function loopQueryOrder($payId, $subMchId)
    {
        $queryResult   = NULL;
        $maxQueryRetry = $this->maxQueryRetry;
        $queryDuration = $this->queryDuration;
		for ($i = 1; $i < $maxQueryRetry; $i++) {
			try {
				sleep($queryDuration);
			} catch (\Exception $e) {
				print_r($e->getMessage());
			}
			$queryResponse = $this->tradeQuery($payId, $subMchId);
			if(!empty($queryResponse)) {
				if($this->stopQuery($queryResponse)) {
					return $queryResponse;
				}
				$queryResult = $queryResponse;
			}
		}
		return $queryResult;
    }

    /**
     * 停止查询
     */
    private function stopQuery($result)
    {
        if($result['return_code'] == 'SUCCESS'
			&& $result['result_code'] == 'SUCCESS')
		{
			//支付成功
			if($result['trade_state'] == 'SUCCESS') {
			   	return true;
			}
		}
        //如果返回错误码为“此交易订单号不存在”则直接认定失败
        if(isset($result['err_code']) && ($result['err_code'] == 'ORDERNOTEXIST'))
		{
			return true;
		}
		return false;
    }
}
