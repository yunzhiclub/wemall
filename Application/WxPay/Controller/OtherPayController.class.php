<?php
/*
 * 其它支付方式
 */
namespace WxPay\Controller;
use User\Controller\UserController;
class OtherPayController extends UserController
{
    public function _initialize() { 
        $this->addCss('/theme/wemall/css/home.css');
        $this->addCss('/theme/wemall/css/xmapp.css');

        parent::_initialize();
    }
    public function payNowAction()
    {
        $otherPay = "其他支付方式未实现，欢迎继续购物！";
        $this->assign("otherPay",$otherPay);
        $pay = T('OtherPay/otherPay');
        $this->assign('YZBody', $this->fetch($pay));
        $this->display(YZ_TEMPLATE);
    }
}