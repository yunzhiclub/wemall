<?php
//客户入口模块
//显示用户首页
namespace Home\Controller;
use User\Controller\UserController;
use Home\Model\GoodsModel;
use Home\Model\SlideShowModel;
class IndexController extends UserController {
    private $url;
    private $rootPath;
    private $pageSize;
    private $goodsDetailUrl;
    public function __construct() {
        parent::__construct();
        $this->url = U('load');//获取loadAction的路径，用于分页加载
        $this->rootPath = add_root_path('');
        $this->pageSize = 10;//单页记录数
        $this->goodsDetailUrl = U('Goods/GoodsDetail/index');
    }
    public function _initialize() {        
        $this->addCss('/theme/wemall/css/home.css');
        $this->addJs('/theme/wemall/js/TouchSlide.1.1.js');
        parent::_initialize();
    }
    public function _getOpenId($openid) {
        $idM = M('customer');
        $map['openid'] = $openid;
        $id = $idM->where($map)->field('id')->find();
        return $id;
    }

    public function indexAction(){
        $openid = get_openid();              //如果不是注册用户不显示直销、线销
        $customerInfo = get_customer_info($openid);
        $this->assign('customerType',$customerInfo['subscribe_state']);
        $searchUrl = U('Search/Index/index');
        $this->assign('searchUrl',$searchUrl);
        $loadUrl = U('Search/Index/load');
        $this->assign('loadUrl',$loadUrl);
         //以上定义搜索的路径
        
        $goodsList = new GoodsModel();
        $pageNum = 1;
        $goodsList->setPage($pageNum);
        $goodsList->setPageSize($this->pageSize);
        $listS = $goodsList->getDetailGoodsList();//获取商品列表
        
        $listL = $this ->_source($listS);
        //var_dump($list);
        $list = $this ->_logistics($listL);
        
        $this->assign('goodsDetailUrl',  $this->goodsDetailUrl);//商品详情的路径
        
        $this->assign('postUrl',  $this->url);
        $this->assign('nextPageNum',2);
        $tpl = T('Home@Index/page');
        $this->assign('page',$this->fetch($tpl));//获取分页
        $this->assign('list', $list);  
        $tpl = T('Home@Index/logo');
        $this->assign('logo',$this->fetch($tpl));//logo
        //以下是幻灯片部分
        $slideShowModel = new SlideShowModel();
        $slideInfo = $slideShowModel->getSlideDetail();
        $this->assign('slideInfo',$slideInfo);
        $this->assign('rootPath', $this->rootPath);
        //$urlBannerImage = add_root_path('/Uploads/Picture/Slider/images/');
        $this->assign('css',$this->fetch(T('Home@Xiaomi/indexCss')));
        $this->assign('js',$this->fetch(T('Home@Xiaomi/indexJs')));
        $this->assign('banner',$this->fetch(T('Home@Xiaomi/banner')));        
        $this->assign('search', $this->fetch(T('Home@Xiaomi/search')));
      
        $this->assign('list',$this->fetch(T('Home@Xiaomi/list')));
        //$this->assign('YZHead', $this->fetch(T('Home@Xiaomi/head')));
        $this->assign('YZBody',$this->fetch(T('Home@Xiaomi/index')));
        $this->display(YZ_TEMPLATE);
    }
    public function loadAction(){//分页加载方法
        $openid = get_openid();              //如果不是注册用户不显示直销、线销
        $customerInfo = get_customer_info($openid);
        $this->assign('customerType',$customerInfo['subscribe_state']);
        $this->assign('goodsDetailUrl',  $this->goodsDetailUrl);
        $this->assign('rootPath', $this->rootPath);
        $this->assign('postUrl',  $this->url);
        $goodsList = new GoodsModel();
        $pageNum = I('get.pageNum');
        $nextPage = $pageNum + 1;//下页页码
        $goodsList->setPage($pageNum);
        $goodsList->setPageSize($this->pageSize);
        $count = $goodsList->getCount();
        $listNum = $this->pageSize * $pageNum;

        $listS = $goodsList->getDetailGoodsList();     
        $listL = $this ->_source($listS);
        //var_dump($list);
        $list = $this ->_logistics($listL);    
        
        
        $this->assign('nextPageNum',$nextPage);
        $this->assign('page',$this->fetch('page'));
        $this->assign('list', $list);     
        $jsonData = array();
        if($listNum < $count){
            $jsonData['state'] = 1;//后面还有页
            $data_list = $this->fetch("Home@Xiaomi/list");
            $data_page = $this->fetch("Home@Xiaomi/page");
            $jsonData['list'] = $data_list;  
            $jsonData['page'] = $data_page;
        }else {
            $jsonData['state'] = 0;//后边没有页了
            $data_list = $this->fetch('Home@Xiaomi/list');
            $jsonData['list'] = $data_list; 
            $jsonData['page'] = 0;
        }      
        $data = json_encode($jsonData);
        echo $data;
        //$this->display('list');
        //$this->ajaxReturn($jsonData);
        
    }

    private function _source($list){
        $source = D('Source');
        foreach ($list as $key =>$value){
            $map['id']  = $value['source'];
            $value['source'] = $source  ->getSource($map);
            $res[] = $value;
        }
        return $res;
    }
    private function _logistics($list){
        $log = D('Logistics');
        foreach ($list as $key =>$value){
            $map['mode'] = $value['logistics_mode'];
            $value['logistics_mode'] = $log ->getLogistics($map);
            $res[] = $value;
        }
        return $res;
        
    }
    
    /*获得来源和物流信息
     * $list是商品所有信息
     * $source是原数组中的来源
     * $logistics_mode是原数组中的物流
     * sample code:
     * use Home\Controller\indexController;
     * $ctrl = new IndexController();
     * $res  = $ctrl ->get_Source_Log($list,source,logistics_mode);
     * $res 为替换来源和物流后的商品所有信息
     */
    public function get_Source_Log($list,$source,$logistics_mode){
        $sou = D('Source');
        $log = D('Logistics');
        foreach ($list as $key =>$value){
            $map['id']  = $value[$source];
            $value['source'] = $sou  ->getSource($map);
            $res[] = $value;
        }
        foreach ($res as $key =>$value1){
            $map1['mode'] = $value1[$logistics_mode];
            $value1['logistics_mode'] = $log ->getLogistics($map1);
            $att[] = $value1;
        }
        return $att;
        
    }
   
}
