<?php

namespace XiMu\Alipay\Model\Response;

/**
 * 获取商户授权令牌返回。
 */
class AlipayOpenAuthTokenResponse extends Response
{
    //app_auth_token 商户授权令牌
    private $appAuthToken;

    //user_id 授权商户的ID
    private $userId;

    //auth_app_id 授权商户的AppId
    private $authAppId;

    //expires_in 令牌有效期 交换令牌的有效期，单位秒，换算成天的话为365天
    private $expiresIn;

    //re_expires_in 刷新令牌有效期 刷新令牌有效期，单位秒，换算成天的话为372天
    private $reExpiresIn;

    //app_refresh_token 刷新令牌时使用 刷新令牌后，老的app_auth_token从刷新开始24小时内可继续使用，请及时替换为最新token
    private $appRefreshToken;

    private $request;

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
                $this->setAppAuthToken($this->response->app_auth_token);
                $this->setAppRefreshToken($this->response->app_refresh_token);
                $this->setAuthAppId($this->response->auth_app_id);
                $this->setExpiresIn($this->response->expires_in);
                $this->setReExpiresIn($this->response->re_expires_in);
            }
        }
        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setAppAuthToken($appAuthToken)
    {
        $this->appAuthToken = $appAuthToken;
        return $this;
    }

    public function getAppAuthToken()
    {
        return $this->appAuthToken;
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

    public function setExpiresIn($expiresIn)
    {
        $this->expiresIn = $expiresIn;
        return $this;
    }

    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    public function setReExpiresIn($reExpiresIn)
    {
        $this->reExpiresIn = $reExpiresIn;
        return $this;
    }

    public function getReExpiresIn()
    {
        return $this->reExpiresIn;
    }

    public function setAppRefreshToken($appRefreshToken)
    {
        $this->appRefreshToken = $appRefreshToken;
        return $this;
    }

    public function getAppRefreshToken()
    {
        return $this->appRefreshToken;
    }
}
