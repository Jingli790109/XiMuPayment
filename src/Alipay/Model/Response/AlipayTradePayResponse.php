<?php

namespace XiMu\Alipay\Model\Response;

class AlipayTradePayResponse extends Response
{
    //trade_no 支付宝交易号
    private $tradeNo;

    //out_trade_no 商家订单号
    private $outTradeNo;

    //buyer_logon_id 买家支付宝账号
    private $buyerLogonId;

    //trade_status 交易状态：WAIT_BUYER_PAY（交易创建，等待买家付款）、TRADE_CLOSED（未付款交易超时关闭，或支付完成后全额退款）、TRADE_SUCCESS（交易支付成功）、TRADE_FINISHED（交易结束，不可退款）
    private $tradeStatus;

    //total_amount 交易的订单金额，单位为元，两位小数。该参数的值为支付时传入的total_amount
    private $totalAmount;

    //receipt_amount 实收金额，单位为元，两位小数。该金额为本笔交易，商户账户能够实际收到的金额
    private $receiptAmount;

    //buyer_pay_amount 买家实付金额，单位为元，两位小数。该金额代表该笔交易买家实际支付的金额，不包含商户折扣等金额
    private $buyerPayAmount;

    //point_amount 积分支付的金额，单位为元，两位小数。该金额代表该笔交易中用户使用积分支付的金额，比如集分宝或者支付宝实时优惠等
    private $pointAmount;

    //invoice_amount 交易中用户支付的可开具发票的金额，单位为元，两位小数。该金额代表该笔交易中可以给用户开具发票的金额
    private $invoiceAmount;

    //gmt_payment
    private $gmtPayment;

    //alipay_store_id 支付宝店铺编号
    private $alipayStoreId;

    //store_id 商户门店编号
    private $storeId;

    //terminal_id 商户机具终端编号
    private $terminalId;

    //fund_bill_list 交易支付使用的资金渠道
    private $fundBillList;

    //fund_channel 交易使用的资金渠道，属于fund_bill_list.
    private $fundChannel;

    //amount 该支付工具类型所使用的金额，属于fund_bill_list.
    private $fundAmount;

    //real_amount 渠道实际付款金额，属于fund_bill_list.
    private $fundRealAmount;

    //store_name 请求交易支付中的商户店铺的名称
    private $storeName;

    //buyer_user_id 买家在支付宝的用户id
    private $buyerUserId;

    //discount_goods_detail 本次交易支付所使用的单品券优惠的商品优惠信息
    private $discountGoodsDetail;

    //industry_sepc_detail 行业特殊信息（例如在医保卡支付业务中，向用户返回医疗信息）。
    private $industrySepcDetail;

    //voucher_detail_list 本交易支付时使用的所有优惠券信息
    private $voucherDetailList;

    //id 券id 属于voucher_detail_list.
    private $voucherId;

    //name 券名称
    private $voucherName;

    //type当前有三种类型： ALIPAY_FIX_VOUCHER - 全场代金券 ALIPAY_DISCOUNT_VOUCHER - 折扣券 ALIPAY_ITEM_VOUCHER - 单品优惠 注：不排除将来新增其他类型的可能，商家接入时注意兼容性避免硬编码
    private $voucherType;

    //amount 优惠券面额，它应该会等于商家出资加上其他出资方出资
    private $voucherAmount;

    //merchant_contribute 商家出资（特指发起交易的商家出资金额）
    private $voucherMerchantContribute;

    //other_contribute 其他出资方出资金额，可能是支付宝，可能是品牌商，或者其他方，也可能是他们的一起出资
    private $voucherOtherContribute;

    //memo 优惠券备注信息
    private $voucherMemo;

