<?php

/* 
 * 梦云智工作室
 *   * 
 */
namespace Customer\Controller;
use Admin\Controller\AdminController;
class EditController extends AdminController  {
        public function indexAction(){
            //echo "成功调用客户管理下的edit（）方法";
            $cus = D('Customer');
            $res = $cus->edit();
            $id = I('get.id');  
            $urlIndex = U('Customer/Index/index?id='.$id);
            $urlUpdate = U('Customer/Update/index?id='.$id);
            $this->assign('urlUpdate',$urlUpdate);
            $this->assign('urlIndex',$urlIndex);
            $this->assign('Customerid', $id);
            $this->assign('customer',$res);
            $TPL =T('Customer@Edit/index');
            $this->assign('YZRight',$this->fetch($TPL));    
            $this->display(YZ_TEMPLATE);
        }
        
}

