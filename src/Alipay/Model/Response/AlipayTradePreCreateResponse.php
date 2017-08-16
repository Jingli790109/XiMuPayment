<?php

namespace XiMu\Alipay\Model\Response;

class AlipayTradePreCreateResponse extends Response
{
    // 商户的订单号
    private $outTradeNo;

    // 当前预下单请求生成的二维码码串，可以用二维码生成工具根据该码串值生成对应的二维码
    private $qrCode;

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
            $this->setQrCode($this->response->qr_code);
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

    public function setQrCode($qrCode)
    {
        $this->qrCode = $qrCode;
        return $this;
    }

    public function getQrCode()
    {
        return $this->qrCode;
    }
}
