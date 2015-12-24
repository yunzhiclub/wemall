<?php
namespace Active\Controller;
use Admin\Controller\AdminController;
use Customer\Model\CustomerModel;
use Coupon\Model\CouponModel;
class IndexController extends AdminController  {
    private $currentPage = null;
    private $pageSize = null;
    //用于用户注册时为注册者与被注册者发放优惠券        
    public function IndexAction(){
        $url = U('activeManage');
        redirect_url($url);
    }
    public function activeManageAction(){
        $url = U('Edit');
        redirect_url($url);
    }
    public function couponManageAction() {
        $url = U('usedcoupon');
        redirect_url($url);
    }
    //注册时应用的为邀请者与被邀请者发送优惠券
    public function EditAction() {
        $active = M('active'); 
        $map0 = array();
        $map0[object] = 0;
        $map1 = array();
        $map1[object] = 1;
        $res0 = $active->where($map0)->find();
        $res0[cover] = $res0[cover]/100;
        $this->assign('oldList',$res0);
        $res1 = $active->where($map1)->find();
        $res1[cover] = $res1[cover]/100;
        $this->assign('newList', $res1);
        $this->assign('update', U('Update'));
        $TPL =T('Active@Edit/index');
        $this->assign('YZRight',$this->fetch($TPl));    
        $this->display(YZ_TEMPLATE);
    }
    //更新操作
      public function UpdateAction(){
                $active = M('active');
                $data0 = array();
                $data0[id] = I('post.newid');
                $data0[cover] = ceil(I('post.newcover')*100);
                $data0[count] = I('post.newcount');
                $data0[duration] = I('post.newduration');
                $data0[object] = I('post.newobject');
                $data0[state] = I('post.newstate',0);
                $data1 = array();
                $data1[id] = I('post.oldid');
                $data1[cover] = ceil(I('post.oldcover')*100);
                $data1[count] = I('post.oldcount');
                $data1[duration] = I('post.oldduration');
                $data1[object] = I('post.oldobject');
                $data1[state] = I('post.oldstate',0);
                $active->save($data0);
                $active->save($data1);
                $urlIndex = U('Active/Index/Edit');
                redirect_url($urlIndex);
                
      }
    public function freezeCouponAction()
    {
        $p = I('get.p',1);
        $id = I('get.id','');
        $url = U("sentCoupon?p=$p");
        if($id == '')
        {
            redirect($url);
            return;
        }
        else
        {
            $coupon = new CouponModel();
            $coupon->freezeCouponById($id);
        }
        redirect($url);   
    }
      
      
    //用户查看自己的优惠券
    public function myCouponAction(){
        $coupon = M('coupon');
        $time = time();
        $map = array();
        $map['customerid'] = array('eq',$this->CustomerId);
        $map['end_time'] = array('lt',$time);
        $map['state'] = 1;
        $res = $coupon->order("end_time desc")->where($map)->select();
    }
    
    //对上一结束周期的用户按销售业绩排序
    public function sortAction(){
        
        $currentPage = I('get.p',1);    
        $orderby = I('order','');
        $customerArr = $this->_getCustomerInfoByMap($map);
        
        $issue = M('achievement_issue');
        $achievement = M('achievement');
        
        
        //取上一往期时间
        $res = $issue->order('id desc')->find();
        $map = array();
        $map[issue] = $res[id];
        
        //取这上一往期时间的所有人业绩
        $key = I('get.key','');
        if($key == 'direct')
        {
            $result = $achievement->where($map)->order('direct_total_fee desc')->select();
        }
        else
        {
            $result = $achievement->where($map)->order('line_total_fee desc')->select();
        }
        $customer = new CustomerModel();    
        $key = 'openid';
        $resKey = '_customer';
        $result = $customer->getCustomerByOpenid($result, $key, $resKey);
        $key = 'openid';
        $result = change_key($result, $key);
        
        //按页截取
        $orderby = I('get.orderby','');
        //按业绩由大到小拼接
        if($orderby == 'sore')
        {
            foreach($result as $key => $value)
            {          
                $resData[$key]['_sore'] = $value;
                $resData[$key]['_customer'] = $customerArr[$key];
                $resData[$key]['sendCouponUrl'] = U("sendCoupon?p=$p&openid=$key");
                unset($customerArr[$key]);
            }
            foreach($customerArr as $key => $value)
            {
                $resData[$key]['_customer'] = $customerArr[$key];
                $resData[$key]['sendCouponUrl'] = U("sendCoupon?p=$p&openid=$key");
            }
        }
        //按关注时间拼接
        else
        {
            foreach($customerArr as $key => $value)
            {
                $resData[$key]['_sore'] = $result[$key];
                $resData[$key]['_customer'] = $value;
                $resData[$key]['sendCouponUrl'] = U("sendCoupon?p=$p&openid=$key");
            }
        }
        
        //开始取分页数据
        $page = $this->page;
        $page->setCurrentPage($currentPage);
        $page->setCounts(count($resData));
        $pageStr = $page->fetch();
        $currentPage = $page->getCurrentPage();
        $pageSize = $page->getPageSize();
        
        $offset = ($currentPage-1)*$pageSize;
        $resData = array_slice($resData,$offset,$pageSize);

        $this->assign('page',$pageStr);
        $this->assign('customerList', $resData);

        $this->assign('YZRight',$this->fetch());    
        $this->display(YZ_TEMPLATE);
     }
    //添加发放优惠券信息
    public function sendCouponAction(){
        $p = I('get.p',1);
        $this->assign('actionadd',U("addCoupon?p=$p"));
        $this->assign('openid',I('get.openid'));

        $this->assign('YZRight',$this->fetch());    
        $this->display(YZ_TEMPLATE);
    }
    
