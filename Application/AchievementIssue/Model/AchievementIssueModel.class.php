<?php
/*往期业绩信息
主要记录生成的业绩期数
 * 包括本期的开始时间
 * 结束时间
 * 叫什么名字等
 * 只要用于查看住期业绩信息
 *  */
namespace AchievementIssue\Model;
use Think\Model;
class AchievementIssueModel extends Model
{
    /*
     * 获取最近count期的历史业绩列表
     */
    public function getIssuesByCount($count)
    {
        return $this->limit(0,$count)->select();
    }
}