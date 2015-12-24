<?php

/*
 * 梦云智工作室
 *   * 
 */

/**
 * Description of OrderRelationModel
 *
 * @author xlj
 */
namespace Pay\Model;
use Think\Model;
class OrderRelationModel extends Model{
   public function getPay($payid) {
        $map['id'] = $payid;
        $res = $this->where($map)->find();
        return $res;
    }
}
