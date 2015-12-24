<?php
/*
 * 系统管理主菜单
 */
namespace System\Controller;
use Admin\Controller\AdminController;
class IndexController extends AdminController {
    public function indexAction(){
        $url = U('system/AchievementDate/index');
        redirect($url);
    }
    public function saveFileAction(){
        echo "hello";
        return;
    }
}