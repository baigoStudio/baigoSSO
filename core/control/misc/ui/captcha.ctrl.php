<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/


//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

class CONTROL_MISC_UI_CAPTCHA {

    function ctrl_make() {
        $_obj_captcha = new CLASS_CAPTCHA(); //初始化视图对象
        $_obj_captcha->secSet();
        $_obj_captcha->secDo();

        unset($_obj_captcha);
    }
}
