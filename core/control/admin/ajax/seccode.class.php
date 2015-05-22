<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_CLASS . "ajax.class.php"); //载入模板类

/*-------------用户控制器-------------*/
class AJAX_SECCODE {

	private $obj_ajax;

	function __construct() { //构造函数
		$this->obj_ajax = new CLASS_AJAX(); //获取界面类型
	}

	/*============提交用户============
	返回数组
		user_id ID
		str_alert 提示信息
	*/
	function ajax_chk() {
		$_str_seccode = strtolower(fn_get("seccode"));
		if ($_str_seccode != fn_session("seccode")) {
			$this->obj_ajax->halt_re("x030101");
		}

		$arr_re = array(
			"re" => "ok"
		);

		exit(json_encode($arr_re));
	}
}
