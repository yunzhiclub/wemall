<?php
namespace ShippingAddress\Controller;
use Admin\Controller\AdminController;
class IndexController extends AdminController {
    private $address = null;//菜单表
    public function __construct() {
        parent::__construct();
        $this->address = D('ShippingAddress');
        
    }
    public function indexAction() {
        $shippingM = $this->address;
        $shipping = $shippingM->init();
        $attachmentM = D('Attachment');                //取附件表里面的ID名
        $attachment = $attachmentM->getName($shipping);
        foreach($attachment as $key => $value)
        {
            $attachment[$key]['actionUrl'] = array('edit' => U('edit?id=' . $value['id']) , 'delete' => U('delete?id=' . $value['id']), 'add' => U('add?id=' . $value['id']));
        }
        var_dump($attachment);
        $this->assign('shipping',$attachment);
        $this->display();
        
    }
}