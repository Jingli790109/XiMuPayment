<?php

namespace XiMu\Alipay\Model\Builder;

/**
 * https://doc.open.alipay.com/docs/api.htm?apiId=1046&docType=4
 * 支付宝统一收单交易创建接口
 */
class AlipayTradeCreateContentBuilder extends ContentBuilder
{
    //out_trade_no 商户订单号,64个字符以内、只能包含字母、数字、下划线；需保证在商户端不重复
    private $outTradeNo;

    //seller_id 卖家支付宝用户ID。 如果该值为空，则默认为商户签约账号对应的支付宝用户ID
    private $sellerId = '';

    //total_amount 订单总金额，单位为元，精确到小数点后两位，取值范围[0.01,100000000] 如果同时传入了【打折金额】，【不可打折金额】，【订单总金额】三者，则必须满足如下条件：【订单总金额】=【打折金额】+【不可打折金额】
    private $totalAmount;

    //discountable_amount 可打折金额. 参与优惠计算的金额，单位为元，精确到小数点后两位，取值范围[0.01,100000000] 如果该值未传入，但传入了【订单总金额】，【不可打折金额】则该值默认为【订单总金额】-【不可打折金额】
    private $discountableAmount;

    //undiscountable_amount 不可打折金额. 不参与优惠计算的金额，单位为元，精确到小数点后两位，取值范围[0.01,100000000] 如果该值未传入，但传入了【订单总金额】,【打折金额】，则该值默认为【订单总金额】-【打折金额】
    private $undiscountableAmount;

    //subject 订单标题
    private $subject;

    //body 对交易或商品的描述
    private $body = '';

    //buyer_id 买家的支付宝唯一用户号（2088开头的16位纯数字）,和buyer_logon_id不能同时为空
    private $buyerId;

    //goods_detail 订单包含的商品列表信息.Json格式. 其它说明详见：“商品明细说明”
    private $goodsDetail = array();

    //operator_id 商户操作员编号
    private $operatorId;

    //store_id 商户门店编号
    private $storeId = '';

    //terminal_id 商户机具终端编号
    private $terminalId = '';

    //extend_params 只有一个参数sys_service_provider_id
    private $extendParams;

    //timeout_express 该笔订单允许的最晚付款时间，逾期将关闭交易。取值范围：1m～15d。m-分钟，h-小时，d-天，1c-当天（1c-当天的情况下，无论交易何时创建，都在0点关闭）。 该参数数值不接受小数点， 如 1.5h，可转换为 90m。
    private $timeoutExpress;

    //描述分账信息，json格式
    private $royaltyInfo;

    //alipay_store_id 支付宝的店铺编号
    private $alipayStoreId;

    //merchant_order_no 商户原始订单号，最大长度限制32位
    private $merchant_order_no;

    public function setOutTradeNo($outTradeNo)
    {
        $this->outTradeNo = $outTradeNo;
        $this->bizContentarr['out_trade_no'] = $outTradeNo;
        return $this;
    }

    public function getOutTradeNo()
    {
        return $this->outTradeNo;
    }

    public function setSellerId($sellerId)
    {
        $this->sellerId = $sellerId;
        $this->bizContentarr['seller_id'] = $sellerId;
        return $this;
    }

    public function getSellerId()
    {
        return $this->sellerId;
    }

    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
        $this->bizContentarr['total_amount'] = $totalAmount;
        return $this;
    }

    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    public function setDiscountableAmount($discountableAmount)
    {
        $this->discountableAmount = $discountableAmount;
        $this->bizContentarr['discountable_amount'] = $discountableAmount;
        return $this;
    }

    public function getDiscountableAmount()
    {
        return $this->discountableAmount;
    }

    public function setUndiscountableAmount($undiscountableAmount)
    {
        $this->undiscountableAmount = $undiscountableAmount;
        $this->bizContentarr['undiscountable_amount'] = $undiscountableAmount;
        return $this;
    }

    public function getUndiscountableAmount()
    {
        return $this->undiscountableAmount;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        $this->bizContentarr['subject'] = $subject;
        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setBody($body)
    {
        $this->body = $body;
        $this->bizContentarr['body'] = $body;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBuyerId($buyerId)
    {
        $this->buyerId = $buyerId;
        $this->bizContentarr['buyer_id'] = $buyerId;
        return $this;
    }

    public function getBuyerId()
    {
        return $this->buyerId;
    }

    public function setGoodsDetail($goodsDetail)
    {
        $this->goodsDetail = $goodsDetail;
        $this->bizContentarr['goods_detail'] = $goodsDetail;
        return $this;
    }

    public function getGoodsDetail()
    {
        return $this->goodsDetail;
    }

    public function setOperatorId($operatorId)
    {
        $this->operatorId = $operatorId;
        $this->bizContentarr['operator_id'] = $operatorId;
        return $this;
    }

    public function getOperatorId()
    {
        return $this->operatorId;
    }

    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
        $this->bizContentarr['store_id'] = $storeId;
        return $this;
    }

    public function getStoreId()
    {
        return $this->storeId;
    }

    public function setTerminalId($terminalId)
    {
        $this->terminalId = $terminalId;
        $this->bizContentarr['terminal_id'] = $terminalId;
        return $this;
    }

    public function getTerminalId()
    {
        return $this->terminalId;
    }

    public function setExtendParams($extendParams)
    {
        $this->extendParams = $extendParams;
        $this->bizContentarr['extend_params'] = $extendParams;
        return $this;
    }

    public function getExtendParams()
    {
        return $this->extendParams;
    }

    public function setTimeoutExpress($timeoutExpress)
    {
        $this->timeoutExpress = $timeoutExpress;
        $this->bizContentarr['timeout_express'] = $timeoutExpress;
        return $this;
    }

    public function getTimeoutExpress()
    {
        return $this->timeoutExpress;
    }

    public function setAlipayStoreId($alipayStoreId)
    {
        $this->alipayStoreId = $alipayStoreId;
        $this->bizContentarr['alipay_store_id'] = $alipayStoreId;
        return $this;
    }

    public function getAlipayStoreId()
    {
        return $this->alipayStoreId;
    }

    public function setMerchantOrderNo($merchantOrderNo)
    {
        $this->merchantOrderNo = $merchantOrderNo;
        $this->bizContentarr['merchant_order_no'] = $merchantOrderNo;
        return $this;
    }

    public function getMerchantOrderNo()
    {
        return $this->merchantOrderNo;
    }
}
