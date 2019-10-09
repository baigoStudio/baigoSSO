<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\response;

use ginkgo\Response;
use ginkgo\Debug;
use ginkgo\Json as Json_Data;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Json extends Response {

    protected $contentType = 'application/json';

    protected function output($data) {
        if (is_array($data)) {
            $_arr_return = Debug::inject($data, 'arr');
            $_str_return = Json_Data::encode($_arr_return);
        } else if (is_string($data)) {
            $_str_return = Debug::inject($data, 'str');
        }

        return $_str_return;
    }

}