    public function parse()
    {
        if ($this->response) {
            $code = $this->response->code;
            $this->setCode($code);
            $this->setMsg($this->response->msg);
            // 返回码大于10000，错误信息。
            if ($code > 10000) {
                if (isset($this->response->sub_code)) {
                    $this->setSubCode($this->response->sub_code);
                }
                if (isset($this->response->sub_msg)) {
                    $this->setSubMsg($this->response->sub_msg);
                }
            }
            if (isset($this->response->out_trade_no)) {
                $this->setOutTradeNo($this->response->out_trade_no);
            }
            if (isset($this->response->trade_no)) {
                $this->setTradeNo($this->response->trade_no);
            }
            if (isset($this->response->buyer_logon_id)) {
                $this->setBuyerLogonId($this->response->buyer_logon_id);
            }
            if (isset($this->response->gmt_payment)) {
                $this->setGmtPayment($this->response->gmt_payment);
            }
            if (isset($this->response->total_amount)) {
                $this->setTotalAmount($this->response->total_amount);
            }
            if (isset($this->response->receipt_amount)) {
                $this->setReceiptAmount($this->response->receipt_amount);
            }
            if (isset($this->response->buyer_pay_amount)) {
                $this->setBuyerPayAmount($this->response->buyer_pay_amount);
            }
            if (isset($this->response->buyer_user_id)) {
                $this->setBuyerUserId($this->response->buyer_user_id);
            }
        }
        return $this;
    }

    public function querySuccess()
    {
        return $this->getCode() == '10000' &&
                ($this->getTradeStatus() == 'TRADE_SUCCESS' ||
                    $this->getTradeStatus() == 'TRADE_FINISHED');
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

    public function setBuyerLogonId($buyerLogonId)
    {
        $this->buyerLogonId = $buyerLogonId;
        return $this;
    }

    public function getBuyerLogonId()
    {
        return $this->buyerLogonId;
    }

    public function setTradeStatus($tradeStatus)
    {
        $this->tradeStatus = $tradeStatus;
        return $this;
    }

    public function getTradeStatus()
    {
        return $this->tradeStatus;
    }

    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    public function setReceiptAmount($receiptAmount)
    {
        $this->receiptAmount = $receiptAmount;
        return $this;
    }

    public function getReceiptAmount()
    {
        return $this->receiptAmount;
    }

    public function setBuyerPayAmount($buyerPayAmount)
    {
        $this->buyerPayAmount = $buyerPayAmount;
        return $this;
    }

    public function getBuyerPayAmount()
    {
        return $this->buyerPayAmount;
    }

    public function setPointAmount($pointAmount)
    {
        $this->pointAmount = $pointAmount;
        return $this;
    }

    public function getPointAmount()
    {
        return $this->pointAmount;
    }

    public function setInvoiceAmount($invoiceAmount)
    {
        $this->invoiceAmount = $invoiceAmount;
        return $this;
    }

    public function getInvoiceAmount()
    {
        return $this->invoiceAmount;
    }

    public function setGmtPayment($gmtPayment)
    {
        $this->gmtPayment = $gmtPayment;
        return $this;
    }

    public function getGmtPayment()
    {
        return $this->gmtPayment;
    }

    public function setAlipayStoreId($alipayStoreId)
    {
        $this->alipayStoreId = $alipayStoreId;
        return $this;
    }

    public function getAlipayStoreId()
    {
        return $this->alipayStoreId;
    }

    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
        return $this;
    }

    public function getStoreId()
    {
        return $this->storeId;
    }

    public function setTerminalId($terminalId)
    {
        $this->terminalId = $terminalId;
        return $this;
    }

    public function getTerminalId()
    {
        return $this->terminalId;
    }

    public function setFundBillList($fundBillList)
    {
        if (isset($fundBillList->amount)) {
            $this->setFundAmount($fundBillList->amount);
        }
        if (isset($fundBillList->fund_channel)) {
            $this->setFundChannel($fundBillList->fund_channel);
        }
        if (isset($fundBillList->real_amount)) {
            $this->setFundRealAmount($fundBillList->real_amount);
        }
        $this->fundBillList = $fundBillList;
        return $this;
    }

    public function getFundBillList()
    {
        return $this->fundBillList;
    }

    public function setFundAmount($fundAmount)
    {
        $this->fundAmount = $fundAmount;
        return $this;
    }

