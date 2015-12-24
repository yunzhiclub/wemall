<?php

/*
 * 梦云智工作室
 *   * 
 */

namespace Customer\Controller;
use Customer\Model\CustomerModel;
use Admin\Controller\AdminController;

class FreezenController extends AdminController {

    public function indexAction() {
        $id = I('get.id','');
        if($id == '')
        {
            return false;
        }
        $customer = new CustomerModel();
        $customerInfo = $customer->changeCustomerFreezenState($id);
        $url = $_SERVER['HTTP_REFERER'];
        redirect_url($url);
    }

}
