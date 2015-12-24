<?php
/*
 * 生产环境，测试环境文件：IndexController.class.php
 * 微支付入口文件，所以的支付信息将在此接口中进行
 * 该文件的生产地址，需要在微信公众平台中进行配置
 * 继承了user类，直接获取了OPENID
 */
namespace WxPay\Controller;
use User\Controller\UserController;
use WxPay\Model\OrderRelationModel;
use WxPay\Model\OrderFormModel;
class PayController extends UserController {    
    private $orderNo = null;//订单号
    private $notifyUrl = null;//微信支付成功异步通知地址
    private $openid = null;
    private $orderPayInfo = null;//支付订单信息
    function setTotalFee($totalFee) {
        $this->totalFee = $totalFee;
    }

    function setOrderNo($orderNo) {
        $this->orderNo = $orderNo;
    }

    function setOrderDescription($orderDescription) {
        $this->orderDescription = $orderDescription;
    }

    public function _initialize() {
        $this->assign('title','正在跳转微信支付');
        $this->addJs('/js/weixin/api.js');
        $this->notifyUrl = add_host(U('Interface/index'));
        parent::_initialize();    
    }
    /*
     * 支付接口，供内部调用。
     */
    private function _payJs(){
        //调用统一支付接口
        $unifiedOrder = new UnifiedOrderPubController();//统一支付接口
        $jsApi = new JsApiPubController();//JSAPI支付——H5网页端调起支付接口
        $openid = $this->openid;//取用户OPENID
        $outTradeNo = $this->orderNo;//传入的订单号
        $notifyUrl = $this->notifyUrl;//接收返回值的地址       
        $tradeType = 'JSAPI';//交易类型
        //传入id，取出总金额与订单描述
        $body = $this->orderPayInfo['body'] == ''?'此订单未生成描述信息':$this->orderPayInfo['body'];//订单描述     
        $totalFee = $this->orderPayInfo['payable'];//总金额，以分为单位，不能有小数点
        if($totalFee == 0)
        {
            //总金额为0,证明有问题,应该输出js提醒
            return;
        }
        $prepayId= $this->orderPayInfo['prepay_id'];
        if($prepayId == '')
        {
        //开始设置参数
            $unifiedOrder->setParameter("openid", "$openid");
            $unifiedOrder->setParameter("body", "$body");
            $unifiedOrder->setParameter("out_trade_no",$outTradeNo);
            $unifiedOrder->setParameter("total_fee", $totalFee);
            $unifiedOrder->setParameter("notify_url", $notifyUrl);
            $unifiedOrder->setParameter("trade_type", $tradeType);
    //        $unifiedOrder->setParameter("sub_mch_id", $parameterValue);//子商户号
    //        $unifiedOrder->setParameter("device_inf", $parameterValue);//设备号
    //        $unifiedOrder->setParameter("attach", $parameterValue);//附加数据
    //        $unifiedOrder->setParameter("time_start", $parameterValue);//交易起始时间
    //        $unifiedOrder->setParameter("time_expire", $parameterValue);//交易结束时间
    //        $unifiedOrder->setParameter("goods_tag", $parameterValue);//商品标记
    //        $unifiedOrder->setParameter("openid", $parameterValue);//用户标识，JSAPI为必填项
    //        $unifiedOrder->setParameter("product_id", $parameterValue);//商品ID       
            $prepayData = $unifiedOrder->getPrepayId();//获取预付费信息replayID
            //查看是否出错,出错则直接弹出alert信息
            if($prepayData['state'] == 'error')
            {
                $msg = $prepayData['msg'];
                $this->assign('msg',$msg);
                $js = $this->fetch('jsError');
                echo $js;
                return;
            }
            $prepayId = $prepayData['prepay_id'];
            //更新该订单的preparid
            
        }
        $jsApi->setPrepayId($prepayId);//设置prepayId
        
        //散闷JSAPI参数，用放置到支付页面中
        $jsApiParameters = $jsApi->getParameters();  
        //生成签名信息
        $orderSign = json_decode($jsApiParameters);
        $sign = array();
        $sign['nonce_str'] = $orderSign->nonceStr;
        $sign['sign'] = $orderSign->paySign;
        $sign['id'] = $outTradeNo;
        
        $this->assign('jsApiParameters',$jsApiParameters);
        return;
    }

    
    
    /*
     * 支付ACTION。用户传入payid,本方法调用相关数据后完成支付
     */
    public function payNowAction()
    {  
        $orderRelation = new OrderRelationModel();
        $payId = I('get.payid','');
        $this->openid = get_openid();
        
        $state = 1;//订单的prepay_id信息的有效性
        if($payId == '')
        {            
            $this->assign('tips','参数错误!');
            $state = 0;
        }
        else
        {
            $arrRes = $orderRelation->chcekPayid($payId,$this->openid);
            if($arrRes['state'] == 0)
            {
                $this->assign('tips',$arrRes['msg']);
                $state = 0;
            }
        }
        //判断repayid,如果为null，证明订单信息有误
        if($state == 0)
        {
            $jumpUrl = U('Home/Index/index');//跳转地址
            $waitSecond = 10;//跳转时间
            $this->assign('waitSecond',$waitSecond);
            $this->assign('jumpUrl',$jumpUrl);
            $this->assign('tips',$tips);
            $this->assign('YZBody',$this->fetch('payResult'));
            $this->display(YZ_TEMPLATE);
            return;
        }
        $this->assign('css',$this->fetch("orderPayTipsCss"));
        $this->assign("YZBody",$this->fetch("orderPayTips"));
        $this->display(YZ_TEMPLATE);
        $this->setOrderNo($payId);
        $this->orderPayInfo = $arrRes['res'];
        $this->_payJs();//获取JS签名信息
        $actionUrl['error'] = U('payResult');
        $actionUrl['success'] = U('payResult');
        $this->assign('actionUrl',$actionUrl);
        $this->display('payNowJs');//调用JS事件，处发支付
        
    }
    
    
    /*
     * 支付回调action用于显示用户支付结果
     */
    public function payResultAction(){
//        $status = I('get.status','error');
//        if($status == 'error')
//        {
//            $tips = '支付失败！';
//        }
//        else
//        {
//            $tips = '支付成功!';
//        }
//        
//        $jumpUrl = U('User/UserCenter/index');//跳转地址
//        $waitSecond = 3;//跳转时间 
//       
//        $this->assign("header",'');
//        $this->assign('status',$status);
//        $this->assign('waitSecond',$waitSecond);
//        $this->assign('jumpUrl',$jumpUrl);
//        $this->assign('tips',$tips);
//        $this->assign('YZBody',$this->fetch('payResult'));
//        $this->display(YZ_TEMPLATE);
        $jumpUrl = U('User/UserCenter/index');//跳转地址
        redirect_url($jumpUrl);
    }
    public function postTestAction()
    {
        echo "hello";
    }
}