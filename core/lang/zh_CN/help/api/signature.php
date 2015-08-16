<?php
return "<a name=\"signature\"></a>
	<h3>生成签名</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于验证签名的正确性。</p>

	<p class=\"text-success\">URL</p>
	<p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=signature</span></p>

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
						<td>接口调用动作，值只能为 signature。</td>
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
						<td class=\"nowrap\">time</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>Unix 时间戳</td>
					</tr>
					<tr>
						<td class=\"nowrap\">random</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>随机字符串</td>
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
						<td class=\"nowrap\">alert</td>
						<td class=\"nowrap\">string</td>
						<td>返回代码，详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">signature</td>
						<td class=\"nowrap\">string</td>
						<td>签名字符串</td>
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

	<hr>

	<a name=\"verify\"></a>
	<h3>验证签名</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于验证签名的正确性。</p>

	<p class=\"text-success\">URL</p>
	<p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=signature</span></p>

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
						<td>接口调用动作，值只能为 verify。</td>
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
						<td class=\"nowrap\">time</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>Unix 时间戳</td>
					</tr>
					<tr>
						<td class=\"nowrap\">random</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>随机字符串</td>
					</tr>
					<tr>
						<td class=\"nowrap\">signature</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>签名字符串</td>
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
						<td class=\"nowrap\">alert</td>
						<td class=\"nowrap\">string</td>
						<td>返回代码，详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
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
	</div>";