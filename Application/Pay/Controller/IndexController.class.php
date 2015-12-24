<?php
namespace Pay\Controller;
use User\Controller\UserController;
use Customer\Model\CustomerModel;
use Pay\Model\OrderRelationModel;
use Introduction\Model\IntroductionModel;
class IndexController extends UserController {
     public function _initialize() {
         $this->assign('title','收银台');
        parent::_initialize();
    }
    
    public function indexAction(){
        //获取支付编号
        //取付款金额
        $payid = I('get.payid','');
        if($payid==''){
            $this->assign('msg','支付失败！');
        }
        else {
            $payM = new OrderRelationModel();
            $res = $payM->getPay($payid);
            
            $customer = new CustomerModel;
            $key='buy_openid';
            $customerInfo = $customer->getCustomerInfo($res[$key]);
            $this->assign('freezen_state',$customerInfo['freezen_state']);
                      
            $pay = $res['payable']; 
            $indexUrl = U('Home/Index/index');
            $wxPayUrl = U('WxPay/Pay/payNow');
            $wxPayUrl .= "?payid=".$payid;
            
            $introductionM = new IntroductionModel();
            $id = 4;
            $tips = $introductionM->getInfoById($id);
            $this->assign('tips',$tips['content']);
            $openid= get_openid();
            $css = $this->fetch('indexCss');
            $js = $this->fetch('js');
            $this->assign('indexUrl',$indexUrl);
            $this->assign('wxPay',$wxPayUrl);
            $this->assign('pay',$pay);
            $this->assign('css',$css);
            $this->assign('js',$js);
            $this->assign("YZBody", $this->fetch());
            $this->display(YZ_TEMPLATE);
        }
    }
}