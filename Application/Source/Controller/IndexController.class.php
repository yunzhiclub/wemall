<?php

namespace Source\Controller;

use Admin\Controller\AdminController;

class IndexController extends AdminController {

    public function indexAction() {
        $source = D('source');
        $data = $source->init();
        foreach ($data as $key => $value) {
            $attachmentInfo = D('Attachment/Attachment');
            //设置关键字：即哪个字段代表的附件ID
            $attachmentInfo->setKey('attachment_id');
            //取出附件信息,信息进行替换后，返回
            $res[] = $attachmentInfo->findInfo($value);
        }
        foreach ($res as $key => $value) {
            $res[$key]['actionUrl'] = array('edit' => U('edit?id=' . $value['id']), 'delete' => U('delete?id=' . $value['id']));
        }
        /** ****************************分页管理******************************* */
        //page分页格式
        $currentPage = I('get.p', 1);
        $page = $this->page;
        $page->setCounts(count($res));
        $page->setCurrentPage($currentPage);
        $page->setPageStyle(2);
        $pageStr = $page->fetch();
        $this->assign('page', $pageStr);
        $pageSise = $page->getPageSize();
        //截取本页内容
        $sourceS = array_slice($res, ($currentPage - 1) * $pageSise, $pageSise);
        //使用模板
        /** ******************************************************************* */
        $this->assign('Source', $sourceS );
        $this->assign('actionUrl', U('add'));
        $this->assign('YZRight', $this->fetch());
        $this->display(YZ_TEMPLATE);
    }

    public function addAction() {
//        echo "this is source add";
        $actionUrl = U('append');
        $url = U('index');
        $model = D('Source');
        $info =$model ->info();
        $infoN =$model ->infoNum();
        //info是为了存附件id，以使图片显示正常
        $this->assign('url', $url);
        $this->assign('info', $info);
        $this ->assign('infoN',$infoN);
        $this->assign('actionUrl',$actionUrl);
        $this->assign('YZRight', $this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    public function appendAction(){
        $source = D('Source');
        //涉及到附件上传，传入upload对象
        $source->setUpload($this->upload);
        $res = $source->appendS();
        if ($res == null) {
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

    public function editAction() {
        $id = I('get.id', 0);
        if ($id == 0) {
            $resData['status'] = 'error';
            $resData['msg'] = '未接收到id值';
        } else {
            $map['id'] = $id;
            $source = D("source");
            $info =$source ->info();
            $res = $source->where($map)->find();
            /* *****************拼接附件信息****************** */
            $attachmentInfo = D('Attachment/Attachment');
            //设置关键字：即哪个字段代表的附件ID
            $attachmentInfo->setKey('attachment_id');
            //取出附件信息,信息进行替换后，返回
            $data = $attachmentInfo->findInfo($res);
            /** ************************************************ */
            $data['actionUrl'] =U('update');
            $url = U('index');
            $this ->assign('info',$info);
            $this->assign('url', $url);
            $this->assign('Source', $data);
            $this->assign('YZRight', $this->fetch());
            $this->display(YZ_TEMPLATE);
        }
    }
    public function updateAction(){
        $source = D('Source');
        //涉及到附件上传，传入upload对象
        $source->setUpload($this->upload);
        $res = $source->update();
        if ($res === null) {
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
        $id = I('get.id', 0);
        if ($id == 0) {
            $resData['status'] = 'error';
            $resData['msg'] = '未接收到id值';
        } else {
            $map['id'] = $id;
            $source = D('Source');
            $resDel = $source->where($map)->delete();
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
