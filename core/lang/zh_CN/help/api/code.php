<?php
return "<h3>返回结果</h3>
	<p>
		baigo SSO 所有 API 接口均返回加密字符串，真正内容需要调用密文接口进行解密。
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
						<td class=\"nowrap\">str_alert</td>
						<td class=\"nowrap\">string</td>
						<td>返回代码，详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">code</td>
						<td class=\"nowrap\">string</td>
						<td>加密字符串，需要解密。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code\">密文接口</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">key</td>
						<td class=\"nowrap\">string</td>
						<td>解密码，配合加密字符串使用，用于解码。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code\">密文接口</a>。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<hr>

	<h3>解密</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于将其他接口返回的加密字符串进行解密。解密后返回真正的结果。</p>

	<p class=\"text-success\">URL</p>
	<p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=code</span></p>

	<p class=\"text-success\">HTTP 请求方式</p>
	<p>GET</p>

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
						<td class=\"nowrap\">act_get</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>接口调用动作，值只能为 decode。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">app_id</td>
						<td class=\"nowrap\">int</td>
						<td class=\"nowrap\">true</td>
						<td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">app_key</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">code</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>从其他接口得到的加密字符串。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">key</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>从其他接口得到的解密码。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

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
						<td class=\"nowrap\">str_alert</td>
						<td class=\"nowrap\">string</td>
						<td>返回代码，详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
					</tr>
					<tr>
						<td colspan=\"3\">返回的其他内容均为 <mark>Base64 编码</mark>，需要对此进行 <mark>Base64 解码</mark>，请查看具体的接口。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>";