<?php
/*
 * 结算周期表
 * 每个开始时间、结束时间代表一个结算周期
 */
namespace Achievement\Model;
use Think\Model;
class AchievementIssueModel extends Model{
    private $issueId;
    private $beginTime = null; //结算期间开始时间
    private $endTime = null;   //结算期间结束时间
    private $map = null;
    private $beginDate = null;
    private $endDate = null;
    
    
    function setBeginTime($beginTime) {
        $this->beginTime = $beginTime;
        $this->map['begin_time'] = $beginTime;
    }

    function setEndTime($endTime) {
        $this->endTime = $endTime;
        $this->map['end_time'] = $endTime;
    }

        
    public function getDescribe(){
        $res = $this->field('describe')->where('id='.$this->issueId)->find();
        return $res;
    }
    public function setIssueId($id) {
        $this->issueId = $id;
    }
    
    
    /*
     * 获取当前结算周期的详情
     */
    public function getInfo()
    {
        if($this->beginTime == null || $this->endTime == null)
        {
            return;
        }
        $map['begin_time'] = $this->beginTime;
        $map['end_time'] = $this->endTime;
        $res = $this->where($map)->find();
        return $res;
    }    
    /*
     * 创建一个结算周期
     * 返回刚刚创建的信息
     */
    public function creatIssue()
    {
        if($this->beginTime == null || $this->endTime == null)
        {
            return false;
        }
        $res = $this->getInfo();
        if($res != false)
        {
            return $res;
        }
        else
        {
            $des = date('y年m月d日',$this->beginTime) . '-' . date('y年m月d日',$this->endTime);
            $this->map['describe'] = $des;
            $this->data($this->map)->add();
            return $this->find();
        }
    }
    /*
     * 获取历史信息
     * 
     */
    public function getHistoryList($currentPage , $pageSize)
    {
        return $this->page($currentPage . ',' . $pageSize)->select();
    }
    /*
     * 获取历史纪录总数
     */
    public function getCounts($map = array())
    {
        return $this->where($map)->count();
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
    
    private function _getTimes()
    {
        //获取开始结束日期
        $config = new ConfigModel();
        $name = 'SYSTEM_BEGIN_DAY';
        $configInfo = $config->getInfoByName();
        $beginDate = $configInfo['value'];  
        $name = 'SYSTEM_END_DAY';
        $configInfo = $config->getInfoByName();
        $endDate = $configInfo['value'];
        
        if($beginDate > $endDate)
        {
            $tem = $beginDate;
            $beginDate = $endDate;
            $endDate = $tem;
        }
       $day = date("d",time());
       $year = date("y",time());
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
       $this->beginTime = $beginTime;
       $this->endTime = $endTime;
    }
}
