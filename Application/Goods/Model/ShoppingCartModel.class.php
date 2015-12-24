<?php
namespace Goods\Model;
use Think\Model;
/*
 * 梦云智工作室
 *   * 
 */

/**
 * Description of ShoopingCartModel
 *
 * @author XINGYANNIAN
 */
class ShoppingCartModel extends Model {
    private $openid; //客户openid
    private $goodsId;//商品编号
    private $goodsNum;//商品数量
    public function setOpenid($openid) {
        $this->openid = $openid;
    }

    public function setGoodsId($goodsId) {
        $this->goodsId = $goodsId;
    }

    public function setGoodsNum($goodsNum) {
        $this->goodsNum = $goodsNum;
    }
    /*
     * 添加商品至购物车
     * 没有则添加
     * 有则更新
     */
    public function addGoodsToCart() {
        $map['openid'] = $this->openid;
        $map['product_number'] = $this->goodsId;
        $res = $this->where($map)->find();         
        if($res == false)
        {
            $map['product_quantity'] = $this->goodsNum;
            $this->data($map)->add();
        }
        else
        {
            $data['product_quantity']= $this->goodsNum;
            $this->where($map)->data($data)->save();
        }
        return;
    }

}
