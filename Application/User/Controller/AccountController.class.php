<?php

/* 
 * 梦云智工作室
 *   * 
 */
namespace User\Controller;
use User\Controller\UserController;
use User\Model\AccountModel;
class AccountController extends UserController {
    public function indexAction(){
        $id = get_openid();
        $model = M('customer');
        $map['openid'] = $id;
        $res = $model ->where($map) ->find();
        $this->assign('account',$res);
        $url = U('save');
        $this->assign('url',$url);
        $this->assign('YZBody',$this->fetch());  
        $this->display(YZ_TEMPLATE);
    }
    public function saveAction(){
        $model = M('customer');
        $model ->create();
        $res = $model ->save();
         if ($res === null) {
            $resData['status'] = 'error';
            $resData['msg'] = '操作失败';
        } else {
            $resData['status'] = 'success';
            $resData['msg'] = '操作成功';
        }
        $url = U('User/UserCenter/index');
        if ($resData['status'] == 'success') {
            $this->success('操作成功', $url, 2);
        } else {
            $this->error('操作失败', $url);
        }
    }
    
}
