<?php
return "<h3>通知概述</h3>
	<p>系统在执行一些特定操作的时候，会根据应用的设置，向指定的“通知接口 URL”推送通知，“通知接口 URL”接收到通知以后，可以根据实际情况，在本地执行一些必要的程序。</p>

	<hr>

	<a name=\"test\"></a>
	<h3>通知测试</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于测试通知接口是否正常。</p>

	<p class=\"text-success\">HTTP 推送方式</p>
	<p>GET</p>

	<p class=\"text-success\">通知的验证</p>
	<p>系统在推送通知时，会在“通知接口 URL”中附加用于验证的数据，结构如下。time、random、signature 用于验证签名，详情请查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=signature#verify\">签名接口</a>，确认签名正确的情况下，进行下一步操作。</p>

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">数据结构</div>
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
						<td class=\"text-nowrap\">mod</td>
						<td class=\"text-nowrap\">string</td>
						<td>通知组件名称，在通知接口中，值均为 notice。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">act_get</td>
						<td class=\"text-nowrap\">string</td>
						<td>通知动作，值为 test。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_id</td>
						<td class=\"text-nowrap\">int</td>
						<td>APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_key</td>
						<td class=\"text-nowrap\">string</td>
						<td>APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">time</td>
						<td class=\"text-nowrap\">string</td>
						<td>Unix 时间戳</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">random</td>
						<td class=\"text-nowrap\">string</td>
						<td>随机字符串</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">signature</td>
						<td class=\"text-nowrap\">string</td>
						<td>签名字符串</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">echostr</td>
						<td class=\"text-nowrap\">string</td>
						<td>输出字符串，直接将该字符串输出便可确认该应用的通知接口正常。</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<p>&nbsp;</p>

	<h4>通知数据示例</h4>
	<p>PHP 通过 <code>print_r(\$_GET);</code></p>

	<p>
<pre><code class=\"language-php\">array(
    &quot;mod&quot;       =&gt; &quot;notice&quot;, //组件名称
    &quot;act_get&quot;   =&gt; &quot;test&quot;, //通知动作
    &quot;app_id&quot;    =&gt; 1, //APP ID
    &quot;app_key&quot;   =&gt; &quot;sdferi84hkdfufdsERTeugroe7treie&quot;, //APP KEY
    &quot;time&quot;      =&gt; 1446527788, //Unix 时间戳
    &quot;random&quot;    =&gt; &quot;sdfwerwer&quot;, //随机字符串
    &quot;signature&quot; =&gt; &quot;sdfdsfsdrewerwugroe7treie&quot;, //签名
    &quot;echostr&quot;   =&gt; &quot;i84hksdfuw98&amp;*Ijiiwer&quot; //输出字符串
);</code></pre>
	</p>

	<hr>

	<a name=\"reg\"></a>
	<h3>用户注册</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于通知各应用有新用户注册成功。</p>

	<p class=\"text-success\">HTTP 推送方式</p>
	<p>POST</p>

	<p class=\"text-success\">通知的验证</p>
	<p>系统在推送通知时，会在“通知接口 URL”中附加用于验证的数据，结构如下。time、random、signature 用于验证签名，详情请查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=signature#verify\">签名接口</a>，确认签名正确的情况下，进行下一步操作，如：向数据库中插入数据等。</p>

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">数据结构</div>
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
						<td class=\"text-nowrap\">mod</td>
						<td class=\"text-nowrap\">string</td>
						<td>通知组件名称，在通知接口中，值均为 notice。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">act_post</td>
						<td class=\"text-nowrap\">string</td>
						<td>通知动作，值为 reg。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_id</td>
						<td class=\"text-nowrap\">int</td>
						<td>APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_key</td>
						<td class=\"text-nowrap\">string</td>
						<td>APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">time</td>
						<td class=\"text-nowrap\">string</td>
						<td>Unix 时间戳</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">random</td>
						<td class=\"text-nowrap\">string</td>
						<td>随机字符串</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">signature</td>
						<td class=\"text-nowrap\">string</td>
						<td>签名字符串</td>
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

	<p>&nbsp;</p>

	<h4>通知数据示例</h4>
	<p>PHP 通过 <code>print_r(\$_POST);</code></p>

	<p>
