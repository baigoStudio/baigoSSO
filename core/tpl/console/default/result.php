<?php $cfg = array(
    "pathInclude"    => BG_PATH_TPLSYS . "console/default/include/"
);

include($cfg["pathInclude"] . "result_head.php");
    $_str_msg   = "";
    $_str_rcode = "";

    if (isset($this->tplData["rcode"])) {
        $_str_rcode = $this->tplData["rcode"];
    }

    if (!fn_isEmpty($_str_rcode) && isset($this->rcode[$_str_rcode])) {
        $_str_msg = $this->rcode[$this->tplData["rcode"]];
    }

    if (isset($this->tplData["msg"]) && !fn_isEmpty($this->tplData["msg"])) {
        $_str_msg = $this->tplData["msg"];
    }

    $_arr_tpl = array(
        "rcode"  => $_str_rcode,
        "msg"    => $_str_msg,
    );

    $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

    exit(json_encode($_arr_tplData)); //输出错误信息
