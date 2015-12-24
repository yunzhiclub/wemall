<?php

/**
 * Description of SetRedpacketModel
 *
 * @author xlj
 */
namespace SetRedpacket\Model;
use Think\Model;
class SetRedpacketModel extends Model {
    public function init() {
          return $this->find();
    }
}
