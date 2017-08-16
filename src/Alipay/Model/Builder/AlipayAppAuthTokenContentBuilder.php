<?php

namespace XiMu\Alipay\Model\Builder;

/**
 * https://doc.open.alipay.com/doc2/detail.htm?treeId=193&articleId=105193&docType=1#s7
 */
class AlipayAppAuthTokenContentBuilder extends ContentBuilder
{
    // 授权类型 如果使用app_auth_code换取token，则为authorization_code，如果使用refresh_token换取新的token，则为refresh_token
    private $grantType;

    // 授权码 与refresh_token二选一，用户对应用授权后得到，即第一步中开发者获取到的app_auth_code值
    private $code;

    // 刷新令牌 与code二选一，可为空，刷新令牌时使用
    private $refreshToken;

    private $appAuthToken;

    public function setGrantType($grantType)
    {
        $this->grantType = $grantType;
        $this->bizContentarr['grant_type'] = $grantType;
        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;
        $this->bizContentarr['code'] = $code;
        return $this;
    }

    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
        $this->bizContentarr['refresh_token'] = $refreshToken;
        return $this;
    }

    public function getGrantType()
    {
        return $this->grantType;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    public function setAppAuthToken($token)
    {
        $this->appAuthToken = $token;
        $this->bizContentarr['app_auth_token'] = $token;
        return $this;
    }

    public function getAppAuthToken()
    {
        return $this->appAuthToken;
    }

    /**
     * 刷新令牌.
     */
    public function refreshToken($refreshToken)
    {
        $this->setGrantType('refresh_token');
        $this->setRefreshToken($refreshToken);
    }

    /**
     * 换取令牌.
     */
    public function appAuthToken($code)
    {
        $this->setGrantType('authorization_code');
        $this->setCode($code);
    }

    /**
     * 查询令牌.
     */
    public function queryAuthToken($token)
    {
        $this->setAppAuthToken($token);
    }
}
