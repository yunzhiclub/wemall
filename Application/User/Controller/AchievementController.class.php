<?php
/*
 * 业绩管理
 */
namespace User\Controller;
use User\Controller\UserController;
use Achievement\Model\AchievementModel;
use AchievementIssue\Model\AchievementIssueModel;
class AchievementController extends UserController
{
    public function _initialize() {
        $this->assign('title','我的往期业绩');
        parent::_initialize();
    }
    public function pastDataAction()
    {
        $openid = get_openid();
        //获取近6期的业绩列表
        $AchievementIssueM = new AchievementIssueModel();
        $count = 6;
        $issueInfoArr = $AchievementIssueM->getIssuesByCount($count);
        
        //拼接当前用户近6期的业绩名细
        $Achievement = new AchievementModel();
        $Achievement->setOpenid($openid);
        $key = 'id';
        $resKey = '_detail';
        $pastAchievementArr = $Achievement->getInfoArrByIssueArr($issueInfoArr , $key , $resKey);
        
        $this->assign('data' , $pastAchievementArr);
        $this->assign("YZBody",$this->fetch('pastData'));
        $this->display(YZ_TEMPLATE);
    }
}