<pre><code class=\"language-php\">array(
    &quot;mod&quot;         =&gt; &quot;notice&quot;, //组件名称
    &quot;act_get&quot;     =&gt; &quot;reg&quot;, //通知动作
    &quot;app_id&quot;      =&gt; 1, //APP ID
    &quot;app_key&quot;     =&gt; &quot;sdferi84hkdfufdsERTeugroe7treie&quot;, //APP KEY
    &quot;time&quot;        =&gt; 1446527788, //Unix 时间戳
    &quot;random&quot;      =&gt; &quot;sdfwerwer&quot;, //随机字符串
    &quot;signature&quot;   =&gt; &quot;sdfdsfsdrewerwugroe7treie&quot;, //签名
    &quot;code&quot;        =&gt; &quot;CSMEIFh7AHYBOFIlXQwAaQE0UXENawF2WUxXUQNFVD4Ac1R%2BUSUFdQgnBmYMcARbAT5RMlprBjZdJQdvBSRQXgkPBEhYZAAnAXFSdV0mAHMBNVEhDQ4BOVliV2wDbFQhAGtUcFElBSwIdgZ2DHEEYQEiUQxaaAY6XWQHPgUkUD0JegRbWFkATwE3UnVdfwAiASVRIA00ASZZXFdxA2lUbgA0VHBRPQUiCBkGVwxTBDQBHVEpWkMGYF1KBxEFR1A1CRcEU1gzADgBf1J7XXEAdQEjUTYNIwELWXdXbANtVGYADlQ%2BUWgFZwg9Bm0MIAQ%2BAXJRHlpSBgJdNwcYBXxQQglrBE9YSgBIAWhSGV0kAD0BbVFxDX0Bdll2V3YDZVRxAA5UO1F3BSIIbgYhDE8EUAEZUStaSgY5XUIHYAVJUFQJbAR6WEMAVgFpUi9dHQBqAR1Rbg1zASk%3D&quot;, //加密字符串
    &quot;key&quot;         =&gt; &quot;tLUwyt&quot;, //解密码
    &quot;prd_sso_ver&quot; =&gt; &quot;1.1.1&quot;, //SSO 版本号
    &quot;prd_sso_pub&quot; =&gt; 20150923 //SSO 版本发布时间
);</code></pre>
	</p>

	<p>&nbsp;</p>

	<p>
		baigo SSO 所有通知接口均推送加密字符串，真正内容需要调用密文接口进行解密。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
	</p>

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
						<td>E-mail</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_nick</td>
						<td class=\"text-nowrap\">true</td>
						<td class=\"text-nowrap\">string</td>
						<td>昵称</td>
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
    &quot;user_nick&quot;: &quot;Zm9uZQ==&quot; //昵称
}</code></pre>
	</p>

	<hr>

	<a name=\"edit\"></a>
	<h3>编辑用户资料</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于通知各应用用户信息编辑成功。</p>

	<p class=\"text-success\">HTTP 推送方式</p>
	<p>POST</p>

	<p class=\"text-success\">通知的验证</p>
	<p>系统在推送通知时，会在“通知接口 URL”中附加用于验证的数据，结构如下。time、random、signature 用于验证签名，详情请查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=signature#verify\">签名接口</a>，确认签名正确的情况下，进行下一步操作，如：编辑数据库中的数据等。</p>

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">数据结构</div>
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
						<td class=\"text-nowrap\">mod</td>
						<td class=\"text-nowrap\">string</td>
						<td>通知组件名称，在通知接口中，值均为 notice。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">act_post</td>
						<td class=\"text-nowrap\">string</td>
						<td>通知动作，值为 edit。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_id</td>
						<td class=\"text-nowrap\">int</td>
						<td>APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_key</td>
						<td class=\"text-nowrap\">string</td>
						<td>APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">time</td>
						<td class=\"text-nowrap\">string</td>
						<td>Unix 时间戳</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">random</td>
						<td class=\"text-nowrap\">string</td>
						<td>随机字符串</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">signature</td>
						<td class=\"text-nowrap\">string</td>
						<td>签名字符串</td>
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

	<p>&nbsp;</p>

	<h4>通知数据示例</h4>
	<p>PHP 通过 <code>print_r(\$_POST);</code></p>

	<p>
