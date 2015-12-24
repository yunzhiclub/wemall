<?php

/*
 * 梦云智工作室
 *   * 
 */

namespace Customer\Controller;

use Admin\Controller\AdminController;

class ChangeController extends AdminController {

    public function indexAction() {
        $cus = D('Customer');
        $res = $cus ->changestate();
        $url = U('Customer/Index/unsubscribe');
        if ($res == 1) {
            $this->success('操作成功', $url, 2);
        } else {
            $this->error('操作失败', $url);
        }
    }
}