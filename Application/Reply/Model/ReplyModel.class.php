<?php
/*
 * 读取自动回复信息
 */
namespace Reply\Model;
use Think\Model;
class ReplyModel extends Model
{
    private $id = null;    //ID主键
    function setId($id) {
        $this->id = $id;
    }
    
    /*
     * 通过ID获取取1条信息
     */
    public function getInfoById()
    {
        if($this->id == null)
        {
            return;
        }
        
        $map['id'] = $this->id;
        return $this->where($map)->find();
    }
}
