<?php
namespace Search\Model;
use Think\Model;
/*
 * 梦云智工作室
 *   * 
 */

/**
 * Description of OrderFormModel
 *
 * @author XINGYANNIAN
 */
class OrderFormModel extends Model {
    private $queryAdd;
    private $openId;
    public function getOpenId() {
        return $this->openId;
    }

    public function setOpenId($openId) {
        $this->openId = $openId;
    }

        public function getQueryAdd() {
        return $this->queryAdd;
    }
    public function setQueryAdd($queryAdd) {
        $this->queryAdd = $queryAdd;
    }
    public function getOrderFormInfo(){
        $map['id'] = $this->queryAdd;
        $map['openid'] = $this->openId;
        $res = $this->where($map)->select();
        return $res;   
    }


}
