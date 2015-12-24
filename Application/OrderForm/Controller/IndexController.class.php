<?php
namespace OrderForm\Controller;
use User\Controller\UserController;
//use OrderForm\Model\OrderRelationModel;
use OrderRelation\Model\OrderRelationModel;
use OrderForm\Model\OrderFormModel;
use OrderForm\Model\ShoppingCartModel;
use OrderForm\Model\OrderGoodsModel;
use System\Model\ConfigModel;
use OrderForm\Model\CouponModel;
use OrderForm\Model\AttachmentModel;
use OrderForm\Model\LogisticsModel;
use OrderForm\Model\SourceModel;
use OrderForm\Model\GoodsModel;
use CustomerAddress\Model\CustomerAddressModel;
class IndexController extends UserController {
    public function _initialize() { 
        $this->addCss('/theme/wemall/css/head_foot.css');
        $this->addCss('/theme/wemall/css/order-detial.css');
        $this->addCss('/theme/wemall/css/foot.css');
        $this->addCss('/theme/wemall/css/head.css');
        $this->addCss('/theme/wemall/css/shopping-cart.css');
        $this->addCss('/theme/wemall/css/reset.css');
        $this->addCss('/theme/wemall/css/xmapp.css');
        parent::_initialize();
    }
    public function indexAction(){       
        return;
    }       
    
