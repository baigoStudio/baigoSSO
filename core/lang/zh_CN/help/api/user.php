<?php
return "<h3>用户注册</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于新用户的注册。用户名、密码、Email、昵称为一个用户在 baigo SSO 的基本数据，提交后 baigo SSO 会按照 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=opt#reg\">注册设置</a> 检测用户名和 Email 的格式是否正确合法。</p>

	<p class=\"text-success\">URL</p>
	<p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=user</span></p>

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
						<td>接口调用动作，值只能为 reg。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_id</td>
						<td class=\"text-nowrap\">int</td>
						<td class=\"text-nowrap\">true</td>
						<td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_key</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_name</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>用户名</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_pass</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>密码，必须用 <mark>MD5</mark> 加密后传输。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_mail</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\"></td>
						<td>视注册设置情况而定。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=opt#reg\">注册设置</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_nick</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">false</td>
						<td>昵称</td>
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
						<td>加密字符串，需要解密。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">key</td>
						<td class=\"text-nowrap\">string</td>
						<td>解密码，配合加密字符串使用，用于解码。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
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

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">解密后的结果</div>
		<div class=\"table-responsive\">
			<table class=\"table\">
				<thead>
					<tr>
						<th class=\"text-nowrap\">名称</th>
						<th class=\"text-nowrap\">Base64</th>
						<th class=\"text-nowrap\">类型</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"text-nowrap\">user_id</td>
						<td class=\"text-nowrap\">true</td>
						<td class=\"text-nowrap\">int</td>
						<td>用户 ID</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<p>&nbsp;</p>

	<h4>返回结果示例</h4>
	<p>
<pre><code class=\"language-javascript\">{
    &quot;user_id&quot;: &quot;MTA=&quot; //用户 ID
}</code></pre>
	</p>

	<hr>

	<h3>用户登录</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于用户的登录验证。</p>

	<p class=\"text-success\">URL</p>
	<p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=user</span></p>

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
						<td>接口调用动作，值只能为 login。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_id</td>
						<td class=\"text-nowrap\">int</td>
						<td class=\"text-nowrap\">true</td>
						<td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_key</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_name</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>用户名</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_pass</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
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
						<td>加密字符串，需要解密。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">key</td>
						<td class=\"text-nowrap\">string</td>
						<td>解密码，配合加密字符串使用，用于解码。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
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

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">解密后的结果</div>
		<div class=\"table-responsive\">
			<table class=\"table\">
				<thead>
					<tr>
						<th class=\"text-nowrap\">名称</th>
						<th class=\"text-nowrap\">Base64</th>
						<th class=\"text-nowrap\">类型</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"text-nowrap\">user_id</td>
						<td class=\"text-nowrap\">true</td>
						<td class=\"text-nowrap\">int</td>
						<td>用户 ID</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_name</td>
						<td class=\"text-nowrap\">true</td>
						<td class=\"text-nowrap\">string</td>
						<td>用户名</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_mail</td>
						<td class=\"text-nowrap\">true</td>
						<td class=\"text-nowrap\">string</td>
						<td>Email</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_nick</td>
						<td class=\"text-nowrap\">true</td>
						<td class=\"text-nowrap\">string</td>
						<td>昵称</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_time</td>
						<td class=\"text-nowrap\">true</td>
						<td class=\"text-nowrap\">int</td>
						<td>注册时间，Unix 时间戳。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_time_login</td>
						<td class=\"text-nowrap\">true</td>
						<td class=\"text-nowrap\">int</td>
						<td>最后登录时间，Unix 时间戳。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_ip</td>
						<td class=\"text-nowrap\">true</td>
						<td class=\"text-nowrap\">string</td>
						<td>最后登录 IP 地址</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<p>&nbsp;</p>

	<h4>返回结果示例</h4>
	<p>