    public function getFundAmount()
    {
        return $this->fundAmount;
    }

    public function setFundChannel($fundChannel)
    {
        $this->fundChannel = $fundChannel;
        return $this;
    }

    public function getFundChannel()
    {
        return $this->fundChannel;
    }

    public function setFundRealAmount($fundRealAmount)
    {
        $this->fundRealAmount = $fundRealAmount;
        return $this;
    }

    public function getFundRealAmount()
    {
        return $this->fundRealAmount;
    }

    public function setStoreName($storeName)
    {
        $this->storeName = $storeName;
        return $this;
    }

    public function getStoreName()
    {
        return $this->storeName;
    }

    public function setBuyerUserId($buyerUserId)
    {
        $this->buyerUserId = $buyerUserId;
        return $this;
    }

    public function getBuyerUserId()
    {
        return $this->buyerUserId;
    }

    public function setDiscountGoodsDetail($discountGoodsDetail)
    {
        $this->discountGoodsDetail = $discountGoodsDetail;
        return $this;
    }

    public function getDiscountGoodsDetail()
    {
        return $this->discountGoodsDetail;
    }

    public function setIndustrySepcDetail($industrySepcDetail)
    {
        $this->industrySepcDetail = $industrySepcDetail;
        return $this;
    }

    public function getIndustrySepcDetail()
    {
        return $this->industrySepcDetail;
    }

    public function setVoucherDetailList($voucherDetailList)
    {
        if (isset($voucherDetailList->id)) {
            $this->setVoucherId($voucherDetailList->id);
        }
        if (isset($voucherDetailList->name)) {
            $this->setVoucherName($voucherDetailList->name);
        }
        if (isset($voucherDetailList->type)) {
            $this->setVoucherType($voucherDetailList->type);
        }
        if (isset($voucherDetailList->amount)) {
            $this->setVoucherAmount($voucherDetailList->amount);
        }
        if (isset($voucherDetailList->merchant_contribute)) {
            $this->setVoucherMerchantContribute($voucherDetailList->merchant_contribute);
        }
        if (isset($voucherDetailList->other_contribute)) {
            $this->setVoucherOtherContribute($voucherDetailList->other_contribute);
        }
        if (isset($voucherDetailList->memo)) {
            $this->setVoucherMemo($voucherDetailList->memo);
        }
        $this->voucherDetailList = $voucherDetailList;
        return $this;
    }

    public function getVoucherDetailList()
    {
        return $this->voucherDetailList;
    }

    public function setVoucherId($voucherId)
    {
        $this->voucherId = $voucherId;
        return $this;
    }

    public function getVoucherId()
    {
        return $this->voucherId;
    }

    public function setVoucherName($voucherName)
    {
        $this->voucherName = $voucherName;
        return $this;
    }

    public function getVoucherName()
    {
        return $this->voucherName;
    }

    public function setVoucherType($voucherType)
    {
        $this->voucherType = $voucherType;
        return $this;
    }

    public function getVoucherType()
    {
        return $this->voucherType;
    }

    public function setVoucherAmount($voucherAmount)
    {
        $this->voucherAmount = $voucherAmount;
        return $this;
    }

    public function getVoucherAmount()
    {
        return $this->voucherAmount;
    }

    public function setVoucherMerchantContribute($voucherMerchantContribute)
    {
        $this->voucherMerchantContribute = $voucherMerchantContribute;
        return $this;
    }

    public function getVoucherMerchantContribute()
    {
        return $this->voucherMerchantContribute;
    }

    public function setVoucherOtherContribute($voucherOtherContribute)
    {
        $this->voucherOtherContribute = $voucherOtherContribute;
        return $this;
    }

    public function getVoucherOtherContribute()
    {
        return $this->voucherOtherContribute;
    }

    public function setVoucherMemo($voucherMemo)
    {
        $this->voucherMemo = $voucherMemo;
        return $this;
    }

    public function getVoucherMemo()
    {
        return $this->voucherMemo;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
