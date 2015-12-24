<?php
namespace Active\Controller;
use Admin\Controller;
use WxPay\Controller\SendRedGiftController;
class GiftTestController extends Controller\AdminController
{
    /*
     * 初始化
     */
    public function indexAction()
    {
        $this->assign('getUrl',U("send"));
        $this->assign('js',$this->fetch('indexJs'));
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    
    /*
     * 点击发放
     */
    public function sendAction()
    {
        $SendRedGiftController =  new SendRedGiftController();
        $nickName = "这里是昵称";
        $sendName = "这里是发放者的名字";
        $openid = I('get.openid');
        $minValue = I('get.minValue');
        $maxValue = I('get.maxValue');
        $wishing = "这里是祝福的话";
        $actName = "这里是活动名称";
        $remark = "这里是备注";
        $SendRedGiftController->send($nickName, $sendName, $openid, $minValue, $maxValue, $wishing, $actName, $remark);
        echo "hello";
    }
}

