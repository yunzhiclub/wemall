<?php
namespace OrderForm\Controller;
use User\Controller\UserController;
use OrderForm\Model\OrderFormModel;
use OrderForm\Model\OrderGoodsModel;
/**
 * Description of viewController
 *前台订单管理
 * @author XINGYANNIAN
 */

class MyOrderFormController extends UserController {
    private $customerId;
    public function __construct() {
        parent::__construct();
        $openId = get_openid();//获取openid
        $customerInfo = get_customer_info($openId);
        $this->customerId = $customerInfo['id'];
    }
    public function _initialize() {       
        $this->addCss('/theme/bootstrap/css/bootstrap.css');
        parent::_initialize();
    }
    public function indexAction() {
        $chooseAllUrl = U('chooseAll');
        $chooseStatusUrl = U('chooseStatus');
        $this->assign('chooseStatusUrl',$chooseStatusUrl);
        $this->assign('chooseAllUrl',$chooseAllUrl);
        $orderForm = new OrderFormModel(); 
        $orderForm->setCustomerId($this->customerId);
        $resOrderForm = $orderForm->getOrderFormInfo();
        $num = count($resOrderForm);
        if($num<1){
            $this->assign('listContent',$this->fetch('noForm'));
        } 
        else {
            $orderGoods = new OrderGoodsModel();
            $orderGoods->setOrderForm($resOrderForm);
            $res = $orderGoods->getOrderGoods();
            $this->assign('res',$res);
            $this->assign('listContent',$this->fetch('list'));
            
        }
        $this->assign('YZBody',$this->fetch('index'));
        $this->assign('YZHead',  $this->fetch('head'));        
        $this->display(YZ_TEMPLATE);
    }//订单管理初始化
    public function chooseStatusAction() {
        $orderFormState = I('get.orderFormState');
        $orderForm = new OrderFormModel(); 
        $orderForm->setCustomerId($this->customerId);
        $orderForm->setOrderFormState($orderFormState);
        $resOrderForm = $orderForm->getOrderFormInfoByState();
        $num = count($resOrderForm);
        if($num<1){
            $HomeUrl = U('Home/Index/index');
            $this->assign('HomeUrl',$HomeUrl);
            $data = $this->fetch('noForm');
        }else{
            $orderGoods = new OrderGoodsModel();
            $orderGoods->setOrderForm($resOrderForm);
            $res = $orderGoods->getOrderGoods();
            $this->assign('res',$res);
            $data = $this->fetch('list');
        }
        echo $data;        
    }
    public function chooseAllAction() {
        $orderForm = new OrderFormModel(); 
        $orderForm->setCustomerId($this->customerId);
        $resOrderForm = $orderForm->getOrderFormInfo();
        $num = count($resOrderForm);
        if($num<1){
            $HomeUrl = U('Home/Index/index');
            $this->assign('HomeUrl',$HomeUrl);
            $data = $this->fetch('noForm');
        }else{
            $orderGoods = new OrderGoodsModel();
            $orderGoods->setOrderForm($resOrderForm);
            $res = $orderGoods->getOrderGoods();
            $this->assign('res',$res);
            $data = $this->fetch('list');
        }
        echo $data;
    }
}
