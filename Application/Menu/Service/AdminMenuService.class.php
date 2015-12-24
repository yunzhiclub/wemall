<?php
//author: Pan Jie
//提供带有权限判断的菜单列表
namespace Menu\Service;
use Think\Model;
class AdminMenuService extends Model
{
    private $rootId = null;//起始菜单ID
    private $level = null;//菜单级数
    public function fetchMainMenu()
    {
        $this->rootId = 0;
        $this->level = 1;
        $menuList = $this->_getList();
        $menuListTemp = array();
        if(APP_DEBUG == FALSE)
        {
            foreach($menuList as $key => $value)     
            {
                if(!$value['is_dev'] && !$value['hide'])
                {
                    $menuListTemp[] = $value;
                }
            }
        }
        else
        {
            $menuListTemp = $menuList; 
        }
        return $menuListTemp;
    }
    //得到该结点下所有菜单
    private function _getList($rootId,$level)
    {
        $menuList = $this->order('sort desc')->where('pid = ' . $rootId)->select();
        if($level--)
        {
            foreach($menuList as $key => $value)
            {
                $child = $this->_getList($value['id'], $level);
                if(count($child)>0)
                {
                    $mennList[$key]['_child'] = $child;
                }
            }
        }
        return $mennList;
    }


}
