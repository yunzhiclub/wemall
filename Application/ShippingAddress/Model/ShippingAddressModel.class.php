<?php

/*
 * 梦云智工作室
 *   * 
 */

/**
 * Description of ShoppingAddressModel
 *
 * @author xlj
 */
namespace ShippingAddress\Model;
use Think\Model;
class ShippingAddressModel extends Model {
    public function init(){
        $res = $this->select();
        return $res;
    }
}
