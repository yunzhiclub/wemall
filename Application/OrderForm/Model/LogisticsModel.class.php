<?php
/*
 * 物流方式
 */
namespace OrderForm\Model;
use Think\Model;
class LogisticsModel extends Model
{
    public function getInfoByMode($id)
    {
        $map['mode'] = $id;
        return $this->where($map)->find();
    }
}

