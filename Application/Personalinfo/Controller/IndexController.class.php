<?php

namespace Personalinfo\Controller;

use Admin\Controller\AdminController;

class IndexController extends AdminController {

    public function indexAction() {
        $url = U('init');
        redirect($url);
    }

    public function initAction() {
        /*
         * 获得当前用户（id）的用户信息
         */
        $userId = session("userId");
        $user = M('user');
        $map['userid'] = $userId;
        $data = $user->where($map)->find();
        $data['actionUrl'] = array('update' => U('update')); //拼接地址
        /*
         * 调用模板
         */
        $this->assign('User', $data);
        $this->assign('YZRight', $this->fetch());
        $this->display(YZ_TEMPLATE);
    }

    /*
     * 用户信息更新页面
     */

    public function changeinfoAction() {
        $userId = session("userId");
        $user = M('user');
        $map['userid'] = $userId;
        $data = $user->where($map)->find();
        $data['actionUrl'] = array('save' => U('save')); //拼接地址
        /*
         * 调用模板
         */
        $url = U('index');
        $this->assign('url', $url);
        $this->assign('User', $data);
        $this->assign('YZRight', $this->fetch());
        $this->display(YZ_TEMPLATE);
    }

    /*
     * 用户密码更新页面
     */

    public function changepasswordAction() {
        $userId = session("userId");
        $user = M('user');
        $map['userid'] = $userId;
        $data = $user->where($map)->find();
        $data['actionUrl'] = array('update' => U('update')); //拼接地址
        $url = U('index');
        $this->assign('url', $url);
        $urlU = U('update');
        $this->assign('urlU', $urlU);
        /*
         * 调用模板
         */
        $this->assign('User', $data);
        $this->assign('YZRight', $this->fetch(changepassword));
        $this->display(YZ_TEMPLATE);
    }

    /*
     * 用户密码修改更新
     */

    public function updateAction() {
        $password = I('post.');
        $password['opwd'] = ao_mi($password['userid'], $password['opwd']);
        $password['password'] = ao_mi($password['userid'], $password['password']);
        $user = M('user');
        $map['userid'] = $password['userid'];
        $data = $user->where($map)->find();
        if ($password['opwd'] == $data['password']) {
            $user->data($password)->save();
            echo "success";
            return;
        } else {
            echo "error";
            return;
        }
    }

    /*
     * 用户信息保存
     */

    public function saveAction() {
        //判断是否传入
        $userM = M('User');
        $userM->create();
        $resUp = $userM->save();
        if ($resUp === null) {
            $resData['status'] = 'error';
            $resData['msg'] = '操作失败';
        } else {
            $resData['status'] = 'success';
            $resData['msg'] = '操作成功';
        }
        $url = U('index');
        if ($resData['status'] == 'success') {
            $this->success('操作成功', $url, 2);
        } else {
            $this->error('操作失败', $url);
        }
    }

}
