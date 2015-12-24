<?php

namespace User\Controller;

use Admin\Controller\AdminController;

class IndexController extends AdminController {
    /*
     * 权限判断
     * 1为普通用户，0为管理员
     * 普通用户无查看此模块的权利
     */

    public function _initialize() {
        parent::_initialize();
        $userId = session("userId");
        $role = $this->_getUserInfoById($userId);
        if ((int) $role == 1) {
            $html = '无此权限';
            $this->assign("YZRight", $html);
            
            $this->display(YZ_TEMPLATE);
            die();
        }
    }

    //初始化用户管理
    public function indexAction() {
        $User = D('User');
        $res = $User->init();
        //page分页格式
        $currentPage = I('get.p', 1);
        //这个get.p是干嘛的？
        $page = $this->page;
        $page->setCounts(count($res));
        $page->setCurrentPage($currentPage);
        $page->setPageStyle(2);
        $pageStr = $page->fetch();
        $this->assign('page', $pageStr);
        $pageSise = $page->getPageSize();
        //截取本页内容
        $userList = array_slice($res, ($currentPage - 1) * $pageSise, $pageSise);
        //使用模板
        foreach ($userList as $key => $value) {
            $userList[$key]['actionUrl'] = array('edit' => U('edit?id=' . $value['userid']), 'delete' => U('delete?id=' . $value['userid']), 'reset' => U('reset?id=' . $value['userid']));
        }        
        $this->assign('UserList', $userList);
        $this->assign('YZRight', $this->fetch());
        $this->display(YZ_TEMPLATE);
    }

    //修改用户信息
    public function editAction() {
        //判断是否传入
        $id = I('get.id', 0);
        if ($id === 0) {
            $resData['status'] = 'error';
            $resData['msg'] = '未接收到id值';
        } else {
            $map['userid'] = $id;
            $userM = M('User');
            $data = $userM->where($map)->find();
            $data['actionUrl'] = array('update' => U('update'));
            $url = U('index');
            $this->assign('url', $url);
            $this->assign('User', $data);
            $this->assign('YZRight', $this->fetch());
            $this->display(YZ_TEMPLATE);
        }
    }

    public function updateAction() {
        //判断是否传入
        $userM = M('User');
        $userM->create();
        if (I('post.userid') == '') {//如果未传ID值，则插入数据
            $resUp = $userM->add();
        } else {
            $resUp = $userM->save();
        }
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

    public function deleteAction() {
        //判断是否传入
        $id = I('get.id', 0);
        if ($id === 0) {
            $resData['status'] = 'error';
            $resData['msg'] = '未接收到id值';
        } else {
            $map['userid'] = $id;
            $userM = M('User');
            $resDel = $userM->where($map)->delete();
            if ($resDel === null) {
                $resData['status'] = 'error';
                $resData['msg'] = '删除失败';
            } else {
                $resData['status'] = 'success';
                $resData['msg'] = '删除成功';
            }
        }
        $url = U('index');
        if ($resData['status'] == 'success') {
            $this->success('删除成功', $url, 2);
        } else {
            $this->error('删除失败', $url);
        }
        //return $this->ajaxReturn($resData); 
    }

    public function resetAction() {
        $id = I('get.id', 0);
        if ($id === 0) {
            $resData['status'] = 'error';
            $resData['msg'] = '未接收到id值';
        } else {
            $map['userid'] = $id;
            $userM = M('User');
            $pwd['password'] = '123456';
            $pwd['password'] = ao_mi($map['userid'], $pwd['password']);
            $resDel = $userM->where($map)->save($pwd);
            if ($resDel === null) {
                $resData['status'] = 'error';
                $resData['msg'] = '重置失败';
            } else {
                $resData['status'] = 'success';
                $resData['msg'] = '重置成功';
            }
        }
        $url = U('index');
        if ($resData['status'] == 'success') {
            $this->success('重置成功', $url, 2);
        } else {
            $this->error('重置失败', $url);
        }
    }

    /*
     * 通过id获取某用户的权限值，以进行判断
     */

    private function _getUserInfoById($userId = null) {
        if ($userId == null) {
            return false;
        }
        $user = M('user');
        $map['userid'] = $userId;
        $res = $user->where($map)->find();
        $role = $res['role'];
        return $role;
    }

}