<pre><code class=\"language-javascript\">{
    &quot;user_id&quot;: &quot;MTA=&quot;, //用户 ID
    &quot;user_name&quot;: &quot;Zm9uZQ==&quot;, //用户名
    &quot;user_mail&quot;: &quot;Zm9uZUBiYWlnby5uZXQ=&quot;, //Email
    &quot;user_nick&quot;: &quot;Zm9uZQ==&quot;, //昵称
    &quot;user_time&quot;: &quot;MTM5NDQxNzg3Mg==&quot;, //注册时间
    &quot;user_time_login&quot;: &quot;MTQ0NjUyNTM1MA==&quot;, //最后登录时间
    &quot;user_ip&quot;: &quot;MTIxLjE5OS4xMS4xNjM=&quot; //最后登录 IP
}</code></pre>
	</p>

	<hr>

	<h3>读取用户数据</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于读取已注册用户的信息。</p>

	<p class=\"text-success\">URL</p>
	<p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=user</span></p>

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
						<th class=\"text-nowrap\">名称</th>
						<th class=\"text-nowrap\">类型</th>
						<th class=\"text-nowrap\">必须</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"text-nowrap\">act_get</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>接口调用动作，值只能为 get。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_id</td>
						<td class=\"text-nowrap\">int</td>
						<td class=\"text-nowrap\">true</td>
						<td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_key</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_id</td>
						<td class=\"text-nowrap\">int</td>
						<td class=\"text-nowrap\"> </td>
						<td>用户 ID，与 user_name 二选一即可，user_id 优先。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_name</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\"> </td>
						<td>用户名，与 user_id 二选一即可，user_id 优先。</td>
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
						<td>加密字符串，需要解密。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">key</td>
						<td class=\"text-nowrap\">string</td>
						<td>解密码，配合加密字符串使用，用于解码。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
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

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">解密后的结果</div>
		<div class=\"table-responsive\">
			<table class=\"table\">
				<thead>
					<tr>
						<th class=\"text-nowrap\">名称</th>
						<th class=\"text-nowrap\">Base64</th>
						<th class=\"text-nowrap\">类型</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"text-nowrap\">user_id</td>
						<td class=\"text-nowrap\">true</td>
						<td class=\"text-nowrap\">int</td>
						<td>用户 ID</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_name</td>
						<td class=\"text-nowrap\">true</td>
						<td class=\"text-nowrap\">string</td>
						<td>用户名</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_mail</td>
						<td class=\"text-nowrap\">true</td>
						<td class=\"text-nowrap\">string</td>
						<td>Email</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_nick</td>
						<td class=\"text-nowrap\">true</td>
						<td class=\"text-nowrap\">string</td>
						<td>昵称</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_time</td>
						<td class=\"text-nowrap\">true</td>
						<td class=\"text-nowrap\">int</td>
						<td>注册时间，Unix 时间戳。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_time_login</td>
						<td class=\"text-nowrap\">true</td>
						<td class=\"text-nowrap\">int</td>
						<td>最后登录时间，Unix 时间戳。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_ip</td>
						<td class=\"text-nowrap\">true</td>
						<td class=\"text-nowrap\">string</td>
						<td>最后登录 IP 地址</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<p>&nbsp;</p>

	<h4>返回结果示例</h4>
	<p>
<pre><code class=\"language-javascript\">{
    &quot;user_id&quot;: &quot;MTA=&quot;, //用户 ID
    &quot;user_name&quot;: &quot;Zm9uZQ==&quot;, //用户名
    &quot;user_mail&quot;: &quot;Zm9uZUBiYWlnby5uZXQ=&quot;, //Email
    &quot;user_nick&quot;: &quot;Zm9uZQ==&quot;, //昵称
    &quot;user_time&quot;: &quot;MTM5NDQxNzg3Mg==&quot;, //注册时间
    &quot;user_time_login&quot;: &quot;MTQ0NjUyNTM1MA==&quot;, //最后登录时间
    &quot;user_ip&quot;: &quot;MTIxLjE5OS4xMS4xNjM=&quot; //最后登录 IP
}</code></pre>
	</p>

	<hr>

	<h3>编辑用户资料</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于编辑已注册用户的信息。</p>

	<p class=\"text-success\">URL</p>
	<p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=user</span></p>

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
						<td>接口调用动作，值只能为 edit。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_id</td>
						<td class=\"text-nowrap\">int</td>
						<td class=\"text-nowrap\">true</td>
						<td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_key</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_id</td>
						<td class=\"text-nowrap\">int</td>
						<td class=\"text-nowrap\"> </td>
						<td>用户 ID，与 user_name 二选一即可，user_id 优先。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_name</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\"> </td>
						<td>用户名，与 user_id 二选一即可，user_id 优先。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_pass</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\"></td>
						<td>密码，根据 user_check_pass 参数而定，必须用 <mark>MD5</mark> 加密后传输。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_check_pass</td>
						<td class=\"text-nowrap\">bool</td>
						<td class=\"text-nowrap\">true</td>
						<td>是否需要验证旧密码，true 为验证旧密码，false 为忽略旧密码。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_mail</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\"></td>
						<td>视注册设置情况而定。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=opt#reg\">注册设置</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_nick</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">false</td>
						<td>昵称</td>
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
						<td>加密字符串，需要解密。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">key</td>
						<td class=\"text-nowrap\">string</td>
						<td>解密码，配合加密字符串使用，用于解码。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
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

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">解密后的结果</div>
		<div class=\"table-responsive\">
			<table class=\"table\">
				<thead>
					<tr>
						<th class=\"text-nowrap\">名称</th>
						<th class=\"text-nowrap\">Base64</th>
						<th class=\"text-nowrap\">类型</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"text-nowrap\">user_id</td>
						<td class=\"text-nowrap\">true</td>
						<td class=\"text-nowrap\">int</td>
						<td>用户 ID</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<p>&nbsp;</p>

	<h4>返回结果示例</h4>
	<p>
