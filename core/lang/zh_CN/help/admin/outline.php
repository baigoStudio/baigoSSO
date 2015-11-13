<?php
return "<h3>后台概述</h3>
	<p>
		baigo SSO 的管理后台地址为 <span class=\"text-primary\">http://www.domain.com/admin/</span>，在如下界面输入用户名、密码、验证码登录。
	</p>

	<p>
		<img src=\"{images}logon.jpg\" class=\"img-responsive thumbnail\">
	</p>

	<hr>

	<p>
		登录后将显示类似如下界面
	</p>

	<p>
		<img src=\"{images}user_list.jpg\" class=\"img-responsive thumbnail\">
	</p>

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">左侧菜单组成说明</div>
		<div class=\"table-responsive\">
			<table class=\"table\">
				<thead>
					<tr>
						<th class=\"text-nowrap\">菜单</th>
						<th class=\"text-nowrap\">功能</th>
						<th>说明</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"text-nowrap\">用户管理</td>
						<td class=\"text-nowrap\">用户管理功能</td>
						<td>已注册的用户管理，您可以在此对用户进行各种管理操作。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">应用管理</td>
						<td class=\"text-nowrap\">应用管理功能</td>
						<td>要整合 baigo SSO，必须在此创建应用，并对应用授予相应的权限，这些应用通过 API 接口调用来实现各种功能。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">日志管理</td>
						<td class=\"text-nowrap\">日志管理功能</td>
						<td>后台、应用的部分操作，会以日志的形式记录在此，方便查看和调试。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">管理员</td>
						<td class=\"text-nowrap\">管理员管理功能</td>
						<td> </td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">系统设置</td>
						<td class=\"text-nowrap\">系统设置功能</td>
						<td>您可以在此设置网站名称、时区、各类格式等。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>";
