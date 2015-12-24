<?php

/*
 * 梦云智工作室
 *   * 
 */

/**
 * Description of SlideshowModel
 *
 * @author xlj
 */
namespace Slideshow\Model;
use Think\Model;
class SlideshowModel extends Model{
     public function slideAction()
    {
         
    $model = M('SlideshowManagement');
    $res = $model->where('status=1')->order('weight')->select();
    //var_dump($res);
    return $res;
    
    }
}
