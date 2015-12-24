<?php
namespace WechatJssdk\Controller;
use User\Controller\UserController;
class IndexController extends UserController {
    public function indexAction(){
        $jssdk = new JSSDKController();
        $signPackage = $jssdk->getSignPackage();
        echo $this->openId . "<br />";
        echo $this->userToken . "<br />";
        echo $this->code . "<br />";
        $addressPackage = $jssdk->getAddressPackage();
        $this->assign('addressPackage',$addressPackage);
        $this->assign('signPackage',$signPackage);
        $this->display();
    }
}