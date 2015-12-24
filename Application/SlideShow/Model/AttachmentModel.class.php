<?php

/*
 * 梦云智工作室
 *   * 
 */

/**
 * Description of AttchmentModel
 *
 * @author xlj
 */
namespace SlideShow\Model;
use Think\Model;
class AttachmentModel extends Model {
     public function getName($SlideM) {
        foreach ($SlideM as $key => $value) {
            $id['id'] = $value['attchment_id'];
            $res[] = $this->where($id)->field('name')->find(); 
        }
        return $res;
    }
}
