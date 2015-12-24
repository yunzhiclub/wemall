<?php

/* 
 * 梦云智工作室
 *   * 
 * 支付表
 * 订单后台管理支付时用
 * ao
 * 2015-05-02 20:34:12
 */
namespace OrderRelation\Model;
use Think\Model;
use Customer\Model\CustomerModel;
class OrderRelationModel extends Model{
    private $totalInfo = null; //支付订单号信息
    private $payId;//支付号
    private $openid = null; 
    private $beginTime = null; //起始时间
    private $endTime = null;   //终止时间
    private $map = null; //查询条件
    private $currentPage = null ; //当前页
    private $pageSize = null; //每页大小
    private $key = null; //指定payid的关键字
    private $orderBy = null; //排序字段,数组
    
    function setOrderBy($orderBy) {
        $this->orderBy = $orderBy;
    }


        
    function setKey($key) {
        $this->key = $key;
    }

        
    function setCurrentPage($currentPage) {
        $this->currentPage = $currentPage;
    }

    function setPageSize($pageSize) {
        $this->pageSize = $pageSize;
    }
    
    function setMap($map)
    {
        $this->map = $map;
    }
    function setBeginTime($beginTime) {
        $this->beginTime = $beginTime;
    }

    function setEndTime($endTime) {
        $this->endTime = $endTime;
    }

    public function setOpenid($openid)
    {
        $this->openid = $openid;
    }
    
    /*
     * 管理员后台填写信息
     * 完成支付
     * 完成订单状态改变
     */
    
    public function toPay($payid){
        $data = I('post.');
        //存入时间戳，账号和流水号
        $data['payed_time'] = time();
        $data['id'] = $payid;
        $data['trade_type'] = 'OTHER';
        $data['is_payed'] = 1;
        $this->save($data);
    }
    
    
    public function setTotalInfo($totalInfo)
    {
        $this->totalInfo = $totalInfo;
    }
    
    public function __construct($name = '', $tablePrefix = '', $connection = '') {
        parent::__construct($name, $tablePrefix, $connection);
        $this->currentPage = 1;
        $this->pageSize = 20;
        $this->map = array();
        $this->key = 'payid';
    }
    /*
     * 生成新的订单，返回订单号
     * @input 
     * payable  int 总金额
     * @output
     * id int 订单号
     * 
     * 1.插入新记录
     * 2.返回订单号
     */
    public function addOrder()
    {     
        $totalInfo = $this->totalInfo;
        $addressInfo = $totalInfo['addressInfo'];
        
        $data['payable'] = $totalInfo['totalPayable'];
        $data['total_goods_fee'] = $totalInfo['totalFee'];
        $data['total_coupon'] = $totalInfo['totalCoupon'];
        $data['total_count'] = $totalInfo['totalCount'];
        $data['total_logistic_fee'] = $totalInfo['totalLogisticFee'];
        $data['total_amount'] = $totalInfo['totalAmount'];
        $data['customer_address'] = $addressInfo['name'] 
                . "&nbsp;&nbsp;" . $addressInfo['phone'] 
                . "<br />" . $addressInfo['provice'] 
                . "&nbsp;&nbsp;" . $addressInfo['city']
                . "&nbsp;&nbsp;" . $addressInfo['address'];
        $data['customer_frontid'] = $addressInfo['frontid'];
        $data['customer_backid'] = $addressInfo['backid'];
        $data['customer_idno'] = $addressInfo['id_no'];
        $data['buy_openid'] = $totalInfo['buyOpenid'];
        $data['total_direct_fee'] = $totalInfo['totalDirectFee']; //直销返点
        $data['total_line_fee'] = $totalInfo['totallineFee'];//线销返点
        $data['total_customs_fee'] = $totalInfo['totalCustomsFee']; //关税
        
        $data['creat_time'] = time();
        $appid = C('WECHAT_APPID');   //商户APPID 
        $mch_id = C('WXPAY_MCHID');  //商户MCH_ID
        $data['appid'] = $appid;
        $data['mch_id'] = $mch_id;
        $this->data($data)->add();
        $id = $this->getLastInsID(); 
        $data = array();
        $data['id'] = $id;
        $data['body'] = "支付订单编号:$id";
        $this->save($data);
        return $id;       
    }
    public function setPayId($payId){
        $this->payId = $payId;
    }

    public function getConsignee(){
        $map['id'] = $this->payId;
        $res = $this->field('customer_address,customer_frontid,customer_backid,customer_idno')->where($map)->find();
        return $res;
    }
    public function getPayInf($payId){
        $map['id'] = $payId;
        $res = $this->field('payment_number,payment_serialnumber')->where($map)->find();
        return $res;
    }
    
    /*
     * 通过payid获取支付信息并拼接
     */
    public function getPayInfoArrByPayIdArr($allOrderArr , $key , $resKey)
    {
        foreach ($allOrderArr as $k => $v)
        {
            $map['id'] = $v[$key];
            $res = $this->where($map)->find();
            $allOrderArr[$k][$resKey] = $res;
        }
        return $allOrderArr;
    }
    
