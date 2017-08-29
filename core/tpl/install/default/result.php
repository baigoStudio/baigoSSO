<?php $cfg = array(
    'pathInclude'   => BG_PATH_TPLSYS . 'install' . DS . 'default' . DS . 'include' . DS,
);

include($cfg['pathInclude'] . 'result_head.php');
    $_str_msg   = '';
    $_str_rcode = '';

    if (isset($this->tplData['rcode'])) {
        $_str_rcode = $this->tplData['rcode'];
    }

    if (!fn_isEmpty($_str_rcode) && isset($this->lang['rcode'][$_str_rcode])) {
        $_str_msg = $this->lang['rcode'][$this->tplData['rcode']];
    }

    if (isset($this->tplData['msg']) && !fn_isEmpty($this->tplData['msg'])) {
        $_str_msg = $this->tplData['msg'];
    }

    $arr_re = array(
        'rcode'  => $_str_rcode,
        'msg'    => $_str_msg,
    );

    exit(json_encode($arr_re)); //输出错误信息
 ?>