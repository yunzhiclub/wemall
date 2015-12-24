<?php

/*
 * 梦云智工作室
 *   * 
 */

/**
 * Description of GoodsModel
 *
 * @author xlj
 */
namespace OrderForm\Model;
use Think\Model;
class GoodsModel extends Model{
/*
 * 取商品信息。
 * 去除下架商品
 */
    public function getProduct($ids,$counts) {
        foreach($ids as $value)
        {
            $map['id'] = $value;
            $res = $this->where($map)->find();
            if( (int)$res['state']&1 === 1)
            {
                $product[] = $res;
            }
        }
        return $product;
    }
    
    public function judge($ids) {
        
    
        foreach ($ids as $key => $value) {
            $id['id'] = $value;
            $source[] = $this->where($id)->field('source,logistics_mode')->select();
        }
        
        foreach ($source as $key => $value) {
            if($source[$key]==$source[$key+1]){
                return 1;
            }
            else{
                return 0;
            }
            
        }
        
        
    }
    
    /*
     * 更新商品的销量
     * input 商品数组
     */
    public function updateSaled($goodsArr)
    {
        foreach($goodsArr as $key => $value)
        {
            $map['id'] = $value['id'];
            $this->where($map)->setInc('sales_volume');
        }
        
    }
}
