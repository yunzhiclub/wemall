<?php

/*
 * 梦云智工作室
 *   * 
 */

namespace User\Model;

use Think\Model;

class UserModel extends Model {

    //初始化用户管理
    public function init() {
        $res = $this->select();
        $map = array();
        foreach ($res as $key => $value) {
            if ($value['role'] == "0") {
                $value['Identity'] = "管理员";
                $map[] = $value;
            } else {
                $value['Identity'] = "普通用户";
                $map[] = $value;
            }    
        }
        return $map;
    }

}