<pre><code class=\"language-javascript\">{
    &quot;user_id&quot;: &quot;MTA=&quot; //用户 ID
}</code></pre>
	</p>

	<hr>

	<h3>删除用户</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于删除已注册用户。</p>

	<p class=\"text-success\">URL</p>
	<p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=user</span></p>

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
						<td>接口调用动作，值只能为 del。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_id</td>
						<td class=\"text-nowrap\">int</td>
						<td class=\"text-nowrap\">true</td>
						<td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_key</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_id</td>
						<td class=\"text-nowrap\">array</td>
						<td class=\"text-nowrap\">true</td>
						<td>用户 ID 数组</td>
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
						<td>加密字符串，需要解密。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">key</td>
						<td class=\"text-nowrap\">string</td>
						<td>解密码，配合加密字符串使用，用于解码。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
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

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">解密后的结果</div>
		<div class=\"table-responsive\">
			<table class=\"table\">
				<thead>
					<tr>
						<th class=\"text-nowrap\">名称</th>
						<th class=\"text-nowrap\">Base64</th>
						<th class=\"text-nowrap\">类型</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"text-nowrap\">alert</td>
						<td class=\"text-nowrap\">false</td>
						<td class=\"text-nowrap\">string</td>
						<td>返回代码，详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<p>&nbsp;</p>

	<h4>返回结果示例</h4>
	<p>
<pre><code class=\"language-javascript\">{
    &quot;alert&quot;: &quot;y010102&quot; //返回代码
}</code></pre>
	</p>

	<hr>

	<h3>检查用户名</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于检查用户名是否可以注册。</p>

	<p class=\"text-success\">URL</p>
	<p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=user</span></p>

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
						<th class=\"text-nowrap\">名称</th>
						<th class=\"text-nowrap\">类型</th>
						<th class=\"text-nowrap\">必须</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"text-nowrap\">act_get</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>接口调用动作，值只能为 check_name。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_id</td>
						<td class=\"text-nowrap\">int</td>
						<td class=\"text-nowrap\">true</td>
						<td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_key</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_name</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>用户名</td>
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
						<td>加密字符串，需要解密。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">key</td>
						<td class=\"text-nowrap\">string</td>
						<td>解密码，配合加密字符串使用，用于解码。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
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

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">解密后的结果</div>
		<div class=\"table-responsive\">
			<table class=\"table\">
				<thead>
					<tr>
						<th class=\"text-nowrap\">名称</th>
						<th class=\"text-nowrap\">Base64</th>
						<th class=\"text-nowrap\">类型</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"text-nowrap\">alert</td>
						<td class=\"text-nowrap\">false</td>
						<td class=\"text-nowrap\">string</td>
						<td>返回代码，详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<p>&nbsp;</p>

	<h4>返回结果示例</h4>
	<p>
<pre><code class=\"language-javascript\">{
    &quot;alert&quot;: &quot;y010102&quot; //返回代码
}</code></pre>
	</p>

	<hr>

	<h3>检查 E-mail</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于检查 E-mail 是否可以注册。</p>

	<p class=\"text-success\">URL</p>
	<p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=user</span></p>

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
						<th class=\"text-nowrap\">名称</th>
						<th class=\"text-nowrap\">类型</th>
						<th class=\"text-nowrap\">必须</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"text-nowrap\">act_get</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>接口调用动作，值只能为 check_mail。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_id</td>
						<td class=\"text-nowrap\">int</td>
						<td class=\"text-nowrap\">true</td>
						<td>应用的 APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_key</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>应用的 APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">查看应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_mail</td>
						<td class=\"text-nowrap\">string</td>
						<td class=\"text-nowrap\">true</td>
						<td>E-mail 地址</td>
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
						<td>加密字符串，需要解密。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">key</td>
						<td class=\"text-nowrap\">string</td>
						<td>解密码，配合加密字符串使用，用于解码。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。</td>
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

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">解密后的结果</div>
		<div class=\"table-responsive\">
			<table class=\"table\">
				<thead>
					<tr>
						<th class=\"text-nowrap\">名称</th>
						<th class=\"text-nowrap\">Base64</th>
						<th class=\"text-nowrap\">类型</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"text-nowrap\">alert</td>
						<td class=\"text-nowrap\">false</td>
						<td class=\"text-nowrap\">string</td>
						<td>返回代码，详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<p>&nbsp;</p>

	<h4>返回结果示例</h4>
	<p>
<pre><code class=\"language-javascript\">{
    &quot;alert&quot;: &quot;y010102&quot; //返回代码
}</code></pre>
	</p>";