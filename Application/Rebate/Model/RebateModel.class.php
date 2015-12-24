<?php

/*
 * 梦云智工作室
 *   * 
 */

namespace Rebate\Model;

use Think\Model;

class RebateModel extends Model {

    //模型层初始化
    public function init($key) {
        if ($key == 0) {
            $rebate = M('rebate');
            $Page = new \Think\Page($count, 10);
//         $res = $this->order('top_money desc')->limit($Page->firstRow.','.$Page->listRows)->select();
            $map[type] = $key;
            $res = $rebate->where($map)->order('top_money asc')->select();
            return $res;
        } else {
            $rebate = M('rebate');
            $Page = new \Think\Page($count, 10);
//         $res = $this->order('top_money desc')->limit($Page->firstRow.','.$Page->listRows)->select();
            $map[type] = $key;
            $res = $rebate->where($map)->order('top_money asc')->select();
            return $res;
        }
    }

    //模型层编辑
    public function edit() {
        $model = M('Rebate');
        $id = I('get.id');
        $map = array();
        $map['id'] = $id;
        $res = $model->where($map)->find();
        return $res;
    }

    public function achievement($money) {
        $rebate = M('rebate');
        $map1[top_money] = array('egt', $money);
        $map1[type] = 0;
        $map2[top_money] = array('egt', $money);
        $map2[type] = 1;
        $res = $rebate->where($map1)->find();
        $ratio[] = $res[direct_ratio];
        $res = $rebate->where($map2)->find();
        
        $ratio[] = $res[line_ratio];
        return $ratio;
    }

    /*
     * 通过业绩获取当前业绩的系统
     * @dataArr array() 传入的数组
     * @key string 取哪个字段，最为计算费率的依据
     * @key1 stirng 返回的数据放在哪个字段中
     * @type 0为直销业绩费率 1为线销业绩费率
     * @return 添加下划线的数组
     */

    public function getRatioByMoney($dataArr, $key, $keyReturn) {
        foreach ($dataArr as $k => $v) {
            $map['top_money'] = array('elt', $v[$key]);
            $map['type'] = 0;
            $res = $this->where($map)->order('top_money desc')->find();
            $data['direct_ratio'] = $res['direct_ratio'];
            
            $map['type'] = 1;
            $res = $this->where($map)->order('top_money desc')->find();
            $data['line_ratio'] = $res['line_ratio'];
            $dataArr[$k][$keyReturn] = $data;
        }
        return $dataArr;
    }

    //模型层删除
    public function del() {
        $model = M('rebate');
        $id = I('get.id');
        $map = array();
        $map['id'] = $id;
        $res = $model->where($map)->delete();
        if ($res !== false) {
            return $string = '数据更新成功！';
        } else {
            return $string = '数据更新失败！';
        }
    }

    //模型层保存
    public function update($key) {
        if ($key == 0) {
            $rebate = M('rebate');
            $data = array();
            $data[id] = I('post.id');
            $data[top_money] = huansuan(I('post.top_money'));
            $data[direct_ratio] = I('post.direct_ratio');
            $rebate->save($data);
        } else {
            $rebate = M('rebate');
            $data = array();
            $data[id] = I('post.id');
            $data[top_money] = huansuan(I('post.top_money'));
            $data[line_ratio] = I('post.line_ratio');
            $rebate->save($data);
        }
    }

    public function datasave($key) {
        if ($key == 0) {
            $rebate = M('rebate');
            $data = array();
            $data[id] = I('post.id');
            $data[top_money] = I('post.top_money') * 100;
            $data[direct_ratio] = I('post.direct_ratio');
            $data[type] = $key;
            $res = $rebate->add($data);
            if ($res !== false) {
                return $string = '数据更新成功！';
            } else {
                return $string = '数据更新失败！';
            }
        } else {
            $rebate = M('rebate');
            $data = array();
            $data[id] = I('post.id');
            $data[top_money] = I('post.top_money') * 100;
            $data[line_ratio] = I('post.line_ratio');
            $data[type] = $key;
            $res = $rebate->add($data);
            if ($res !== false) {
                return $string = '数据更新成功！';
            } else {
                return $string = '数据更新失败！';
            }
        }
    }

}
