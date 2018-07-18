<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/1
 * Time: 18:31
 */

namespace zdy\wechat;


class Pay
{

    function __construct()
    {
        include_once __DIR__ . '/pay/WxPay.Api.php';
        include_once __DIR__ . '/pay/WxPay.Config.php';
        include_once __DIR__ . '/pay/WxPay.Data.php';
        include_once __DIR__ . '/pay/WxPay.Exception.php';
        include_once __DIR__ . '/pay/WxPay.Notify.php';
        include_once __DIR__ . '/pay/PayNotifyCallBack.php';
    }

    /**
     * jsapi下单接口
     */
    function jsapi($params)
    {
        $body = $params['body'];
        $out_trade_no = $params['out_trade_no'];
        $total_fee = $params['total_fee'];
        $notify_url = $params['notify_url'];
        $openId = $params['openid'];

        $time = time();
        $input = new \WxPayUnifiedOrder();
        $input->SetBody($body);
        $input->SetAttach($body);
        $input->SetOut_trade_no($out_trade_no);
        $input->SetTotal_fee($total_fee * 100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag($body);
        $input->SetNotify_url($notify_url);
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $input->SetSign();
        $order = \WxPayApi::unifiedOrder($input);

        $jsapi = new \WxPayJsApiPay();
        $jsapi->SetAppid($order["appid"]);
        $jsapi->SetTimeStamp((string)time());
        $jsapi->SetNonceStr(\WxPayApi::getNonceStr());
        $jsapi->SetPackage("prepay_id=" . $order['prepay_id']);
        $jsapi->SetSignType("MD5");
        $jsapi->SetPaySign($jsapi->MakeSign());
        return $jsapi->GetValues();
    }

    /**
     * 微信异步通知回调
     * @param $CallBack
     */
    function notifyProcess($CallBack)
    {
        $notify = new \PayNotifyCallBack();
        $notify->setCallBack($CallBack);
        $notify->Handle(false);
    }

}