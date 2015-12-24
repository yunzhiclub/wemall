<?php
/*
 * 订单对应的商品表
 */
namespace OrderGoods\Model;
use Think\Model;
use Attachment\Model\AttachmentModel;
class OrderGoodsModel extends Model
{
    //通过orderid获取对应的商品信息
    public function getGoodsArrByOrderIdArr($orderArr , $key ,$resKey)
    {
        $attachment = new AttachmentModel();
        foreach($orderArr as $k => $v)
        {
            $map['order_id'] = $v[$key];
            $res = $this->where($map)->select();
            
            //拼接图片链接信息
            foreach($res as $ke => $va)
            {
                $pics = $va['focus_pictures'];
                $foucsPicIds = explode(',',$pics);
                $foucsPicId = $foucsPicIds[0];
                $res[$ke]['_pic'] = $attachment->getInfoById($foucsPicId);
            }
            $orderArr[$k][$resKey] = $res;
        }
        return $orderArr;
    }
}

