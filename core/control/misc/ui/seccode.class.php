<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/


//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

class CONTROL_MISC_UI_SECCODE {

    function ctrl_make() {
        header('Content-type: image/png');
        $this->obj_seccode = new CLASS_SECCODE(); //初始化视图对象
        $this->obj_seccode->secSet();
        $this->obj_seccode->secDo();
    }
}
