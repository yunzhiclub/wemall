<?php
namespace Customer\Controller;
use Admin\Controller\AdminController;
class AddController extends AdminController {

    public function useraddAction() {
        $cus = D('Customer');
        $code = I('post.code');
        $res = $cus->fetchcode();
        if ($code == $res) {
            $cus = D('Customer');
            $cus->create();
            $cus->add();
            $url = U('Home/Index/index');
            $this->success('恭喜您注册成功',$url,2);
        }
        else{
            echo "信息填写有误";
        }
    }
}
    