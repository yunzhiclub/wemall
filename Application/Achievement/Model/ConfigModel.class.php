<?php
namespace Achievement\Model;
use Think\Model;
class ConfigModel extends Model{
    private $group = null;  //设置group值
    private $type = null;   //设置type值
    function setGroup($group) {
        $this->group = $group;
    }
    function setType($type) {
        $this->type = $type;
    }
    function __construct($name = '', $tablePrefix = '', $connection = '') {
        parent::__construct($name, $tablePrefix, $connection);
        $this->group = '10';
        $this->type = '9';
    }
    /*
     * 返回配置结算日期信息
     */
    public function fetConfig()
    {
        $map['group'] = $this->group;
        $map['type'] = $this->type;
        $res = $this->field('value')->where($map)->select();
        return $res;
    }
}
