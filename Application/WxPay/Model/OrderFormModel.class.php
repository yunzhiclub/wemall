<?php
/* 
 *操作订单表order_form
 * pan jie 
 */
namespace WxPay\Model;
use Think\Model;
class OrderFormModel extends Model
{
    private $id = null; //存用户opendid
    private $prepayId = null;//预处理信息
    private $sign = null;//签名信息,arr.包括id 随机字符串 签名
    private $postArr = null;//微信服务器异步能知数据
    public function setId($id) {
        $this->id = $id;
    }
    public function setPrePayId($prepayId)
    {
        $this->prepayId = $prepayId;
    }  
    function setSign($sign) {
        $this->sign = $sign;
    }
    function setPostArr($postArr) {
        $this->postArr = $postArr;
    }

    //根据订单号查出当前订单预处理号
    //用户未及时提交，可以在我的订单中，继续付款。
    public function getPrepayId()
    {
        /*
         * 获取基本信息
         * 前置条件,用户设置了id
         */
        $res = $this->_getOrderInfo();
        if($res == FALSE || $res['prepay_id'] == '' || $res['prepay_id'] == null )
        {
            return false;
        }
        else
        {
            return $res['prepay_id'];
        }
    }
    /*
     *  存相关订单号的预处理信息
     */
    public function savePrepayId(){
        if($this->id == null || $this->prepayId == null)
        {
            return false;
        }
        $data['id'] = $this->id;
        $data['prepay_id'] = $this->prepayId;
        $this->data($data)->save();
        return;
    }
    //获取描述信息
    public function getBody()
    {
        $res = $this->_getOrderInfo();
        if($res == FALSE)
        {
            return false;
        }
        else
        {
            return $res['body'];
        }
    }
    //获取商品总额
    public function getTotalFee()
    {
        $res = $this->_getOrderInfo();
        if($res == FALSE)
        {
            return false;
        }
        else
        {
            return $res['total_fee'];
        }
        
    }
    public function saveSign()
    {
        if($this->sign == null)
        {
            return false;
        }
        $this->data($this->sign)->save();
    }
    /*
     * 查询订单状态
     * 验证订单是否已支付
     * @return 未支付 -1
     * @return 已支付 返回状态字
     */
    public function checkStatus()
    {
        if($this->postArr == null)
        {
            return -1;
        }
        $map = array();
        $map['payid'] = $this->postArr['out_trade_no'];
        $res = $this->where($map)->find();
        if($res == false)
        {
            return -1;
        }
        else
        {
            $status = $res['order_status'];
            //检测是否已支付
            if( ((int)$status & 2) == 2)
            {
                return -1;
            }
        }
        return $status;
    }
    /*
     * 接收微信返回值后对记录进行更新
     * 1.查找所有的支付订单号为当前支付订单号的订单
     * 2.进行遍历，取出第一位。
     * 2.1 状态为微信已支付的（第1位为0），返回
     * 2.2 状态为微信未支付的，将第1位取出后置1.
     */
    public function saveInfo()
    {
        if($this->postArr == null)
        {
            return;
        }
        //如果状态为成功,则更新状态字
        $map['payid'] = $this->postArr['out_trade_no'];
        $res = $this->where($map)->select();
        if($res == false)
        {
            return;
        }
        //状态字第2位，是否已支付  10
        foreach($res  as $key => $value)
        {
            $value['order_status'] = (int)$value['order_status'] | 2;
            $value['is_pay'] = 1;
            $value['pay_time'] = time();
            $this->data($value)->save();
        }        
        return true;
    }
    //获取当前ID下的商品信息
    //@require 商品ID
    private function _getOrderInfo()
    {
        if($this->id == null)
        {
            return FALSE;
        }
        else
        {
            $map['id'] = $this->id;
            $res = $this->where($map)->find();
            if($res == false)
            {
                return false;
            }
            return $res;  
        }                    
    }
}

