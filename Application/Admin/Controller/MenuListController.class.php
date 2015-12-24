<?php
/*
 * 获取菜单列表
 * 1.获取菜单主列表
 * 2.获取二级菜单列表.
 * 两部分代码很多相同点
 * 下一步进行整合
 */
namespace Admin\Controller;
use Think\Controller;
class MenuListController extends Controller
{
    /*
     * 获取主菜单信息
     * 1.调用D
     * 2.抓取所有一级菜单信息
     * 3.为菜单拼接URL信息
     * 4.若当前ACTION未对应菜单,说明非展示给用户项,退出.
     * 5.获取当前用户菜单信息
     * 6.为当前用户菜单添加选中class
     * 7.调用模块,进行拼接(若菜单项为隐藏,则不显示)
     */
    public function fetchMainMenu()
    {
        $adminM = D('Admin/AdminMenu');
        $menuList = $adminM->fetchMainMenu();
        //添加URL信息
        foreach($menuList as $key => $value)
        {
            $menuList[$key][url] = U($value['module'] . '/' . $value['controller'] . '/' .$value['action']);
        }
        //获取当前菜单与父菜单的列表
        $menuInfo = $this->_fetchCurrentMenuInfo();
        //如果无此菜单,则证明为ajax方法.直接退出
        if($menuInfo == false)
        {
            return;
        }
        $adminM->setCurrentMenuId($menuInfo['id']);
        $menus = $adminM->fetchParetnsMenus();
        $menuList = $this->_addCurrent($menuList,$menus);
        $this->assign('class','nav');
        $this->assign('data',$menuList);
        return $this->fetch(T('Admin@MenuList/index'));
    }
    /*
     * 返回当前URL对应的二级菜单
     */
    public function fetchSubMenu()
    {
        $menuInfo = $this->_fetchCurrentMenuInfo();
        //如果无此菜单,则证明为ajax方法.直接退出
        if($menuInfo == false)
        {
            return;
        }
        $id = $menuInfo['id'];
        $pid = $menuInfo['pid'];
        /*
         * 有记录,查找出目录结构
         */
        $adminM = D('Admin/AdminMenu'); 
        $adminM->setCurrentMenuId($id);
        $menus = $adminM->fetchParetnsMenus();                
        $subMenu = $adminM->fetchSubMenu($menus,1,2);
        $subMenu = tree_to_list($subMenu);
        //添加CURRENT,parent信息
        $subMenu = $this->_addCurrent($subMenu,$menus);
        $tree = list_to_tree($subMenu, $pk = 'id', $pid = 'pid', $child = '_child', $menus[1]);
        return '<ul class="nav nav-list">'.$this->_treeAddHtml($tree) . "</ul>";
    }
    /*
     * 获取当前所在菜单的信息
     */
    public function getCurrentMenuInfo()
    {
        return $this->_fetchCurrentMenuInfo();
    }
    //将TREE变为有UL,LI的HTML代码,适用于thinkphp
    //@tree array
    //module 模块名
    //controller 控制器名
    //action 触发器名
    //
    private function _treeAddHtml($tree){
        $html = '';
        foreach($tree as $key => $value)
        {
            if($value['hide'] == true)
            {
                continue;
            }
            $html .= '<li';
            $html .= isset($value['class'])?(' class="' . $value['class'] .'"'):'';
            $html .= '><a href="';
            $html .= U($value['module'] . '/'. $value['controller'] . '/' .$value['action']);
            $html .= '">' . $value['title'] . '</a>';
            if( is_array($value['_child']))
            {
                $html .= '<ul>';
                $html .= $this->_treeAddHtml($value['_child']);
                $html .= '</ul>';
            }
            $html .= '</li>';
        }
        return $html;
    }
    /*
     * 添加current parent 信息
     * @subMenu 要显示的目录数组
     * @menus 当前ACTION所对应的目录上级结构
     * 1.取出menus的最后一项,查找进行current添加
     * 2.其它项,查找进行parrent添加
     */
    private function _addCurrent($subMenu,$menus)
    {
        $currentMenuId = array_pop($menus);
        foreach($subMenu as $key => $value)
        {         
            if($currentMenuId == $value['id'])
            {
                $subMenu[$key]['class'] = 'active';
            }
            foreach($menus as $k => $v)
            {
                if($v == $value['id'])
                {
                    $subMenu[$key]['class'] = 'parent active';
                }
            }
        }
        return $subMenu;
    }
    /*
     * 获取当前菜单信息.
     * 没空或没找到,返回false
     */
    private function _fetchCurrentMenuInfo()
    {
        $data = array();
        $data['module'] = MODULE_NAME;
        $data['controller'] = CONTROLLER_NAME;
        $data['action'] = ACTION_NAME;
        $menuM = M('AdminMenu');
        $menuInfo = $menuM->where($data)->find();
        return $menuInfo;
    }
        
}
