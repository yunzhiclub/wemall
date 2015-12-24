<?php
/*
 * 购物车(前台,继承userAdmin类)
 * JS变量:两个数组:一个用来记录商品ID,另一个记录商品ID对应的数量
 * 实现功能:
 * 1.将用户添加到购物车的商品按来源及物流方式分类
 * 2.实现JS事件.
 * 1)用户点击加减时,加减本商品的金额及数量.
 * 2)如果当前加减的商品被选中,被对总额进行处理.
 * 3)点击结算时,将两个数组分别变成处理过的字符串.
 * 4)将两个字符串用GET的方式传给订单管理
 * author:纪鹏 潘杰
 */

namespace ShoppingCart\Controller;
use User\Controller\UserController;

class IndexController extends UserController {

    private $shoppingcart; //购物车对象

    //构造方法，实例化对象

    public function __construct() {
        parent::__construct();
        $this->shoppingcart = D('ShoppingCart');
    }
    public function _initialize() {      
        $title = '我的购物车';
        $this->assign('title',$title);
        parent::_initialize();
    }
    /*
     * 初始化函数
     * 1取出当前用户的购物车商品信息
     * 2.将商品信息按来源及物流方式进行分组
     * 3.按分组分别展示商品信息
     */
    public function indexAction() {
        $shoppingCart = $this->shoppingcart;
        $openid = get_openid();
        $shoppingCart->setOpenid($openid);
        //读取信息
        $resArr = $shoppingCart->selectInfo();
        //拼接商品信息
        foreach ($resArr as $key => $value) {
            $goods = M('goods');
            $map['id'] = $value['product_number'];
            $map['state'] = 1;
            $good = $goods->where($map)->find();
            if($good != FALSE)
            {
                $resGoods[$key] = $good;
                $resGoods[$key]['product_quantity'] = $resArr[$key]['product_quantity'];
            }
        }
                //无记录则提示继续逛逛
        if(!is_array($resArr) || count($resArr) === 0 )
        {
            $flag = 1;
            $this->assign('flag',$flag);
        }
        //拼接附件信息
        $attachmentInfo = D('Attachment/Attachment');
        //设置关键字：即哪个字段代表的附件ID
        $attachmentInfo->setKey('focus_pictures');
        $resGoods  = $attachmentInfo->selectInfo($resGoods);
        /*
         * 按商品来源及物流方式分类
         */
        $resGoods = group_by_key($resGoods, 'source');
        foreach($resGoods as $key => $value)
        {
            $resGoods[$key] = group_by_key($value, 'logistics_mode');
        }
        $this->assign('shoppingcart', $resGoods);
        $submitUrl = U('OrderForm/Submit/index');
        $actionUrl['deleteUrl'] = U('delete');
        $actionUrl['changeCountUrl'] = U('changeCount');
        $indexUrl = U('Home/Index/index');
        $this->assign('actionUrl',$actionUrl);
        $this->assign('submitUrl',$submitUrl);    
        $js = $this->fetch('js');
        $css =$this->fetch('css');
        $this->assign('css',$css);
        $this->assign('js',$js);
        $this->assign('indexUrl',$indexUrl);
        $this->assign('url', $url);      
        $TPL = T('index');
        //$this ->fetch($TPL);
        $this->assign('YZBody', $this->fetch($TPL));
        $this->display(YZ_TEMPLATE);
    }
    /*
     * 示例代码,请将private修改为public
     * 获取购物车信息的
     * 获取get信息.
     * IDS,商品ID的数组
     * COUNTS,商品数量的数组
     */
    private function submitAction()
    {
        //获取get信息,去除html过滤.数据类型为JSON格式的字符串
        $ids = I('get.ids','',false);
        $counts = I('get.counts','',false);
        //josn转为数组
        $ids = json_decode($ids);
        $counts = json_decode($counts);
        var_dump($ids);
        var_dump($counts);
    }
    //添加购物车内商品数量
    public function addAction() {
        $res = I('get.id');
        var_dump($res);
        $this->shoppingcart->create();
        $this->shoppingcart->save();
    }
    
    //删除购物车内商品
    public function deleteAction() {
        echo 'success';
        $type = I('get.type','');
        $id = I('get.id',0);
        $map['openid'] = get_openid();
        //全部删除
        if($type == 'deleteAll')
        {
            M("ShoppingCart")->where($map)->delete();
            return;
        }
        if($id === 0)
        {
            return;
        }       
        $map['product_number'] = $id;
        M("ShoppingCart")->where($map)->delete();   
        return;
    }
    
    /*
     * 修改购物车内商品数量
     * count:商品数量
     * id:商品ID
     */
    public function changeCountAction() {
        echo 'success';
        $count = I('get.count',1);
        $id = I('get.id',0);
        if($id === 0)
        {
            return;
        }
        $map['product_number'] = $id;
        $map['openid'] = get_openid();
        $data['product_quantity'] = $count;
        M('ShoppingCart')->where($map)->data($data)->save();       
    }

    //结算购物车内商品
    public function settlementAction() {
        //调用结算模块
    }

    //查看详情
    public function detailsAction() {
        //只查看一个商品的具体详情
    }

}
