<?php

/*
 * 梦云智工作室
 *   * 
 */

namespace Customer\Model;

use Think\Model;

class CustomerModel extends Model {
    private $map = null;
    
    public function setMap($map) {
        $this->map = $map;
    }

    //初始化客户管理
    public function init() {
        $Page = new \Think\Page($count, 10);
        $map[subscribe_state] = 1;
        $res = $this->where($map)->order('subscribe_time desc')->select();
        $name = array();
        foreach ($res as $key => $value) {
            $name[] = $this->where('id=' . $value[parentid])->field('nickname')->find();
        }
        $subcribeState = 'subscribe_state';
        $freezen = 'freezen_state';
        for ($i = 0; $i < count($name); $i++) {
            $res[$i][parent_name] = $name[$i][nickname];
            if ((int) $res[$i][$subcribeState] == 0) {
                $res[$i][$subcribeState] = "未注册";
            } else {
                $res[$i][$subcribeState] = "已注册";
            }
            if ((int) $res[$i][$freezen] == 0) {
                $res[$i][$freezen] = "解冻";
            } else {
                $res[$i][$freezen] = "冻结";
            }
        }
        return $res;
    }

    public function getCountByMap()
    {
        return $this->where($this->map)->count();
    }
    public function getDataArrByMap($currentPage,$pageSize)
    {
        return $this->where($this->map)->page($currentPage,$pageSize)->order("subscribe_time desc")->select();
    }
    
    public function changeCustomerFreezenState($id)
    {
        $map['id'] = $id;
        $res = $this->where($map)->find();
        $res['freezen_state'] = $res['freezen_state'] ^ 1;
        $this->save($res);
    }
    public function getAllDataArrByMap()
    {
        return $this->where($this->map)->order('subscribe_time desc')->select();
    }
    
    //未注册用户初始化
    public function unsubscribe() {
        $Page = new \Think\Page($count, 10);
        $map[subscribe_state] = 0;
        $res = $this->where($map)->order('subscribe_time desc')->select();
        $name = array();
        foreach ($res as $key => $value) {
            $name[] = $this->where('id=' . $value[parentid])->field('nickname')->find();
        }
        $subcribeState = 'subscribe_state';
        $freezen = 'freezen_state';
        for ($i = 0; $i < count($name); $i++) {
            $res[$i][parent_name] = $name[$i][nickname];
            if ((int) $res[$i][$subcribeState] == 0) {
                $res[$i][$subcribeState] = "未注册";
            } else {
                $res[$i][$subcribeState] = "已注册";
            }
            if ((int) $res[$i][$freezen] == 0) {
                $res[$i][$freezen] = "解冻";
            } else {
                $res[$i][$freezen] = "冻结";
            }
        }
        return $res;
    }

    //客户管理编辑功能
    public function edit() {
        $id = I('get.id');
        $map = array();
        $map['id'] = $id;
        $res = $this->where($map)->field('nickname,weixinid,aipayid,phone_number,parentid')->find();
        return $res;
    }

    //更新操作
    public function update() {
        $this->create();
        $res = $this->save();
        return $res ;
    }

    //冻结或解冻用户
    public function freezen() {
        $id = I('get.id');
        $map = array();
        $map['id'] = $id;
        $data = $this->where($map)->find();
        if ($data[subscirbe_state == 0]) {
            $key = 0;
        }else{
            $key = 1;
        }
        if ($data['freezen_state'] == 0) {
            $data['freezen_state'] = 1;
            $this->where($map)->save($data);
        } else {
            $data['freezen_state'] = 0;
            $this->where($map)->save($data);
        }
        return $key;
    }

    public function look() {
        $id = I('get.id');
        $map = array();
        $map['id'] = $id;
        $res = $this->where($map)->find();
        $a = array();
        $a[id] = $res[parentid];
        $array = $this->field(nickname)->where($a)->find();
        $res[parent_name] = implode("", $array);
        return $res;
    }

