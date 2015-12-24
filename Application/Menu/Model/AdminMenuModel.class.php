<?php
namespace Menu\Model;
use Think\Model;
class AdminMenuModel extends Model{
    public function setRootId($rootId) {
        $this->rootId = $rootId;
    }
    public function setLevel($level) {
        $this->level = $level;
    }
    public function getMenuList()
    {
        $rootId = $this->rootId === null ? 0 : $this->rootId;
        $level = $this->level === null ? 1 : $this->level;
        $menuList = $this->getList($rootId, $level);
        $menuList = list_to_tree($menuList, $pk = 'id', $pid = 'pid', $child = '_child', $rootId);
        return $menuList;
    }
    //得到该结点下所有菜单
    public function getList($rootId,$level)
    {
        $list = array();
        $menuList = $this->order('sort desc')->where('pid = ' . $rootId)->select();
        if($level--)
        {
            foreach($menuList as $key => $value)
            {
                $child = $this->getList($value['id'], $level);
                if(count($child)>0)
                {
                    $menuList[$key]['_child'] = $child;
                }
            }
        }
        return $menuList;
    }
}