<?php
/*
 * 个人中心-我的优惠券--查看
 */
namespace User\Controller;
use User\Controller\UserController;
use Coupon\Model\CouponModel;   //优惠券

class CouponController extends UserController
{
    public function _initialize() {
        $title = '我的优惠券';
        $this->assign('title',$title);
        parent::_initialize();
    }
    /*
     * 初始化
     * 取出使用与未使用的优惠券信息
     */
    public function indexAction()
    {
        $openid = get_openid();
        $coupon = new CouponModel();
        $unUsedCoupons = $coupon->getUnusedCouponsByOpenid($openid);
        $usedInCoupons = $coupon->getUsedCouponsByOpenid($openid);

        $this->assign('unUsedCoupons',$unUsedCoupons);
        $this->assign('usedInCoupons',$usedInCoupons);
       
        $this->assign("YZBody",$this->fetch('index'));
        $this->display(YZ_TEMPLATE);
    }
}