    /*
     * $userinfo是个二维数组
     * 每一个行均代表一个用户信息
     * 添加或更新用户的基本信息
     * @userInfo 二维数组
     * 1.查询，没有则添加
     * 2.有则更新
     * 2.1上级为0，则更新上下级关系
     * 2.2上级不为0，则不更新上下级关系
     */

    public function addUserInfo($userInfo) {
        foreach ($userInfo as $key => $value) {
            $map['openid'] = $value['openid'];
            $customer = $this->where($map)->find();
            if ($customer == FALSE) {
                //没有则添加
                $this->data($value)->add();
            } else {
                /*
                 * 有则更新。看上级是否为0，即（系统）
                 * 决定是否更新上级parendid
                 */
                if (array_key_exists('parentid', $value)) {
                    if ($customer['parentid'] != 0) {
                        unset($value['parentid']);
                    }
                }
//                if( array_key_exists('parentid', $value) )
//                {
//                    if($customer['parentid'] != 0)
//                    {
//                        unset($value['parentid']);
//                    }                    
//                }
                $this->data($value)->where($map)->save();
            }
        }
        return;
    }

    /*
     * 判断产生的邀请码
     * 如果为abcd则迭代
     * 如果为表内原有，则迭代
     */
    public function checkCode(){
        $code = $this->_checkAbcd();
        $code = $this ->_checkDif($code);
        if($code == FALSE){
            $code = $this->checkCode();
        }
        return $code; 
    }
    //判断是否为abcd
    private function _checkAbcd(){
       $code = $this->_create_noncestr();
        if($code == "abcd"){
            $this->_checkAbcd();
        } 
        return $code;
    }
    //判断是否相同
    private function _checkDif($code){
        
        $res = $this->select();
        foreach ($res as $key =>$value){
            if($value['customer_code']==$code){
                return FALSE;
            }
        }
        return $code;
    }


    /*
     * 
     * 作用：产生随机字符串，不长于4位.
     * 输入大于0小于32的INT
     * 输出：指定位数的随机数,默认为4位
     */
    private function _create_noncestr($length = 4){
        $chars = "abcdefghijklmnopqrstuvwxyz";  
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {  
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
        }  
        return $str;
    }
    /*
     * 更新用户状态信息
     */

    public function updateState($data) {
        $map['openid'] = $data['openid'];
        $this->data($data)->where($map)->save();
        return;
    }

    public function fetchcode() {
        $code = $this->cache('code')->select();
        return $code;
    }

    /*
     * 通过openid获取客户信息
     */

    public function getCustomerInfo($openid) {
        $map['openid'] = $openid;
        return $this->where($map)->find();
    }

    /*
     * 获取所以下线的OPENID,返回数据
     */
    public function getLineOpenids($openid)
    {
        $customer = $this->getCustomerInfo($openid);
        $map['parentid'] = $customer['id'];
        return $this->where($map)->select();
    }
    
    public function getCustomerInfoById($id)
    {
        $map['id'] = $id;
        return $this->where($map)->find();
    }
    /*
     * 通过OPENID获取客户信息
     * $key 指定的key
     * $resKey 返回的key
     */

    public function getCustomerByOpenid($noPayOrder, $key, $resKey) {
        foreach ($noPayOrder as $k => $v) {
            $map['openid'] = $v[$key];
            $noPayOrder[$k][$resKey] = $this->where($map)->find();
        }
        return $noPayOrder;
    }
    //更改用户手机号
    public function changPhone(){
        $id = I('get.id');
        $data = I('post.');
        $map['id'] = $id;
        $this->where($map)->save($data);
        $resData['status'] = 'success';
        $resData['msg'] = ' 操作成功';
        return $resData;
    }
     /*
     * 更改注册状态
     */
    public function changestate(){
        $id = I('get.id');
        $map = array();
        $map['id'] = $id;
        $data = $this->where($map)->find();
        $data['subscribe_state'] = 1;
        $key=$this->where($map)->save($data);
        return $key;
    }
    
    /*
     * 获取所有已注册用户的列表
     */
    public function getRegister()
    {
        $map['subscribe_state'] = 1;
        $res = $this->where($map)->select();
        return $res;
    }
}
