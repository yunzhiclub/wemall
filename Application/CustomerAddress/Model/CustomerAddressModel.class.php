<?php

/*
 * 梦云智工作室
 *   * 
 */
namespace CustomerAddress\Model;
use Think\Model;
/**
 * Description of CustomerAddressModel
 *客户守护地址模块
 * @author XINGYANNIAN
 */
class CustomerAddressModel extends Model{
    private $openid;
    private $customerName;
    private $address;
    private $phone;
    private $frontid;//身份证正面id
    private $backid;//背面id
    private $id_no;//身份证号
    private $last_used_time;//上次使用时间
    private $id;//id
    private $postData ; //POST数据
    public function getOpenid() {
        return $this->openid;
    }
    function setPostData($postData) {
        $this->postData = $postData;
    }
    
    public function getName() {
        return $this->customerName;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getFrontid() {
        return $this->frontid;
    }

    public function getBackid() {
        return $this->backid;
    }

    public function getId_no() {
        return $this->id_no;
    }

    public function getLast_used_time() {
        return $this->last_used_time;
    }

    public function getId() {
        return $this->id;
    }

    public function setOpenid($openid) {
        $this->openid = $openid;
    }

    public function setName($name) {
        $this->customerName = $name;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function setFrontid($frontid) {
        $this->frontid = $frontid;
    }

    public function setBackid($backid) {
        $this->backid = $backid;
    }

    public function setId_no($id_no) {
        $this->id_no = $id_no;
    }

    public function setLast_used_time($last_used_time) {
        $this->last_used_time = $last_used_time;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    public function __construct() {
        parent::__construct();
    }
    
    public function addAddress() {
        $data['openid'] = $this->openid;
        $data['address'] = $this->address;
        $data['backid'] = $this->backid;
        $data['frontid'] = $this->frontid;
        $data['id_no'] = $this->id_no;
        $data['last_used_time'] = time();
        $data['phone'] = $this->phone;
        $res = $this->add($data);
        return $res;
    }
    public function deleteDataById($id)
    {
        $map['id'] = $id;
        $this->where($map)->delete();
    }
    /*保存传入的POST(GET)数据,有关键字，则更新
     * 无关健字，则添加
     */
    public function savePostAddress()
    {
        $data  = $this->postData;
        $data['last_used_time'] = time();
        if($data == null)
        {
            return false;
        }
        if($data['id'] == '')
        {
            unset($data['id']);
            $lastid = $this->data($data)->add();
            return $lastid;
        }
        else
        {
            unset($data['openid']);
            $this->data($data)->save();
            return $data['id'];
        }
    }
    /*
     * 取收获地址信息
     * 如果传入的是id值，则取对应的1条。
     * 如果传入的openid值，则取多条
     */
    public function getAddress() {
        if($this->openid == null)
        {
            $map['id'] = $this->id;
            $res = $this->where($map)->find();
        }
        else
        {
            $map['openid'] = $this->openid;
            $res = $this->where($map)->order('last_used_time desc')->select();
        }
        
        
        return $res;
    }
    
    public function getLastUseAdderss() {
        $map['openid'] = $this->openid;
        $res = $this->where($map)->order('last_used_time desc')->find();
        return $res;//成功返回一条记录
    }
    
    public function updateTime() {
        $map['id'] = $this->id;
        $data['last_used_time'] = time();
        $res = $this->where($map)->save($data);
        return $res;//更新成功返回所影响的记录数，这里为1.否则返回false
    }
}