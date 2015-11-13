<?php
return "<h3>所有用户</h3>
	<p>
		点左侧菜单用户管理，可以对用户进行编辑、删除、改变状态等操作。
	</p>

	<p>
		<img src=\"{images}user_list.jpg\" class=\"img-responsive thumbnail\">
	</p>

	<p>
		<img src=\"{images}user_form.jpg\" class=\"img-responsive thumbnail\">
	</p>

	<h3>批量导入</h3>
	<p>baigo SSO 自 1.1.2 开始支持批量导入功能。可以将需要导入的用户用 EXCEL 进行编辑，EXCEL 的第一行必须为字段名，如：用户名、密码、昵称等，同时建议 EXCEL 文件做成 3 列，用户名、密码为必须，密码的内容必须为经过 MD5 加密后的字符。baigo SSO 在批量导入界面提供 MD5 加密工具，将密码填入第一行的密码栏，点击生成加密结果，第二行的加密结果栏会显示加密字符串，然后将此字符串天如 EXCEL 的密码列。EXCEL 文件制作完成后，请将 EXCEL 另存为 CSV 文件。</p>

	<p>
		CSV 全称：逗号分隔值（Comma-Separated Values，CSV，有时也称为字符分隔值，因为分隔字符也可以不是逗号），其文件以纯文本形式存储表格数据（数字和文本）。详见 <a href=\"http://www.baike.com/wiki/CSV\" target=\"_blank\">互动百科</a>
	</p>

	<p>
		<img src=\"{images}user_import.jpg\" class=\"img-responsive thumbnail\">
	</p>";
