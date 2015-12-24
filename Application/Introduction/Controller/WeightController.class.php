<?php
namespace Introduction\Controller;
use Admin\Controller\AdminController;
class WeightController extends AdminController {
    public function UpdateAction(){
        $intro = M('config');
        $map1[name] = "SYSTEM_FIRST_HEAVY";
        $map2[name] = "SYSTEM_FIRST_COST";
        $map3[name] = "SYSTEM_CONTINUE_HEAVY";
        $map4[name] = "SYSYEM_CONTINUE_COST";
        $map5[name] = "SYSTEM_PACKAGE_HEAVY";
        $map6[name] = "SYSTEM_FREIGHT_EXPLAIN";
        $data1[value] = I('post.firstWeight');
        $data2[value] = I('post.firstCost');
        $data3[value] = I('post.continueWeight');
        $data4[value] = I('post.continueCost');
        $data5[value] = I('post.packageHeavy');
        $data6[value] = I('post.freightExplain');
        $intro->where($map1)->save($data1);
        $intro->where($map2)->save($data2);
        $intro->where($map3)->save($data3);
        $intro->where($map4)->save($data4);
        $intro->where($map5)->save($data5);
        $intro->where($map6)->save($data6);
        $url = U('Introduction/Weight/index');
        redirect_url($url);
        
    }
    public function IndexAction(){
        $intro = M('config');
        $map1[name] = "SYSTEM_FIRST_HEAVY";
        $map2[name] = "SYSTEM_FIRST_COST";
        $map3[name] = "SYSTEM_CONTINUE_HEAVY";
        $map4[name] = "SYSYEM_CONTINUE_COST";
        $map5[name] = "SYSTEM_PACKAGE_HEAVY";
        $map6[name] = "SYSTEM_FREIGHT_EXPLAIN";
        $firstHeavy = $intro->where($map1)->find();
        $firstCost = $intro->where($map2)->find();
        $continueHeavy = $intro->where($map3)->find();
        $continueCost = $intro->where($map4)->find();
        $packageHeavy = $intro->where($map5)->find();
        $freightExplain = $intro->where($map6)->find();
        $this->assign('firstHeavy', $firstHeavy);
        $this->assign('firstCost', $firstCost);
        $this->assign('continueHeavy', $continueHeavy);
        $this->assign('continueCost', $continueCost);
        $this->assign('packageHeavy', $packageHeavy);
        $this->assign('freightExplain', $freightExplain);
        $this->assign('urlUpdate', U('Update'));
        $TPL =T('Introduction@Weight/index');
        $this->assign('YZRight',$this->fetch($TPl));    
        $this->display(YZ_TEMPLATE);     
    }
}