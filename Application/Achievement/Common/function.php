<?php
/*
 * 通过关健字，获取结算信息，将填充到关键字所在字段
 * 传入
 * array(array(issue=>'3)
 * 传出
 * array(array(issue=>array(id=3,decs=..
 * $key关键字标志
 */
function get_issueinfo_by_id($dataArr,$key='issue')
{
    $achIssue = M('AchievementIssue');
    $map = array();
    foreach($dataArr as $k => $v)
    {
        $map['id'] = $dataArr[$k][$key];
        $dataArr[$k][$key] = $achIssue->where($map)->find();
    }
    return $dataArr;
}

