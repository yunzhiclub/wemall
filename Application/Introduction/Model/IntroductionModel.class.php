<?php
/*
 * 其它支付方式说明介绍
 * 我只想说,有人太懒了,前台和后台是一起的,不知道吗?
 */
namespace Introduction\Model;
use Think\Model;
class IntroductionModel extends Model
{
    private $id = null;
    function setId($id) {
        $this->id = $id;
    }

        
    /*
     * 返回支付说明信息
     */
    public function getInfoById($id)
    {
        $map['id'] = $id;
        $res = $this->where($map)->find();
        return $res;
    }
}


