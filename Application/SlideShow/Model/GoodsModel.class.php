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

namespace SlideShow\Model;

use Think\Model;

class GoodsModel extends Model {

    public function getName($SlideM) {
        foreach ($SlideM as $key => $value) {
            $id['id'] = $value['product_id'];
            $res[] = $this->where($id)->field('name')->find();
        }
        return $res;
    }

    public function getAName($a) {

        $id['id'] = $a['product_id'];
        $res = $this->where($id)->field('name')->find();
        return $res;
    }

    public function getTOP(){
        $map[state] = 1;
        $res = $this->where($map)->order('id')->limit(10)->select();
        return $res;
    }
}
