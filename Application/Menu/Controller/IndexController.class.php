<?php
namespace Menu\Controller;
use Admin\Controller\AdminController;
class IndexController extends AdminController {
    private $menu = null;//菜单表
    public function __construct() {
        parent::__construct();
        $this->menu = D('AdminMenu');
    }
    public function _initialize() {
        parent::_initialize();
    }
    /*
     * 初始化
     * 获取当前页
     */
    public function indexAction(){
        $currentPage = I('get.p',1);
        $menuM = $this->menu;
        $menuM->setLevel(3);
        //取树
        $menuList = $menuM->getMenuList();
        //将树转化为按顺序输出
        $menuList = $this->_treeToList($menuList);
        //引用adminUser父级page类
        $page = $this->page;
        $page->setCounts(count($menuList));
        $page->setCurrentPage($currentPage);
        $page->setPageStyle(2);
        $pageStr = $page->fetch();
        $pageSize = $page->getPageSize();
        //截取本页内容
        $menuList = array_slice($menuList , ($currentPage-1)*$pageSize , $pageSize);
        //添加链接
        foreach($menuList as $key => $value)
        {
            $menuList[$key]['actionUrl'] = array('edit' => U('edit?id=' . $value['id']) , 'delete' => U('delete?id=' . $value['id']), 'addSub' => U('addSub?id=' . $value['id']));
        }
        $this->assign('page',$pageStr);
        $this->assign('addRootMenuUrl',U('add?id=0'));
        $this->assign('MenuList',$menuList);
        $this->assign('YZRight',$this->fetch());   
        $this->display(YZ_TEMPLATE);
    }
    //增加
    public function addAction(){
        $menu = $this->menu;
        $data = array();
        $data['id'] = I('get.id',0);
        $data['actionUrl'] = array('update' => U('update'));
        $this->assign('adminMenu',$data);
        $this->assign('YZRight',$this->fetch(T('edit')));
        $this->display(YZ_TEMPLATE);
    }
    public function addSubAction(){
        $data = array();
        $data['pid'] = I('get.id',0);
        $data['actionUrl'] = array('update' => U('update'));
        $this->assign('adminMenu',$data);
        $this->assign('YZRight',$this->fetch(T('edit')));
        $this->display(YZ_TEMPLATE);
    }
    //修改
    public function editAction(){
      //判断是否传入
        $id = I('get.id',0);
        if($id == 0)
        {
            $resData['status'] = 'error';
            $resData['msg'] = '未接收到id值';
        }
        else
        {
            $map['id'] = $id;
            $menuM = M('adminMenu');
            $data = $menuM->where($map)->find();
            $data['actionUrl'] = array('update' => U('update'));
            $this->assign('adminMenu',$data);
            $this->assign('YZRight',$this->fetch());   
            $this->display(YZ_TEMPLATE);
        }
    }
    //更新
    public function updateAction(){
        //判断是否传入
        $menuM = M('adminMenu');
        $menuM->create(); 
        if(I('post.id') == '')//如果未传ID值，则插入数据
            $hello = $menuM->add();
        else
            $menuM->save();
        $resData['status'] = 'success';
        $resData['msg'] = ' 操作成功';
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
    //删除
    public function deleteAction(){
        //判断是否传入
        $id = I('get.id',0);
        if($id == 0)
        {
            $resData['status'] = 'error';
            $resData['msg'] = '未接收到id值';
        }
        else
        {
            $map['id'] = $id;
            $menuM = M('adminMenu');
            $resDel = $menuM->where($map)->delete();         
            if($resDel == null)
            {
                $resData['status'] = 'error';
                $resData['msg'] = '删除失败';
            }
            else
            {
                $resData['status'] = 'success';
                $resData['msg'] = '删除成功';
            }
        }
        $url = U('index');
        if($resData['status'] == 'success')
        {
            $this->success('删除成功',$url,2);
        }
        else
        {
            $this->error('删除失败',$url);
        }
        //return $this->ajaxReturn($resData); 
    }
    //保存
    public function saveAction(){
        
    }
    //将树转化为添加前后缀
    //_befor前缀
    //_after后缀
    //输入TREE，返回还befor和after的数组
    private function _listAddBeforAfter($tree)
    {
        $list = array();
        foreach($tree as $key => $value)
        {
           //如果不存在子结点，则将前后直接给LI
           if(!is_array($value['_child'])) 
           {
                $tree[$key]['_befor'] = '<li>';
                $tree[$key]['_after'] = '</li>';
           }
           //如果存在，则加UL
           else
           {
                $tree[$key]['_befor'] = '<li><ul>';
                $list = array_merge( $list,$this->_listAddBeforAfter($value['_child']));
                $tree[$key]['_after'] = '</ul></li>';
           }
           $list[] = $tree[$key];
        }
        return $list;
    }
    //将treelf
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
}