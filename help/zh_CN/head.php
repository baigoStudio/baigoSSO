	<header class="navbar navbar-inverse navbar-static-top">
		<div class="container">
			<div class="navbar-header">
				<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="./">
					<img alt="baigo SSO" src="./image/logo_baigo_sso.png">
				</a>
			</div>
			<nav class="collapse navbar-collapse bs-navbar-collapse">
				<ul class="nav navbar-nav navbar-right">
					<li<?php if ($_str_mod == "intro") { ?> class="active"<?php } ?>><a href="./?lang=zh_CN">简介</a></li>
					<li class="dropdown<?php if ($_str_mod == "install" || $_str_mod == "upgrade") { ?> active<?php } ?>">
						<a href="./?lang=zh_CN&mod=install" class="dropdown-toggle" data-toggle="dropdown">
							安装
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li<?php if ($_str_mod == "install") { ?> class="active"<?php } ?>>
								<a href="./?lang=zh_CN&mod=install">安装</a>
							</li>
							<li<?php if ($_str_mod == "upgrade") { ?> class="active"<?php } ?>>
								<a href="./?lang=zh_CN&mod=upgrade">升级</a>
							</li>
						</ul>
					</li>
					<li<?php if ($_str_mod == "help") { ?> class="active"<?php } ?>><a href="./?lang=zh_CN&mod=help">使用帮助</a></li>
					<li<?php if ($_str_mod == "api") { ?> class="active"<?php } ?>><a href="./?lang=zh_CN&mod=api">API 接口</a></li>
					<li><a href="http://www.baigo.net/Products/baigoSSO/download.php" target="_blank">下载</a></li>
				</ul>
			</nav>
		</div>
	</header>
