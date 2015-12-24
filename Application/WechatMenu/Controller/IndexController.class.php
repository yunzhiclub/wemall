<?php
namespace WechatMenu\Controller;
use Admin\Controller\AdminController;
class IndexController extends AdminController{
    public function indexAction(){
        var_dump(D('CustomMenu')->select());
    }
}