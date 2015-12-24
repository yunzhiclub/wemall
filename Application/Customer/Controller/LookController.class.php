<?php

/* 
 * 梦云智工作室
 *   * 
 */
namespace Customer\Controller;
use Admin\Controller\AdminController;
class LookController extends AdminController  {
        public function indexAction(){
            $cus = D('Customer');
            $res = $cus->look();
            if($res[subscribe_state]==0){
                $res[subscribe_state] = "未注册";
                 $urlIndex = U("Customer/Index/unsubscribe");
            }else{
                $res[subscribe_state] = "已注册";
                 $urlIndex = U("Customer/Index/subscribe");
            }
            $this->assign('urlIndex', $urlIndex);
            $this->assign('customer',$res);
            $TPL =T('Customer@Look/index');
            $this->assign('YZRight',$this->fetch($TPl));    
            $this->display(YZ_TEMPLATE);
            
            
        }
        
}