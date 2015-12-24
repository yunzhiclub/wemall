<?php

namespace User\Controller;

use User\Controller\UserController;
use OrderForm\Model\OrderFormModel;
use OrderForm\Model\OrderGoodsModel;
use Customer\Model\CustomerModel;
use Achievement\Model\AchievementModel;
use Home\Model\AttachmentModel;
use Coupon\Model\CouponModel;
use OrderRelation\Model\OrderRelationModel;
use Config\Model\ConfigModel;
use Rebate\Model\RebateModel;

/**
 * Description of viewController
 * 前台订单管理
 * @author DENGHAOYANG
 */
class UserCenterController extends UserController {

    private $customerInfo; //客户信息
    private $customerId;
    private $open_id;

    public function __construct() {
        parent::__construct();
        $openId = get_openid(); //获取openid
        $this->open_id = $openId;
        $this->customerInfo = get_customer_info($openId);
        $this->customerId = $this->customerInfo['id'];
//        $config = D("Config");
//        $configArr = $config->fetConfig();
//        $this->beginDate = $configArr[0]['value'];
//        $this->endDate = $configArr[1]['value'];
    }

    public function _initialize() {
        $title = '用户中心';
        $this->assign('title', $title);
        parent::_initialize();
    }

    /*
     * 用户中心初始化界面
     * 1.取个人基本信息
     * 2.取个人业绩信息
     */

    public function indexAction() {
        /* 取用户基本信息**** */
        $customerInfo = $this->customerInfo;
        
        //设置用户状态，用以显示“修改个人信息还是请完善个人信息”
        if(trim($customerInfo['phone_number'])=='' || trim($customerInfo['account'])=='' )
        {
            $customerInfo['flag'] = 1;
        }
        else
        {
            $customerInfo['flag'] = 0;
        }
        $this->assign('customer', $customerInfo);
        $openid = get_openid();
        
        //取订单数量
        $orderForm = new OrderFormModel();
        $noPayOrderCount = $orderForm->getNopayOrderCountByOpenid($openid);        
        $payedOrderCount = $orderForm->getPayedOrderCountByOpenid($openid);       
        
        //传订单数量数据
        $this->assign('noPayOrderCount', $noPayOrderCount);
        $this->assign('payedOrderCount', $payedOrderCount);
        
        //获取当前周期的开始结束日期
        $config = new ConfigModel();
        $times = $config->getBeginTimeAndEndTime();
        $beginTime = $times['endTime'];
        $endTime = time();
        
        //取当期业绩信息中直销业绩
        $relationForm = new OrderRelationModel();
        $currentAchievement['direct'] = $relationForm->getAchievementByOpenid($openid,$beginTime,$endTime);
       
        //取出上线为自已的所有用户的业绩
        $customer = new CustomerModel();
        $openids = $customer->getLineOpenids($openid);
        $currentAchievement['line'] = $relationForm->getAchievementByOpenid($openids,$beginTime,$endTime);

        //添加直销,线销系数
        $rebate = new RebateModel();
        
        //取直销系数
        $dataArr[0] = $currentAchievement['direct'];
        $key = '_totalGoodsFee';
        $keyReturn = '_direct_radio';
        $dataArr = $rebate->getRatioByMoney($dataArr, $key, $keyReturn);
        $currentAchievement['_direct_radio'] = $dataArr[0][$keyReturn];
        
        //取线销系数
        $dataArr[0] = $currentAchievement['line'];
        $keyReturn = '_line_radio';
        $dataArr = $rebate->getRatioByMoney($dataArr, $key, $keyReturn);
        $currentAchievement['_line_radio'] = $dataArr[0][$keyReturn];
        
        $this->assign('achievement',$currentAchievement);
        
        $changeUrl = U('ChangePhone/index');
        $this->assign('changeUrl', $changeUrl);
        $account = U('Account/index');

        $couponUrl = U('User/Coupon/index');
        $this->assign('couponUrl', $couponUrl);

        $this->assign('wangqiurl', U('User/Achievement/pastData'));
        
        $finishedOrderUrl = U('UserCenter/OrderManage/finishedOrder');
        $payedOrderUrl = U('UserCenter/OrderManage/payedOrder');
        $unpayOrderUrl = U('UserCenter/OrderManage/unpayedOrder');
        $allOrderUrl = U('UserCenter/OrderManage/allOrder');
        
        $this->assign('finishedOrderUrl',$finishedOrderUrl);
        $this->assign('payedOrderUrl',$payedOrderUrl);
        $this->assign('unpayOrderUrl',$unpayOrderUrl);
        $this->assign('allOrderUrl',$allOrderUrl);
        
        $this->assign('count', $count);
        $css = $this->fetch('indexCss');
        $this->assign('indexCss', $css);
        $this->assign("YZBody", $this->fetch());
        $this->display(YZ_TEMPLATE);
    }

