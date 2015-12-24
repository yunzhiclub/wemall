<?php
namespace WechatAdmin\Controller;
use Admin\Controller;
class EditController extends Controller\AdminController {
    public function indexAction(){
//        $replyM = M('reply');
//        $reply = $replyM->find();
//        $this->reply = $reply;
        $this->assign('reply',M('reply')->find());
        var_dump(M('reply')->find());
        $this->assign('YZRight',$this->fetch());//将你的数据送给right
        $this->display(YZ_TEMPLATE);
    }
}