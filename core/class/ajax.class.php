<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

/*-------------模板类-------------*/
class CLASS_AJAX {

	private $obj_base; //配置
	private $config; //配置
	private $alert; //配置
	public $log; //配置

	function __construct() { //构造函数
		$this->obj_base   = $GLOBALS["obj_base"]; //获取界面类型
		$this->config     = $this->obj_base->config;
		$this->alert      = include_once(BG_PATH_LANG . $this->config["lang"] . "/alert.php"); //载入提示信息
		$this->log        = include_once(BG_PATH_LANG . $this->config["lang"] . "/log.php"); //载入日志内容
	}

	function halt_alert($str_alert) {
		$arr_re = array(
			"msg"    => $this->alert[$str_alert],
			"alert"  => $str_alert,
		);
		exit(json_encode($arr_re)); //输出错误信息
	}

	function halt_re($str_alert) {
		$arr_re = array(
			"re" => $this->alert[$str_alert]
		);
		exit(json_encode($arr_re)); //输出错误信息
	}
}
?>