<?php
/*
 * 后台商品管理模块
 * 
 */
namespace Goods\Controller;
use Admin\Controller\AdminController;
class IndexController extends AdminController {
    private $goods;//Goods对象
    private $myUpload;
    private $urlArray;//传入到初始化页面的左边的url数组
    public function __construct() {
        parent::__construct();
        $this->goods = D('Goods');
        $this->myUpload = $this->upload;
        $path = './Picture/Goods/';
        $this->myUpload->savePath = $path;
        $this->urlArray = array('urlonSheleves' => U('onShelevesManager'),'urlunderSheleves' => U('underShelevesManager'),'urladd' => U('add'));
    }
    public function _initialize() {
        //$this->addCss('/theme/default/ckeditor/sample.css');
        $this->addJs('/theme/default/ckeditor/sample.js');
        $this->addJs('/theme/default/ckeditor/ckeditor.js');
        parent::_initialize();
    }

    public function indexAction()
    {
        $url = U('onShelevesManager');
        redirect_url($url);
    }
        //架上商品管理
    public function onShelevesManagerAction(){
        $menuList = ($this->goods->onShelevesView());
        //添加链接
        foreach($menuList as $key => $value)
        {
            $menuList[$key]['actionUrl'] = array('edit' => U('edit?id=' . $value['id']) , 'delete' => U('delete?id=' . $value['id']), 'underSheleves' => U('underSheleves?id=' . $value['id']));
        }
        $this->goodsManager($menuList);
    }
    //架下商品管理
    public function underShelevesManagerAction(){
        $menuList = ($this->goods->underShelevesView());
        //添加链接
        foreach($menuList as $key => $value)
        {
            $menuList[$key]['actionUrl'] = array('edit' => U('edit?id=' . $value['id']) , 'delete' => U('delete?id=' . $value['id']), 'onSheleves' => U('onSheleves?id=' . $value['id']));
        }
        $this->goodsManager($menuList);
    }
    //添加商品
    public function addAction(){
       $url = U('save');
       $goods = M('goods');
       $res = $goods->find();
       $this->assign('GoodsList',$res);
        $js=$this->fetch('js');
       $this->assign('js',$js);
     
       $allSource = get_all_source();
       $allLog = get_all_logistics();
       $this->assign(url,$url);
       $this ->assign('Source',$allSource);
       $this ->assign('Log',$allLog);
       $this->assign('YZRight',$this->fetch());
       $this->display(YZ_TEMPLATE);
    }
    //商品编辑
    public function editAction(){
        $id = I('get.id',0);
        if($id == 0){
            $resData['status'] = 'error';
            $resData['msg'] = '未接收到id值';
        }
        else {
            $this->goods->setGoodsId($id);
            $data = $this->goods->getGoodsInf();
            $data['actionUrl'] = array('update' => U('updateAction'));
            //设置来源和物流
            $allSource = get_all_source();
            $allLog = get_all_logistics();
            $this ->assign('Source',$allSource);
            $this ->assign('Log',$allLog);
            //设置取消按钮
            $urlIndex['onShelevesManager'] = U('onShelevesManager');
            $urlIndex['underShelevesManager'] = U('underShelevesManager');
            $this->assign('urlIndex',$urlIndex);
            $this->assign('GoodsList',$data);
            //传Url
            $url = U('update');
            $this->assign(url,$url);
            $this->assign('YZRight',$this->fetch());
            $this->display(YZ_TEMPLATE);
        }  
    }
    //图片上传
    public function myUploadAction(){
       
    }
    public function saveAction(){
        //上传附件
        $info = $this->myUpload->upload();
        if($info){
            $saveInfo = D('Attachment/Attachment');
            foreach ($info as $key => $value) {
                $saveInfo->setInfo($info);
                $id = $saveInfo->saveInfo();
            } 
            $picture = $id[0].",".$id[1].",".$id[2];        
        }
        //保存信息
        $resData = $this->goods->insert($picture);
        $url = U('index');
        if($resData['status'] == 'success')
        {
            $this->success('添加商品成功',$url,2);
        }
        else
        {
            $this->error('添加商品失败',$url);
        }
    }
    //商品修改
    /*
     * 商品修改，修改商品的显示图片
     * 先取出原来的一串数据
     * 然后拆分成数组
     * 判断是否又信上传了图片
     * 根据新上传的图片拼接成新的数组，
     * 然后再转成新的字符串
     */
    public function updateAction(){
        $info = $this->myUpload->upload();
        $id = I('post.id');
            $this->goods->setGoodsId($id);
            $data = $this->goods->getGoodsInf();
            //获取之前的图片
            $picture = $data['focus_pictures'];
        if($info){ 
            $picArr = explode(',', $pic);      
            $saveInfo = D('Attachment/Attachment');           
            foreach ($info as $key => $value) {
                $keyarr[] = substr($key,5,1);
            }
            $saveInfo->setInfo($info);
            $picId = $saveInfo->saveInfo();            
            foreach ($picId as $key => $value) {
                $picArr[$keyarr[$key]] = $value;
            }
            $picture = $picArr[0].",".$picArr[1].",".$picArr[2];
        }
        $resData = $this->goods->update($picture);
        $url = U('index');
        if($resData['status'] == 'success')
        {
            $this->success('操作成功',$url,2);
        }
        else
        {
            $this->error('操作失败',$url);
        }
    }
    //删除商品
    public function deleteAction(){
        $state = 2;
        $url = U('index');
        $this->onAndUnder($state, $url);
    }
    //商品上架
    public function onShelevesAction(){
        $state = 1;
        $url = U('underShelevesManager');
        $this->onAndUnder($state, $url);
    }
    //商品下架
    public function underShelevesAction(){
        $state = 0;
        $url = U('onShelevesManager');
        $this->onAndUnder($state, $url);
    }
    private function goodsManager($me){
        $menuList = $me;
        $currentPage = I('get.p',1);
        //将树转化为按顺序输出
        $page = $this->page;
        $page->setCounts(count($menuList));
        $page->setCurrentPage($currentPage);
        $page->setPageStyle(2);
        $pageStr = $page->fetch();
        $pageSise = $page->getPageSize();
        //截取本页内容
        $menuList = array_slice($menuList , ($currentPage-1)*$pageSise , $pageSise);
        $this->assign('page',$pageStr);
        $this->assign('MenuList',$menuList);
        $this->assign(url,$this->urlArray);
        //显示你定义的同名的html页面
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    private function onAndUnder($state,$url){
        $id = I('get.id',0);
        if($id == 0)
        {
            $resData['status'] = 'error';
            $resData['msg'] = '未接收到id值';
        }
        else
        {
            $data['state']  = $state;
            $map['id'] = $id;
            $resUnder= $this->goods->where($map)->save($data);
            if($resUnder == null)
            {
                $resData['status'] = 'error';
                $resData['msg'] = '操作失败';
            }
            else
            {
                $resData['status'] = 'success';
                $resData['msg'] = '操作成功';
            }
        }
        if($resData['status'] == 'success')
        {
            $this->success('操作成功',$url,2);
        }
        else
        {
            $this->error('操作失败',$url);
        }
    }
}