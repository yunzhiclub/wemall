<?php

/* 
 * 梦云智工作室
 *   * 
 */

 namespace Active\Model;
 use Think\Model;
 class ActiveModel extends Model{
     private $CustomerId;//被邀请者编号
     private $parentId;//邀请者编号
     //设置被邀请者id
     public function setCustomerId($customerId){
        $this->CusotomerId = $customerId;
     }
     public function setopenId($openId){
        $this->openId = $openId;
     }
     public function setParentId($parentId){
        $this->parentId = $parentId;
     } 
     
     /*
      * 获取老用户送券
      */
    public function getOldCustomerSendCouponInfo(){
        $map['object'] = 0; //0为老客户
        return $this->where($map)->find();
    }
    /*
      * 获取新用户送券
      */
    public function getNewCustomerSendCouponInfo()
    {
        $map['object'] = 1; //1为新客户
        return $this->where($map)->find();
    }
     //注册发放优惠券
      public function subscribe(){
                $act = M('active');
                $res = $act->find();
                if($res[state] = 1)
                {
                    if($this->parentId == 0)
                        {
                            $map0 = array();
                            $map0[object] = 0;
                            $res0 = $act->where($map0)->find();
                            $coupon = M('coupon');
                            for($i=0;$i<$res0['count'];$i++){
                                $data1 = array();
                                $data1[cover] = $res0[cover];
                                $data1[duration] = $res0[duration];
                                $data1[start_time] = time();
                                $data1[end_time] = time()+$res0[duration]*24*60*60;
                                $data1[openid] = $this->openId;
                                $coupon->add($data1);
                                return 2;
                                }
                        }
                        else{
                            $map0 = array();
                            $map0[object] = 0;
                            $map1 = array();
                            $map1[object] = 1;
                            $res0 = $act->where($map0)->find();
                            $res1 = $act->where($map1)->find();
                            $coupon = M('coupon');
                            $customer = M('customer');
                            $map2[id] = $this->parentId;
                            $customerid = $customer->where($map2)->field('openid')->find();
                            for($i=0;$i<$res0['count'];$i++){
                                $data1 = array();
                                $data1[cover] = $res0[cover];
                                $data1[duration] = $res0[duration];
                                $data1[start_time] = time();
                                $data1[end_time] = time()+$res0[duration]*24*60*60;
                                $data1[openid] = $this->openId;
                                $coupon->add($data1);
                            }
                            for($i=0;$i<$res1['count'];$i++){
                                $data2 = array();
                                $data2[cover] = $res1[cover];
                                $data2[duration] = $res1[duration];
                                $data2[start_time] = time();
                                $data2[end_time] = time()+$res1[duration]*24*60*60;
                                $data2[openid] = implode("", $customerid);
                                $coupon->add($data2);
                            }
                        }
                }else{
                    //
                }
    }
 }