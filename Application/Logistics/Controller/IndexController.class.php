<?php

namespace Logistics\Controller;

use Admin\Controller\AdminController;

class IndexController extends AdminController {

    public function indexAction() {
//        echo "this is logistics index";
        $model = D('Logistics');
        $res = $model->init();
        foreach ($res as $key => $value) {
            $res[$key]['actionUrl'] = array('edit' => U('edit?id=' . $value['id']), 'delete' => U('delete?id=' . $value['id']));
        }
        $this->assign('actionUrl', U('add'));
        $this->assign('Log', $res);
        $this->assign('YZRight', $this->fetch());
        $this->display(YZ_TEMPLATE);
    }

    public function addAction() {
        $model = D('Logistics');
        $num = $model->getNum();
        $this->assign('Num', $num);
        $this->assign('url', U(index));
        $this->assign('actionUrl', U(save));
        $this->assign('YZRight', $this->fetch());
        $this->display(YZ_TEMPLATE);
    }

    public function saveAction() {
        $model = D('Logistics');
        $model->create();
        $res = $model->add();
        $url = U('index');
        if ($res == null) {
            $this->error('保存失败', $url, 2);
        } else {
            $this->success('操作成功', $url);
        }
    }

    public function editAction() {
        $id = I('get.id', 0);
        if ($id == 0) {
            $resData['status'] = 'error';
            $resData['msg'] = '未接收到id值';
        } else {
            $map['id'] = $id;
            $model = D('Logistics');
            $res = $model->where($map)->find();
            $this->assign('Log', $res);
            $this->assign('url', U(index));
            $this->assign('actionUrl', U(update));
            $this->assign('YZRight', $this->fetch());
            $this->display(YZ_TEMPLATE);
        }
    }
    public function updateAction(){
        $model = D('Logistics');
        $model->create();
        $res = $model->save();
        $url = U('index');
        if ($res === null) {
            $this->error('保存失败', $url, 2);
        } else {
            $this->success('操作成功', $url);
        }
        
    }
    public function deleteAction(){
        $id = I('get.id', 0);   
        if ($id == 0) {
            $resData['status'] = 'error';
            $resData['msg'] = '未接收到id值';
        } else {
            $map['id'] = $id;
            $model = D('Logistics');
            $resDel = $model->where($map)->delete();
            if ($resDel == null) {
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
    }

}