    /*
     * 立即支付action
     * 一、接收数据
     * 1.接收传入ids,counts
     * 1.1取出第一个组商品、数量
     * 1.1.1 计算商品总重 计算运费
     * 1.1.2 计算商品的总费用
     * 1.1.2 计算商品总线销返点、总直销返点
     * 1.2剩余商品重新组成数组
     * 2.接收优惠券信息
     * 2.1 优惠券数量大于商品时，返回错误信息
     * 2.2 将优惠券ID变成数组
     * 2.3 计算总共优惠金额
     * 3.接收收货地址信息
     * 4.接收身份证电子版本信息
     * 5.接收支付类型信息
     * 
     * 二、存数据
     * 1.生成订单支付数据
     * 2.生成订单数据
     * 3.生成订单－商品数据
     * 4.删除购物车数据
     * 
     * 三、回调页面
     * 1.支付类型为微信支付,调转至微支付界面
     * 1.1加ids和counts字符串参数
     * 2.支付类型为其它，转至其它支付说明界面
     * 2.1 ids为空，提示用户跳转至我的订单(未支付)
     * 2.2 ids不为空，提示用户跳转至(立即支付)
     */
    public function payNowAction() {
        $openid= get_openid();
        $ids = I('get.ids','',false);
        $counts = I('get.counts','',false);
        $coupon = I("get.coupons",'',false);
        //字符串转为数组
        if($ids == '')
        {
            $ids = array();
        }
        else {
            $ids = explode("-",$ids);
        }
        if($counts == '')
        {
            $counts = array();
        }
        else {
            $counts = explode("-",$counts);
        }
        if($coupon == '')
        {
            $coupon = array();
        }
        else
        {
            $coupon = explode("-",$coupon);
        } 
        
        //取出商品信息,总计信息
        $group = new SubmitController();
        $product = $group->dataAccess($ids, $counts);
        if($product == false)
        {
            return;
        }
        $goods = $product[0];
        $totalInfo = $product[1];
        $countTotal = $totalInfo['totalCount'];
        //看优惠券数量是否大于商品数量
        if(count($coupon) > $countTotal )
        {
            //跳转至报错界面
            return;
        }
        
        //送入优惠券ID组成的数组，按ID值取出优惠券信息
        $couponModel = new CouponModel();
        $couponInfo = $couponModel->getInfo($coupon,$openid);        
        
        //利用foreach，计算优惠券总金额(过期优惠券\已使用优惠券直接抛弃）
        $couponTotal = 0;
        foreach($couponInfo as $key => $value)
        {
            /* 优惠券使用为０，未使用为１*/
            if($value['end_time'] > time() && (((int)$value['state'])&1==1) )
            {
                $couponTotal += $value['cover'];
            }
        }
        $totalInfo['totalCoupon'] = $couponTotal;
        $totalInfo['totalPayable'] -= $couponTotal;
        $totalInfo['totalPayable'] = ($totalInfo['totalPayable'] > 0)?$totalInfo['totalPayable']:1;
        
        //接收收货地址信息，通过收货地址，取详细信息
        $addressId = I('get.addressId','');
        $address =new CustomerAddressModel();
        $address->setId($addressId);
        $addressInfo = $address->getAddress(); 
        $totalInfo['addressInfo'] = $addressInfo;        
        
        $totalInfo['buyOpenid'] = $openid;
        
        //接收支付类型,类型为0为微信支付，类型为1为其它支付方式
//        $payType = I('get.payType','0');
        
        //传入总金额,生成订单支付数据
        $orderRelation = new OrderRelationModel();
        $orderRelation->setTotalInfo($totalInfo);
        $payId = $orderRelation->addOrder();
        //传入订单信息，生成订单数据，返回订单ID
        /*
         * $payid, 支付id
         * $openid, 
         * $logisticsMode, 物流方式
         * $source, 来源
         * $payable, 总金额
         * $weightTotal, 总重量
         * $countTotal,商品总数量
         * $address,收货地址
         * $telephone, 收货电话
         * $name,则货人姓名
         * $frontId, 身份证正面ID
         * $backId, 身份证反面ID
         * $idNo, 身份证号
         * $couponTotal, 优惠总数
         * $directTotal, 直销返点总数
         * $lineTotal,线销返点总数
         * $logisticFee,物流费用
         * $maxAmonut,最大优惠金额
         * $coupon,使用优惠券信息
         */
        $orderForm = new OrderFormModel();
        $orderForm->setPayid($payId); 
        $orderForm->setOpenid($openid); 
        foreach($goods as $key => $value)
        {
            $orderForm->setLogisticsMode($value['logisticType']['name']); 
            $orderForm->setSource($value['source']['name']);
            $orderForm->setPayable($value['payable']); 
            $orderForm->setWeight($value['weightTotal']); 
            $orderForm->setCountTotal($value['countTotal']);
            $orderForm->setDirectFee($value['directTotal']); 
            $orderForm->setLineFee($value['lineTotal']);
            $orderForm->setLogisticFee($value['logisticFee']);
            $orderForm->setMaxAmonut($value['maxAmonut']);
            $orderId = $orderForm->addOrder();

            /*生成订单－商品数据**************/
            $orderGoods = new OrderGoodsModel();
            $orderGoods->addOrderGoods($orderId,$value['goods']);
            
            /*添加商品的销量********************/
            $goodsM = new GoodsModel();
            $goodsM->updateSaled($value['goods']);
        }

        /*删除购物车数据******************/
        $shoppingCart = new ShoppingCartModel();
        $shoppingCart->deleteGoods($openid,$goods);

        /*将当前使用优惠券做使用处理*/
        $couponModel->updateUsed($coupon);
              
        /* 判断支付类型,拼接好URL后跳转 1为其它支付方式 0为微信支付 
        
         * 注意，在不同模式下，Ｕ方法将生成不同的路径，必须按Ｕ方法生成的目录设置微信的授权目录
         * 否则将导致出现  get_brand_wcpay_request:fail_invalid appid错误(      */
//        if($payType == 1)
//        {
//            $url = U('WxPay/OtherPay/payNow?payid=' . $payId);        
//        }
//        else
//        {
//            $url = U('WxPay/Pay/payNow?payid=' . $payId);
//            //$url1 = U('WxPay/Pay/payNow') . '?payid=' . $payId;
//        }   
        $url = U('Pay/Index/index').'?payid=' . $payId;
        redirect_url($url);
    }   
}