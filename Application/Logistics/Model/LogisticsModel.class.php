<?php

/* 
 * 梦云智工作室
 *   * 
 */
namespace Logistics\Model;
use Think\Model;
class LogisticsModel extends Model {
    public function init(){
        $res = $this->select();
        return $res;
        
    }
    public function getNum(){
        $res = $this->select();
        $count = count($res);
        return  $count;
    }
    
    /*
     * 将代表物流方式的关键字替换为数组
     */
    public function getLogisticInfo($arr , $key = 'logistics_mode')
    {
        foreach($arr as $k => $v)
        {
            $map['mode'] = $v[$key];
            $arr[$k][$key] = $this->where($map)->find();
        }
        return $arr;        
    }
}
