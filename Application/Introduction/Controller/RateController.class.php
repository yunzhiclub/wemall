<?php
namespace Introduction\Controller;
use Admin\Controller\AdminController;
class RateController extends AdminController {
    public function UpdateAction(){
        $intro = M('Introduction');
        $intro->create();
        $intro->save();
        $url = U('Introduction/Rate/index');
        redirect_url($url);
    }
    public function IndexAction(){
        $intro = M('Introduction');
        $map = array();
        $map[id] = 2;
        $res = $intro->where($map)->find();
        $this->assign('Introduction', $res);
        $this->assign('urlUpdate', U('Update'));
        $TPL =T('Introduction@Rate/index');
        $this->assign('YZRight',$this->fetch($TPl));    
        $this->display(YZ_TEMPLATE);
        
    }
}