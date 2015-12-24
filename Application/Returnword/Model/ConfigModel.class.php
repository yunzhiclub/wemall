<?php

/* 
 * 梦云智工作室
 *   * 
 */
namespace Returnword\Model;
use Think\Model;
class ConfigModel extends Model{
    public function init(){
        $map['name']="SYSTEM_RETURN_WORD";
        $res = $this -> where($map) ->select();
        $word = $res[0]['value'];
        return $word;
    }
    public function update($data){
        $res = $this->save($data);
        return $res;
    }
}
