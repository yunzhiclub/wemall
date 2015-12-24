<?php
/*
 * orderGoods 对象
 * ao 
 * 
 */
namespace OrderForm\Model;
use Think\Model;
class OrderGoodsModel extends Model{
    private $orderId;//单一订单ID
    private $orderForm;//多个订单的数组
    private $customerId;//用户id
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }
    public function getOrderForm() {
        return $this->orderForm;
    }

    public function setOrderForm($orderForm) {
        $this->orderForm = $orderForm;
    }
    public function getOrderId() {
        return $this->orderId;
    }

    public function setOrderId($orderId) {
        $this->orderId = $orderId;
    }
    /*获取商品信息，供订单管理调用    
     * ao
     * 2015-5-4 16:01:33
     * 
     */
    public function getOrderGoodsInfo() {
        $map['order_id'] = $this->orderId;
        $res = $this->where($map)->select();
        return $res;
    }
    /*
        获取订单列表中的商品信息
     * 1.添加_goods字段
     * 2.取订单商品表(订单ID与customerid)
     * 3.将取出的信息进行拼接
     * 4.返回拼接后的数组
     */
    public function getOrderGoods() {
        if($this->orderForm == null)
        {
            return false;
        }
        $orderForm = $this->orderForm;
        foreach($orderForm as $key => $value)
        {
            $map['order_id'] = $value[id];
            $orderForm[$key]['_goods'] = $this->where($map)->select();
        }     
        return $orderForm;
    }
    //获取商品总数ao
    public function getGoodsQuantity(){
        $res = $this->field('quantity')->where('order_id='.$this->orderId)->select();
        $count;
        foreach ($res as $key => $value) {
            $count = $count + $value['quantity'];
        }
        return $count;
    }
    /*
     * 添加订单信息
     * @input
     * orderId 订单号
     * goodsArr 商品
     */
    public function addOrderGoods($orderId,$goods)
    {
        $data = array();
        $data['order_id'] = $orderId;
        foreach($goods as $key => $value)
        {           
            $data['goods_id'] = $value['id'];
            $data['quantity'] = $value['product_quantity'];
            $data['price'] = $value['purchasing_price'];
            $data['ds_coefficient'] = $value['direct_selling_commission'];
            $data['ls_coefficient']= $value['line_selling_commission'];
            $data['focus_pictures'] = $value['focus_pictures']['id'];
            $data['goods_name'] = $value['name'];
            $this->data($data)->add();
        }
        return true;
    }
}