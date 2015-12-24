<?php
/* 
 * 管理微信公众平台自定义菜单
 *   * 
 */
namespace WechatAdmin\Controller;
use Admin\Controller\AdminController;
class CustomerMenuController extends AdminController
{
    public function indexAction(){
        $customMenu = D('CustomMenu');
        $list = $customMenu->fetchList();
        foreach($list as $key => $value)
        {
            $list[$key]['actionUrl']['edit'] = U('WechatAdmin/CustomerMenu/edit?id=' . $value['id']);
            $list[$key]['actionUrl']['delete'] = U('WechatAdmin/CustomerMenu/delete?id=' . $value['id']);        
        }
        $this->assign('list',$list);
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    public function editAction()
    {
        echo "hello edit";
    }
    public function deleteAction()
    {
        
    }
}

