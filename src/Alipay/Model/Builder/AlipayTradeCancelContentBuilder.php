<?php

namespace XiMu\Alipay\Model\Builder;

/**
 * https://doc.open.alipay.com/doc2/apiDetail.htm?spm=a219a.7629065.0.0.1hBqdQ&apiId=866&docType=4
 * 统一收单交易撤销接口.
 */
class AlipayTradeCancelContentBuilder extends ContentBuilder
{
     //out_trade_no 订单支付时传入的商户订单号,和支付宝交易号不能同时为空。 trade_no,out_trade_no如果同时存在优先取trade_no
    private $outTradeNo;

    //trade_no 支付宝交易号，和商户订单号不能同时为空
    private $tradeNo;

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

    public function setTradeNo($tradeNo)
    {
        $this->tradeNo = $tradeNo;
        $this->bizContentarr['trade_no'] = $tradeNo;
        return $this;
    }

    public function getTradeNo()
    {
        return $this->tradeNo;
    }
}
