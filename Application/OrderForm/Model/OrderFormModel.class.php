<?php

/*
 *订单表
 */
namespace OrderForm\Model;
use Think\Model;
class OrderFormModel extends Model{
    //取库
    private $customerId;//客户的编号author xingyannian
    private $orderFormState;
    private $payidKey; //payid的key
    private $orderData = null;  //订单的数据，下面为数据项
    private $orderPayId = null; //支付订单号
    
    function setOrderPayId($orderPayId) {
        $this->orderPayId = $orderPayId;
    }

        
    function setPayidKey($payidKey) {
        $this->payidKey = $payidKey;
    }

        
    /*
    * payid int 支付ID
     * openid string  用户openid
     * logisticsMode 物流方式
     * source 来源
     * payable总价格
     * weight 总重量
     * countTotal 商品总数 
     * address详细地址
     * telephone 电话号码
     * name 名字
     * frontId 身份证正面附件ID
     * $backId反面附件id
     * idNo 身份证号码
     * $couponFee 优惠金额
     * directFee 直接返点金额
     * lineFee 线销返点金额
     * $logisticFee 运费
     * $maxAmonut 最大单一优惠
     * $couponArr 使用优惠券
     * $countTotal 使用优惠券最大优惠金额
     */
    function setData($data)
    {
        $this->orderData = $data;
    }
    
    function setPayid($payid) {
        $this->orderData['payid'] = $payid;
        $this->payid = $payid;
    }

    function setOpenid($openid) {
        $this->openid = $openid;
        $this->orderData['openid'] = $openid;
    }

    function setLogisticsMode($logisticsMode) {
        $this->orderData['logistics_mode'] = $logisticsMode;
    }
    function setSource($source) {
        $this->orderData['source'] = $source;
    }
    function setSourcel($sourcel) {
        $this->orderData['sourcel'] = $sourcel;
    }

    function setPayable($payable) {
        $this->orderData['total_prices'] = $payable;
    }

    function setWeight($weight) {
        $this->orderData['weight'] = $weight;
    }

    function setCountTotal($countTotal) {
        $this->orderData['total_count'] = $countTotal;
    }

    function setDirectFee($directFee) {
        $this->orderData['direct_fee'] = $directFee;
    }

    function setLineFee($lineFee) {
        $this->orderData['line_fee'] = $lineFee;
    }

    function setLogisticFee($logisticFee) {
        $this->orderData['logistic_fee'] = $logisticFee;
    }

    function setMaxAmonut($maxAmonut) {
        $this->orderData['max_amonut'] = $maxAmonut;
        $this->orderData['privilege_money'] = $maxAmonut;
    }
    
        
    public function getOrderFormState() {
        return $this->orderFormState;
    }

    public function setOrderFormState($orderFormState) {
        $this->orderFormState = $orderFormState;
    }
    
    /*
     * 取出近三个月的所有订单信息
     */
    public function getAllOrderArrByOpenid($openid)
    {
        $map['openid'] = $openid;
        $map['buy_time'] = array('gt',time()-90*24*60*60);
        return $this->where($map)->order('id desc')->select();
    }
    
    
    public function getCustomerId() {
        return $this->customerId;
    }
    
    public function setCustomerId($customerId) {
        $this->customerId = $customerId;
    }//设置客户的编号author xingyannian

    public function getOrderFormInfo(){
        $map['openid'] = $this->openid;
        $res = $this->where($map)->order('buy_time desc')->select();
        return $res;   
    }//根据客户的编号获取全部订单信息author xingyannian
    public function getOrderFormInfoByState() {
        $map['customer_id'] = $this->customerId;
        $where = "$this->orderFormState&127==126";
        
        return $res;
    }
    
    /*
     * 已支付、未完成、未取消的订单数量
     */
    
    public function getPayedOrderCountByOpenid($openid)
    {
        $map['is_pay'] = 1;
        $map['is_cancel'] = 0;
        $map['is_finish'] = 0;
        $map['openid'] = $openid;
        $count = $this->where($map)->count();
        return $count;
    }
    
