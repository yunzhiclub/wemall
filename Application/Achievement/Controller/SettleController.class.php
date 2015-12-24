<?php
/*
 * 后台用户结算管理
 * indexAction,显示当期(未结算),正在生成的信息
 */
namespace Achievement\Controller;
use Admin\Controller\AdminController;
use OrderRelation\Model\OrderRelationModel;
use Rebate\Model\RebateModel;
class SettleController extends AdminController
{
    private $beginDate = null; //配置信息,开始时间点
    private $endDate = null;    //结束时间点
    private $rebate = null;    //提成系数
    private $dateArr = null;    //记录结束开始结束时间
    //取配置信息
     /*
     * 1.当日日期小于最小日期
     * 2.当前区间:上月"最大日期"至本月最小日期
     * 3.当日日期大于最小日期,小于最大日期
      * 当前区间:本月最小日期至本月最大日期
      * 4.当日日期大于最大日期
      * 当前区间:本月最大日期至下月最小日期
     * 
     */
    public function __construct() {
        parent::__construct();
        $config = D("Config");
        $configArr = $config->fetConfig();
        $this->beginDate = $configArr[0]['value'];
        $this->endDate = $configArr[1]['value'];
        $this->dateArr = $this->_getTimes();
    }
    /*
     * 跳转至当期业绩
     */
    public function indexAction()
    {
        $url = U('current');
        redirect_url($url);
    }
    /*
     * 当期业绩
     * 指正在发生的，还不能结算的部分
     */
    public function currentAction()
    {      
        //取出起始时间
        $beginTime = $this->dateArr['endTime'];
        $endTime = time();
        
        //取出当前时间段的数据
        $orderRelation = new OrderRelationModel();
        $orderRelation->setBeginTime($beginTime);
        $orderRelation->setEndTime($endTime);    
        $currentSettles = $orderRelation->getPayedOrder();
        
        //添加用户信息
        $key = '_customer_id';
        $currentSettles = get_customer_info_by_id($currentSettles, $key);
        
        //添加系数信息
        $rebate = new RebateModel();
        $key = 'totalGoodsFee';
        $keyRes = '_totalGoodsFee_rebate';
        $currentSettles = $rebate->getRatioByMoney($currentSettles, $key , $keyRes );
        $key = 'lineTotalGoodsFee';
        $keyRes = '_lineTotalGoodsFee_rebate';
        $currentSettles = $rebate->getRatioByMoney($currentSettles, $key , $keyRes );
        
        //计算佣金
        foreach($currentSettles as $key => $value)
        {
            $currentSettles[$key]['commission'] = 
                    floor($value['totalDirectFee']*$value['_totalGoodsFee_rebate']['direct_ratio']
                            +
                    $value['lineTotalLineFee']*$value['_lineTotalGoodsFee_rebate']['line_ratio']);
        }
        
        //数据分页
        $currentPage = I('get.p',1);
        $this->page->setCounts(count($currentSettles));
        $this->page->setCurrentpage($currentPage);
        $this->page->setPageStyle(2);
        $pageSize = $this->page->getPageSize();
        
         //截取本页内容
        $currentSettles = array_slice($currentSettles , ($currentPage-1)*$pageSize , $pageSize);
        $this->assign('beginTime',$beginTime);
        $this->assign('endTime',$endTime);
        $pageStr = $this->page->fetch();
        $this->assign('page',$pageStr);
        $this->assign('data',$currentSettles);
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    /*
     * 本期业绩（指刚刚发生过，需要在当期进行结算的）
     * *生成\重新生成业绩
         * 1.取出本期开始与结束区间内所有数据
         * 2.查找issue表，看是否存在相同开始时间
         * 2.1存在，取出ID
         * 2.2不存在，插入数据，并返回ID
         * 3.删除所有当期信息中“未发放”的记录
         * 4.更新当期信息
         * 4.1以opnenid和期id查找是否存在记录
         * 4.1.1存在，跳过
         * 4.1.2不存在，增加
         *
     **查看当期业绩
     * 1.取出当前beginTime对应的期限id
     * 2.取出业绩表中当前期限的所有记录
     * 3.展示记录
     */
    public function justAction()
    {
        $flag = I('get.flag','');
        $p = I('get.p',1);
        $beginTime = $this->dateArr['beginTime'];
        $endTime = $this->dateArr['endTime'];
        $achModel = new \Achievement\Model\AchievementModel;
        
        //取出当前结算周期的信息,没有，则说明未生成数据
        $asModel = new \Achievement\Model\AchievementIssueModel();
        $asModel->setBeginTime($beginTime);
        $asModel->setEndTime($endTime);
        $asInfo = $asModel->getInfo();
        
        //如果未生成,或是用户手动生成,则执行
        if($flag == 'createSettle' || $asInfo == false)
        {
            //未生成结算周期则先生成
            if($asInfo == false)
            {
                $asInfo = $asModel->creatIssue();
            }
            $issue = $asInfo['id'];
            
            //取结算信息
            $settles = $achModel->getJustInfo();
            //进行信息的更新
            $achModel->updateInfo($issue,$settles);
        } 
        $issue = $asInfo['id'];
        //取出信息并展示
        $achModel->setIssue($issue);
        
        //取总条数
        $counts = $achModel->getIssueCounts();
        $this->page->setCurrentPage($p);
        $this->page->setPageStyle(2);
        $this->page->setCounts($counts);
        $pageStr = $this->page->fetch();
        $currentPage = $this->page->getCurrentPage();
        $pageSize = $this->page->getPageSize();
        
        $achModel->setCurrentPage = $currentPage - 1 ;
        $achModel->setPageSize = $pageSize;
        
        $data = $achModel->getIssueData();  
        foreach($data as $key => $value)
        {
            $data[$key]['actionUrl']['payUrl'] = U('pay?id=' . $value['id']);
        }
        $data = get_customer_info_by_openid($data);
        $this->assign('beginTime',$beginTime);
        $this->assign('endTime',$endTime);
        $createUrl = U('just?flag=createSettle&p='.$p);//重新生成业绩参数

        $this->assign('createUrl',$createUrl);
        $this->assign('page',$pageStr);
        $this->assign('data',$data);
        $this->assign('YZRight',$this->fetch('just'));
        $this->display(YZ_TEMPLATE);
    }
    /*
     * 历史业绩
     * 1. 查出所以历史结算周期并列表
     * 2. 给出url点击后，出现当前周期详情
     */
    
    public function historyAction()
    {
        $asModel = new \Achievement\Model\AchievementIssueModel();
        $currentPage = I('get.p',1);
        $count = $asModel->getCounts();
        $this->page->setCurrentPage($currentPage);
        $this->page->setCounts($count);
        $pageSize = $this->page->getPageSize();
        $currentPage = $this->page->getCurrentPage();
        $pageStr = $this->page->fetch();
        $data = $asModel->getHistoryList($currentPage,$pageSize);
        foreach($data as $key => $value)
        {
            $actionUrl['viewUrl'] = U('showHistoryDetail?issueId=' . $value['id']);
            $data[$key]['actionUrl'] = $actionUrl;
        }
        
        $this->assign("page",$pageStr);
        $this->assign('data',$data);
        $this->assign("YZRight",$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    /*
     * 业绩发放
     * 1.获取id
     * 2.判断状态
     * 2.1 状态为已发送，则直接提示已发放
     * 3.输出其它信息
     * 4.给出update
     */
    public function payAction()
    {
        $p = I('get.p',1);
        $id = I('get.id',0);
        if($id == 0)
        {
            $html = '输入参数有误';
        }
        else
        {
            $achModel = new \Achievement\Model\AchievementModel();
            $data = $achModel->getInfo($id);
            if($data == false)
            {
                $html = '输入参数有误';
            }
            else
            {
                $data['openid'] = get_customer_info($data['openid']);
            }
        }
        $actionUrl['postUrl'] = U('save?p='.$p);
        $this->assign('actionUrl',$actionUrl);
        $this->assign('js',$this->fetch('payJs'));
        $this->assign('data',$data);
        $html = $this->fetch();
        $this->assign("YZRight",$html);
        $this->display(YZ_TEMPLATE);
    }
    /*
     * 存结算信息
     * 1.查看当前订单是否已结算
     * 1.1已结算，直接return
     * 2.对传入信息进行校验
     * 3.存入订单结算信息，更改业绩状态为已结算。
     */
    public function saveAction()
    {
        $p = I('get.p','');
        $id = I('post.id','');
        $initUrl = U('just?p=' . $p);
        if($id == '')
        {
            $this->error('参数错误',$initUrl);
            return;
        }
        $ach = new \Achievement\Model\AchievementModel();
        $res = $ach->savePayedInfo(I('post.'));
        if($res === true)
        {
            $this->success('操作成功', $initUrl);
        }
        else
        {
            $this->error('操作失败,原因:'.$res, $initUrl);
        }
        return;
    }
    /*
     * 显示历史业绩记录详情
     * 1.查出当期结算周期的标题
     * 2.在结算表中，查出当前周期下的所以记录
     * 3.分页显示记录
     * 注意：此时分页信息中，需要传入issueId,生成的分页，则需要带着此参数
     */
    public function showHistoryDetailAction()
    {
        $issueId = I('get.issueId',0);
        if($issueId == 0)
        {
            $html = '输入信息有误或您无权查看';
        }
        $currentPage = I('get.page',1);
        $achModel = new \Achievement\Model\AchievementModel();
        $map['issue'] = $issueId;
        $counts = $achModel->getCounts($map);
        $this->page->setCurrentPage($currentPage);
        $this->page->setCounts($counts);
        $this->page->setMap($map);
        $pageStr = $this->page->fetch();
        $currentPage = $this->page->getCurrentPage();
        $pageSize = $this->page->getpageSize();
        $data = $achModel->getData($currentPage,$pageSize,$map);
        $this->assign('data',$data);
        $this->assign('page',$pageStr);
        $html = $this->fetch();
        $this->assign('YZRight',$html);
        $this->display(YZ_TEMPLATE);
    }
    /*
     * 动态取出所有用户在当前区间内的信息
     * 
     */
    private function _getSettles($beginTime,$endTime)
    {
       $orderForm = D('OrderForm');
       $orderForm->setBeginTime($beginTime);
       $orderForm->setEndTime($endTime);
       $settles = $orderForm -> getSettle();
       /*
        * 数组最后添加popenid
        * 上级的OPENID信息
        */
       foreach($settles as $key => $value)
       {
           $customer = get_customer_info($value['openid']);
           $pid = $customer['parentid'];
           $pcustomer = get_customer_info($pid);
           $settles[$key]['pOpenid'] = $pcustomer['openid'];
       }
       /*
        * 按不同的openid进行分组
        * 将子元素求和
        */
       $key = 'openid';
       $currentSettles = group_by_key($settles, $key);
       $currentSettles = $this->_getTotal($currentSettles);
        /*
        * 按不同的父级openid进行分组
        */
       $key = 'pOpenid';
       $childSettles = group_by_key($settles, $key);
       $childSettles = $this->_getTotal($childSettles);
       foreach($currentSettles as $key => $value)
       {
           $currentSettles[$key]['_child'] = $childSettles[$key];
       }
       return $currentSettles;
    }
    /*
     * 计算每位用户的销售总和，放到新的数组中
     * @key 用户openid
     */
    private function _getTotal($settles)
    {
        $customer = array();
        foreach ($settles as $key => $value)
        {
            $payableTotal = 0;
            $directFeeTotal = 0;
            $customer[$key]['openid'] = get_customer_info($key);
            //汇总业绩
            foreach($value as $k => $v)
            {
                $payableTotal += $v['payable'];
                $directFeeTotal += $v['direct_fee'];
            }
            $customer[$key]['payable'] = $payableTotal;
            $customer[$key]['directFee'] = $directFeeTotal;
            $customer[$key]['detail'] = $value;
            $this->rebate->setMoney($payableTotal);
            $customer[$key]['ratio'] = $this->rebate->getRadio();
        }
        return $customer;        
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
    private function _getTimes($vol = 'current')
    {
        if($vol != 'current' && $vol !='past')
        {
            return false;
        }
        $beginDate = $this->beginDate;
        $endDate = $this->endDate;
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
       return array("beginTime"=>$beginTime , "endTime"=>$endTime);
    }
    /*
     * 
     */
    public function testAction()
    {
        
    }
}

