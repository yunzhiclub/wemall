<?php
/*
 * 关注送红包
 * author:panjie  email:3792535@qq.com
 */
namespace Active\Controller;
use Admin\Controller\AdminController;
use SetRedpacket\Model\SetRedpacketModel;
class SendRedGiftController extends AdminController{
    public function indexAction()
    {
        
        $setPacketM = new SetRedpacketModel();
        $res = $setPacketM->init();
        $this->assign('list', $res);
        $this->assign('update', U('update'));
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    //更新操作
      public function updateAction(){
        $setPacketM = new SetRedpacketModel();
        $data = $setPacketM->create();
        $data['id'] = I('post.id');
        $data['old_state'] = I('post.old_state',0);
        $data['new_state'] = I('post.new_state',0);
        $data['old_min_value'] = huansuan(I('post.old_min_value'));
        $data['old_max_value'] = huansuan(I('post.old_max_value'));
        $data['new_min_value'] = huansuan(I('post.new_min_value'));
        $data['new_max_value'] = huansuan(I('post.new_max_value'));
        $data['old_max_num'] = I('post.old_max_num');
        $data['attention_give'] = I('post.attention_give',0);
        $data['wishing'] = trim(I('post.wishing'));
        $data['share_url'] = trim(I('post.share_url'));
        $setPacketM->save($data);
        $urlIndex = U('Active/SendRedGift/index');
        redirect_url($urlIndex);
                
      }
}
