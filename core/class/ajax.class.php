<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

/*-------------ajax 类-------------*/
class CLASS_AJAX {

    private $obj_base; //基类
    private $config; //配置
    public $alert; //返回代码
    public $log; //日志

    function __construct() { //构造函数
        $this->obj_base   = $GLOBALS["obj_base"]; //基类
        $this->config     = $this->obj_base->config;
        $this->alert      = include_once(BG_PATH_LANG . $this->config["lang"] . "/alert.php"); //载入提示信息
        $this->log        = include_once(BG_PATH_LANG . $this->config["lang"] . "/log.php"); //载入日志内容
        $this->type       = include_once(BG_PATH_LANG . $this->config["lang"] . "/type.php"); //载入类型文件
        $this->opt        = include_once(BG_PATH_LANG . $this->config["lang"] . "/opt.php"); //载入设置配置
    }


    /** 返回出错信息（含具体信息）
     * halt_alert function.
     *
     * @access public
     * @param mixed $str_alert
     * @return void
     */
    function halt_alert($str_alert) {
        $arr_re = array(
            "msg"    => $this->alert[$str_alert],
            "alert"  => $str_alert,
        );
        exit(json_encode($arr_re)); //输出错误信息
    }


    /** ajax 返回出错信息（仅代码）
     * halt_re function.
     *
     * @access public
     * @param mixed $str_alert
     * @return void
     */
    function halt_re($str_alert) {
        $arr_re = array(
            "re" => $this->alert[$str_alert]
        );
        exit(json_encode($arr_re)); //输出错误信息
    }


    /** 验证是否安装
     * chk_install function.
     *
     * @access public
     * @return void
     */
    function chk_install() {
        if (file_exists(BG_PATH_CONFIG . "is_install.php")) { //如果已安装文件存在
            include_once(BG_PATH_CONFIG . "is_install.php"); //载入
            if (!defined("BG_INSTALL_PUB") || PRD_SSO_PUB > BG_INSTALL_PUB) { //如果已安装版本小于当前版本则抛出需要升级信息
                $this->halt_alert("x030411");
            }
        } else {
            $this->halt_alert("x030410"); //如已安装文件不存在则抛出需要安装信息
        }
    }
}
