<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

/*-------------文件操作类类-------------*/
class CLASS_DIR {

	/*============删除目录============
	@str_path 路径

	无返回
	*/
	function del_dir($str_path) {

		//删除目录及目录里所有的文件夹和文件
		if (is_dir($str_path)) {
			$_arr_dir = $this->list_dir($str_path); //逐级列出

			foreach ($_arr_dir as $_value) {
				if ($_value["type"] == "file") {
					unlink($str_path . "/" . $_value["name"]);  //删除
				} else {
					$this->del_dir($str_path . "/" . $_value["name"]); //递归
				}
			}

			rmdir($str_path);
		}

	}

	/*============生成目录============
	@str_path 路径

	返回返回代码
	*/
	function mk_dir($str_path) {

		if (is_dir($str_path)) { //已存在
			$_str_alert = "y030201";
		} else {
			$_arr_dir = $this->mk_dir(dirname($str_path));
			//创建目录
			if ($_arr_dir["alert"] == "y030201") {
				if (mkdir($str_path)) { //创建成功
					$_str_alert = "y030201";
				} else {
					$_str_alert = "x030201"; //失败
				}
			} else {
				$_str_alert = "x030201";
			}
		}

		return array(
			"alert" => $_str_alert,
		);
	}

	/*============逐级列出目录============
	@str_path 路径

	返回多维数组
		type 类型 文件，目录
		name 目录名
	*/
	function list_dir($str_path) {

		$_arr_return  = array();
		$_arr_dir     = scandir($str_path);

		if ($_arr_dir) {
			foreach ($_arr_dir as $_key=>$_value) {
				if ($_value != "." && $_value != "..") {
					if (is_dir($str_path . $_value)) {
						$_arr_return[$_key]["type"] = "dir";
					} else {
						$_arr_return[$_key]["type"] = "file";
					}

					$_arr_return[$_key]["name"] = $_value;
				}
			}
		}

		return $_arr_return;
	}
}
