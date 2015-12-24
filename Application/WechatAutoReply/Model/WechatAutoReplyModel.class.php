<?php
/*
 * 微信自动回复
 * 主要为：关注回复，关键字回复
 */
namespace WechatAutoReply\Model;
use Think\Model;
class WechatAutoReplyModel extends Model{
    public function getInitData()
    {
        $res = $this->select();
        return $res;
    }
}

