<?php
namespace Search\Controller;
use User\Controller\UserController;
use Search\Model\OrderFormModel;
use OrderForm\Model\OrderGoodsModel;
use Home\Model\GoodsModel;
use Logistics\Model\LogisticsModel; //物流方式
use Source\Model\SourceModel;  //来源
class IndexController extends UserController {
    private $pageSize;
    private $goodsList;
    public function __construct() {
        parent::__construct();
        $this->pageSize = 9;//初始化商品查询显示条数
        $this->goodsList = T('Home@Xiaomi/list');
    }
    public function indexAction(){
        $queryAdd = I('get.condition');
        $data = array();
        if(is_numeric($queryAdd)){
            $openId = get_openid();
            $orderFormModel = new OrderFormModel();
            $orderFormModel->setQueryAdd($queryAdd);
            $orderFormModel->setOpenId($openId);
            $orderForm = $orderFormModel->getOrderFormInfo();
            $num = count($orderForm);
            if($num < 1){
                $data['content'] = $this->fetch('noForm');
            }  
            else {
                $orderGoods = new OrderGoodsModel();
                $orderGoods->setOrderForm($orderForm);
                $res = $orderGoods->getOrderGoods();
                $this->assign('res',$res);
                $data['content'] = $this->fetch('list');
            }
            $data['state'] = 0;//表示返回的订单信息
        }
        else {
            $goodsDetailUrl = U('Goods/GoodsDetail/index');
            $this->assign('goodsDetailUrl',  $goodsDetailUrl);
            $goodsModel = new GoodsModel();
            $goodsModel->setPageSize($this->pageSize);
            $goodsModel->setName($queryAdd);
            $num = $goodsModel->likeNameCount();
            if($num == 0){
                $data['content'] = 'soory';
            }
            else{
                if($num > $this->pageSize){                   
                    $this->assign('nextPageNum',2);
                    $data['page'] = $this->fetch('page');
                }
                $res = $goodsModel->goodsList();
                
                //添加物流方式
                $logistic = new LogisticsModel();
                $key = 'logistics_mode';
                $res = $logistic->getLogisticInfo($res, $key);
                
                //添加来源
                $source = new SourceModel();
                $key = 'source';
                $keyRes = 'source';
                $res = $source->getInfoById($res, $key, $keyRes);
                
                $this->assign('list',$res);               
                $data['content'] = $this->fetch($this->goodsList);               
            }
            $data['state'] = 1;//表示返回的商品信息            
        }
        $jsonData = json_encode($data);
        echo $jsonData;
    }
    
    public function loadAction() {
        $goodsModel = new GoodsModel();
        $pageNum = I('get.pageNum');
        $queryAdd = I('get.queryAdd');
        $nextPage = $pageNum + 1;//下页页码
        $goodsModel->setPage($pageNum);
        $goodsModel->setName($queryAdd);
        $goodsModel->setPageSize($this->pageSize);
        $count = $goodsModel->likeNameCount();
        $listNum = $this->pageSize * $pageNum;
        $list = $goodsModel->goodsList();       
        $this->assign('nextPageNum',$nextPage);
        $this->assign('list', $list);        
        $jsonData = array();
        if($listNum < $count){
            $jsonData['state'] = 1;//后面还有页
            $data_list = $this->fetch('queryGoodsList');
            $data_page = $this->fetch('page');
            $jsonData['content'] = $data_list;  
            $jsonData['page'] = $data_page;
        }else {
            $jsonData['state'] = 0;//后边没有页了
            $data_list = $this->fetch($this->goodsList);
            $jsonData['content'] = $data_list;  
        }       
        
        $data = json_encode($jsonData);
        echo $data;
        
    }
}