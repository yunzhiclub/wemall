<?php

/* 
 * 梦云智工作室
 * 返点设置添加控制器
 * author：dhy  *
 *  
 */
namespace Rebate\Controller;
use Admin\Controller\AdminController;
class SaveController extends AdminController {
   //返点设置的edit方法
    public function IndexAction(){
        $reb = D('Rebate');
        $key = 0;
        $res = $reb->datasave($key);
        $url = U('Rebate/Index/index');
        redirect_url($url);
    }
    public function lineAction(){
        $reb = D('Rebate');
        $key = 1;
        $res = $reb->datasave($key);
        $url = U('Rebate/Index/line');
        redirect_url($url);
    }
}