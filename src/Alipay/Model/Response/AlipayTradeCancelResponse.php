<?php

namespace XiMu\Alipay\Model\Response;

class AlipayTradeCancelResponse extends Response
{
    //out_trade_no
    private $outTradeNo;

    //trade_no
    private $tradeNo;

    //retry_flag
    private $retryFlag;

    //action
    private $action;

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
                if (isset($this->response->trade_no)) {
                    $this->setTradeNo($this->response->trade_no);
                }
                $this->setOutTradeNo($this->response->out_trade_no);
                if (isset($this->response->action)) {
                    $this->setAction($this->response->action);
                }
                $this->setRetryFlag($this->response->retry_flag);
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

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setRetryFlag($retryFlag)
    {
        $this->retryFlag = $retryFlag;
        return $this;
    }

    public function getRetryFlag()
    {
        return $this->retryFlag;
    }
}
