<?php

/*
 * 梦云智工作室
 *   * 
 */

/**
 * Description of ConfigModel
 *
 * @author xlj
 */
namespace Orderform\Model;
use Think\Model;
class ConfigModel extends Model{
    //计算运费详情
    
    public function freightCount() {
        $freightM = $this->where('type=5')->field('name,value')->select();
        foreach ($freightM as $key => $value) {
          $freight[] = array($freightM[$key]['name']=>$freightM[$key]['value']);  
        }
        return $freight;
    }
    public function getFreight() {
        
        
    }
}