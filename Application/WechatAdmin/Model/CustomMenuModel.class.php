<?php
namespace WechatAdmin\Model;
use Think\Model;
/* 
 * 微信公众平台前端菜单
 *   * 
 */
class CustomMenuModel extends Model{
    public function fetchList(){
        $menuList = $this->order('sort')->select();
        $tree = list_to_tree($menuList);
        $list = tree_to_list($tree);
        return $list;
    }
}

