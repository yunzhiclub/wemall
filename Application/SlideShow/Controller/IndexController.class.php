<?php

namespace SlideShow\Controller;

use Admin\Controller\AdminController;

class IndexController extends AdminController {

    private $slide = null; //菜单表

    public function __construct() {
        parent::__construct();
        $this->slide = D('SlideShow');
    }

    /*
     * 幻灯片模块初始化
     */

    public function indexAction() {
        $model = $this->slide;
        $slideM = $model->init();                     //取幻灯片表里面的所有数据
        $goodsM = D('Goods');                          //取商品表里面的商品描述
        $goods = $goodsM->getName($slideM);
        foreach ($slideM as $key => $value) {
            $value['describe'] = $goods[$key]['name'];
            if ($value['status'] === '1') {
                $value['status'] = "启用";
            } else if ($value['status'] === '0') {
                $value['status'] = "禁用";
            }
            $attachmentInfo = D('Attachment/Attachment');
            //设置关键字：即哪个字段代表的附件ID
            $attachmentInfo->setKey('attchment_id');
            //取出附件信息,信息进行替换后，返回
            $res[] = $attachmentInfo->findInfo($value);
        }
        foreach ($res as $key => $value) {
            $res[$key]['actionUrl'] = array('edit' => U('edit?id=' . $value['id']), 'delete' => U('delete?id=' . $value['id']), 'add' => U('add?id=' . $value['id']));
        }

        /*         * ****************************分页管理******************************* */
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
        $slideS = array_slice($res, ($currentPage - 1) * $pageSise, $pageSise);
        //使用模板
        /*         * ******************************************************************* */
        $this->assign('SlideShow', $res);
        $this->assign('slideS', $slideS);
        $this->assign('addSlideUrl', U('add?id=0'));
        $this->assign(url, $url);
        $this->assign('YZRight', $this->fetch());
        $this->display(YZ_TEMPLATE);
    }

    /*
     * 修改幻灯片内容及图片
     */

    public function editAction() {
        $id = I('get.id', 0);
        if ($id == 0) {
            $resData['status'] = 'error';
            $resData['msg'] = '未接收到id值';
        } else {
            $map['id'] = $id;
            $menuM = $this->slide;
            $data = $menuM->where($map)->find();
            /*             * *****************拼接附件信息****************** */
            $attachmentInfo = D('Attachment/Attachment');
            //设置关键字：即哪个字段代表的附件ID
            $attachmentInfo->setKey('attchment_id');
            //取出附件信息,信息进行替换后，返回
            $data = $attachmentInfo->findInfo($data);
            /** ************************************************ */
            $goodsM = D('Goods');
            $res = $goodsM->getTop();
            //取商品表中前十项
            $data['actionUrl'] = array('update' => U('update'));
            $url = U('index');
            $this->assign('url', $url);
            $this->assign('slide', $data);
            $this->assign('goods', $res);
            $this->assign('YZRight', $this->fetch());
            $this->display(YZ_TEMPLATE);
        }
    }

    /*
     * 添加新的幻灯片内容及图片
     */

    public function addAction() {
        //echo "成功调用幻灯片设置下的add（）方法";
        $goodsM = D('Goods');
        $res = $goodsM->getTop();
        $this->assign('goods', $res);
        $info = $this->slide->info();
        //取商品表中前十项
        $data = array();
        $data['actionUrl'] = array('append' => U('append'));
        //以上两行，单纯是为了form提供一个action
        $url = U('index');
        $this->assign('info', $info);
        $this->assign('url', $url);
        $this->assign('slide', $data);
        $this->assign('YZRight', $this->fetch());
        $this->display(YZ_TEMPLATE);
    }

    /*
     * 对新增的内容进行保存
     */

    public function AppendAction() {
        $slideShow = D('SlideShow');
        //涉及到附件上传，传入upload对象
        $slideShow->setUpload($this->upload);
        $res = $slideShow->appendS();
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

    /*
     * 删除某幻灯片内容，并跳转至首页
     */

    public function DeleteAction() {
        $id = I('get.id', 0);
        if ($id == 0) {
            $resData['status'] = 'error';
            $resData['msg'] = '未接收到id值';
        } else {
            $map['id'] = $id;
            $menuM = $this->slide;
            $resDel = $menuM->where($map)->delete();
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

    /*
     * 更新修改后的幻灯片内容及图片
     */

    public function UpdateAction() {
        $slideShow = D('SlideShow');
        //涉及到附件上传，传入upload对象
        $slideShow->setUpload($this->upload);
        $res = $slideShow->update();
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

}
