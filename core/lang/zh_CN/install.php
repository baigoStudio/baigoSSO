<?php
/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
！！！！警告！！！！
以下为系统文件，请勿修改
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

/*++++++提示信息++++++
x开头为错误
y开头为成功
++++++++++++++++++*/
return array(
	"x030403" => "<h3>如需重新安装，请执行如下步骤：</h3>
		<ol>
			<li>删除 ./config/is_install.php 文件</li>
			<li>重新运行 <a href=\"" . BG_URL_INSTALL . "ctl.php\">" . BG_URL_INSTALL . "ctl.php</a></li>
		<ol>",

	"x030404" => "<h3>数据库未正确设置：</h3>
		<ol>
			<li><a href=\"" . BG_URL_INSTALL . "ctl.php?mod=install&act_get=dbconfig\">返回重新设置</a></li>
		</ol>",


	"x030412" => "<h3>数据库未正确设置：</h3>
		<ol>
			<li><a href=\"" . BG_URL_INSTALL . "ctl.php?mod=upgrade&act_get=dbconfig\">返回重新设置</a></li>
		</ol>",


	"x030413" => "<h3>未通过服务器环境检查，安装无法继续：</h3>
		<ol>
			<li>重新检查环境 <a href=\"" . BG_URL_INSTALL . "ctl.php?mod=install\">" . BG_URL_INSTALL . "ctl.php?mod=install</a></li>
			<li>根据检查结果，正确安装所必需的 PHP 扩展库。</li>
		</ol>",

	"x030414" => "<h3>未通过服务器环境检查，升级无法继续：</h3>
		<ol>
			<li>重新检查环境 <a href=\"" . BG_URL_INSTALL . "ctl.php?mod=upgrade\">" . BG_URL_INSTALL . "ctl.php?mod=upgrade</a></li>
			<li>根据检查结果，正确安装所必需的 PHP 扩展库。</li>
		</ol>",
);
