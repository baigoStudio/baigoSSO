<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\response;

use ginkgo\Response;
use ginkgo\Debug;
use ginkgo\Arrays;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

// json 响应类
class Json extends Response {

    public $header   = array(
        'Content-Type'  => 'application/json; Charset=UTF-8',
    ); // 头数据

    protected function output($data) {
        //$this->contentType('application/json');
        if (is_array($data)) {
            $_arr_return = Debug::inject($data, 'arr');
            $_str_return = Arrays::toJson($_arr_return);
        } else if (is_string($data)) {
            $_str_return = Debug::inject($data, 'str');
        }

        return $_str_return;
    }
}
