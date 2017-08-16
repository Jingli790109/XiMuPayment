<?php

namespace XiMu\Alipay\Model\Builder;

class AlipayDataserviceBillContentBuilder extends ContentBuilder
{
    //bill_type 账单类型，商户通过接口或商户经开放平台授权后其所属服务商通过接口可以获取以下账单类型：trade、signcustomer；trade指商户基于支付宝交易收单的业务账单；signcustomer是指基于商户支付宝余额收入及支出等资金变动的帐务账单；
    private $billType;

    //bill_date 账单时间：日账单格式为yyyy-MM-dd，月账单格式为yyyy-MM。
    private $billDate;

    public function setBillType($type)
    {
        $this->billType = $type;
        $this->bizContentarr['bill_type'] = $type;
        return $this;
    }

    public function setBillDate($date)
    {
        $this->billDate = $date;
        $this->bizContentarr['bill_date'] = $date;
        return $this;
    }
}
