<?php
namespace Returnword\Controller;
use Admin\Controller\AdminController;
use Returnword\Model\ConfigModel;
class IndexController extends AdminController {
    public function indexAction(){
            $model = new ConfigModel();
            $word = $model ->init();
            $this ->assign('word',$word);
            $res =U('save');
            $this ->assign('actionUrl',U(save));
            $this->assign('YZRight', $this->fetch());
            $this->display(YZ_TEMPLATE);
    }
    public function saveAction(){
        $data= I('post.');
        $model = new ConfigModel();
        $res = $model ->update($data);
        if ($res === null) {
            $this->error('保存失败', $url, 2);
        } else {
            $this->success('操作成功', $url);
        }
    }
        
}