<?php

/*
 * 梦云智工作室
 *   * 
 */

/**
 * Description of FreightModel
 *
 * @author xlj
 */
namespace System\Model;
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
    /*
     * 获取运费
     * @input $wight重量
     * @output 总运费
     * 1.抓五个字段
     * 2.加包重
     * 3.判断是否大于着重
     * 3.1 大于，减首重/续重，上取整
     * 4.续重整数*续重钱数
     */
    public function getFee($weight)
    {
        $freightArr = $this->freightCount();
        foreach($freightArr as $key=>$value)
        {
            foreach($value as $k => $v)
            {
                $res[$k] = $v;
            }               
        }
        foreach($res as $key => $value)
        {
            if($key == 'SYSTEM_FIRST_HEAVY')
            {
                $firstWeight = $value;
                continue;
            }
            if($key == 'SYSTEM_FIRST_COST')
            {
                $firstPrice = $value;
                continue;
            }
            if($key == 'SYSTEM_CONTINUE_HEAVY')
            {
                $continueWeight = $value;
                continue;
            }
            if($key == 'SYSYEM_CONTINUE_COST')
            {
                $continuePrice = $value;
                continue;
            }
             if($key == 'SYSTEM_PACKAGE_HEAVY')
            {
               $pickWight = $value;                
            }
                        
        }
        $price = 0;
        $weight += $pickWight;
        if($weight > $firstWeight)
        {
            $counts = ceil(($weight - $firstWeight)/$continueWeight);
            $price = $counts*$continuePrice;
        }
        $totalFee = $firstPrice + $price;
        $totalFee = $totalFee*100;
        return $totalFee;
    }
}