    /*
     * 已支付、未完成、未取消的订单详情
     */
    public function getPayedOrderArrByOpenid($openid)
    {
        $map['is_pay'] = 1;
        $map['is_cancel'] = 0;
        $map['is_finish'] = 0;
        $map['openid'] = $openid;
        $resArr = $this->where($map)->order('id desc')->select();
        return $resArr;
    }
    
    public function __construct($name = '', $tablePrefix = '', $connection = '') {
        parent::__construct($name, $tablePrefix, $connection);
        $this->orderData = array();
        $this->payidKey = 'payid';
    }
    public function init() {
        $res = $this->select();
        return $res;
    }
    
    public function calculate($totalweight) {
        
        //1.config表取五字段
        //2.计算运费
        $freightM = D('System/Config');
        $freight = $freightM->freightCount();
        $ttweight = $totalweight+$freight[4]['SYSTEM_PACKAGE_HEAVY'];
        if($ttweight<=$freight[0]['SYSTEM_FIRST_HEAVY']){
            $interfreight = $freight[0]['SYSTEM_FIRST_HEAVY'];
        }
        else {
            $weight1 = (($totalweight+$freight[4]['SYSTEM_PACKAGE_HEAVY'])-$freight[0]['SYSTEM_FIRST_HEAVY'])/$freight[2]['SYSTEM_CONTINUE_HEAVY'];
            $weight2 = ceil($weight1);
            
            $interfreight = $weight2*$freight[3]['SYSYEM_CONTINUE_COST']+$freight[1]['SYSTEM_FIRST_COST'];
        }
        
    }
     /*
     * 根据payid取消订单
     */
    public function cancelDataByPayid()
    {
        $payid = $this->payid;
        if($payid == null)
        {
            return false;
        }
        
        if(is_array($payid) )
        {
            return false;
            //添加对数据的处理
        }
        else
        {
            $map['payid'] = $payid;
            $res = $this->where($map)->select();
            foreach($res as $key => $value)
            {
                $value['is_cancel'] = 1;
                $this->data($value)->save();
            }
        }
        return true;
    }
    
    /*
     * 通过ID完成订单 
     */
    public function finishOrderById($id)
    {
        $data['is_finish'] = 1;
        $data['id'] = $id;
        $data['is_inter'] = 1;
        $this->save($data);
        return true;
    }
    
    function addOrderForm($product) {
        var_dump($product);
        foreach ($product as $key => $value) {
            $orderform['payid'] = time().rand(100,999);
            $orderform['logistics_mode'] = $value['logistics_mode'];            
            $orderform['buy_time'] = time();
            $orderform['openid'] = get_openid();
            $orderform[''] = $value['logistics_mode'];
            $orderform[''] = $value['logistics_mode'];
            $orderform[''] = $value['logistics_mode'];
//        $orderform[''] = $value['logistics_mode'];
//        $orderform[''] = $value['logistics_mode'];
//        $orderform[''] = $value['logistics_mode'];
//        $orderform[''] = $value['logistics_mode'];
//        $orderform[''] = $value['logistics_mode'];
//        $orderform[''] = $value['logistics_mode'];
//        $orderform[''] = $value['logistics_mode'];
//        $orderform[''] = $value['logistics_mode'];
//        $orderform[''] = $value['logistics_mode'];
//        $orderform[''] = $value['logistics_mode'];
//        $orderform[''] = $value['logistics_mode'];
//        $orderform[''] =   $value['logistics_mode'];
        }
    }
    
    //设置查询的字段
    public function setAfield($afield){
        $this->afield = $afield;
    }
    
    //设置查询条件
    public function setAwhere($awhere){
        $this->awhere = $awhere;
    }
    
    /*
     * 获取当前OPEND未支付的订单个数
     */
    
