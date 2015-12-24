<?php
namespace WechatAdmin\Controller;
use Admin\Controller;
class IndexController extends Controller\AdminController {
    public function indexAction(){
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);//最后直接FETCH框架模板文件
    }
}