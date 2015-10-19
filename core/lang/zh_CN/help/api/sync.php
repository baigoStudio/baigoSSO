<?php
return "<h3>同步概述</h3>
	<p>如果当前应用程序在 SSO 中设置允许同步登录，那么在调用本接口时，系统会通知其他设置了同步登录的应用程序登录，以达到一点登录，所有网站登录的目的。把返回的 HTML 输出在页面中即可完成对其它应用程序的通知。</p>

	<hr>
	<a name=\"login\"></a>
	<h3>同步登录</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口使得用户在一点登录以后，在所有网站同时登录的目的。</p>

	<p class=\"text-success\">URL</p>
	<p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=sync</span></p>

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
						<td>接口调用动作，值只能为 login。</td>
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
						<td>签名字符串，需要调用签名接口来生成，详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=signature#signature\">签名接口</a>。</td>
					</tr>

					<tr>
						<td class=\"nowrap\">code</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>加密字符串，利用密文接口将下表中的“加密前数据”进行加密，详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#encode\">密文接口</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">key</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>解密码，配合加密字符串使用，用于解码。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#encode\">密文接口</a>。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<p>
		下表数据需要加密后提交。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#encode\">密文接口</a>。
	</p>

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">加密前数据</div>
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
						<td class=\"nowrap\">app_id</td>
						<td class=\"nowrap\">int</td>
						<td class=\"nowrap\">true</td>
						<td>APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">app_key</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">user_id</td>
						<td class=\"nowrap\">int</td>
						<td class=\"nowrap\">true</td>
						<td>需同步登录的用户 ID。</td>
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
						<th class=\"nowrap\">Base64</th>
						<th class=\"nowrap\">类型</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"nowrap\">alert</td>
						<td class=\"nowrap\">false</td>
						<td class=\"nowrap\">string</td>
						<td>返回代码，详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">html</td>
						<td class=\"nowrap\">true</td>
						<td class=\"nowrap\">string</td>
						<td>把返回的字符串进行 Base64 解码，然后将得到的 HTML 输出在页面中即可完成对其它应用程序的通知。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<hr>
	<a name=\"login_notice\"></a>
	<h3>同步登录通知</h3>

	<p class=\"text-success\">接口说明</p>
	<p>应用调用同步接口以后，将会直接访问“通知接口 URL”，并且在 URL 中附带通知的数据。</p>

	<p class=\"text-success\">HTTP 推送方式</p>
	<p>GET</p>

	<p class=\"text-success\">通知的验证</p>
	<p>系统在推送通知时，会附带用于验证的数据。其中 APP ID 与 APP KEY 需要解密后才能得到，得到后请与当前应用进行对比验证，time、random、signature 用于验证签名，详情请查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=signature#verify\">签名接口</a>。</p>

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
						<td class=\"nowrap\">mod</td>
						<td class=\"nowrap\">string</td>
						<td>通知组件名称，值为 sync。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">act_get</td>
						<td class=\"nowrap\">string</td>
						<td>通知动作，值为 login。</td>
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
	</div>

	<p>
		baigo SSO 所有通知接口均推送加密字符串，真正内容需要调用密文接口进行解密。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。“通知接口 URL”接收到通知以后，可以根据实际情况，在本地执行一些必要的程序。
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
						<td class=\"nowrap\">app_id</td>
						<td class=\"nowrap\">true</td>
						<td class=\"nowrap\">int</td>
						<td>APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">app_key</td>
						<td class=\"nowrap\">true</td>
						<td class=\"nowrap\">string</td>
						<td>APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<hr>
	<a name=\"logout\"></a>
	<h3>同步登出</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口使得用户在一点登出以后，在所有网站同时登出的目的。把返回的 HTML 输出在页面中即可完成对其它应用程序的通知。</p>

	<p class=\"text-success\">URL</p>
	<p><span class=\"text-primary\">http://www.domain.com/api/api.php?mod=sync</span></p>

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
						<td>接口调用动作，值只能为 logout。</td>
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
						<td>签名字符串，需要调用签名接口来生成，详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=signature#signature\">签名接口</a>。</td>
					</tr>

					<tr>
						<td class=\"nowrap\">code</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>加密字符串，利用密文接口将下表中的“加密前数据”进行加密，详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#encode\">密文接口</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">key</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>解密码，配合加密字符串使用，用于解码。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#encode\">密文接口</a>。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<p>
		下表数据需要加密后提交。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#encode\">密文接口</a>。
	</p>

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">加密前数据</div>
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
						<td class=\"nowrap\">app_id</td>
						<td class=\"nowrap\">int</td>
						<td class=\"nowrap\">true</td>
						<td>APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">app_key</td>
						<td class=\"nowrap\">string</td>
						<td class=\"nowrap\">true</td>
						<td>APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">user_id</td>
						<td class=\"nowrap\">int</td>
						<td class=\"nowrap\">true</td>
						<td>需同步登出的用户 ID。</td>
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
						<th class=\"nowrap\">Base64</th>
						<th class=\"nowrap\">类型</th>
						<th>具体描述</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class=\"nowrap\">alert</td>
						<td class=\"nowrap\">false</td>
						<td class=\"nowrap\">string</td>
						<td>返回代码，详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=alert\">返回代码</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">html</td>
						<td class=\"nowrap\">true</td>
						<td class=\"nowrap\">string</td>
						<td>把返回的字符串进行 Base64 解码，然后将得到的 HTML 输出在页面中即可完成对其它应用程序的通知。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<hr>
	<a name=\"logout_notice\"></a>
	<h3>同步登出通知</h3>

	<p class=\"text-success\">接口说明</p>
	<p>应用调用同步接口以后，将会直接访问“通知接口 URL”，并且在 URL 中附带通知的数据。</p>

	<p class=\"text-success\">HTTP 推送方式</p>
	<p>GET</p>

	<p class=\"text-success\">通知的验证</p>
	<p>系统在推送通知时，会附带用于验证的数据。其中 APP ID 与 APP KEY 需要解密后才能得到，得到后请与当前应用进行对比验证，time、random、signature 用于验证签名，详情请查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=signature#verify\">签名接口</a>。</p>

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
						<td class=\"nowrap\">mod</td>
						<td class=\"nowrap\">string</td>
						<td>通知组件名称，值为 sync。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">act_get</td>
						<td class=\"nowrap\">string</td>
						<td>通知动作，值为 logout。</td>
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
	</div>

	<p>
		baigo SSO 所有通知接口均推送加密字符串，真正内容需要调用密文接口进行解密。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。“通知接口 URL”接收到通知以后，可以根据实际情况，在本地执行一些必要的程序。
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
						<td class=\"nowrap\">app_id</td>
						<td class=\"nowrap\">true</td>
						<td class=\"nowrap\">int</td>
						<td>APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"nowrap\">app_key</td>
						<td class=\"nowrap\">true</td>
						<td class=\"nowrap\">string</td>
						<td>APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>";