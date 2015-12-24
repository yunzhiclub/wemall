<?php

/* 
 * 梦云智工作室
 *返点设置初始化   *
 * author：dhy
 *  
 */
namespace Rebate\Controller;
use Admin\Controller\AdminController;
class IndexController extends AdminController  {
   //返点设置的index初始化
    public function indexAction(){
       header("Content-Type:text/html; charset=UTF-8");
       $res = $this->achievementAction(10000);
       $rebate = D('Rebate');
       $key = 0;
       $res = $rebate->init($key);       
       $Page = new \Think\Page(count($res),10);// 实例化分页类 传入总记录数和每页显示的
       $Page->setConfig('theme', '%FIRST% %UP_PAGE% %HEADER% %DOWN_PAGE% %END%');
       $Page->setConfig('header','<span class="rows">共 %TOTAL_ROW% 个返点</span>%LINK_PAGE%<span class="currentrows">当前第 %NOW_PAGE% 页</span>');
       $show = $Page->show();// 分页显示输出
       $this->assign('page',$show);// 赋值分页输出
       foreach ($res as $key=>$value) 
         {
            $res[$key]['actionUrl']['edit'] = U('Rebate/Edit/index?id=' . $value['id']);
            $res[$key]['actionUrl']['delete'] = U('Rebate/Delete/index?id=' . $value['id']);        
        }
       $this->assign('rebate',$res);
       $this->assign('urlAdd', U('Rebate/Add/index'));
       $TPL =T('Rebate@Index/index');
       $this->assign('YZRight',$this->fetch($TPl));    
       $this->display(YZ_TEMPLATE);
    }
    public function achievementAction($money){
        $rebate = D('Rebate');
        $res = $rebate->achievement($money);
        return $res;
        
    }
    public function lineAction(){
        header("Content-Type:text/html; charset=UTF-8");
        $res = $this->achievementAction(10000);
        $rebate = D('Rebate');
        $key = 1;
        $res = $rebate->init($key);       
        $Page = new \Think\Page(count($res),10);// 实例化分页类 传入总记录数和每页显示的
        $Page->setConfig('theme', '%FIRST% %UP_PAGE% %HEADER% %DOWN_PAGE% %END%');
        $Page->setConfig('header','<span class="rows">共 %TOTAL_ROW% 个返点</span>%LINK_PAGE%<span class="currentrows">当前第 %NOW_PAGE% 页</span>');
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        foreach ($res as $key=>$value) 
          {
             $res[$key]['actionUrl']['edit'] = U('Rebate/Edit/line?id=' . $value['id']);
             $res[$key]['actionUrl']['delete'] = U('Rebate/Delete/line?id=' . $value['id']);        
         }
        $this->assign('rebate',$res);
        $this->assign('urlAdd', U('Rebate/Add/line'));
        $TPL =T('Rebate@Index/index');
        $this->assign('YZRight',$this->fetch($TPl));    
        $this->display(YZ_TEMPLATE);
    }
}
