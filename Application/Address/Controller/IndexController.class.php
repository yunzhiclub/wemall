<?php
namespace Address\Controller;
use User\Controller\UserController;
class IndexController extends UserController{
    public function indexAction(){
        $this->assign('listCss',$this->fetch('listCss'));
        $this->assign('YZBody',$this->fetch('list'));
        $this->display(YZ_TEMPLATE);
    }
}