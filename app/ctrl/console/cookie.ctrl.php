<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\console;

use app\classes\console\Ctrl;

//不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

class Cookie extends Ctrl {

  public function clear() {
    $_mix_init = $this->init(false);

    if ($_mix_init !== true) {
      return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
    }

    $this->obj_auth->end(false, $this->url['route_console']);

    return $this->fetchJson('Clear cookies successfully', 'y030413');
  }
}
