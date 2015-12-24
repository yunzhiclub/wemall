<?php
/*
 * 自动回复模块
 * 实现用户关注后的自动回复
 * author:pan jie
 */
namespace System\Controller;
use Admin\Controller\AdminController;
use WechatAutoReply\Model\WechatAutoReplyModel;
use System\Model\ReplyModel;
use Attachment\Model\AttachmentModel;
class AutoReplyController extends AdminController{
    /*
     * 初始化 
     */
    public function indexAction()
    {
        $autoReply = new WechatAutoReplyModel();
        $initData = $autoReply->getInitData();
           
    }
    public function editAction(){
        $model = new ReplyModel();
        $pic = new AttachmentModel();
//        $res = $model ->info();
        $rel = $model ->all();
        $pic->setKey('picurl');
        $rel1 = $pic->findInfo($rel);
        $this->assign('reply',$rel1);
        $this->assign('id',$res);
        $this->assign('actionUrl',U(update));
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
        
    }
    public function updateAction(){
        $model = new ReplyModel();
        $model ->setUpload($this->upload);
        $res = $model->appendS();
        if ($res == null) {
            $resData['status'] = 'error';
            $resData['msg'] = '操作失败';
        } else {
            $resData['status'] = 'success';
            $resData['msg'] = '操作成功';
        }
        $url = U('edit');
        if ($resData['status'] == 'success') {
            $this->success('操作成功', $url, 2);
        } else {
            $this->error('操作失败', $url);
        }

        
    }
            
}
