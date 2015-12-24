<?php

/* 
 * 梦云智工作室
 *   * 
 * 订单管理
 * noPay未支付订单
 * havePay已支付订单
 * 状态3-7查看详情为同一模板
 * Ao
 * 2015-4-29 19:47:19
 */
namespace OrderForm\Controller;
use Admin\Controller\AdminController;
use Home\Model\AttachmentModel;
use OrderRelation\Model\OrderRelationModel;
use OrderForm\Model\OrderFormModel;
use Customer\Model\CustomerModel;
class OrderManagerController extends AdminController {
    private $orderForm;//订单表
    private $orderGoods;//订单商品表
    private $customer;//客户表
    private $relation;//支付表
    public function __construct() {
        parent::__construct();
        $this->orderForm = D('OrderForm');
        $this->orderGoods = D('OrderGoods');
        $this->customer = D('Customer');
        $this->relation = new OrderRelationModel();
    }
    public function indexAction(){
        $url = U('noPay');
        redirect_url($url);
    }
    
    //状态 1未支付订单管理
    public function noPayAction(){
        
        $currentPage = I('get.p',1);
        
        //获取未支付订单数量
        $relation = $this->relation;
        $map['is_payed'] = 0;
        $map['is_cancel'] = 0;
        $relation->setMap($map);
        $counts = $relation->getOrderCounts();

        //获取分页信息
        $page = $this->page;
        $page->setCounts($counts);
        $page->setCurrentPage($currentPage);
        $page->setPageSize(10);
        $pageSize = $page->getPageSize;
        $page->setPageStyle(2);
        $pageStr = $page->fetch();
        $currentPage = $page->getCurrentPage();
        
        //获取当前页未支付数据
        $relation->setCurrentPage($currentPage);
        $relation->setPageSize($pageSize);
        $orderBy = array('creat_time'=>"desc");
        $relation->setOrderBy($orderBy);
        $noPayOrder = $relation->getCurrentPageOrder();
        
        //获取当前支付订单下的所有订单信息
        $orderForm = new OrderFormModel();
        $key = 'id';
        $resKey = '_detail';
        $noPayOrder = $orderForm->getOrderByPayid($noPayOrder , $key ,$resKey);

        //获取购买人信息
        $customer = new CustomerModel();
        $key = 'buy_openid';
        $resKey = '_customer';
        $noPayOrder = $customer->getCustomerByOpenid($noPayOrder,$key,$resKey);
        
        //添加URL信息
        $noPayOrder = $this->_addNopayUrlInfo($noPayOrder,$currentPage);
        
        $this->assign('noPay',$noPayOrder);
        $this->assign('page',$pageStr);
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    //调用支付界面
    public function payAction(){
        $payid = I('get.payid'); // 相当于 $_GET['id']
        $js=$this->fetch('js');
        $this->assign('js',$js);
        $url = U('payOk?payid='.$payid);
        $this->assign(url,$url);
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    //调用修改金额界面
    public function changePayAction(){
        $payid = I('get.payid'); // 相当于 $_GET['id']
        $relation = new OrderRelationModel;
        $change = $relation->changePay($payid);
        if($change['prepay_id'] == null){
            $url = U('changeOk?payid='.$payid);
            $this->assign(url,$url);
            $this->assign(change,$change);
            $this->assign('YZRight',$this->fetch());
            $this->display(YZ_TEMPLATE);
        }
        else {
            $text =  "该订单无法进行修改";
            $this->assign('YZRight',$text);
            $this->display(YZ_TEMPLATE);
        }
    }
    public function changeOkAction(){
        $payid = I('get.payid','');
        if($payid == '')
        {
            die("系统错语：未接收到payid");
        }
        //改变支付表中的支付信息
        $relation = new OrderRelationModel;
        $resData = $relation->toChange($payid);
        $url = U('noPay');
        if($resData['status'] == 'success')
        {
            $this->success('操作成功',$url,2);
        }
        else
        {
            $this->error('操作失败',$url);
        }        
    }

    //支付提交
    public function payOkAction(){
        $payid = I('get.payid','');
        if($payid == '')
        {
            die("系统错语：未接收到payid");
        }
        //改变支付表中的支付信息
        $this->relation->toPay($payid);
        //改变订单表中订单状态
        $resData =  $this->orderForm->toPay();
        $url = U('noPay');
        if($resData['status'] == 'success')
        {
            $this->success('操作成功',$url,2);
        }
        else
        {
            $this->error('操作失败',$url);
        }        
    }
    //状态2正在配货订单管理
    public function pickingAction(){
        $field = array('id','payid','openid','pay_time');
        $map['is_pay'] = 1;
        $map['is_pick'] = 0;
        $map['is_cancel'] = 0;
        $where = $map;
        $this->orderForm->setAfield($field);
        $this->orderForm->setAwhere($where);
        $picking = $this->orderForm->getOrder();
        foreach ($picking as $key => $value) {
            $picking[$key]['goodsQuantity'] = $this->_addGoodsQuantity($value['id']);
            $picking[$key]['url'] = array('detail'=>U('pickingDetail?id='.$value['id']),'pick'=>U('pick?id='.$value['id']));
            $picking[$key]['customer'] = $this->_addCustomer($value['openid']);
            $picking[$key]['_payInf'] = $this->_addPickPayInfo($value['payid']);
        }
        
        $menuList = $picking;
        $currentPage = I('get.p',1);
        //将树转化为按顺序输出
        $page = $this->page;
        $page->setCounts(count($menuList));
        $page->setCurrentPage($currentPage);
        $page->setPageStyle(2);
        $pageStr = $page->fetch();
        $pageSise = $page->getPageSize();
        //截取本页内容
        $menuList = array_slice($menuList , ($currentPage-1)*$pageSise , $pageSise);
        $this->assign(picking,$menuList);
        $this->assign('page',$pageStr);

        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    //状态2查看详情
    public function pickingDetailAction(){
        $id = I('get.id');
        $field = array('id','payid','openid','pay_time','name','telephone');
        $where = 'id='.$id;
        $this->orderForm->setAfield($field);
        $this->orderForm->setAwhere($where);
        $pickingD = $this->orderForm->getOrder();      
        $pickingD[0]['goodsQuantity'] = $this->_addGoodsQuantity($pickingD[0]['id']);
        $pickingD[0]['customer'] = $this->_addCustomer($pickingD[0]['openid']);
        $pickingD[0]['goods'] = $this->_addGoods($pickingD[0]['id']);
        $pickingD[0]['consignee'] = $this->_addConsignee($pickingD[0]['payid']);
        $this->assign(pickingD,$pickingD[0]);
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    //调用状态改变页面
    public function pickAction(){
        $id = I('get.id');
        $url = U('pickOk?id='.$id);
        $this->assign(url, $url);
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    //更改订单状态2->3
    public function pickOkAction(){
        //改变订单表中订单状态
        $resData =  $this->orderForm->toPick();
        $url = U('picking');
        if($resData['status'] == 'success')
        {
            $this->success('操作成功',$url,2);
        }
        else
        {
            $this->error('操作失败',$url);
        }
    }
    //状态3正在获取原产国物流信息订单管理
    public function getLogisticsinfAction(){
        $field = array('id','openid','pay_time','remarks','procure_source1','procure_orderId1');
        $map['is_payed'] = 1;
        $map['is_pick'] = 1;
        $map['is_getlog'] = 0;
        $map['is_cancel'] = 0;
        $this->orderForm->setAfield($field);
        $this->orderForm->setAwhere($map);
        $getLog = $this->orderForm->getOrder();
        foreach ($getLog as $key => $value) {
            $getLog[$key]['goodsQuantity'] = $this->_addGoodsQuantity($value['id']);
            $getLog[$key]['url'] = array('detail'=>U('detail?id='.$value['id']),'getLog'=>U('getLog?id='.$value['id']));
            $getLog[$key]['customer'] = $this->_addCustomer($value['openid']);
        }
        $menuList = $getLog;
        $currentPage = I('get.p',1);
        //将树转化为按顺序输出
        $page = $this->page;
        $page->setCounts(count($menuList));
        $page->setCurrentPage($currentPage);
        $page->setPageStyle(2);
        $pageStr = $page->fetch();
        $pageSise = $page->getPageSize();
        //截取本页内容
        $menuList = array_slice($menuList , ($currentPage-1)*$pageSise , $pageSise);
        $this->assign(getLog,$menuList);
        $this->assign('page',$pageStr);
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    
    //调用改变状态的界面
    public function getLogAction(){
        //传一个备注过去
        $id = I('get.id');
        $field = array('remarks,procure_orderid1,procure_source1');
        $where = 'id='.$id;
        $this->orderForm->setAfield($field);
        $this->orderForm->setAwhere($where);
        $getlog = $this->orderForm->getOrder();
        
        $url = U('getLogOk?id='.$id);
        $this->assign(url, $url);
        $this->assign(getlog, $getlog);
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    //更改订单状态3->4
    public function getLogOkAction(){
        //改变订单表中订单状态
        $resData =  $this->orderForm->toGetLog();
        $url = U('getLogisticsinf');
        if($resData['status'] == 'success')
        {
            $this->success('操作成功',$url,2);
        }
        else
        {
            $this->error('操作失败',$url);
        }
    }
    //状态4正在原产国配送订单管理
    public function originDistributionAction(){
        $field = array('id','openid','pay_time','remarks','origin_orderid1','origin_company1');
        $map['is_payed'] = 1;
        $map['is_pick'] = 1;
        $map['is_getlog'] = 1;
        $map['is_origin'] = 0;
        $map['is_cancel'] = 0;
        $this->orderForm->setAfield($field);
        $this->orderForm->setAwhere($map);
        $origin = $this->orderForm->getOrder();
        foreach ($origin as $key => $value) {
            $origin[$key]['goodsQuantity'] = $this->_addGoodsQuantity($value['id']);
            $origin[$key]['url'] = array('detail'=>U('detail?id='.$value['id']),'originDistrib'=>U('originDistrib?id='.$value['id']));
            $origin[$key]['customer'] = $this->_addCustomer($value['openid']);
        }
        $menuList = $origin;
        $currentPage = I('get.p',1);
        //将树转化为按顺序输出
        $page = $this->page;
        $page->setCounts(count($menuList));
        $page->setCurrentPage($currentPage);
        $page->setPageStyle(2);
        $pageStr = $page->fetch();
        $pageSise = $page->getPageSize();
        //截取本页内容
        $menuList = array_slice($menuList , ($currentPage-1)*$pageSise , $pageSise);
        $this->assign(origin,$menuList);
        $this->assign('page',$pageStr);
 
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    
    //调用改变状态的界面
    public function originDistribAction(){
        $id = I('get.id');
        $url = U('originDistribOk?id='.$id);
        $this->assign(url, $url);
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    //更改订单状态4->5
    public function originDistribOkAction(){
        //改变订单表中订单状态
        $resData =  $this->orderForm->toOriginDistrib();
        $url = U('originDistribution');
        if($resData['status'] == 'success')
        {
            $this->success('操作成功',$url,2);
        }
        else
        {
            $this->error('操作失败',$url);
        }
    }
    //状态5正在国际配送 订单管理
    public function interDistributionAction(){
        $field = array('id','openid','pay_time','remarks','shipments_time','international_orderid');
        $map['is_payed'] = 1;
        $map['is_pick'] = 1;
        $map['is_getlog'] = 1;
        $map['is_origin'] = 1;
        $map['is_inter'] = 0;
        $map['is_cancel'] = 0;
        $this->orderForm->setAfield($field);
        $this->orderForm->setAwhere($map);
        $inter = $this->orderForm->getOrder();
        foreach ($inter as $key => $value) {
            $inter[$key]['goodsQuantity'] = $this->_addGoodsQuantity($value['id']);
            $inter[$key]['url'] = array('detail'=>U('detail?id='.$value['id']),'finish'=>U('finishOk?id='.$value['id']),'cancel'=>U('cancelOk?id='.$value['id']));
            $inter[$key]['customer'] = $this->_addCustomer($value['openid']);
        }
        $menuList = $inter;
        $currentPage = I('get.p',1);
        //将树转化为按顺序输出
        $page = $this->page;
        $page->setCounts(count($menuList));
        $page->setCurrentPage($currentPage);
        $page->setPageStyle(2);
        $pageStr = $page->fetch();
        $pageSise = $page->getPageSize();
        //截取本页内容
        $menuList = array_slice($menuList , ($currentPage-1)*$pageSise , $pageSise);
        $this->assign(inter,$menuList);
        $this->assign('page',$pageStr);

        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    
    //完成订单更改状态5->6
    public function finishOkAction(){
        $resData =  $this->orderForm->toFinish();
        $url = U('interDistribution');
        if($resData['status'] == 'success')
        {
            $this->success('操作成功',$url,2);
        }
        else
        {
            $this->error('操作失败',$url);
        }
    }
    //取消订单更改状态5->7
    public function cancelOkAction(){
        $resData =  $this->orderForm->toCancel();
        $url = U('interDistribution');
        if($resData['status'] == 'success')
        {
            $this->success('操作成功',$url,2);
        }
        else
        {
            $this->error('操作失败',$url);
        }
    }
    //状态6已完成订单
    public function finishedAction(){
        $field = array('id','total_prices','openid','buy_time','pay_time','shipments_time','remarks');
        $map['is_cancel'] = 0;
        $map['is_finish'] = 1;
        $this->orderForm->setAfield($field);
        $this->orderForm->setAwhere($map);
        $finished = $this->orderForm->getOrder();
        foreach ($finished as $key => $value) {
            $finished[$key]['goodsQuantity'] = $this->_addGoodsQuantity($value['id']);           
            $finished[$key]['customer'] = $this->_addCustomer($value['openid']);
            $finished[$key]['url'] = array('detail'=>U('detail?id='.$value['id']));
        }
        $menuList = $finished;
        $currentPage = I('get.p',1);
        //将树转化为按顺序输出
        $page = $this->page;
        $page->setCounts(count($menuList));
        $page->setCurrentPage($currentPage);
        $page->setPageStyle(2);
        $pageStr = $page->fetch();
        $pageSise = $page->getPageSize();
        //截取本页内容
        $menuList = array_slice($menuList , ($currentPage-1)*$pageSise , $pageSise);
        $this->assign(finished,$menuList);
        $this->assign('page',$pageStr);
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    
    }
    //状态7已取消订单
    public function canceledAction(){
        $field = array('id','total_prices','openid','buy_time','pay_time','shipments_time','remarks');
        $map['is_cancel'] = 1;
        $this->orderForm->setAfield($field);
        $this->orderForm->setAwhere($map);
        $canceled = $this->orderForm->getOrder();
        foreach ($canceled as $key => $value) {
            $canceled[$key]['goodsQuantity'] = $this->_addGoodsQuantity($value['id']);           
            $canceled[$key]['customer'] = $this->_addCustomer($value['openid']);
            $canceled[$key]['url'] = array('detail'=>U('detail?id='.$value['id']));
        }
        
        $menuList = $canceled;
        $currentPage = I('get.p',1);
        //将树转化为按顺序输出
        $page = $this->page;
        $page->setCounts(count($menuList));
        $page->setCurrentPage($currentPage);
        $page->setPageStyle(2);
        $pageStr = $page->fetch();
        $pageSise = $page->getPageSize();
        //截取本页内容
        $menuList = array_slice($menuList , ($currentPage-1)*$pageSise , $pageSise);
        $this->assign(canceled,$menuList);
        $this->assign('page',$pageStr);
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    
    }
    //查看详情
    public function detailAction(){
        $id = I('get.id');
        $field = array('id','openid','pay_time','payid',
            'procure_source1','procure_orderid1','procure_source2',
            'procure_orderid2','procure_source3','procure_orderid3',
            'origin_orderid1','origin_company1','origin_orderid2',
            'origin_company2','origin_orderid3','origin_company3');
        $where = 'id='.$id;
        $this->orderForm->setAfield($field);
        $this->orderForm->setAwhere($where);
        $detail = $this->orderForm->getOrder();
        $detail = $detail[0];
        $detail['goodsQuantity'] = $this->_addGoodsQuantity($detail['id']);
        $detail['customer'] = $this->_addCustomer($detail['openid']);
        $detail['goods'] = $this->_addGoods($detail['id']);
        $detail['consignee'] = $this->_addConsignee($detail['payid']);
        $this->assign(detail,$detail);
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    
    /*
     * 取消订单,只有在订单状态未完成的时候才可以取消
     */
    public function cancelPayAction()
    {
        $payId = I('get.payid','');
        if($payId == '')
        {
            die("系统错误：未接收到payid");
        }
        $page = I('get.p',1);
        
        //删除支付订单表
        $relation = new OrderRelationModel();
        $relation->setPayId($payId);
        $key = 'id';
        $relation->setKey($key);
        $relation->cancleInfoByPayid();
        
        //删除订单表
        $orderForm = new OrderFormModel();
        $orderForm->setPayid($payId);
        $key = 'id';
        $orderForm->setPayidKey($key);
        $orderForm->cancelDataByPayid();
        
        //设置跳转URL
        $jumpUrl = U("nopay?payid=" . $payid . "&p=" . $page);
        $this->success('订单已取消', $jumpUrl);      
    }
    
    
    //添加商品总数
    private function _addGoodsQuantity($id){
        $this->orderGoods->setOrderId($id);
        $goodsQuantity = $this->orderGoods->getGoodsQuantity();
        return $goodsQuantity?$goodsQuantity:0;
    }
    //添加商品信息
    private function _addGoods($id){
        $this->orderGoods->setOrderId($id);
        $goods = $this->orderGoods->getOrderGoodsInfo();
        return $goods;
    }
    //添加客户昵称和手机号
    private function _addCustomer($openId){
        $this->customer->setOpenId($openId);
        $customerInf = $this->customer->getCustomerInf();
        return $customerInf;
    }
    //添加收货人信息，包括收货人地址，收货人身份证号，身份证照片
    private function _addConsignee($payId){
        $this->relation->setPayId($payId);
        $consignee = $this->relation->getConsignee();
        $attachment = new AttachmentModel;
        $attachment->setId($consignee['customer_frontid']);
        $consignee['frontpath'] = $attachment->getAttchmentPath();
        $attachment->setId($consignee['customer_backid']);
        $consignee['backpath'] = $attachment->getAttchmentPath();
        return $consignee;
    }
    /*
     * 为正在配货订单添加支付信息
     * 包括支付账号和支付流水号
     */
    private function  _addPickPayInfo($payId)
    {
        $relation = new OrderRelationModel;
        $payInf = $this->relation->getPayInf($payId);
        return $payInf;
    }
    
    /*
     * 为“未支付订单添加URL信息”
     */
    private function  _addNopayUrlInfo($dataArr,$page)
    {
        foreach($dataArr as $key => $value)
        {
            $url['pay'] = U('OrderForm/OrderManager/pay?payid=' . $value['id'] . '&page=' . $page);
            $url['cancel'] = U('OrderForm/OrderManager/cancelPay?payid=' . $value['id'] . '&page=' . $page);
            $url['change'] = U('OrderForm/OrderManager/changePay?payid=' . $value['id'] . '&page=' . $page);
            $dataArr[$key]['_actionUrl'] = $url;
        }
        return $dataArr;
    }
}