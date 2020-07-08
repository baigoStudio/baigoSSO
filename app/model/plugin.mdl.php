<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model;

use ginkgo\Func;
use ginkgo\Config;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------插件模型-------------*/
class Plugin {

    function __construct() { //构造函数
        $this->configPlugin = Config::get('plugin');
    }
}
