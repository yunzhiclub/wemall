<?php

/* 
 * 梦云智工作室
 * 业绩发放日期设置  * 
 */
namespace System\Controller;
use Admin\Controller\AdminController;
class AchievementDateController extends AdminController {
    private $achDateInf;
    public function __construct() {
        $this->achDateInf = D('config');
        parent::__construct();
    }

    public function indexAction(){
        $data = array();
        $data[] = $this->achDateInf->field('value')->where("name='SYSTEM_BEGIN_DAY'")->select();
        $data[] = $this->achDateInf->field('value')->where("name='SYSTEM_END_DAY'")->select();
        $this->assign('data',$data);
        $this->assign('actionUrl',U('save'));
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    public function saveAction(){
        $data = $_POST;
        $url = U('index');
        $this->achDateInf->where("name='SYSTEM_BEGIN_DAY'")->setField('value',$data['SYSTEM_BEGIN_DAY']);
        $this->achDateInf->where("name='SYSTEM_END_DAY'")->setField('value',$data['SYSTEM_END_DAY']);
        $this->success('数据保存成功',$url,2);
    }
}