<pre><code class=\"language-php\">array(
    &quot;mod&quot;         =&gt; &quot;notice&quot;, //组件名称
    &quot;act_get&quot;     =&gt; &quot;edit&quot;, //通知动作
    &quot;app_id&quot;      =&gt; 1, //APP ID
    &quot;app_key&quot;     =&gt; &quot;sdferi84hkdfufdsERTeugroe7treie&quot;, //APP KEY
    &quot;time&quot;        =&gt; 1446527788, //Unix 时间戳
    &quot;random&quot;      =&gt; &quot;sdfwerwer&quot;, //随机字符串
    &quot;signature&quot;   =&gt; &quot;sdfdsfsdrewerwugroe7treie&quot;, //签名
    &quot;code&quot;        =&gt; &quot;CSMEIFh7AHYBOFIlXQwAaQE0UXENawF2WUxXUQNFVD4Ac1R%2BUSUFdQgnBmYMcARbAT5RMlprBjZdJQdvBSRQXgkPBEhYZAAnAXFSdV0mAHMBNVEhDQ4BOVliV2wDbFQhAGtUcFElBSwIdgZ2DHEEYQEiUQxaaAY6XWQHPgUkUD0JegRbWFkATwE3UnVdfwAiASVRIA00ASZZXFdxA2lUbgA0VHBRPQUiCBkGVwxTBDQBHVEpWkMGYF1KBxEFR1A1CRcEU1gzADgBf1J7XXEAdQEjUTYNIwELWXdXbANtVGYADlQ%2BUWgFZwg9Bm0MIAQ%2BAXJRHlpSBgJdNwcYBXxQQglrBE9YSgBIAWhSGV0kAD0BbVFxDX0Bdll2V3YDZVRxAA5UO1F3BSIIbgYhDE8EUAEZUStaSgY5XUIHYAVJUFQJbAR6WEMAVgFpUi9dHQBqAR1Rbg1zASk%3D&quot;, //加密字符串
    &quot;key&quot;         =&gt; &quot;tLUwyt&quot;, //解密码
    &quot;prd_sso_ver&quot; =&gt; &quot;1.1.1&quot;, //SSO 版本号
    &quot;prd_sso_pub&quot; =&gt; 20150923 //SSO 版本发布时间
);</code></pre>
	</p>

	<p>&nbsp;</p>

	<p>
		baigo SSO 所有通知接口均推送加密字符串，真正内容需要调用密文接口进行解密。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
	</p>

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
						<td>E-mail</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">user_nick</td>
						<td class=\"text-nowrap\">true</td>
						<td class=\"text-nowrap\">string</td>
						<td>昵称</td>
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
    &quot;user_nick&quot;: &quot;Zm9uZQ==&quot; //昵称
}</code></pre>
	</p>

	<hr>

	<a name=\"del\"></a>
	<h3>删除用户</h3>

	<p class=\"text-success\">接口说明</p>
	<p>本接口用于删除已注册用户。</p>

	<p class=\"text-success\">HTTP 推送方式</p>
	<p>POST</p>

	<p class=\"text-success\">通知的验证</p>
	<p>系统在推送通知时，会在“通知接口 URL”中附加用于验证的数据，结构如下。time、random、signature 用于验证签名，详情请查看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=signature#verify\">签名接口</a>，确认签名正确的情况下，进行下一步操作，如：删除数据库中的数据等。</p>

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">数据结构</div>
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
						<td class=\"text-nowrap\">mod</td>
						<td class=\"text-nowrap\">string</td>
						<td>通知组件名称，在通知接口中，值均为 notice。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">act_post</td>
						<td class=\"text-nowrap\">string</td>
						<td>通知动作，值为 del。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_id</td>
						<td class=\"text-nowrap\">int</td>
						<td>APP ID，后台创建应用时生成的 ID。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">app_key</td>
						<td class=\"text-nowrap\">string</td>
						<td>APP KEY，后台创建应用时生成的 KEY。详情查看 <a href=\"{BG_URL_HELP}ctl.php?mod=admin&act_get=app#show\">应用</a>。</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">time</td>
						<td class=\"text-nowrap\">string</td>
						<td>Unix 时间戳</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">random</td>
						<td class=\"text-nowrap\">string</td>
						<td>随机字符串</td>
					</tr>
					<tr>
						<td class=\"text-nowrap\">signature</td>
						<td class=\"text-nowrap\">string</td>
						<td>签名字符串</td>
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

	<p>&nbsp;</p>

	<h4>通知数据示例</h4>
	<p>PHP 通过 <code>print_r(\$_POST);</code></p>

	<p>
