<?php
/*
 * 本文件为测试环境文件，修改后供白名单人员测试。
 * 生产环境文件为：
 * PayController.class.php
 * 微支付入口文件，所以的支付信息将在此接口中进行
 * 该文件的测试地址，需要在微信公众平台中进行配置
 * 继承了user类，直接获取了OPENID
 */
namespace WxPay\Controller;
use User\Controller\UserController;
class IndexController extends UserController {    
    private $totalFee = null;//总金额
    private $orderNo = null;//订单号
    private $orderDescription = null;//订单描述
    private $notifyUrl = null;//微信支付成功异步通知地址
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
        $this->addJs('/js/weixin/api.js');
        $this->notifyUrl = add_host(U('Interface/index'));
        parent::_initialize();    
    }
    /*
     * 支付接口，供内部调用。
     */
    public function payJs(){
        //调用统一支付接口
        $unifiedOrder = new UnifiedOrderPubController();//统一支付接口
        $jsApi = new JsApiPubController();//JSAPI支付——H5网页端调起支付接口
        $openid = $this->openId;//取用户OPENID
        $body = $this->orderDescription;//订单描述
        $outTradeNo = $this->orderNo;//传入的订单号
        $totalFee = $this->totalFee;//总金额，以分为单位，不能有小数点
        $notifyUrl = $this->notifyUrl;//接收返回值的地址
        $tradeType = 'JSAPI';//交易类型
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
        $prepayId = $unifiedOrder->getPrepayId();//获取replayID
        $jsApi->setPrepayId($prepayId);//设置prepayId
        //散闷JSAPI参数，用放置到支付页面中
        $jsApiParameters = $jsApi->getParameters();   
        //获取SSDK配置信息
//        $JSSDK = A('JSSDK');
//        $signPackage = $JSSDK->getSignPackage();
//        $this->assign('signPackage',$signPackage);
        $actionUrl['success'] = U('payResult?status=success');
        $actionUrl['error'] = U('payResult?status=error');
        $this->assign('actionUrl',$actionUrl);
        $this->assign('jsApiParameters',$jsApiParameters);
        return $this->fetch('Index/index');
    }
    /*
     * 提交订单后选择支付页面
     */   
    public function orderSubmitAction(){
        $postUrl = U('orderPay');
        $this->assign('postUrl',$postUrl);
        $this->assign('orderNo',time());
        $this->assign('totalFee',1);
        $this->assign('orderDescription','hello');
        $this->assign('YZBody',$this->fetch('Index/orderSubmit'));
        $this->display(YZ_TEMPLATE);
    }
    /*
     * 支付页面用JQUERY调用本action，发起支付。
     */
    public function orderPayAction()
    {  
        $totalFee = I('get.totalFee',0); //总费用
        $orderNo = I('get.orderNo',0);  //订单号
        $orderDescription = I('get.orderDescription','');//订单描述信息
        $this->setOrderDescription($orderDescription);
        $this->setOrderNo($orderNo);
        $this->setTotalFee($totalFee);
        $js = $this->payJs();
        echo $js;
    }
    /*
     * 支付回调action用于显示用户支付结果
     */
    public function payResultAction(){
        $status = I('get.status','error');
        if($status == 'error')
        {
            $tips = '支付失败，请稍后重试';
        }
        else
        {
            $tips = '支付成功!';
        }
        $jumpUrl = U('Home/Index/index');//跳转地址
        $waitSecond = 3;//跳转时间
        $this->assign('waitSecond',$waitSecond);
        $this->assign('jumpUrl',$jumpUrl);
        $this->assign('tips',$tips);
        $this->assign('YZBody',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    public function testAction()
    {
        $url = U('fetch');
        $this->assign('url',$url);
        $JSSDK = A('JSSDK');
        $signPackage = $JSSDK->getSignPackage();
        $this->assign('signPackage',$signPackage);
        $this->assign('YZBody',$this->fetch('Index/orderSubmit'));

        $this->display(YZ_TEMPLATE);
    }
    public function fetchAction()
    {
        $frontid = I('get.frontid','');
        $backid = I('get.backid','');
        if($frontid == '')
        {
            echo S('frontid');
            echo "<br />";
            echo S('backid');
            return;
        }
        S('frontid',$frontid,7200);
        S('backid',$backid,7200);
    }
}