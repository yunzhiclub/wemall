<?php
/*
 * 购物车表
 */
namespace OrderForm\Model;
use Think\Model;
class ShoppingCartModel extends Model
{
    /*
     * 删除购物车信息
     * @input 
     * openid 用户openid
     * goodsArr 商品的数组
     */
    public function deleteGoods($openid,$goodsArr)
    {
        $map = array();
        $map['openid'] = $openid;
        foreach($goodsArr as $key => $value)
        {
            foreach($value['goods'] as $k => $v)
            {
                $map['product_number'] = $v['id'];
                $this->where($map)->delete();
            }
            
        }
        return true;
    }
    
    public function getCounts($ids,$openid)
    {
        $map['openid'] = $openid;
        foreach($ids as $key => $value)
        {
            $map['product_number'] = $value;
            $res = $this->where($map)->find();
            if($res)
            {
                if($res['product_quantity'] > 0)
                {
                    $counts[] = $res['product_quantity'];  
                }
                    
                else {
                    $counts[] = 1;
                }
            }
            else
            {
                $counts[] = 1;
            }
        }
        return $counts;
    }
}
