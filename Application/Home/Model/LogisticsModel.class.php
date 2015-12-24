<?php

/* 
 * 梦云智工作室
 *   * 
 */
/**
 * Description of SourceModel
 *
 * @author J
 */
namespace Home\Model;
use Think\Model;

class LogisticsModel extends Model {
    //put your code here
    public function getLogistics($map){
        $res = $this ->where($map) ->find();
        return $res;
    }
}

