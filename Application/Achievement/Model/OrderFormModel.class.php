<?php

/* 
 * 梦云智工作室
 *   * 
 */
namespace Achievement\Model;
use Think\Model;
class OrderFormModel extends Model{
    private $openid;
    private $beginTime = null; //业绩统一开始时间
    private $endtime = null; //业绩统一结束时间 
    public function setOpenid($openid){
        $this->openid = $openid;
    }

    function setBeginTime($beginTime) {
        $this->beginTime = $beginTime;
    }

    function setEndtime($endtime) {
        $this->endtime = $endtime;
    }
    
    public function getTotalPrices(){
        $sql = "SELECT SUM(total_prices) sum FROM wemall_order_form WHERE openid = '".$this->openid."' AND order_status = '1'";
        $res = $this->query($sql);
        return $res[0]['sum'];
    }        
    /*
     * 获取当前时间段所有人员的业绩
     * @use: 开始日期、结束日期
     * @return:返回当前时间段内所以支付时间符合且订单状态为正常的订单
     */
    function getSettle()
    {
        if($this->beginTime == null || $this->endtime == null)
        {
            return false;
        }
        $map['pay_time'] = array('between',array($this->beginTime,$this->endtime));
        //$map['_string'] = 'order_status&1=1';
        $resArr = $this->where($map)->select();
        return $resArr;
    }
}