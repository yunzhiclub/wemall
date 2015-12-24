<?php
/*
 * 取应用配置信息
 */
namespace Config\Model;
use Think\Model;
class ConfigModel extends Model
{
    private $id = null;
    private $vName = null;
    function setId($id) {
        $this->id = $id;
    }
    function setName($name) {
        $this->vName = $name;
    }

        /*
     * 通过ID获取信息
     */
    
    public function getInfoById()
    {
        if($this->id == null)
        {
            return false;
        }
        $map['id'] = $this->id;
        $res = $this->where($map)->find();
        return $res;
    }
    
    /*
     * 通过name获取信息
     */
    
    public function getInfoByName()
    {
        if($this->vName == null)
        {
            return false;
        }
        $map['name'] = $this->vName;
        $res = $this->where($map)->find();
        return $res;
    }
    
    /*
     * 获取上一周期的开始,结束时间
     */
    public function getBeginTimeAndEndTime()
    {
        $this->setName('SYSTEM_BEGIN_DAY');
        $beginTime = $this->getInfoByName();
        $beginTime = $beginTime['value'];
        $this->setName('SYSTEM_END_DAY');
        $endTime = $this->getInfoByName();
        $endTime = $endTime['value'];
        return $this->_getTimes($beginTime, $endTime);
    }
    
    /*
     * 获取起止时间
     * $return array("begin"=>,"end"=>);
     * 1.获取数据库起始时间
     * 2.起始时间排序
     * 3.取出当前时间
     * 4.进行区间判断
     * 5.返回(上一计费周期的)起始结束时间
     */
    private function _getTimes($beginDate , $endDate)
    {
        if($beginDate > $endDate)
        {
            $tem = $beginDate;
            $beginDate = $endDate;
            $endDate = $tem;
        }
       $day = date("d",time());
       $year = date("Y",time());
       $month = date("m",time());
       $perMonth = $month - 1 ;
       if($perMonth == 0)
       {
            $perMonth = 12;
            $perYear = $year-1;
       }
       else
       {
           $perYear = $year;
       }
       //小于最小日期,结束时间起上月结结束时间
       //开始时间取上月开始时间
       if($day < $beginDate)
       {
            $endTime = mktime(0, 0, 0,$perMonth,$endDate,$perYear);
            $beginTime = mktime(0, 0, 0,$perMonth,$beginDate,$perYear);
       }
       /*
        * 当前日期处于两个日期之间
        * 开始时间取上月结束时间
        * 结束时间取本月开始时间
        */
       elseif($day < $endDate)
       {
            $beginTime = mktime(0, 0, 0,$perMonth,$endDate,$perYear); 
            $endTime = mktime(0, 0, 0,$month,$beginDate,$year);
       }
       /*
        * 当前日期大于结束日期
        * 开始时间取本月开始时间
        * 结束时间取本月结束时间
        */
       else
       {
            $beginTime = mktime(0, 0, 0,$month,$beginDate,$year); 
            $endTime = mktime(0, 0, 0,$month,$endDate,$year); 
       }
       return array("beginTime"=>$beginTime , "endTime"=>$endTime);
    }
    
}