    public function getNopayOrderCountByOpenid($openid)
    {
        $map['is_pay'] = 0;
        $map['is_cancel'] = 0;
        $map['openid'] = $openid;
        $count = $this->where($map)->count();
        return $count;
    }
    
    /*
     * 获取当前openid所以未支付的订单信息
     */
    public function getNopayOrderArrByOpenid($openid)
    {
        $map['is_pay'] = 0;
        $map['is_cancel'] = 0;
        $map['openid'] = $openid;
        $resArr = $this->where($map)->order('id desc')->select();
        return $resArr;
        
    }  
    
    //获取订单信息
    public function getOrder(){
        $res = $this->field($this->afield)->where($this->awhere)->order('pay_time desc')->select();//
        return $res;
    }
    
    //改变支付状态00->10
    public function toPay(){
        $payid = I('get.payid',0); 
        $map['payid'] = $payid;
        $res = $this->where($map)->select();
        foreach ($res as $key => $value) {
            $res[$key]['is_pay'] = 1;
            $res[$key]['pay_time'] = time();
            $this->data($res[$key])->save();
        }
        $resData['status'] = 'success';
        $resData['msg'] = ' 操作成功';
        return $resData;
    }
    
    //改变正在配货状态010->110
    public function toPick(){
        $id = I('get.id'); 
        $map['id'] = $id;
        //更改状态
        
        //保存来源，采购订单号，备注等信息
        $data = I('post.');
        $data['is_pick'] = 1;
        $data['id'] = $id;
        $this->save($data);
        $resData['status'] = 'success';
        $resData['msg'] = ' 操作成功';
        return $resData;
    }
    
    public function toGetLog(){
        $id = I('get.id'); 
        $map['id'] = $id;
        //更改状态
        //保存来源，采购订单号，备注等信息
        $data = I('post.');
        $data['is_getlog'] = 1;
        $data['id'] = $id;
        $this->save($data);
        $resData['status'] = 'success';
        $resData['msg'] = ' 操作成功';
        return $resData;
    }
    
    public function toOriginDistrib(){
        $id = I('get.id'); 
        $map['id'] = $id;
        //更改状态
        //保存来源，采购订单号，备注等信息
        $data = I('post.');
        $data['is_origin'] = 1;
        $data['shipments_time'] = time();
        $data['id'] = $id;
        $this->save($data);
        $resData['status'] = 'success';
        $resData['msg'] = ' 操作成功';
        return $resData;
    }
    
    public function toFinish(){
        $id = I('get.id'); 
        $map['id'] = $id;
        //更改状态
        //保存来源，采购订单号，备注等信息
        $data = array();
        $data['is_inter'] = 1;
        $data['is_finish'] = 1;
        $data['finish_time'] = time();
        $data['id'] = $id;
        $this->save($data);
        $resData['status'] = 'success';
        $resData['msg'] = ' 操作成功';
        return $resData;
    }
    
    public function toCancel(){
        $id = I('get.id'); 
        $map['id'] = $id;
        //更改状态
        //保存来源，采购订单号，备注等信息
        $data = array();
        $data['is_cancel'] = 1;
        $data['id'] = $id;
        $this->save($data);
        $resData['status'] = 'success';
        $resData['msg'] = ' 操作成功';
        return $resData;
    }
    
     /*
     * 生成订单信息
     * @input data array 订单数据项    
     */
    public function addOrder()
    {
        $this->orderData['buy_time'] = time();
        $this->data($this->orderData)->add();
        return $this->getLastInsID();
    }
    
    /*
     * 通过payId获取订单数据
     * dataArr 传入数据
     * key 指出哪项是payid
     * reskey 将数据拼接到哪
     */
    public function getOrderByPayid($dataArr , $key ,$resKey)
    {
        foreach ($dataArr as $k => $v)
        {
            $map['payid'] = $v[$key];
            $res = $this->where($map)->select();
            $dataArr[$k][$resKey] = $res;
        }
        return $dataArr;        
    }
    
    

}
