<?php

namespace XiMu\Model\Response;

class AlipayUserAuthTokenResponse extends Response
{
    //access_token 交换令牌 用于获取用户信息
    private $accessToken;

    //alipay_user_id 用户的open_id 已废弃，请勿使用
    private $alipayUserId;

    //expires_in 令牌有效期 交换令牌的有效期，单位秒
    private $expiresIn;

    //re_expires_in 刷新令牌有效期
    private $reExpiresIn;

    //refresh_token 刷新令牌
    private $refreshToken;

    //user_id 用户的userId 支付宝用户的唯一userId
    private $userId;

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
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

    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    public function parse()
    {
        if ($this->response) {
            $this->setUserId($this->response->user_id);
            $this->setAccessToken($this->response->access_token);
            $this->setRefreshToken($this->response->refresh_token);
            $this->setExpiresIn($this->response->expires_in);
            $this->setReExpiresIn($this->response->re_expires_in);
        }
        return $this;
    }
}
