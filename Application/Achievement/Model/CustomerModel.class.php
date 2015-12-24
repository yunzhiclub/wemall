<?php

/* 
 * 梦云智工作室
 *   * 
 */
namespace Achievement\Model;
use Think\Model;
class CustomerModel extends Model{
    private $openId;
    private $userId;
    public function setUserId($userId){
        $this->openId = $userId;
    }
    public function getJunior(){
        $data = $this->field('id')->where( 'openid='."'$this->openId'")->find();
        $this->userId = $data['id'];
        $res = $this->field('openid,nickname')->where('parentid='.$this->userId)->select();
        return $res;
    } 
}