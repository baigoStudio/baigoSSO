<?php
/*-----------------------------------------------------------------

！！！！警告！！！！
以下为系统文件，请勿修改

-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_CLASS . "seccode.class.php"); //载入模板类

class CONTROL_SECCODE {

	public $obj_seccode;

	function __construct() { //构造函数
		$this->obj_seccode = new CLASS_SECCODE(); //初始化视图对象
	}

	function ctl_make() {
		$this->obj_seccode->secSet();
		$this->obj_seccode->secDo();
	}
}
