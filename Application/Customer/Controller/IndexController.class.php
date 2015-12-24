<?php
namespace Customer\Controller;
use Admin\Controller\AdminController;
use Achievement\Model\AchievementModel;
use Customer\Model\CustomerModel;
class IndexController extends AdminController  {
    public function indexAction(){
        $url = U('unsubscribe');
        redirect_url($url);
    }
    
    /*
     * 关注用户列表
     */
    
    public function subscribeAction(){

        $map['subscribe_state'] = 1;
        
        $this->_getCustomerInfoByMap($map);
        
        $this->assign('YZRight',$this->fetch());               
        $this->display(YZ_TEMPLATE);  
    }
    
    
    public function unsubscribeAction(){
        //获取当前业绩信息
        //$map['subscribe_state'] = 0;
        $map = array();
        $this->_getCustomerInfoByMap($map);
        
        $this->assign('YZRight',$this->fetch());               
        $this->display(YZ_TEMPLATE);  
    }
    
    private function _getCustomerInfoByMap($map)
    {
                //获取当前业绩信息
        $ach = new AchievementModel();
        $achInfo = $ach->getCurrentInfo();
        $this->assign('achievement',$achInfo);
        
        $customer = new CustomerModel();
        $customer->setMap($map);
        $count = $customer->getCountByMap();
        
        $page = $this->page;
        $currentPage = I('get.p',1);
        $page->setCounts($count);
        $page->setCurrentPage($currentPage);
        $page->setPageStyle(2);
        $pageStr = $page->fetch();
        $currentPage = $page->getCurrentPage();
        $pageSize = $page->getPageSize();
        
        $menuList = $customer->getDataArrByMap($currentPage,$pageSize);
        $key='openid';
        $menuList = change_key($menuList, $key);
        //截取本页内容
        
        $this->assign('page',$pageStr);
        foreach ($menuList as $key=>$value) 
         {
            $menuList[$key]['actionUrl']['edit'] = U('Customer/Edit/index?id=' . $value['id']);
            $menuList[$key]['actionUrl']['freezen'] = U('Customer/Freezen/index?id=' . $value['id']);        
            $menuList[$key]['actionUrl']['look'] = U('Customer/Look/index?id=' . $value['id']);
            $menuList[$key]['actionUrl']['parent_look'] = U('Customer/Look/index?id=' . $value['parentid']);
            
         }
         //添加上级用户名称
         foreach ($menuList as $key=>$value) 
         {
            $menuList[$key]['_parentname'] = $this->_addParentName($value['parentid']);
         }
         $css = $this->fetch('indexCss');
         $this->assign('css',$css);
        $this->assign('customer',$menuList);
        $this->assign('listCustomerUrl',U('ListCustomer/index')); 
    }
    //添加上级用户名称
    private function _addParentName($parentId){
        $map['id'] = $parentId;
        $customer = new CustomerModel();
        $parentName = $customer->where($map)->field('nickname')->find();
        return $parentName;
    }
}