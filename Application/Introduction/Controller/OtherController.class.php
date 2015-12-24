<?php
namespace Introduction\Controller;
use Admin\Controller\AdminController;
class OtherController extends AdminController {
    public function UpdateAction(){
        $intro = M('Introduction');
        $intro->create();
        $intro->save();
        $url = U('Introduction/Other/index');
        redirect_url($url);
    }
    public function IndexAction(){
        $intro = M('Introduction');
        $map = array();
        $map[id] = 5;
        $res = $intro->where($map)->find();
        $this->assign('Introduction', $res);
        $this->assign('urlUpdate', U('Update'));
        $TPL =T('Introduction@Other/index');
        $this->assign('YZRight',$this->fetch($TPl));    
        $this->display(YZ_TEMPLATE);
        
    }
}