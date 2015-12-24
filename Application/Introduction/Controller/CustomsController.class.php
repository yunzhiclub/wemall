<?php
namespace Introduction\Controller;
use Admin\Controller\AdminController;
class CustomsController extends AdminController {
    public function UpdateAction(){
        $intro = M('Introduction');
        $conf = M('config');
        $intro->create();
        $intro->save();
        $map1[name] = 'SYSTEM_UPPER_LIMIT';
        $map2[name] = 'SYSTEM_LOWER_LIMIT';
        $map3[name] = 'SYSTEM_COUNT_LIMIT';
        $data1 = array();
        $data2 = array();
        $data3 = array();
        $data1[value] = I('post.up');
        $data2[value] = I('post.low');
        $data3[value] = I('post.count');
        $conf->where($map2)->save($data2);
        $conf->where($map1)->save($data1);
        $conf->where($map3)->save($data3);
        $url = U('Introduction/Customs/index');
        redirect_url($url);
    }
    public function IndexAction(){
        $intro = M('Introduction');
        $conf = M('config');
        $map1[name] = 'SYSTEM_UPPER_LIMIT';
        $map2[name] = 'SYSTEM_LOWER_LIMIT';
        $map3[name] = 'SYSTEM_COUNT_LIMIT';
        $up = $conf->where($map1)->find();
        $low = $conf->where($map2)->find();
        $count = $conf->where($map3)->find();
        $map = array();
        $map[id] = 1;
        $res2 = $intro->where($map)->find();
        $this->assign('Introduction', $res2);
        $this->assign('up', $up);
        $this->assign('count',$count);
        $this->assign('low', $low);
        $this->assign('config', $res1);
        $this->assign('urlUpdate', U('Update'));
        $TPL =T('Introduction@Customs/index');
        $this->assign('YZRight',$this->fetch($TPl));    
        $this->display(YZ_TEMPLATE); 
    }
}