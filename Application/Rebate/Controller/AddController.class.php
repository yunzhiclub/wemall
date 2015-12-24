<?php

/* 
 * 梦云智工作室
 * 返点设置编辑控制器
 * author：dhy  *
 *  
 */
namespace Rebate\Controller;
use Admin\Controller\AdminController;
class AddController extends AdminController  {
   //返点设置的edit方法
    public function IndexAction(){
        $urlSave = U('Rebate/Save/index');
        $urlIndex = U('Rebate/Index/index');
        $this->assign('urlIndex',$urlIndex);
        $this->assign('urlSave',$urlSave);
        $TPL =T('Rebate@Save/index');
        $this->assign('YZRight',$this->fetch($TPl));    
        $this->display(YZ_TEMPLATE);
    }
     public function lineAction(){
        $urlSave = U('Rebate/Save/line');
        $urlIndex = U('Rebate/Index/line');
        $this->assign('urlIndex',$urlIndex);
        $this->assign('urlSave',$urlSave);
        $TPL =T('Rebate@Save/line');
        $this->assign('YZRight',$this->fetch($TPl));    
        $this->display(YZ_TEMPLATE);
    }
}