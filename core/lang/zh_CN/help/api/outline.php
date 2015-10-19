<?php
return "<h3>API 概述</h3>
	<p>
		各种应用整合 baigo SSO 都是通过 API 接口实现的，您可以在各类应用程序中使用该接口，通过发起 HTTP 请求方式调用 baigo SSO 服务，返回 JSON 数据。
	</p>
	<p>
		使用 API 接口，您需先在 baigo SSO 创建应用，创建成功后会给出 APP ID 和 APP KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。
	</p>

	<hr>

	<h3>应用的验证</h3>
	<p>
		baigo SSO 的所有 API 接口均需要验证应用以及验证应用的权限。详情请查看具体接口说明。
	</p>

	<hr>

	<h3>返回结果</h3>
	<p>
		baigo SSO 所有 API 接口均返回加密字符串（安装接口除外），真正内容需要调用密文接口进行解密。解密后部分结果为 <mark>Base64 编码</mark>，需要进行 <mark>Base64 解码</mark>，请查看具体的接口。
	</p>
	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">返回结果</div>
		<div class=\"table-responsive\">
			<table class=\"table\">
				<thead>
					<tr>
						<th class=\"nowrap\">名称</th>
						<th class=\"nowrap\">类型</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"nowrap\">alert</td>
						<td class=\"nowrap\">string</td>
						<td>返回代码，详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">code</td>
						<td class=\"nowrap\">string</td>
						<td>加密字符串，需要解密。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">key</td>
						<td class=\"nowrap\">string</td>
						<td>解密码，配合加密字符串使用，用于解码。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>";
