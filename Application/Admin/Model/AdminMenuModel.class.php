<?php
namespace Admin\Model;
use Think\Model;
class AdminMenuModel extends Model
{
    private $currentMenuId = null;//当前菜单的id
    public function setCurrentMenuId($currentMenuId) {
        $this->currentMenuId = $currentMenuId;
    }
    //取出根一级菜单
    public function fetchMainMenu(){
        return $this->_menuList(0, 1);
    }
    //取出二级子菜单
    /*
     * @$menus 菜单数组,自根菜单开始,一直到当前菜单,每项记录菜单的ID值
     * @rootLevel 需要取出的根菜单在第几级.比如主菜单在第0级.而二级菜单在第1级
     * @level菜单取出的深度
     */
    public function fetchSubMenu($menus,$rootLevel,$level){
        $rootId = $menus[$rootLevel];
        return $this->_menuList($rootId, $level);
    }
    /*
     * 返回包括本菜单的上级菜单
     * 1.存本菜单ID
     * 2.取上级菜单ID
     * 3.取上级菜单ID
     * 4.上级菜单ID为0,退出
     */
    public function fetchParetnsMenus()
    {
        $id = $this->currentMenuId;
        if($id == 0 || $id == null)
        {
            return false;
        }
        $returnArr[] = $id;
        $map = array();
        do{
            $map['id'] = $id;
            $res = $this->where($map)->find();
            $returnArr[] = $res['pid'];
            $id = $res[pid];
        }while($id != 0);
        return array_reverse($returnArr);
    }
    //取出level级的子菜单
    //rootId 根菜单ID
    //level 取出目录数层数
    //返回目录
    private function _menuList($rootId,$level)
    {
        if($level--)
        {
            $menuList = $this->order('sort desc')->where('pid = ' . $rootId)->select();
            foreach($menuList as $key => $value)
            {
                $child = $this->_menuList($value['id'], $level);
                if(count($child)>0)
                {
                    $menuList[$key]['_child'] = $child;
                }
            }
        }
        return $menuList;
    }
}

