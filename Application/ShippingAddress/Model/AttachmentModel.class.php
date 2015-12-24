<?php

/*
 * 梦云智工作室
 *   * 
 */

/**
 * Description of AttachmentModel
 *
 * @author xlj
 */
namespace ShippingAddress\Model;
use Think\Model;
class AttachmentModel extends Model {
     public function getName($shipping) {
        foreach ($shipping as $key => $value) {
            $res[] = $this->where(array('id'=>$value['front_id']))->field('name')->find();
            $ras[] = $this->where(array('id'=>$value['reverse_id']))->field('name')->find();
            $shippinglenght = count($shipping);
            for($i=0;$i<$shippinglenght;$i++){
                $frontname = $res[$i]['name'];
                $reversename = $ras[$i]['name'];
                $way1[$i] = __ROOT__ ."/Uploads/Picture/Slide/"."$frontname".".jpg";//图片地址
                $way2[$i] = __ROOT__ ."/Uploads/Picture/Slide/"."$reversename".".jpg"; 
                $shipping[$i]['pic_frontname'] = $way1[$i];
                $shipping[$i]['pic_reversename'] = $way2[$i];
            }
        }
        return $shipping;
    }
}