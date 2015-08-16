<?php
return "<h3>创建管理员</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于创建管理员。</p>

	<div class=\"alert alert-warning\">
		<span class=\"glyphicon glyphicon-warning-sign\"></span>
		注意：本接口仅在自动部署 SSO 时有用，安装成功后将自动失效。
	</div>

	<p class=\"text-success\">URL</p>
	<p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=admin</span></p>

	<p class=\"text-success\">HTTP 请求方式</p>
	<p>POST</p>

	<p class=\"text-success\">返回格式</p>
	<p>JSON</p>

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">接口参数</div>
		<div class=\"table-responsive\">
			<table class=\"table\">
				<thead>
					<tr>
						<th class=\"nowrap\">名称</th>
						<th class=\"nowrap\">类型</th>
						<th class=\"nowrap\">必须</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"nowrap\">act_post</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>接口调用动作，值只能为 add。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">app_id</td>
						<td class=\"nowrap\">int</td>
						<td class=\"nowrap\">true</td>
						<td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">app_key</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">admin_name</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>管理员用户名</td>
					</tr>
					<tr>
						<td class=\"nowrap\">admin_pass</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>密码，必须用 <mark>MD5</mark> 加密后传输。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<p>
		baigo SSO 所有 API 接口均返回加密字符串，真正内容需要调用密文接口进行解密。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
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
					<tr>
						<td class=\"nowrap\">prd_sso_ver</td>
						<td class=\"nowrap\">string</td>
						<td>baigo SSO 版本号。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">prd_sso_pub</td>
						<td class=\"nowrap\">string</td>
						<td>baigo SSO 版本发布时间，格式为年月日。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">解密后的结果</div>
		<div class=\"table-responsive\">
			<table class=\"table\">
				<thead>
					<tr>
						<th class=\"nowrap\">名称</th>
						<th class=\"nowrap\">Base64</th>
						<th class=\"nowrap\">类型</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"nowrap\">alert</td>
						<td>false</td>
						<td>string</td>
						<td>返回代码，详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">admin_id</td>
						<td>true</td>
						<td>int</td>
						<td>管理员 ID</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>";
