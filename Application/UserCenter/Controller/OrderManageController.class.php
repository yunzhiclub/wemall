<?php
/*
 * 用户中心--订单管理
 */
namespace UserCenter\Controller;
use User\Controller\UserController;
use OrderForm\Model\OrderFormModel;
use OrderGoods\Model\OrderGoodsModel;
use OrderRelation\Model\OrderRelationModel;
class OrderManageController extends UserController
{
    public function _initialize() {
        $this->assign('title','订单管理');
        $this->assign('cancelOrderUrl',U('cancelOrder'));
        $this->assign('finishOrderUrl',U('finishOrder'));
        $css = $this->fetch('indexCss');
        $js = $this->fetch('indexJs');
        $this->assign('js',$js);
        $this->assign('css',$css);
        $this->addJs('/theme/wemall/js/tooltip.js');
        parent::_initialize();
        
    }
    /*全部订单*/
    public function allOrderAction()
    {
        $openid = get_openid();
        
        //取所有订单信息
        $order = new OrderFormModel();
        $allOrderArr = $order->getAllOrderArrByOpenid($openid);
        
        //添加其它信息
        $allOrderArr = $this->_addInfo($allOrderArr);
        
        $this->assign('data',$allOrderArr);
        $this->assign("YZBody",$this->fetch('index'));
        $this->display(YZ_TEMPLATE);
    }
    
    /*
     * 取消订单
     */
    public function cancelOrderAction()
    {
        $payid = I('payid','');
        if($payid == '')
        {
            return false;
        }
        
        //删除订单数据
        $orderForm = new OrderFormModel();
        $orderForm->setPayid($payid);
        $orderForm->cancelDataByPayid();
        
        //删除支付订单数据
        $orderRelation = new OrderRelationModel();
        $orderRelation->setPayId($payid);
        $orderRelation->cancleInfoByPayid();
        
        return true;
    }
    
    /*已完成订单*/  
    public function finishedOrderAction()
    {
        $openid = get_openid();
        
        //取出近三月所有订单信息
        $order = new OrderFormModel();
        $allOrderArr = $order->getAllOrderArrByOpenid($openid);
        
        //去除掉未完成
        $allOrderArr = $this->_subUnFinshedOrder($allOrderArr);
        
        //添加其它信息
        $allOrderArr = $this->_addInfo($allOrderArr);
        
        $this->assign('data',$allOrderArr);
        $this->assign("YZBody",$this->fetch('index'));
        $this->display(YZ_TEMPLATE);
    }
    
    /*
     * 触发用户 确认收货 事件
     */
    public function finishOrderAction()
    {
        $id = I('get.orderid','');
        if($id == '')
        {
            return false;
        }
        
        //确认收货
        $order = new OrderFormModel();
        $order->finishOrderById($id);
        
        return true;      
    }
    
    
    /*已支付订单*/  
    public function payedOrderAction()
    {
        
        $openid = get_openid();
        
        //取出所有已支付信息
        $order = new OrderFormModel();
        $allOrderArr = $order->getPayedOrderArrByOpenid($openid);
        
        //添加其它信息
        $allOrderArr = $this->_addInfo($allOrderArr);
        
        $this->assign('data',$allOrderArr);
        $this->assign("YZBody",$this->fetch('index'));
        $this->display(YZ_TEMPLATE);
    }
    
    /*未支付订单*/  
    public function unPayedOrderAction()
    {
        $openid = get_openid();
        
        //取出所有未支付信息
        $order = new OrderFormModel();
        $allOrderArr = $order->getNopayOrderArrByOpenid($openid);
        
        //添加其它信息
        $allOrderArr = $this->_addInfo($allOrderArr);
        
        $this->assign('data',$allOrderArr);
        $this->assign("YZBody",$this->fetch('index'));
        $this->display(YZ_TEMPLATE);
    }
    
    private function _addPayUrl($arr)
    {
        foreach($arr as $key => $value)
        {
            $arr[$key]['_payUrl'] = U('Pay/Index/index') . '?payid=' . $value['payid'];
        }
        return $arr;
    }
    
    private function _addInfo($allOrderArr)
    {
        //拼接订单中的商品信息
        $orderGoods = new OrderGoodsModel();
        $key = 'id';
        $resKey = '_goods';
        $allOrderArr = $orderGoods->getGoodsArrByOrderIdArr($allOrderArr , $key , $resKey);
        
        //拼接支付订单信息
        $orderRelation = new OrderRelationModel();
        $key = 'payid';
        $resKey = '_payid';
        $allOrderArr = $orderRelation->getPayInfoArrByPayIdArr($allOrderArr , $key , $resKey);
        
        //拼接支付URL
        $allOrderArr = $this->_addPayUrl($allOrderArr);
        return $allOrderArr;
    }
    
    /*
     * 去除未完成的订单
     */
    private function _subUnFinshedOrder($orderArr)
    {
        foreach($orderArr as $key => $value)
        {
            if($value['is_finish'] == 0 )
            {
                unset($orderArr[$key]);
            }
        }
        return $orderArr;
    }
}