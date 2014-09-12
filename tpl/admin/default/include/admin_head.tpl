{* admin_head.tpl 管理后台头部，包含菜单 *}
{include "include/html_head.tpl"}

<body>

	<header class="navbar navbar-inverse navbar-static-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
					<span class="sr-only">nav</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="{$smarty.const.PRD_SSO_URL}" target="_blank">
					<img alt="baigoSSO" src="{$smarty.const.BG_URL_STATIC_ADMIN}default/image/admin_logo.png">
				</a>
			</div>
			<nav class="collapse navbar-collapse bs-navbar-collapse">
				<ul class="nav navbar-nav">
					<li><a href="{$smarty.const.BG_URL_ADMIN}ctl.php">{$smarty.const.BG_SITE_NAME}</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=profile&act_get=info">
							<span class="glyphicon glyphicon-user"></span>
							{$tplData.adminLogged.admin_name}
							{if $tplData.adminLogged.admin_note}[ {$tplData.adminLogged.admin_note} ]{/if}
						</a>
					</li>
					<li>
						<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=profile&act_get=pass">
							<span class="glyphicon glyphicon-lock"></span>
							{$lang.href.passModi}
						</a>
					</li>
					<li>
						<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=logon&act_get=logout">
							<span class="glyphicon glyphicon-log-out"></span>
							{$lang.href.logout}
						</a>
					</li>
				</ul>
			</nav>
		</div>
	</header>

	<div class="container-fluid">
		<ol class="breadcrumb">
			<li>
				<a href="{$smarty.const.BG_URL_ADMIN}ctl.php">
					<span class="glyphicon glyphicon-home"></span>
					{$lang.page.admin}
				</a>
			</li>