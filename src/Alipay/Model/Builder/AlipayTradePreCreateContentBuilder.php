<?php

namespace XiMu\Alipay\Model\Builder;

/**
 * https://docs.open.alipay.com/api_1/alipay.trade.precreate
 * 统一收单线下交易预创建
 */
class AlipayTradePreCreateContentBuilder extends ContentBuilder
{
    // out_trade_no 商户订单号64个字符以内
    private $outTradeNo;

    // total_amount 订单总金额，单位为元，精确到小数点后两位
    private $totalAmount;

    // subject 订单标题
    private $subject;

    // seller_id 卖家支付宝用户ID
    private $sellerId;

    // discountable_amount 可打折金额. 参与优惠计算的金额，单位为元，精确到小数点后两位
    private $discountableAmount;

    // body 对交易或商品的描述
    private $body;

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

    //timeout_express 该笔订单允许的最晚付款时间，逾期将关闭交易。
    private $timeoutExpress;

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
}
