<?php

/* 
 * 梦云智工作室
 * 返点设置删除控制器
 * author：dhy  *
 *  
 */
namespace Rebate\Controller;
use Admin\Controller\AdminController;
class DeleteController extends AdminController {
   //返点设置的edit方法
    public function IndexAction(){
        $reb = D('Rebate');
        $reb->del();
        $url = U('Rebate/Index/index');
        redirect_url($url);
        }
         public function lineAction(){
        $reb = D('Rebate');
        $reb->del();
        $url = U('Rebate/Index/line');
        redirect_url($url);
        }
}