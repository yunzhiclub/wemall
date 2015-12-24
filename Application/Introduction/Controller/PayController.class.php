<?php
namespace Introduction\Controller;
use Admin\Controller\AdminController;
class PayController extends AdminController {
    public function UpdateAction(){
        $intro = M('Introduction');
        $intro->create();
        $intro->save();
        $url = U('Introduction/Pay/index');
        redirect_url($url);
    }
    public function IndexAction(){
        $intro = M('Introduction');
        $map = array();
        $map[id] = 4;
        $res = $intro->where($map)->find();
        $this->assign('Introduction', $res);
        $this->assign('urlUpdate', U('Update'));
        $TPL =T('Introduction@Pay/index');
        $this->assign('YZRight',$this->fetch($TPl));    
        $this->display(YZ_TEMPLATE);
        
    }
}