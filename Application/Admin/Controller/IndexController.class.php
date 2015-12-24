<?php
namespace Admin\Controller;
use Admin\Controller\AdminController;
class IndexController extends AdminController {
    public function _initialize() {
        parent::_initialize();
    }
    public function indexAction(){
        /*$userId = session('userId');
        $map['userId'] = $userId;
        $userInfo = M('User')->where($map)->find();
        $this->assign('userInfo',$userInfo);*/
        $this->assign(logout,U('Admin/Login/logout'));
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    
}