    public function orderManageAction() {
        $orderGoods = new OrderGoodsModel();
        $orderForm = new OrderFormModel();
        $orderForm->setOpenid($this->open_id);
        $resOrderForm = $orderForm->getOrderFormInfo();
        $orderGoods->setOrderForm($resOrderForm);
        $res = $orderGoods->getOrderGoods();
        $relation = M('order_relation');
        $map[buy_openid] = $this->open_id;
        $payOrder = array();
        $payOrder = $relation->where($map)->select();
        //为payOrder拼接所含订单数据
        foreach ($payOrder as $key => $value) {
            $map1[payid] = $value[id];
            $form = M('order_form');
            $payOrder[$key][order_form] = $form->where($map1)->select();
        }
        $doneOrder = array();
        $internationalSendOrder = array();
        $nativeSourceOrder = array();
        $getLogisticOrder = array();
        $sendingOrder = array();
        $unPayOrder = array();
        $cancelOrder = array();
        //0表示取消 1表示未支付 2表示正在配货 3表示获取物流 4表示原产国配送 5表示国际配送 6表示完成
        foreach ($res as $key => $value) {
            if ($value[is_finish] == 1) {
                $doneOrder[] = $value;
            } else {
                if ($value[is_inter] == 1) {
                    
                } else {
                    if ($value[is_origin] == 1) {
                        $paidOrder[$key] = $value;
                        $paidOrder[$key][key] = 5;
                    } else {
                        if ($value[is_getlog] == 1) {
                            $paidOrder[$key] = $value;
                            $paidOrder[$key][key] = 4;
                        } else {
                            if ($value[is_pick] == 1) {
                                $paidOrder[$key] = $value;
                                $paidOrder[$key][key] = 3;
                            } else {
                                if ($value[is_pay] == 1) {
                                    $paidOrder[$key] = $value;
                                    $paidOrder[$key][key] = 2;
                                } else {
                                    if ($value[is_cancel] == 1) {
                                        $cancelOrder[] = $value;
                                    } else {
                                        $unPayOrder[] = $value;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $case = I('get.key');
        switch ($case) {
            case 2:
                $donebutton = U('doingtoDone') . '?id=';
                $this->assign('todone', $donebutton);
                $this->assign('list', $paidOrder);
                $this->assign('button', $button);
                break;
            case 6:
                for ($i = 0; $i < count($doneOrder); $i++) {
                    $doneOrder[$i][key] = $case;
                }
                $this->assign('list', $doneOrder);
                break;
        }
        $url = U('orderManageDetails') . '?id=';
        $this->assign('key', $key);
        $this->assign('url', $url);
        $this->assign("YZBody", $this->fetch());
        $this->display(YZ_TEMPLATE);
    }

    //未支付订单的管理
    public function unPayManageAction() {
        $orderGoods = new OrderGoodsModel();
        $orderForm = new OrderFormModel();
        $orderForm->setOpenid($this->open_id);
        $resOrderForm = $orderForm->getOrderFormInfo();
        $orderGoods->setOrderForm($resOrderForm);
        $res = $orderGoods->getOrderGoods();
        $relation = M('order_relation');
        $map[buy_openid] = $this->open_id;
        $map[is_payed] = 0;
        $map[is_cancel] = 0;
        $unpayOrder = array();
        $unpayOrder = $relation->where($map)->select();
        //为payOrder拼接所含订单数据
        foreach ($unpayOrder as $key => $value) {
            $map1[payid] = $value[id];
            $form = M('order_form');
            $unpayOrder[$key][order_form] = $form->where($map1)->select();
            foreach ($unpayOrder[$key][order_form] as $value) {
                $unpayOrder[$key][count] = $value[total_prices];
            }
        }
        $this->assign('topay',U('Pay/Index/index').'?payid=');
        $this->assign('tocancel',U('noPaytoCancel').'?id=');
        $this->assign('list', $unpayOrder);
        $url = U('orderManageDetails') . '?id=';
        $this->assign('key', $key);
        $this->assign('url', $url);
        $this->assign("YZBody", $this->fetch());
        $this->display(YZ_TEMPLATE);
    }

    public function orderManageDetailsAction() {
        $id = I('get.id');
        $map[id] = $id;
        $order = M('order_form');
        $a = M('order_goods');
        $b = M('attachment');
        $c = M('goods');
        $res = $order->where($map)->find();
        $map1[order_id] = $id;
        //取订单中的商品编号
        $order_goods = $a->where($map1)->field('goods_id,quantity')->select();
        //取商品的图片路径
        $savepath = array();
        $goods = array();
        $result = array();
        //拼接物品详情数组
        foreach ($order_goods as $key => $value) {
            $map2[id] = $value[goods_id];
            $goods[] = $c->where($map2)->field('name,purchasing_price,discribe,id')->find();
            $pictures = $c->where($map2)->field('focus_pictures')->find();
            $attach_id = explode(',', $pictures[focus_pictures]);
            $attachmodel = new AttachmentModel;
            $attachmodel->setId($attach_id[0]);
            $src = $attachmodel->getAttchmentPath();
            $result[$key][src] = $src;
            $result[$key][name] = $goods[$key][name];
            $result[$key][id] = $goods[$key][id];
            $result[$key][purchasing_price] = $goods[$key][purchasing_price];
            $result[$key][discribe] = $goods[$key][discribe];
            $result[$key][quantity] = $value[quantity];
        }
        //为原产国与配送来源拼接空格
        $res[procure_source1] == ""? : $res[procure_source1] = $res[procure_source1] . '&nbsp';
        $res[procure_source2] == ""? : $res[procure_source2] = $res[procure_source2] . '&nbsp';
        $res[procure_source3] == ""? : $res[procure_source3] = $res[procure_source3] . '&nbsp';
        $res[origin_company1] == ""? : $res[origin_company1] = $res[origin_company1] . '&nbsp';
        $res[origin_company2] == ""? : $res[origin_company2] = $res[origin_company2] . '&nbsp';
        $res[origin_company3] == ""? : $res[origin_company3] = $res[origin_company3] . '&nbsp';
        $this->assign('goods', $result);
        $this->assign('order', $res);
        $this->assign('detail', U('Goods/GoodsDetail/index') . '?goodsId=');
        $this->assign("YZBody", $this->fetch());
        $this->display(YZ_TEMPLATE);
    }

    public function noPaytoPayAction() {
        $map = array();
        $map[id] = I('get.id');
        $order = M('order_relation');
        $data[is_cancel] = 1;
        $order->where($map)->save($data);
        $res = $order->where($map)->select();
        
    }

    public function noPaytoCancelAction() {
        $map = array();
        $map[id] = I('get.id');
        $map2 = array();
        $map2[payid] = I('get.id');
        $order = M('order_relation');
        $form = M('order_form');
        $data[is_cancel] = 1;
        $order->where($map)->save($data);
        $res = $form->where($map2)->select();
        foreach ($res as $value) {
            $map1[id] = $value[id];
            $data[is_cancel] = 1;
            $result = $form->where($map1)->save($data);
            
        }
        $urlIndex = U('User/UserCenter/index') . '?key=1';
        redirect_url($urlIndex);
    }

    public function dangqiyejiAction() {
        $this->assign('YZBody', $this->fetch());
        $this->display(YZ_TEMPLATE);
    }

    public function wangqiyejiAction() {
        $this->assign('YZBody', $this->fetch());
        $this->display(YZ_TEMPLATE);
    }

    public function doingtoDoneAction() {
        $map = array();
        $map[id] = I('get.id');
        $order = M('order_form');
        $state = $order->where($map)->find();
        $data[is_finish] = 1;
        $order->where($map)->save($data);
        $urlIndex = U('User/UserCenter/index') . '?key=2';
        redirect_url($urlIndex);
    }

    public function couponAction() {
        $model = new CouponModel();
        $res = $model ->search();
        foreach ($res as $key =>$value){
            $stay_time = $value['end_time']-$value['start_time'];
            $stay_time= $stay_time/60/60/24;
            $res[$key]['stay_time']=  floor($stay_time);
        }
        $this->assign('coupon',$res);
        $this->assign('YZBody',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }

}
