<?php

/* 
 * 梦云智工作室
 *   * 
 * 客户表
 * 给订单提供数据
 * ao
 * 2015-5-2 17:27:48
 */
namespace OrderForm\Model;
use Think\Model;
class CustomerModel extends Model{
    private $openId;
    public function setOpenId($openId){
        $this->openId = $openId;
    }
    public function getCustomerInf(){
        $res = $this->field('nickname,phone_number')->where( 'openid='."'$this->openId'")->find();
        return $res;
    }
}