    /*获取客户在一段时间内的所有已支付信息
     * input array() 用户opendid组成的数组
     * output 按数组形式返回的数据
     * 包括：直销业绩　线销业绩　直销基数　线销基数
     * 
     */
    public function getPayedOrder()
    {
        if( $this->beginTime == null || $this->endTime == null)
        {
            return false;
        }
        
        /*
         * 取出本时间段的全部信息
         * 1.拼接ＰＩＤ信息
         * 2.按ＩＤ进行分组
         * 3.按PID分组        * 
         * 4.得到PID信息
         * 5.在原ID信息拼接上对应的PID信息
         */
        
        $map['payed_time'] = array('BETWEEN',$this->beginTime.','.$this->endTime);
        $map['is_pay'] = 1;
        $map['is_cancel'] = 0;
        $resData = $this->where($map)->select();
        
        //拼接客户信息
        $kewWord = 'buy_openid';
        $resData = get_customer_info_by_openid($resData,$kewWord);
        
        
        //按客户id分组
        $key1 = 'buy_openid';
        $key2 = 'id';
        $direData = group_by_key1_key2($resData, $key1 , $key2);
        
        $dataRes = array();
        //生成客户信息
        foreach($direData as $key => $value)
        {
            $data = array();
            $totalDirectFee = 0; //直销业绩
            $totalLineFee = 0;  //线销业绩
            $totalGoodsFee = 0; //商品总价(基数)
            foreach ( $value  as $ke => $va)
            {        
                $totalDirectFee += $va['total_direct_fee'];
                $totalLineFee += $va['total_line_fee'];
                $totalGoodsFee += $va['total_goods_fee'];
            }
            //添加业绩信息
            $data['totalDirectFee'] = $totalDirectFee;
            $data['totalLineFee'] = $totalLineFee;
            $data['totalGoodsFee'] = $totalGoodsFee;
            $data['_detail'] = $value;
            //添加parentId
            $data['parId'] = $value[0]['buy_openid']['parentid'];  
            $data['_customer_id'] = $value[0]['buy_openid']['id'];  
            $dataRes[$key] = $data;
        }
        
        //按客户pid再分组,拼接线销信息
        $key2 = 'parId';
        $lineData = group_by_key($dataRes, $key2);
        $lineDateRes = array();    
        foreach ($lineData as $key1 => $value1){
            $ltotalDirectFee = 0; //下线直销业绩
            $ltotalLineFee = 0;  //下线线销业绩
            $ltotalGoodsFee = 0; //下线商品总价(基数)
            foreach ($value1 as $key => $value) {
                $ltotalDirectFee += $value['totalDirectFee'];
                $ltotalLineFee += $value['totalLineFee'];
                $ltotalGoodsFee += $value['totalGoodsFee'];
            } 
            $lineDateRes[$key1]['parId'] = $value['parId'];
            $lineDateRes[$key1]['totalDirectFee'] = $ltotalDirectFee;
            $lineDateRes[$key1]['totalLineFee'] = $ltotalLineFee;
            $lineDateRes[$key1]['totalGoodsFee'] = $ltotalGoodsFee;
            $lineDateRes[$key1]['_lineDetail'] = $value1;
        }
        
        //设置空数组
        $emptyArr = array("totalDirectFee"=>0,
            "totalLineFee"=>0,
            "totalGoodsFee"=>0,
            "_lineDetail"=>array());
        
        //把id与它所对应的下线信息拼接到一起
        foreach ($dataRes as $key => $value) {
            if(isset($lineDateRes[$key]))
            {
                
                $dataRes[$key]['_line'] = $lineDateRes[$key];
                unset($lineDateRes[$key]);
            }
            else
            {
                $dataRes[$key]['_line'] = $emptyArr;
            }
            $dataRes[$key]['lineTotalLineFee'] = $dataRes[$key]['_line']['totalLineFee'];
            $dataRes[$key]['lineTotalGoodsFee'] = $dataRes[$key]['_line']['totalGoodsFee'];
        }
        
        //设置空数组
        $emptyArr = array(
            "totalDirectFee"=>0,
            "totalLineFee"=>0,
            "totalGoodsFee"=>0,
            "_detail"=>array()
        );
        
        //将上线无数据的元素进行空数拼接
        foreach($lineDateRes as $key => $value)
        {
            $dataRes[$key] = $emptyArr;
            $dataRes[$key]['_line'] = $value;
            $dataRes[$key]['_customer_id'] = $key;
            $dataRes[$key]['lineTotalLineFee'] = $value['totalLineFee'];
            $dataRes[$key]['lineTotalGoodsFee'] = $value['totalGoodsFee'];
                    
        }
        
        //去除上线为系统的记录
        unset($dataRes[0]);

        return $dataRes;
//        
//        
//        
//        
//                
//        
//        
//        /*
//         * 先获取直销信息，再获取线销信息 
//         */
//        foreach($customerArr as $ke => $value)
//        {
//            $map['openid'] = $value[$key];
//            
//            $res = $this->where($map)->select();
//            $customerArr[$ke]['achievement'] = $res;
//            $customerArr[$ke] = array_merge($value,$this->_getAchieveData($res));
//            
//            //取下线的业绩
//            $mapChild = array();
//            $mapChild['parentid'] = $v['id'];
//            $child = $this->where($mapChild)->select();
//            
//            $totalDirectFee = 0; //直销业绩
//            $totalLineFee = 0;  //线销业绩
//            $totalGoodsFee = 0; //商品总价(基数)
//            foreach($child as $cke => $cva)
//            {
//                $map['openid'] = $cva[$key];
//                $res = $this->where($map)->select();
//                $detailData = $this->_getAchieveData($res);
//                $child[$cke]['achievement'] = array_merge($cva,$detailData);
//                $totalDirectFee += $detailData['_totalDirectFee'];
//                $totalLineFee += $detailData['_totalLineFee'];
//                $totalGoodsFee += $detailData['_totalGoodsFee'];
//            }
//            $child['_totalDirectFee'] = $totalDirectFee;
//            $child['_totalLineFee'] = $totalLineFee;
//            $child['_totalGoodsFee'] = $totalGoodsFee;
//            
//            $customerArr[$ke]['_child'] = $child;   
//        }
//        
//        return $customerArr;
    }
    
    
    public function getNopayOrderByOpenid($openid )
    {
        $map['buy_openid'] = $openid;
        $map['is_payed'] = 0;
        $map['is_cancel'] = 0;
        $res = $this->where($map)->select();
        return $res;
    }   
    
