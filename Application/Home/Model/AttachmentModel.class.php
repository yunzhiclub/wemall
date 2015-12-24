<?php
namespace Home\Model;
use Think\Model;
/*
 * 梦云智工作室
 *   * 
 */

/**
 * Description of AttachmentModel
 *
 * @author XINGYANNIAN
 */
class AttachmentModel extends Model {
    private $id;
    private $saveName;
    private $savePath;
    public function getId() {
        return $this->id;
    }

    public function getSaveName() {
        return $this->saveName;
    }

    public function getSavePath() {
        return $this->savePath;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setSaveName($saveName) {
        $this->saveName = $saveName;
    }

    public function setSavePath($savePath) {
        $this->savePath = $savePath;
    }
    public function getAttchmentPath() {
        $map['id'] = $this->id;
        $res = $this->where($map)->select();
        if(substr($res[0]['savepath'],0,1) == '.')
        {
            $savepath = substr($res[0]['savepath'],1,strlen($res[0]['savepath'])-1);
        }
        $path = add_root_path( C('UPLOAD_ROOT_PATH') . $savepath . $res[0]['savename'] );
        return $path;
    }

}
