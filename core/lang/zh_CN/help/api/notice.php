<?php
return "<h3>通知概述</h3>
	<p>系统在执行一些特定操作的时候，会根据应用的设置，向指定的“通知接口 URL”推送通知，“通知接口 URL”接收到通知以后，可以根据实际情况，在本地执行一些必要的程序。</p>

	<hr>

	<h3>通知的验证</h3>
	<p>系统在推送通知时，会附带用于验证的数据，结构如下。其中 APP ID 与 APP KEY 请与当前应用进行对比验证，time、random、signature 用于验证签名，详情请查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=signature#verify\">签名接口</a>。</p>

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">数据结构</div>
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
						<td class=\"nowrap\">time</td>
						<td class=\"nowrap\">string</td>
						<td>Unix 时间戳</td>
					</tr>
					<tr>
						<td class=\"nowrap\">random</td>
						<td class=\"nowrap\">string</td>
						<td>随机字符串</td>
					</tr>
					<tr>
						<td class=\"nowrap\">signature</td>
						<td class=\"nowrap\">string</td>
						<td>签名字符串</td>
					</tr>
					<tr>
						<td class=\"nowrap\">app_id</td>
						<td class=\"nowrap\">int</td>
						<td>APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">app_key</td>
						<td class=\"nowrap\">string</td>
						<td>APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<hr>

	<a name=\"reg\"></a>
	<h3>用户注册</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于通知各应用有新用户注册成功。</p>

	<p class=\"text-success\">HTTP 推送方式</p>
	<p>POST</p>

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">数据结构</div>
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
						<td class=\"nowrap\">act_post</td>
						<td class=\"nowrap\">string</td>
						<td>通知动作，值为 reg。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">time</td>
						<td class=\"nowrap\">string</td>
						<td>Unix 时间戳</td>
					</tr>
					<tr>
						<td class=\"nowrap\">random</td>
						<td class=\"nowrap\">string</td>
						<td>随机字符串</td>
					</tr>
					<tr>
						<td class=\"nowrap\">signature</td>
						<td class=\"nowrap\">string</td>
						<td>签名字符串</td>
					</tr>
					<tr>
						<td class=\"nowrap\">app_id</td>
						<td class=\"nowrap\">int</td>
						<td>APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">app_key</td>
						<td class=\"nowrap\">string</td>
						<td>APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
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

	<p>
		baigo SSO 所有通知接口均推送加密字符串，真正内容需要调用密文接口进行解密。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
	</p>

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
						<td class=\"nowrap\">user_id</td>
						<td class=\"nowrap\">true</td>
						<td class=\"nowrap\">int</td>
						<td>用户 ID</td>
					</tr>
					<tr>
						<td class=\"nowrap\">user_name</td>
						<td class=\"nowrap\">true</td>
						<td class=\"nowrap\">string</td>
						<td>用户名</td>
					</tr>
					<tr>
						<td class=\"nowrap\">user_mail</td>
						<td class=\"nowrap\">true</td>
						<td class=\"nowrap\">string</td>
						<td>E-mail</td>
					</tr>
					<tr>
						<td class=\"nowrap\">user_nick</td>
						<td class=\"nowrap\">true</td>
						<td class=\"nowrap\">string</td>
						<td>昵称</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<hr>

	<a name=\"edit\"></a>
	<h3>编辑用户资料</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于通知各应用用户信息编辑成功。</p>

	<p class=\"text-success\">HTTP 推送方式</p>
	<p>POST</p>

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">数据结构</div>
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
						<td class=\"nowrap\">act_post</td>
						<td class=\"nowrap\">string</td>
						<td>通知动作，值为 edit。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">time</td>
						<td class=\"nowrap\">string</td>
						<td>Unix 时间戳</td>
					</tr>
					<tr>
						<td class=\"nowrap\">random</td>
						<td class=\"nowrap\">string</td>
						<td>随机字符串</td>
					</tr>
					<tr>
						<td class=\"nowrap\">signature</td>
						<td class=\"nowrap\">string</td>
						<td>签名字符串</td>
					</tr>
					<tr>
						<td class=\"nowrap\">app_id</td>
						<td class=\"nowrap\">int</td>
						<td>APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">app_key</td>
						<td class=\"nowrap\">string</td>
						<td>APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
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

	<p>
		baigo SSO 所有通知接口均推送加密字符串，真正内容需要调用密文接口进行解密。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
	</p>

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
						<td class=\"nowrap\">user_id</td>
						<td class=\"nowrap\">true</td>
						<td class=\"nowrap\">int</td>
						<td>用户 ID</td>
					</tr>
					<tr>
						<td class=\"nowrap\">user_name</td>
						<td class=\"nowrap\">true</td>
						<td class=\"nowrap\">string</td>
						<td>用户名</td>
					</tr>
					<tr>
						<td class=\"nowrap\">user_mail</td>
						<td class=\"nowrap\">true</td>
						<td class=\"nowrap\">string</td>
						<td>E-mail</td>
					</tr>
					<tr>
						<td class=\"nowrap\">user_nick</td>
						<td class=\"nowrap\">true</td>
						<td class=\"nowrap\">string</td>
						<td>昵称</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<hr>

	<a name=\"del\"></a>
	<h3>删除用户</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于删除已注册用户。</p>

	<p class=\"text-success\">HTTP 推送方式</p>
	<p>POST</p>

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">数据结构</div>
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
						<td class=\"nowrap\">act_post</td>
						<td class=\"nowrap\">string</td>
						<td>通知动作，值为 del。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">time</td>
						<td class=\"nowrap\">string</td>
						<td>Unix 时间戳</td>
					</tr>
					<tr>
						<td class=\"nowrap\">random</td>
						<td class=\"nowrap\">string</td>
						<td>随机字符串</td>
					</tr>
					<tr>
						<td class=\"nowrap\">signature</td>
						<td class=\"nowrap\">string</td>
						<td>签名字符串</td>
					</tr>
					<tr>
						<td class=\"nowrap\">app_id</td>
						<td class=\"nowrap\">int</td>
						<td>APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">app_key</td>
						<td class=\"nowrap\">string</td>
						<td>APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
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

	<p>
		baigo SSO 所有通知接口均推送加密字符串，真正内容需要调用密文接口进行解密。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
	</p>

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
						<td class=\"nowrap\">user_ids</td>
						<td class=\"nowrap\">false</td>
						<td class=\"nowrap\">array</td>
						<td>用户 ID 数组</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>";