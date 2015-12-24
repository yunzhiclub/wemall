<?php

/**
 * Description of CouponModel
 *
 * @author xlj
 */
namespace OrderForm\Model;
use Think\Model;
class CouponModel extends Model{
    
    public function getCoupon($openid) {
        $map['openid'] = $openid;
        $map['state'] = 1;
        $res = $this->where($map)->select();
        foreach($res as $key => $value)
        {
            if($value['end_time'] >= time())
            {
                $coupon[] = $value;
            }
        }
        return $coupon;
    }
    
    /*
     * 输入一维数组
     * 返回带有优惠券信息的二维数组
     */
    public function getInfo($couponArr,$openid)
    {
        if(!is_array($couponArr))
        {
            return false;
        }
        $map['openid'] = $openid;
        foreach($couponArr as $key => $value)
        {
            $map['id'] = $value;
            $couponArr[$key] = $this->where($map)->find();
        }
        return $couponArr;
    }
    
    /*
     * 将使用过的优惠券做状态处理
     */
    public function updateUsed($coupon)
    {
        foreach($coupon as $key => $value)
        {
            $map['id'] = $value;
            $res = $this->where($map)->find();
            $state = (int)$res['state'] & 0;
            $map['state'] = $state;
            $map['use_time'] = time();
            $this->data($map)->save();
        }
        return true;
    }
}
