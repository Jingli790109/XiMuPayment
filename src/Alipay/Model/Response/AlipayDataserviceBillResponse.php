<?php

namespace XiMu\Alipay\Model\Response;

class AlipayDataserviceBillResponse extends Response
{
    //bill_download_url
    private $billDownloadUrl;
    public $subMsg;

    public function setBillDownloadUrl($billDownloadUrl)
    {
        $this->billDownloadUrl = $billDownloadUrl;
        return $this;
    }

    public function getBillDownloadUrl()
    {
        return $this->billDownloadUrl;
    }

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
                $this->setBillDownloadUrl($this->response->bill_download_url);
            }
        }
        return $this;
    }
}
