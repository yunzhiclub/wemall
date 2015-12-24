<?php
namespace Active\Controller;
use Admin\Controller\AdminController;
class CouponController extends AdminController  {
    //用于用户注册时为注册者与被注册者发放优惠券        
    private $CustomerId;//被邀请者编号
    private $parentid;//邀请者编号
    //设置被邀请者id
    public function setCustomerId($customerId){
        $this->CusotomerId = $customerId;
    }
    public function setParentId($parentId){
        $this->parentId = $parentId;
    }
    //注册时应用的为邀请者与被邀请者发送优惠券
    public function subscribeAction() {
        $active = M('active');
        $a = $active->field('state')->find();
        if($a[state]==1){
                $map0 = array();
                $map0[object] = 0;
                $map1 = array();
                $map1[object] = 1;
                $res0 = $active->where($map0)->find();
                $res1 = $active->where($map1)->find();
                $coupon = M('coupon');
                for($i=0;$i<$res0['count'];$i++){
                    $data1 = array();
                    $data1[cover] = $res0[cover];
                    $data1[duration] = $res0[duration];
                    $data1[start_time] = time();
                    $data1[end_time] = time()+$res0[duration]*24*60*60;
                    $data1[customerid] = 23;//$this->CustomerId;
                    $coupon->add($data1);
                }
                for($i=0;$i<$res1['count'];$i++){
                    $data2 = array();
                    $data2[cover] = $res1[cover];
                    $data2[duration] = $res1[duration];
                    $data2[start_time] = time();
                    $data2[end_time] = time()+$res1[duration]*24*60*60;
                    $data2[customerid] = 23;//$this->parentId;
                    $coupon->add($data2);
                }
            echo $string = "数据添加成功";
        }else{
            echo $string = "该活动已停止";
        }
    }
    //用户查看自己的优惠券
    public function myCouponAction(){
        $coupon = M('coupon');
        $time = time();
        $map = array();
        $map['customerid'] = array('eq',21);//$this->CustomerId);
        $map['end_time'] = array('lt',$time);
        $map['state'] = 1;
        $res = $coupon->order("end_time desc")->where($map)->select();
        var_dump($res);
    }
    //手动发放优惠券
    public function sendCouponAction(){
        for($i=0;$i<I('post.value');$i++)
        {
            $data = array();
            $data['cover'] = I('post.cover');
            $data['duration'] = I('post.duration');
            $data['start_time'] = time();
            $data['end_time'] = time()+I('post.duration')*24*60*60;
            $data['customerid'] = I('post.customerid');
            $active = M("coupon");
            $res = $active->add($data);
        }
    }
    public function sentCouponAction(){
        $coupon = M('coupon');
        $res = $coupon->select();
        $customer = M('customer');
        foreach ($res as $key => $value) {                        
            $map = array();
            $map['id'] = $value['customerid'];
            $res[$key][openid] = implode('',$customer->where($map1)->field('openid')->find());
            $res[$key][phone_number] = implode('',$customer->where($map1)->field('phone_number')->find());
        }
        var_dump($res);
    }
    public function usedCouponAction(){
        $coupon = M('coupon');
        $map = array();
        $map['state'] = 0;
        $res = $coupon->where($map)->select();
        $customer = M('customer');
        foreach ($res as $key => $value) {                        
            $map1 = array();
            $map1['id'] = $value['customerid'];
            $res[$key][openid] = implode('',$customer->where($map1)->field('openid')->find());
            $res[$key][phone_number] = implode('',$customer->where($map1)->field('phone_number')->find());
        }
        var_dump($res);
    }    
}