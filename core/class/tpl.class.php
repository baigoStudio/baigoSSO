<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_SMARTY . "smarty.class.php"); //载入 Smarty 类

/*-------------模板类-------------*/
class CLASS_TPL {

	public $common; //通用
	public $obj_base;
	public $obj_smarty; //Smarty
	public $config; //配置
	public $lang; //语言 通用
	public $status; //语言 状态
	public $type; //语言 类型
	public $alert; //语言 提示代码
	public $adminMod; //语言 后台
	public $opt; //语言 后台

	function __construct($str_pathTpl) { //构造函数
		$this->obj_base                   = $GLOBALS["obj_base"]; //获取界面类型
		$this->config                     = $this->obj_base->config;

		$this->obj_smarty                 = new Smarty(); //初始化 Smarty 对象
		$this->obj_smarty->template_dir   = $str_pathTpl;
		$this->obj_smarty->compile_dir    = BG_PATH_TPL_COMPILE;
		$this->obj_smarty->debugging      = BG_SWITCH_SMARTY_DEBUG; //调试模式

		$this->lang       = include_once(BG_PATH_LANG . $this->config["lang"] . "/common.php"); //载入语言文件
		$this->status     = include_once(BG_PATH_LANG . $this->config["lang"] . "/status.php"); //载入状态文件
		$this->type       = include_once(BG_PATH_LANG . $this->config["lang"] . "/type.php"); //载入类型文件
		$this->allow      = include_once(BG_PATH_LANG . $this->config["lang"] . "/allow.php"); //载入权限文件
		$this->alert      = include_once(BG_PATH_LANG . $this->config["lang"] . "/alert.php"); //载入提示代码
		$this->install    = include_once(BG_PATH_LANG . $this->config["lang"] . "/install.php"); //载入提示代码
		$this->opt        = include_once(BG_PATH_LANG . $this->config["lang"] . "/opt.php"); //载入管理权限配置
		$this->adminMod   = include_once(BG_PATH_LANG . $this->config["lang"] . "/adminMod.php"); //载入管理权限配置
	}

	/*============显示模板============
	@str_view 模板文件
	@arr_tplData 需要显示的数据
	@str_token 是否生成令牌
	@str_fileHtml 静态页面文件名

	无返回
	*/
	function tplDisplay($str_view, $arr_tplData = "") {
		$this->common["token_session"]    = fn_token();
		$this->common["thisUrl"]          = base64_encode($_SERVER["REQUEST_URI"]);
		$this->common["ssid"]             = session_id();
		$this->common["view"]             = $GLOBALS["view"];

		$this->obj_smarty->assign("common", $this->common);
		$this->obj_smarty->assign("config", $this->config);
		$this->obj_smarty->assign("lang", $this->lang);
		$this->obj_smarty->assign("status", $this->status);
		$this->obj_smarty->assign("type", $this->type);
		$this->obj_smarty->assign("allow", $this->allow);
		$this->obj_smarty->assign("alert", $this->alert);
		$this->obj_smarty->assign("install", $this->install);
		$this->obj_smarty->assign("opt", $this->opt);
		$this->obj_smarty->assign("adminMod", $this->adminMod);
		$this->obj_smarty->assign("tplData", $arr_tplData);

		$this->obj_smarty->display($str_view);
	}
}
?>