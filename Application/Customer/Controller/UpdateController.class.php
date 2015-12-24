<?php

/* 
 * 梦云智工作室
 * 返点设置编辑控制器
 * author：dhy  *
 *  
 */
namespace Customer\Controller;
use Admin\Controller\AdminController;
class UpdateController extends AdminController{
   //返点设置的update方法
    public function indexAction(){
       //echo "成功调用客户管理下的update（）方法";
        $customer = D('Customer');
        $res = $customer->update();
        $url = U('Customer/Index/subscribe');
        if ($res === null) {
            $resData['status'] = 'error';
            $resData['msg'] = '操作失败';
        } else {
            $resData['status'] = 'success';
            $resData['msg'] = '操作成功';
        }
        if ($resData['status'] == 'success') {
            $this->success('操作成功', $url, 2);
        } else {
            $this->error('操作失败', $url);
        }
//        $map = array();
//        $map[id] = I('get.id');
//        $cus = M('customer');
//        $res = $cus->where($map)->field('subscribe_state')->find();
//        if ($res[subscribe_state] == 1) {
//            $urlIndex = U('Customer/Index/subscribe');
//            redirect_url($urlIndex);
//        }
//        if ($res[subscribe_state] == 0) {
//            $urlIndex = U('Customer/Index/unsubscribe');
//            redirect_url($urlIndex);
//        }
    }
}