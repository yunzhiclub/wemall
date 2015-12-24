<?php

/* 
 * 业绩管理
 * 当期业绩
 *   * 
 */
namespace Achievement\Model;
use Think\Model;
use OrderRelation\Model\OrderRelationModel;
use Rebate\Model\RebateModel;
class AchievementModel extends Model{
    private $currentpage = null;
    private $pageSize = null;
    private $openid;   //用户openid
    private $customer;  //用户信息
    private $id;
    private $issue = null; //结算周期id
    function setIssue($issue) {
        $this->issue = $issue;
    }
    function setCurrentpage($currentpage) {
        $this->currentpage = $currentpage;
    }

    function setPageSize($pageSize) {
        $this->pageSize = $pageSize;
    }

    function setCustomer($customer) {
        $this->customer = $customer;
    }
    
    public function getHistory(){
        $res = $this->where( 'openid='."'$this->openId'")->select();
        return $res;
    }
    public function getHistoryInf(){
        $res = $this->where('id='.$this->id)->find();
        return $res;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function setOpenid($openid) {
        $this->openid = $openid;
    }
    /*
     * 获取总数
     */
    public function getCounts($map = array())
    {
        return $this->where($map)->count();
    }
    /*
     * 获取相关记录
     * 拼接OPENID信息
     * 拼接结算信息
     */
    public function getData($currentPage,$pageSize,$map = array())
    {
        $data = $this->where($map)->page($currentPage . ',' . $pageSize)->select();
        $data = get_customer_info_by_openid($data);
        $data = get_issueinfo_by_id($data);
        return $data;
    }
    /*
     * 获取当前issue结算周期的所有数据
     */
    public function getIssueData()
    {
        if($this->issue == null)
        {
            return false;
        }
        $map['issue'] = $this->issue;
        
        $resArr = $this->where($map)->order('state')->page($this->currentpage.','.$this->pageSize)->select();
        return $resArr;
    }
    
    /*
     * 拼接业绩信息
     * 如果有opend信息
     * 则取当前openid数据
     * 没有openid,则取全部数据
     */
    public function getInfoArrByIssueArr($issueInfoArr , $key , $resKey)
    {
        foreach($issueInfoArr as $k => $v)
        {
            $map['issue'] = $v[$key];
            if($this->openid != null)
            {
                $map['openId'] = $this->openid;
                $res = $this->where($map)->find();
                $issueInfoArr[$k][$resKey] = $res;
            }
        }
        return $issueInfoArr;
        
    }
    /*
     * 删除当前结算周期下所以状态为“未发放”的记录
     * 查询要添加的信息中是否有OPENID和结算周期都相同的记录
     * 有，则跳过。
     * 无，则添加
     * 业绩发放状态：1已发放 0未发放
     */
    
    public function updateInfo($issue,$settles)
    {
        $map = array();
        $map['state'] = 0;
        $map['issue'] = $issue;
        $this->where($map)->delete();
        foreach($settles as $key => $value)
        {
            $map = array();
            $map['openId'] = $key;
            $map['issue'] = $issue;
            $res = $this->where($map)->find();
            if($res == false)
            {
                $data = array();
                $data['openId'] = $key;
                $data['time'] = time();
                
                $data['direct_total_fee'] = $value['totalGoodsFee']; //直销业绩
                $data['line_total_fee'] = $value['lineTotalGoodsFee']; //分销业绩              
                $data['direct_base'] = $value['totalDirectFee']; //直销基数
                $data['line_base'] = $value['lineTotalLineFee']; //分销基数
                $data['direct_coe'] = $value['_totalGoodsFee_rebate']['direct_ratio']; //直销系数
                $data['line_coe'] = $value['_lineTotalGoodsFee_rebate']['line_ratio']; //分销系统
                
                $data['direct_result']= floor($data['direct_base'] * $data['direct_coe']); //直销应发
                $data['line_result'] = floor( $data['line_base'] * $data['line_coe']);     //分销应发
                
                
                $data['issue'] = $issue;
                $data['due_pay'] = $data['direct_result'] + $data['line_result'];
                $data['state'] = 0;
                $data['opreate_userid'] = session('userId');
                $this->data($data)->add();
            }
        }
    }
    /*
     * 获取ID为$ID的记录信息
     */
    public function getInfo($id)
    {
        $map['id'] = $id;
        return $this->where($map)->find();
    }
    
    /*
     * 获取当期结算业绩详情
     * 包括直销\线销\系数等所有信息
     */
    
    public function getCurrentInfo()
    {
        //获取上周期的开始结束日期
        $time = $this->_getTimes();
        $beginTime = $time['endTime'];
        $endTime = time();
        $currentInfo = $this->_getInfoBetweenTime($beginTime, $endTime);
        return $currentInfo;     
    }
    
    /*
     * 生成上期业绩数据
     * 上期业绩:指刚刚发生需要结算的业绩
     */
    public function getJustInfo()
    {
        //获取上周期的开始结束日期
        $time = $this->_getTimes();
        $beginTime = $time['beginTime'];
        $endTime = $time['endTime'];
        $justInfo = $this->_getInfoBetweenTime($beginTime, $endTime);
        return $justInfo;     
    }
    
    
    /*
     * 获取当前用户当期的业绩信息
     */
    public function getCurrentCustomerCurrentIssueData()
    {
        if($this->customer == null)
        {
            return false;
        }
        /*取出起始时间******/
        $time = $this->_getTimes();
        $beginTime = $time['endTime'];
        $endTime = time();
        
        /*取出当前时段下的所有直销业绩\直销基数******/
        $orderRelation = new OrderRelationModel();
        $customerArr[] = $this->customer;
        $orderRelation->setBeginTime($beginTime);
        $orderRelation->setEndTime($endTime);
        $achievementDataArr = $orderRelation->getPayedOrder($customerArr);
        $achievementData = $achievementDataArr[0];
        return $achievementData;
        
    }
    
    /*
     * 未当前周期的总条数
     */
    public function getIssueCounts()
    {
        $issue = $this->issue;
        if($issue == null)
        {
            return false;
        }
        $map['issue'] = $this->issue;
        return $this->where($map)->count(); 
    }
    
    /*
     * 存单条支付信息
     * 1.查询支付状态
     * 1.1 状态为支付,返回"订单已支付,请务重复提交"
     * 1.2 未支付,更改订单状态
     */
    public function savePayedInfo($data)
    {
        $map['id'] = $data['id'];
        $res = $this->where($map)->find();
        if($res['state'] == 1)
        {
            return '订单已支付,请务重复提交';
        }
        else
        {
            $data['real_pay'] = huansuan($data['real_pay']);
            $data['state'] = 1;
            $this->save($data);
            return true;
        }
    }
    
    /*
     * 获取起止时间
     * $return array("begin"=>,"end"=>);
     * 1.获取数据库起始时间
     * 2.起始时间排序
     * 3.取出当前时间
     * 4.进行区间判断
     * 5.返回(上一计费周期的)起始结束时间
     */
    private function _getTimes()
    {
        $config = new ConfigModel();
        $configArr = $config->fetConfig();
        $beginDate = $configArr[0]['value'];
        $endDate = $configArr[1]['value'];
        
        if($beginDate > $endDate)
        {
            $tem = $beginDate;
            $beginDate = $endDate;
            $endDate = $tem;
        }
       $day = date("d",time());
       $year = date("y",time());
       $month = date("m",time());
       $perMonth = $month - 1 ;
       if($perMonth == 0)
       {
            $perMonth = 12;
            $perYear = $year-1;
       }
       else
       {
           $perYear = $year;
       }
       //小于最小日期,结束时间起上月结结束时间
       //开始时间取上月开始时间
       if($day < $beginDate)
       {
            $endTime = mktime(0, 0, 0,$perMonth,$endDate,$perYear);
            $beginTime = mktime(0, 0, 0,$perMonth,$beginDate,$perYear);
       }
       /*
        * 当前日期处于两个日期之间
        * 开始时间取上月结束时间
        * 结束时间取本月开始时间
        */
       elseif($day < $endDate)
       {
            $beginTime = mktime(0, 0, 0,$perMonth,$endDate,$perYear); 
            $endTime = mktime(0, 0, 0,$month,$beginDate,$year);
       }
       /*
        * 当前日期大于结束日期
        * 开始时间取本月开始时间
        * 结束时间取本月结束时间
        */
       else
       {
            $beginTime = mktime(0, 0, 0,$month,$beginDate,$year); 
            $endTime = mktime(0, 0, 0,$month,$endDate,$year); 
       }
       return array("beginTime"=>$beginTime , "endTime"=>$endTime);
    }
    
    private function _getInfoBetweenTime($beginTime,$endTime)
    {
        //取出当前时间段的数据
        $orderRelation = new OrderRelationModel();
        $orderRelation->setBeginTime($beginTime);
        $orderRelation->setEndTime($endTime);    
        $currentSettles = $orderRelation->getPayedOrder();
        
        //添加用户信息
        $key = '_customer_id';
        $currentSettles = get_customer_info_by_id($currentSettles, $key);
        
        //添加系数信息
        $rebate = new RebateModel();
        $key = 'totalGoodsFee';
        $keyRes = '_totalGoodsFee_rebate';
        $currentSettles = $rebate->getRatioByMoney($currentSettles, $key , $keyRes );
        $key = 'lineTotalGoodsFee';
        $keyRes = '_lineTotalGoodsFee_rebate';
        $currentSettles = $rebate->getRatioByMoney($currentSettles, $key , $keyRes );
        
        //计算佣金
        foreach($currentSettles as $key => $value)
        {
            $currentSettles[$key]['commission'] = 
                    floor($value['totalDirectFee']*$value['_totalGoodsFee_rebate']['direct_ratio']
                            +
                    $value['lineTotalLineFee']*$value['_lineTotalGoodsFee_rebate']['line_ratio']);
        }
        $key1 = '_customer_id';
        $key2 = 'openid';
        $currentSettles = change_key_by_key1_key2($currentSettles, $key1, $key2);
        return $currentSettles;
    }
}
