<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\response;

use ginkgo\Response;
use ginkgo\Debug;
use ginkgo\Func;
use ginkgo\Json as Json_Data;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Jsonp extends Response {

    protected $contentType = 'application/javascript';

    protected function output($data) {
        $_param = $this->config['jsonp_callback_param'];

        if (Func::isEmpty($_param)) {
            $_param = 'callback';
        }

        $_callback = $this->obj_request->request($_param);

        if (Func::isEmpty($_callback)) {
            $_callback = $this->config['jsonp_callback'];
        }

        if (is_array($data)) {
            $_str_return = Debug::inject($data, 'arr');
            $_str_return = Json_Data::encode($_str_return);
        } else if (is_string($data)) {
            $_str_return = Debug::inject($data, 'str');
        }

        return $_callback . '(' . $_str_return . ')';
    }

}


