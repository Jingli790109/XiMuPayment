<?php

namespace XiMu\Alipay\Model\Response;

class AlipayTradeRefundResponse extends Response
{
    // trade_no 支付宝交易号
    private $tradeNo;

    // out_tarade_no 商户订单号
    private $outTradeNo;

    // buyer_logon_id 用户的登录id
    private $buyerLoginId;

    // fund_change 本次退款是否发生了资金变化
    private $fundChange;

    // refund_fee 退款总金额
    private $refundFee;

    // gmt_refund_pay 退款支付时间 交易使用的资金渠道
    private $gmtRefundPay;

    // refund_detail_item_list
    private $refundDetailItemList = array();

    // fund_channel 交易使用的资金渠道
    private $fundChannel;

    // store_name 交易在支付时候的门店名称
    private $storeName;

    // buyer_user_id 买家在支付宝的用户id
    private $buyerUserId;

    public function parse()
    {
        if ($this->response) {
            $code = $this->response->code;
            $this->setCode($code);
            $this->setMsg($this->response->msg);
            // 返回码大于10000，错误信息。
            if ($code > 10000) {
                $this->setSubCode($this->response->sub_code);
                $this->setSubMsg($this->response->sub_msg);
            } else {
                $this->setOutTradeNo($this->response->out_trade_no);
                $this->setTradeNo($this->response->trade_no);
                $this->setBuyerLogonId($this->response->buyer_logon_id);
                $this->setFundChange($this->response->fund_change);
                $this->setRefundFee($this->response->refund_fee);
                $this->setGmtRefundPay($this->response->gmt_fefund_pay);
                $this->setFundChannel($this->response->fund_channel);
                $this->setStoreName($this->response->store_name);
                $this->setBuyerUserId($this->response->buyer_user_id);
            }
        }
        return $this;
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

    public function setTradeNo($tradeNo)
    {
        $this->tradeNo = $tradeNo;
        return $this;
    }

    public function getTradeNo()
    {
        return $this->tradeNo;
    }

    public function setBuyerLogonId($buyerLogonId)
    {
        $this->buyerLogonId = $buyerLogonId;
        return $this;
    }

    public function getBuyerLogonId()
    {
        return $this->buyerLogonId;
    }

    public function setFundChange($fundChange)
    {
        $this->fundChange = $fundChange;
        return $this;
    }

    public function getFundChange()
    {
        return $this->fundChange;
    }

    public function setRefundFee($refundFee)
    {
        $this->refundFee = $refundFee;
        return $this;
    }

    public function getRefundFee()
    {
        return $this->refundFee;
    }

    public function setGmtRefundPay($gmtRefundPay)
    {
        $this->gmtRefundPay = $gmtRefundPay;
        return $this;
    }

    public function getGmtRefundPay()
    {
        return $this->gmtRefundPay;
    }

    public function setFundChannel($fundChannel)
    {
        $this->fundChannel = $fundChannel;
        return $this;
    }

    public function getFundChannel()
    {
        return $this->fundChannel;
    }

    public function setStoreName($storeName)
    {
        $this->storeName = $storeName;
        return $this;
    }

    public function getStoreName()
    {
        return $this->storeName;
    }

    public function setBuyerUserId($buyerUserId)
    {
        $this->buyerUserId = $buyerUserId;
        return $this;
    }

    public function getBuyerUserId()
    {
        return $this->buyerUserId;
    }
}
