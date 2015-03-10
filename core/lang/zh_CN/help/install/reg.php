<?php
return "<h3>注册设置</h3>

	<p>
		<img src=\"{images}reg.jpg\" class=\"img-responsive\">
	</p>

	<div class=\"panel panel-default\">
		<div class=\"panel-heading\">填写说明</div>
		<div class=\"panel-body\">
			<h4 class=\"text-success\">强制输入 E-mail</h4>
			<p>如此项开启，用户在注册时必须输入 E-mail 地址。</p>

			<h4 class=\"text-success\">允许 E-mail 地址重复</h4>
			<p>如选择是，则允许用户注册是，重复使用 E-mail 地址。</p>

			<h4 class=\"text-success\">允许注册的 E-mail</h4>
			<p>允许注册的 E-mail 地址，只需要填写域名部分，每行一个域名，如：<mark>@hotmail.com</mark>，此时，只有 <mark>@hotmail.com</mark> 为后缀的 E-mail 地址才被 <mark>允许</mark> 注册。</p>

			<h4 class=\"text-success\">禁止注册的 E-mail</h4>
			<p>禁止注册的 E-mail 地址，只需要填写域名部分，每行一个域名，如：<mark>@hotmail.com</mark>，此时，<mark>@hotmail.com</mark> 为后缀的 E-mail 地址 <mark>禁止</mark> 注册。</p>

			<h4 class=\"text-success\">禁止注册的用户名</h4>
			<p>禁止注册的用户名，可使用通配符 <mark>*</mark>，每行一个用户名，如：<mark>*版主*</mark>，此时，只要用户名中含有 <mark>版主</mark> 两字时，<mark>禁止</mark> 注册。</p>
		</div>
	</div>

	<p>
		填写完毕，点击保存，提交成功后点击下一步。
	</p>";
