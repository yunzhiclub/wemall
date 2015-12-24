<?php
namespace Goods\Model;
use Think\Model;
/* 
 * 梦云智工作室
 *   * 
 */


/**
 * Description of CustomerModel
 *用于加入购物车是查询对应的userID
 * @author XINGYANNIAN
 */
class CustomerModel extends Model {
    private $openId;
    public function getUserId() {
        $map['openid'] = $this->openId;
        
        $res = $this->where($map)->getField('id');
        return $res;
    }

    public function setOpenId($openId) {
        $this->openId = $openId;
    }
    


    
}