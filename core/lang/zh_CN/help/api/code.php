<?php
return "<a name=\"encode\"></a>
	<h3>加密</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于将数据进行加密。</p>

	<p class=\"text-success\">URL</p>
	<p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=code</span></p>

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
						<th class=\"text-nowrap\">名称</th>
						<th class=\"text-nowrap\">类型</th>
						<th class=\"text-nowrap\">必须</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"text-nowrap\">act_post</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>接口调用动作，值只能为 encode。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_id</td>
						<td class=\"text-nowrap\">int</td>
						<td class=\"text-nowrap\">true</td>
						<td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_key</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">data</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>待加密的 JSON 字符串。首先必须将待加密的数组键值进行 Base64 编码（切勿将键名进行 Base64 编码），然后将数组进行 JSON 编码。</td>
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
						<th class=\"text-nowrap\">名称</th>
						<th class=\"text-nowrap\">类型</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"text-nowrap\">alert</td>
						<td class=\"text-nowrap\">string</td>
						<td>返回代码，详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">code</td>
						<td class=\"text-nowrap\">string</td>
						<td>加密字符串。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">key</td>
						<td class=\"text-nowrap\">string</td>
						<td>解密码。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">prd_sso_ver</td>
						<td class=\"text-nowrap\">string</td>
						<td>baigo SSO 版本号。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">prd_sso_pub</td>
						<td class=\"text-nowrap\">string</td>
						<td>baigo SSO 版本发布时间，格式为年月日。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<p>&nbsp;</p>

	<h4>返回结果示例</h4>
	<p>
<pre><code class=\"language-javascript\">{
    &quot;prd_sso_ver&quot;: &quot;1.1.1&quot;, //SSO 版本号
    &quot;prd_sso_pub&quot;: 20150923, //SSO 版本发布时间
    &quot;code&quot;: &quot;CSMEIFh7AHYBOFIlXQwAaQE0UXENawF2WUxXUQNFVD4Ac1R%2BUSUFdQgnBmYMcARbAT5RMlprBjZdJQdvBSRQXgkPBEhYZAAnAXFSdV0mAHMBNVEhDQ4BOVliV2wDbFQhAGtUcFElBSwIdgZ2DHEEYQEiUQxaaAY6XWQHPgUkUD0JegRbWFkATwE3UnVdfwAiASVRIA00ASZZXFdxA2lUbgA0VHBRPQUiCBkGVwxTBDQBHVEpWkMGYF1KBxEFR1A1CRcEU1gzADgBf1J7XXEAdQEjUTYNIwELWXdXbANtVGYADlQ%2BUWgFZwg9Bm0MIAQ%2BAXJRHlpSBgJdNwcYBXxQQglrBE9YSgBIAWhSGV0kAD0BbVFxDX0Bdll2V3YDZVRxAA5UO1F3BSIIbgYhDE8EUAEZUStaSgY5XUIHYAVJUFQJbAR6WEMAVgFpUi9dHQBqAR1Rbg1zASk%3D&quot;, //加密字符串
    &quot;key&quot;: &quot;tLUwyt&quot;, //解密码
    &quot;alert&quot;: &quot;y010102&quot; //返回代码
}</code></pre>
	</p>

	<hr>

	<a name=\"decode\"></a>
	<h3>解密</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于将其他接口返回的加密字符串进行解密。解密后返回真正的结果。</p>

	<p class=\"text-success\">URL</p>
	<p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=code</span></p>

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
						<th class=\"text-nowrap\">名称</th>
						<th class=\"text-nowrap\">类型</th>
						<th class=\"text-nowrap\">必须</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"text-nowrap\">act_post</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>接口调用动作，值只能为 decode。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_id</td>
						<td class=\"text-nowrap\">int</td>
						<td class=\"text-nowrap\">true</td>
						<td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_key</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">code</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>从其他接口得到的加密字符串。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">key</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
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
						<th class=\"text-nowrap\">名称</th>
						<th class=\"text-nowrap\">类型</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"text-nowrap\">alert</td>
						<td class=\"text-nowrap\">string</td>
						<td>返回代码，详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
					</tr>
					<tr>
						<td colspan=\"3\">返回的其他内容均为 <mark>Base64 编码</mark>，需要对此进行 <mark>Base64 解码</mark>，请查看具体的接口。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<p>&nbsp;</p>

	<h4>返回结果示例</h4>
	<p>
<pre><code class=\"language-javascript\">{
    &quot;user_id&quot;: &quot;MTA=&quot;,
    &quot;user_name&quot;: &quot;Zm9uZQ==&quot;,
    &quot;user_mail&quot;: &quot;Zm9uZUBiYWlnby5uZXQ=&quot;,
    &quot;user_nick&quot;: &quot;Zm9uZQ==&quot;,
    &quot;user_time&quot;: &quot;MTM5NDQxNzg3Mg==&quot;,
    &quot;user_time_login&quot;: &quot;MTQ0NjUyNTM1MA==&quot;,
    &quot;user_ip&quot;: &quot;MTIxLjE5OS4xMS4xNjM=&quot;
}</code></pre>
	</p>";