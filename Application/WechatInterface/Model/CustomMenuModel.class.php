<?php
/*
 * 取微信自定义菜单信息
 */
namespace WechatInterface\Model;
use Think\Model;
class CustomMenuModel extends Model
{
    private $eventKey = null;//事件值
    public function setEventKey($eventKey)
    {
        $this->eventKey = $eventKey;
    }
    public function findInfo($eventKey = null)
    {
        if($eventKey == null)
        {
            if($this->eventKey == null)
            {
                return;
            }
            else
            {
                $eventKey = $this->eventKey;
            }     
        }
        $map['keyword'] = $eventKey;
        $data = $this->where($map)->find();  
        if($data)
        {
            $attachment = D('Attachment/Attachment');
            $attachment->setKey('reply_image');
            $data = $attachment->findInfo($data);
        }
        return $data;
    }
}
