<?php

/*
 * 梦云智工作室
 *   * 
 */

namespace User\Model;

use Think\Model;

class ChangePhoneModel extends Model {
    private $openid; //客户openid
    private $newphone;//商品编号
    public function setOpenid($openid) {
        $this->openid = $openid;
    }

    public function setNewPhone($phone) {
        $this->newphone = $phone;
    }
    //更新用户新手机号    
    public function change(){
        $map = array();
        $map[id] = $this->openid;
        $data = array();
        $data[phone_number] = $this->newphone;
        $cus = M('customer');
        $cus->where($map)->save($data);
    }
}