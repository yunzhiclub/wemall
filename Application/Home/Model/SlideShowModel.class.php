<?php
namespace Home\Model;
use Think\Model;
use Home\Model\AttachmentModel;
/*
 * 梦云智工作室
 *   * 
 */

/**
 * Description of SlideShowModel
 *
 * @author XINGYANNIAN
 */
class SlideShowModel extends Model {
    public function getSlideInfo() {
        $map['status'] = 1;
        $res = $this->where($map)->order('weight desc')->limit(6)->select();
        return $res;
    }
    public function getSlideDetail(){
        $slideInfo = $this->getSlideInfo();
        $attchmentModel = new AttachmentModel();
        foreach ($slideInfo as $key => $value) {
            $attchmentModel->setId($value['attchment_id']);
            $attchmentPath = $attchmentModel->getAttchmentPath();
            $slideInfo[$key]['path'] = $attchmentPath;
        }
        return $slideInfo;
    }//调用附件类，获取幻灯片的图片信息，返回详细的幻灯片信息
}
