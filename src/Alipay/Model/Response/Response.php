<?php

namespace XiMu\Alipay\Model\Response;

class Response
{
    private $RESPONSE_SUFFIX = '_response';

    protected $response;

    private $code;

    private $msg;

    private $subMsg;

    private $subCode;

    public function __construct($request, $response)
    {
        $apiName        = $request->getApiMethodName();
        $rootNodeName   = str_replace('.', '_', $apiName) . $this->RESPONSE_SUFFIX;
        $this->response = $response->$rootNodeName;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    protected function setMsg($msg)
    {
        $this->msg = $msg;
        return $this;
    }

    public function getMsg()
    {
        return $this->msg;
    }

    protected function setSubMsg($subMsg)
    {
        $this->subMsg = $subMsg;
        return $this;
    }

    public function getSubMsg()
    {
        return $this->subMsg;
    }

    protected function setSubCode($subCode)
    {
        $this->subCode = $subCode;
        return $this;
    }

    public function getSubCode()
    {
        return $this->subCode;
    }
}