    public function addCouponAction(){
        $count = I('post.count');
        $data['cover'] = huansuan(I('post.cover'));
        $data['duration'] = I('post.duration');
        $data['start_time'] = time();
        $data['end_time'] = time()+I('post.duration')*24*60*60;
        $data['openid'] = I('post.openid');
        $active = M("coupon");
        for($i=0;$i < $count ;$i++)
        {
            $active->add($data);
        }
        $urlIndex = U('Active/Index/sort');
        redirect_url($urlIndex);
    }
    //查看已发放的优惠券
    public function sentCouponAction(){
        $map = array();
        $couponData = $this->_getCouponInfoByMap($map);
        $this->assign('sent',$couponData);

        $this->assign('YZRight',$this->fetch());    
        $this->display(YZ_TEMPLATE);
    }
    //查看已使用的优惠券
    public function usedCouponAction(){
        $map[state] = 0;
        $couponData = $this->_getCouponInfoByMap($map);
        
        $this->assign('used',$couponData);
        $this->assign('YZRight',$this->fetch());    
        $this->display(YZ_TEMPLATE);
    } 
    private function _treeToList($tree,$i = 0){
        $list = array();
        foreach($tree as $key => $value)
        {
            $value['level'] = $i;
            $list[] = $value;
            if(is_array($value['_child']))
            {
                $i++;
                $list = array_merge($list,$this->_treeToList($value['_child'],$i));
                $i--;
            }
        }
        return $list;
    }
    
    public function _addFreezeUrl($couponData,$currentPage)
    {
        foreach($couponData as $key => $value)
        {
            $couponData[$key]['url']['freezeUrl'] = U("freezeCoupon?p=$currentPage&id=$value[id]");
        }
        return $couponData;
    }
    
    private function _getCouponInfoByMap($map)
    {
        $p = I('get.p',1);
        $coupon = new CouponModel();
        $coupon->setMap($map);
        $count = $coupon->getCounts();
        
        $page = $this->page;
        $page->setCounts($count);
        $page->setCurrentPage($currentPage);
        $page->setPageStyle(2);
        $pageStr = $page->fetch();
        
        $currentPage = $page->getCurrentPage();
        $pageSize = $page->getPageSize();
        
        $coupon->setOrderBy('start_time desc');
        $couponData = $coupon->getCouponArrByMap($currentPage,$pageSize);
        
       
        $customer = new CustomerModel();
        $key = 'openid';
        $resKey = '_openid';
        $couponData = $customer->getCustomerByOpenid($couponData, $key, $resKey);
        
        $couponData = $this->_addFreezeUrl($couponData,$currentPage);
        $this->assign('page',$pageStr);
        
        return $couponData;
    }
    
    
    private function _getCustomerInfoByMap($map)
    {
        
        $customer = new CustomerModel();
        $customer->setMap($map);       
        $customerArr = $customer->getAllDataArrByMap();
 
        $key='openid';
        $customerArr = change_key($customerArr, $key);
        
        return $customerArr;
    }
    
    
}