<pre><code class=\"language-php\">array(
    &quot;mod&quot;         =&gt; &quot;notice&quot;, //组件名称
    &quot;act_get&quot;     =&gt; &quot;del&quot;, //通知动作
    &quot;app_id&quot;      =&gt; 1, //APP ID
    &quot;app_key&quot;     =&gt; &quot;sdferi84hkdfufdsERTeugroe7treie&quot;, //APP KEY
    &quot;time&quot;        =&gt; 1446527788, //Unix 时间戳
    &quot;random&quot;      =&gt; &quot;sdfwerwer&quot;, //随机字符串
    &quot;signature&quot;   =&gt; &quot;sdfdsfsdrewerwugroe7treie&quot;, //签名
    &quot;code&quot;        =&gt; &quot;CSMEIFh7AHYBOFIlXQwAaQE0UXENawF2WUxXUQNFVD4Ac1R%2BUSUFdQgnBmYMcARbAT5RMlprBjZdJQdvBSRQXgkPBEhYZAAnAXFSdV0mAHMBNVEhDQ4BOVliV2wDbFQhAGtUcFElBSwIdgZ2DHEEYQEiUQxaaAY6XWQHPgUkUD0JegRbWFkATwE3UnVdfwAiASVRIA00ASZZXFdxA2lUbgA0VHBRPQUiCBkGVwxTBDQBHVEpWkMGYF1KBxEFR1A1CRcEU1gzADgBf1J7XXEAdQEjUTYNIwELWXdXbANtVGYADlQ%2BUWgFZwg9Bm0MIAQ%2BAXJRHlpSBgJdNwcYBXxQQglrBE9YSgBIAWhSGV0kAD0BbVFxDX0Bdll2V3YDZVRxAA5UO1F3BSIIbgYhDE8EUAEZUStaSgY5XUIHYAVJUFQJbAR6WEMAVgFpUi9dHQBqAR1Rbg1zASk%3D&quot;, //加密字符串
    &quot;key&quot;         =&gt; &quot;tLUwyt&quot;, //解密码
    &quot;prd_sso_ver&quot; =&gt; &quot;1.1.1&quot;, //SSO 版本号
    &quot;prd_sso_pub&quot; =&gt; 20150923 //SSO 版本发布时间
);</code></pre>
	</p>

	<p>&nbsp;</p>

	<p>
		baigo SSO 所有通知接口均推送加密字符串，真正内容需要调用密文接口进行解密。详情请看 <a href=\"{BG_URL_HELP}ctl.php?mod=api&act_get=code#decode\">密文接口</a>。
	</p>

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
						<td class=\"text-nowrap\">user_ids</td>
						<td class=\"text-nowrap\">false</td>
						<td class=\"text-nowrap\">array</td>
						<td>用户 ID 数组</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<p>&nbsp;</p>

	<h4>返回结果示例</h4>
	<p>
<pre><code class=\"language-javascript\">{
    &quot;user_ids&quot;: [ 1, 2, 3 ] //用户 ID
}</code></pre>
	</p>";