<?php

namespace XiMu\Wxpay\Common;

use XiMu\Wxpay\Model\WxPayNotifyReply;

use XinYing\ConfigBundle\Component\Utils;

/**
 *
 * 回调基础类
 * @author widyhu
 */
class WxPayNotify extends WxPayNotifyReply
{
	private $content;

	// appid 微信分配的公众账号ID
	private $appid;

	// mch_id 微信支付分配的商户号
	private $mchId;

	// sub_appid 微信分配的子商户公众账号ID
	private $subAppid;

	// sub_mch_id 微信支付分配的子商户号
	private $subMchId;

	// device_info 微信支付分配的终端设备号
	private $deviceInfo;

	// openid 用户在商户appid下的唯一标识
	private $openid;

	// trade_type JSAPI、NATIVE、APP
	private $tradeType;

	// bank_type 付款银行
	private $bankType;

	// total_fee 订单总金额，单位为分
	private $totalFee;

	// cash_fee 现金支付金额
	private $cashFee;

	// coupon_fee 代金券或立减优惠金额<=订单总金额，订单总金额-代金券或立减优惠金额=现金支付金额
	private $couponFee;

	// transactionId 微信支付订单号
	private $transactionId;

	// out_trade_no 商户系统的订单号，与请求一致。
	private $outTradeNo;

	// attach 商家数据包，原样返回
	private $attach;

	// time_end 支付完成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。
	private $timeEnd;

	// result_code 业务结果 SUCCESS/FAIL
	private $resultCode;

	private $em;

	private $redis;

	private $order;

	public function setEntityManager($em)
	{
		$this->em = $em;
		return $this;
	}

	public function setRedisService($redis)
	{
		$this->redis = $redis;
		return $this;
	}

	public function setOrder($order)
	{
		$this->order = $order;
		return $this;
	}

	public function setAppId($appId)
	{
		$this->appid = $appId;
		return $this;
	}

	public function getAppId()
	{
		return $this->appid;
	}

	public function setMchId($mchId)
	{
		$this->mchId = $mchId;
		return $this;
	}

	public function getMchId()
	{
		return $this->mchId;
	}

	public function setSubAppid($subAppid)
	{
		$this->subAppid = $subAppid;
		return $this;
	}

	public function getSubAppid()
	{
		return $this->subAppid;
	}

	public function setOpenid($openid)
	{
		$this->openid = $openid;
		return $this;
	}

	public function getOpenid()
	{
		return $this->openid;
	}

	public function setTradeType($tradeType)
	{
		$this->tradeType = $tradeType;
		return $this;
	}

	public function getTradeType()
	{
		return $this->tradeType;
	}

	public function setTotalFee($totalFee)
	{
		$this->totalFee = $totalFee;
		return $this;
	}

	public function getTotalFee()
	{
		return $this->totalFee;
	}

	public function setBankType($bankType)
	{
		$this->bankType = $bankType;
		return $this;
	}

	public function getBankType()
	{
		return $this->bankType;
	}

	public function setCashFee($cashFee)
	{
		$this->cashFee = $cashFee;
		return $this;
	}

	public function getCashFee()
	{
		return $this->cashFee;
	}

	public function setCouponFee($couponFee)
	{
		$this->couponFee = $couponFee;
		return $this;
	}

	public function getCouponFee()
	{
		return $this->couponFee;
	}

	public function setTransactionId($transactionId)
	{
		$this->transactionId = $transactionId;
		return $this;
	}

	public function getTransactionId()
	{
		return $this->transactionId;
	}

	public function setOutTradeNo($outTradeNo)
	{
		$this->outTradeNo = $outTradeNo;
		return $this;
	}

	public function getOutTradeNo()
	{
		return $this->outTradeNo;
	}

	public function setTimeEnd($timeEnd)
	{
		$this->timeEnd = $timeEnd;
		return $this;
	}

	public function getTimeEnd()
	{
		return $this->timeEnd;
	}

	public function setAttach($attach)
	{
		$this->attach = $attach;
		return $this;
	}

	public function getAttach()
	{
		return $this->attach;
	}

	public function setResultCode($resultCode)
	{
		$this->resultCode = $resultCode;
		return $this;
	}

	public function getResultCode()
	{
		return $this->resultCode;
	}

	public function setContent($content)
	{
		$this->content = $content;
		return $this;
	}

	/**
	 *
	 * 回调入口
	 * @param bool $needSign  是否需要签名输出
	 */
	final public function handle($needSign = true)
	{
		$msg = "OK";
		//当返回false的时候，表示notify中调用NotifyCallBack回调失败获取签名校验失败，此时直接回复失败
		$result = WxPayApi::notify(array($this, 'notifyCallBack'), $this->content, $msg);
		if($result == false){
			$this->SetReturn_code("FAIL");
			$this->SetReturn_msg($msg);
			$this->replyNotify(false);
			return;
		} else {
			//该分支在成功回调到NotifyCallBack方法，处理完成之后流程
			$this->SetReturn_code("SUCCESS");
			$this->SetReturn_msg("OK");
		}
		$this->replyNotify($needSign);
	}

	/**
	 *
	 * 回调方法入口，子类可重写该方法
	 * 注意：
	 * 1、微信回调超时时间为2s，建议用户使用异步处理流程，确认成功之后立刻回复微信服务器
	 * 2、微信服务器在调用失败或者接到回包为非确认包的时候，会发起重试，需确保你的回调是可以重入
	 * @param array $data 回调解释出的参数
	 * @param string $msg 如果回调处理失败，可以将错误信息输出到该方法
	 * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
	 */
	public function notifyProcess($data, &$msg)
	{
		//TODO 用户基础该类之后需要重写该方法，成功的时候返回true，失败返回false
		/*$this->setAppId($data['appid'])
			->setAttach($data['attach'])
			->setCashFee($data['cash_fee'])
			->setMchId($data['mch_id'])
			->setOutTradeNo($data['out_trade_no'])
			->setTransactionId($data['transaction_id'])
			->setTotalFee($data['total_fee'])
			->setTradeType($data['trade_type'])
			->setTimeEnd($data['time_end'])
			->setResultCode($data['result_code'])
		;*/
        Utils::logFile('weixinpay', __FILE__, 'notify trade ', serialize($data));

		if ($data['result_code'] == 'SUCCESS') {
			$this->order->notifyWxOrderPay($data);
		}

		return ($data['result_code'] == 'SUCCESS');
	}

	/**
	 *
	 * notify回调方法，该方法中需要赋值需要输出的参数,不可重写
	 * @param array $data
	 * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
	 */
	final public function notifyCallBack($data)
	{
		$msg = "OK";
		$result = $this->notifyProcess($data, $msg);
		if($result == true){
			$this->SetReturn_code("SUCCESS");
			$this->SetReturn_msg("OK");
		} else {
			$this->SetReturn_code("FAIL");
			$this->SetReturn_msg($msg);
		}
		return $result;
	}

	/**
	 *
	 * 回复通知
	 * @param bool $needSign 是否需要签名输出
	 */
	final private function replyNotify($needSign = true)
	{
		//如果需要签名
		if($needSign == true &&
			$this->GetReturn_code() == "SUCCESS")
		{
			$this->SetSign();
		}
		WxpayApi::replyNotify($this->ToXml());
	}
}
