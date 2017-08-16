<?php

namespace XiMu\Alipay\Model\Response;

class AlipayTradeCreateResponse extends Response
{
    //out_trade_no 商户订单号
    private $outTradeNo;

    //trade_no 支付宝交易号
    private $tradeNo;

    public function parse()
    {
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
}
