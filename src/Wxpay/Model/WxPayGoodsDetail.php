<?php

namespace XiMu\Wxpay\Model;

/**
 * 统一下单商品详情结构.
 * 商品详细列表，使用Json格式，传输签名前请务必使用CDATA标签将JSON文本串保护起来。
 */
class WxPayGoodsDetail
{
    //cost_price Int 可选 32 订单原价，商户侧一张小票订单可能被分多次支付，订单原价用于记录整张小票的支付金额。当订单原价与支付金额不相等则被判定为拆单，无法享受优惠。
    private $costPrice;

    //receipt_id String 可选 32 商家小票ID
    private $receiptId;

    //goods_detail 服务商必填.
    private $goodsDetail = array();

    //goods_id String 必填 32 商品的编号
    private $goodsId;

    //wxpay_goods_id String 可选 32 微信支付定义的统一商品编号
    private $wxpayGoodsId;

    //goods_name String 可选 256 商品名称
    private $goodsName;

    //quantity Int 必填  32 商品数量
    private $quantity = 1;

    //price Int 必填 32 商品单价，如果商户有优惠，需传输商户优惠后的单价 注意：单品总金额应<=订单总金额total_fee，否则会无法享受优惠。
    private $price;

    private $value = array();

    public function setCostPrice($price)
    {
        $this->costPrice = $price;
        $this->value['cost_price'] = $price;
        return $this;
    }

    public function getCostPrice()
    {
        return $this->costPrice;
    }

    public function setReceiptId($receiptId)
    {
        $this->receiptId = $receiptId;
        $this->value['receipt_id'] = $receiptId;
        return $this;
    }

    public function getReceiptId()
    {
        return $this->receiptId;
    }

    public function setGoodsId($goodsId)
    {
        $this->goodsId = $goodsId;
        $this->goodsDetail['goods_id'] = $goodsId;
        return $this;
    }

    public function getGoodsId()
    {
        return $this->goodsId;
    }

    public function setWxpayGoodsId($wxpayGoodsId)
    {
        $this->wxpayGoodsId = $wxpayGoodsId;
        $this->goodsDetail['wxpay_goods_id'] = $wxpayGoodsId;
        return $this;
    }

    public function getWxpayGoodsId()
    {
        return $this->wxpayGoodsId;
    }

    public function setGoodsName($goodsName)
    {
        $this->goodsName = $goodsName;
        $this->goodsDetail['goods_name'] = $goodsName;
        return $this;
    }

    public function getGoodsName()
    {
        return $this->goodsName;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        $this->goodsDetail['quantity'] = $quantity;
        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setPrice($price)
    {
        $this->price = $price;
        $this->goodsDetail['price'] = $price;
        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getContent()
    {
        $this->value['goods_detail'] = $this->goodsDetail;
        return json_encode($this->value, JSON_UNESCAPED_UNICODE);
    }
}
