<?php

/* 
 * 梦云智工作室
 * 返点设置编辑控制器
 * author：dhy  *
 *  
 */
namespace Rebate\Controller;
use Admin\Controller\AdminController;
class EditController extends AdminController  {
   //返点设置的edit方法
    public function IndexAction(){
        $rebate = D('Rebate');
        $res = array();
        $res = $rebate->edit();
        $this->assign('rebate',$res); 
        $urlIndex = U('Rebate/Index/index');
        $urlEdit = U('Rebate/Edit/index').'?';
        $urlUpdate = U('Rebate/Update/index');
        $this->assign('urlUpdate',$urlUpdate);
        $this->assign('urlIndex',$urlIndex);
        $this->assign('urlEdit',$urlEdit);
        $TPL =T('Rebate@Edit/index');
        $this->assign('YZRight',$this->fetch($TPL));    
        $this->display(YZ_TEMPLATE);
    }
    public function lineAction(){
        $rebate = D('Rebate');
        $res = array();
        $res = $rebate->edit();    
        $this->assign('rebate',$res); 
        $urlIndex = U('Rebate/Index/index');
        $urlEdit = U('Rebate/Edit/index').'?';
        $urlUpdate = U('Rebate/Update/line');
        $this->assign('urlUpdate',$urlUpdate);
        $this->assign('urlIndex',$urlIndex);
        $this->assign('urlEdit',$urlEdit);
        $TPL =T('Rebate@Edit/line');
        $this->assign('YZRight',$this->fetch($TPL));    
        $this->display(YZ_TEMPLATE);
    }
}

