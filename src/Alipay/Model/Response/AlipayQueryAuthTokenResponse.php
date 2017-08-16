<?php

namespace XiMu\Alipay\Model\Response;

class AlipayQueryAuthTokenResponse extends Response
{
    //user_id 授权商户的ID
    private $userId;

    //auth_app_id 授权商户的AppId
    private $authAppId;

    //auth_methods 当前app_auth_token的授权接口列表
    private $authMethods;

    //auth_start
    private $authStart;

    //auth_end
    private $authEnd;

    //status
    private $status;

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
                $this->setUserId($this->response->user_id);
                $this->setAuthMethods($this->response->auth_methods);
                $this->setAuthAppId($this->response->auth_app_id);
                //$this->setAuthStart($this->response->auth_start);
                //$this->setAuthEnd($this->response->auth_end);
                $this->setStatus($this->response->status);
            }
        }
        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setAuthAppId($authAppId)
    {
        $this->authAppId = $authAppId;
        return $this;
    }

    public function getAuthAppId()
    {
        return $this->authAppId;
    }

    public function setAuthMethods($authMethods)
    {
        $this->authMethods = $authMethods;
        return $this;
    }

    public function getAuthMethods()
    {
        return $this->authMethods;
    }

    public function setAuthStart($authStart)
    {
        $this->authStart = $authStart;
        return $this;
    }

    public function getAuthStart()
    {
        return $this->authStart;
    }

    public function setAuthEnd($authEnd)
    {
        $this->authEnd = $authEnd;
        return $this;
    }

    public function getAuthEnd()
    {
        return $this->authEnd;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