    /*
     * 获取当前OPENID的当期业绩信息
     * 包括直销业绩
     * 返回包括总和信息的数组
     */
    public function getAchievementByOpenid($openid , $beginTime , $endTime)
    {
        $map['is_payed'] = 1;
        $map['is_cancel'] = 0;
        $map['payed_time'] = array('BETWEEN',"$beginTime,$endTime");
        if( is_array($openid) )
        {               
            $customer = new CustomerModel();
            $totalDirectFee = 0; //直销业绩
            $totalLineFee = 0;  //线销业绩
            $totalGoodsFee = 0; //商品总价(基数)
            $resArr = array();
            foreach($openid as $k => $v)
            {
                $map['buy_openid'] = $v['openid'];
                $data = $this->where($map)->select();              
                $res = $this->_getAchieveData($data);
                $res['_customer'] = $customer->getCustomerInfo($v['openid']);
                $res['_detail'] = $data;
                $totalDirectFee += $res['_totalDirectFee'];
                $totalLineFee += $res['_totalLineFee'];
                $totalGoodsFee += $res['_totalGoodsFee'];                     
                $resArr['_detail'][$v['openid']] = $res;
            }
            $resArr['_totalDirectFee'] = $totalDirectFee;
            $resArr['_totalLineFee'] = $totalLineFee;
            $resArr['_totalGoodsFee'] = $totalGoodsFee;
            return $resArr;
        }
        else
        {
            $map['buy_openid'] = $openid;
            $data = $this->where($map)->select();
            $res = $this->_getAchieveData($data);
            $res['_detail'] = $data;
            return $res;
        }
    }
    
    /*
     * 获取未支付订单的条数
     */
    public function getOrderCounts()
    {
        $counts = $this->where($this->map)->count();
        return $counts;

    }
    
    /*
     * 获取当前页未支付的数据
     */
    public function getCurrentPageOrder()
    {
        $map = $this->map;
        $orderBy = $this->orderBy;
        $res = $this->where($this->map)->order($orderBy)->page(($this->currentPage-1) . ',' . $this->pageSize)->select();
        return $res;
    }
    
    /*
     * 通过payid,取消订单
     * payid可以是数组，也可以是单个数据
     * 前提：需要指定KEY。
     */
    public function cancleInfoByPayid()
    {
        $payid = $this->payId;
        if($payid == null)
        {
            return false;
        }
        
        $key = $this->key;
        if(is_array($payid))
        {
            return false;
        }
        else
        {
            $map['id'] = $payid;
            $data['is_cancel'] = 1;
            $this->where($map)->save($data);
            return true;
        }
    }


    /*
     * 计算获取业绩信息的分项值，并返回包括：直销业绩、线销业绩、商品总价的数组
     */
    private function _getAchieveData($data)
    {
        $totalDirectFee = 0; //直销业绩
        $totalLineFee = 0;  //线销业绩
        $totalGoodsFee = 0; //商品总价(基数)

        foreach($data as $k => $v)
        {
            $totalDirectFee += $v['total_direct_fee'];
            $totalLineFee += $v['total_line_fee'];
            $totalGoodsFee += $v['total_goods_fee'];                
        }
        
        $dataArr = array('_totalDirectFee' => $totalDirectFee,'_totalLineFee' => $totalLineFee,'_totalGoodsFee' => $totalGoodsFee);
        return $dataArr;
    }
    function changePay($id){
        $map['id'] = $id;
        $res = $this->where($map)->find();
        return $res;
    }
    //修改未支付订单中的订单总额
    function toChange($id){
        $map['id'] = $id;
        $payable = I('post.payable');
        $data['payable'] = huansuan($payable);
        $this->where($map)->save($data);
        $resData['status'] = 'success';
        $resData['msg'] = ' 操作成功';
        return $resData;
    }
    
}

