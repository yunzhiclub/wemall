<?php
/*
 * 商品来源表
 */
namespace OrderForm\Model;
use Think\Model;
class SourceModel extends Model
{
    public function getInfoById($id)
    {
        $map['id'] = $id;
        $res = $this->where($map)->find();
        $res['icon_url'] = add_root_path('/' . $res['icon_url']);
        return $res;
    }
}

