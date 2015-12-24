<?php

/* 
 * 梦云智工作室
 * 返点设置编辑控制器
 * author：dhy  *
 *  
 */
namespace Rebate\Controller;
use Admin\Controller\AdminController;
class UpdateController extends AdminController{
   //返点设置的update方法
    public function indexAction(){
        $rebate = D('Rebate');
        $key = 0;
        $rebate->update($key);
        $url = U('Rebate/Index/index');
        redirect_url($url);
    }
    public function lineAction(){
        $rebate = D('Rebate');
        $key = 1;
        $rebate->update($key);
        $url = U('Rebate/Index/line');
        redirect_url($url);
    }
}

