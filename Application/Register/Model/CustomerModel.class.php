<?php
namespace Register\Model;
use Think\Model;
class CustomerModel extends Model
{
    private $openid = null;
    private $weixinId = null; //微信号
    
    function setWeixinId($weixinId) {
        $this->weixinId = $weixinId;
    }

    public function setOpenid($openid)
    {
        $this->openid = $openid;
    }
    /*
     * 添加用户。
     * 信息openid 
     */
    public function addCustomer()
    {
        $data['openid'] = $this->openid;
        $this->add($data);
        return;
    }
    
    public function updateState()
    {
        if($this->openid == null || $this->weixinId == null)
        {
            return false;
        }
        $map['openid'] = $this->openid;
        $data = $this->where($map)->find();
        $data['subscribe_state'] = (int)$data['subscribe_state'] | 1;
        $data['weixinid'] = $this->weixinId;
        $this->data($data)->save();
        return;
    }
    
}

