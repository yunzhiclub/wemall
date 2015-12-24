<?php
/*
 * 我的业绩模块
 * 
 * 
 */
namespace Achievement\Controller;
use User\Controller\UserController;
use OrderRelation\Model\OrderRelationModel;
use Rebate\Model\RebateModel;
class IndexController extends UserController {
    private $openId;//客户的openId
    private $orderRelation;//orderRelation对象
    private $id;//客户的id,用来取出客户业绩
    public function __construct() {
        $this->orderRelation = new OrderRelationModel;
        parent::__construct();
        $this->openId = get_openid();
        $custmoer = get_customer_info($this->openId);
        $this->id = $custmoer['id'];
    }
    public function _initialize() {
        $title = '用户中心';
        $this->assign('title', $title);
        $this->addCss('/theme/wemall/css/head.css');
        $this->addCss('/theme/wemall/css/reset.css');
        $this->addCss('/theme/wemall/css/style.css');
        $this->addCss('/theme/wemall/css/user.css');
        $this->addCss('/theme/wemall/css/xmapp.css');
        $this->addCss('/theme/wemall/css/order-list.css');
        $this->addCss('/theme/wemall/css/order-detial.css');
        $this->addJs('/theme/wemall/js/jquery.autocomplete.js');
        $this->addJs('/theme/wemall/js/jquery.lazyload.js');
        $this->addJs('/theme/wemall/js/jquery.touchScroll.js');
        $this->addJs('/theme/wemall/js/fbi.js');
        $this->addJs('/theme/default/js/achievemnt.js');
        parent::_initialize();
    }   
    public function dangqiyejiAction() {
        $achieve = $this->_getDangAchieve();
        $result['direct'] = intval ($achieve['totalDirectFee'] * $achieve['_totalGoodsFee_rebate']['direct_ratio']);
        $result['line'] = intval ($achieve['_line']['totalGoodsFee'] * $achieve['_lineTotalGoodsFee_rebate']['line_ratio']);
        $this->assign(result,$result);
        $this->assign(dangqi,$achieve);
        $this->assign('YZBody',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    public function wangqiyejiAction() {
        $this->assign('YZBody',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    
    private function _getDangAchieve()//获取当期业绩
    {
        $endTime = time();
        $beginTime = $endTime - 30*24*60*60;
        $this->orderRelation->setBeginTime($beginTime);
        $this->orderRelation->setEndTime($endTime);
        $res = $this->orderRelation->getPayedOrder();
        //添加系数信息
        $rebate = new RebateModel();
        $key = 'totalGoodsFee';
        $keyRes = '_totalGoodsFee_rebate';
        $res = $rebate->getRatioByMoney($res, $key , $keyRes );
        $key = 'lineTotalGoodsFee';
        $keyRes = '_lineTotalGoodsFee_rebate';
        $res = $rebate->getRatioByMoney($res, $key , $keyRes );
        
        $achieve = $res[$this->id];
        return $achieve;
    }
}