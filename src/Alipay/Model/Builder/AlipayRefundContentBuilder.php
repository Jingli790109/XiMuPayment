<?php

namespace XiMu\Alipay\Model\Builder;

/**
 * https://doc.open.alipay.com/doc2/detail.htm?treeId=193&articleId=105193&docType=1#s7
 */
class AlipayRefundContentBuilder extends ContentBuilder
{

    private $outTradeNo;

    private $tradeNo;

    private $price;

    private $reason;

    public function setOutTradeNo($outTradeNo)
    {
        $this->outTradeNo = $outTradeNo;
        $this->bizContentarr['out_trade_no'] = $outTradeNo;
        return $this;
    }

    public function setTradeNo($tradeNo)
    {
        $this->tradeNo = $tradeNo;
        $this->bizContentarr['trade_no'] = $tradeNo;
        return $this;
    }

    public function setPrice($price)
    {
        $this->price = $price;
        $this->bizContentarr['refund_amount'] = $price;
        return $this;
    }

    public function setReason($reason)
    {
        $this->reason = $reason;
        $this->bizContentarr['reason'] = $reason;
        return $this;
    }

    public function getoutTradeNo()
    {
        return $this->outTradeNo;
    }

    public function gettradeNo()
    {
        return $this->tradeNo;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getReason($reason)
    {
        return $this->reason;
    }
}
