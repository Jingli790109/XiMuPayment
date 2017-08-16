<?php

namespace XiMu\Alipay\Model\Builder;

class AlipayUserAuthTokenContentBuilder extends ContentBuilder
{
    // 授权类型 如果使用app_auth_code换取token，则为authorization_code，如果使用refresh_token换取新的token，则为refresh_token
    private $grantType;

    // 授权码 与refresh_token二选一，用户对应用授权后得到，即第一步中开发者获取到的app_auth_code值
    private $code;

    // 刷新令牌 与code二选一，可为空，刷新令牌时使用
    private $refreshToken;

    public function setGrantType($grantType)
    {
        $this->grantType = $grantType;
        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }
}
