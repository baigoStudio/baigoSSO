<!DOCTYPE html>
<html lang="{$config.lang|truncate:2:''}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>{$tplData.helpConfig.title}</title>

	<!--jQuery 库-->
	<script src="{$smarty.const.BG_URL_STATIC}js/jquery.min.js" type="text/javascript"></script>

	<!--bootstrap-->
	<link href="{$smarty.const.BG_URL_STATIC}js/bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">
	<link href="{$smarty.const.BG_URL_STATIC}js/prism/prism.css" type="text/css" rel="stylesheet">
	<link href="{$smarty.const.BG_URL_STATIC}help/{$config.ui}/css/help.css" type="text/css" rel="stylesheet">
</head>

<body>

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
					<img alt="baigo SSO" src="{$smarty.const.BG_URL_STATIC}admin/{$config.ui}/image/admin_logo.png">
				</a>
			</div>
			<nav class="collapse navbar-collapse bs-navbar-collapse">
				<ul class="nav navbar-nav navbar-right">
					{foreach $tplData.helpConfig.menu as $key=>$value}
						{if isset($value.sub)}
							<li class="dropdown{if $tplData.config.active == $key} active{/if}">
								<a href="{$smarty.const.BG_URL_HELP}ctl.php?mod={$key}" class="dropdown-toggle" data-toggle="dropdown">
									{$value.title}
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									{foreach $value.sub as $key_sub=>$value_sub}
										<li{if $tplData.mod == $key_sub} class="active"{/if}>
											<a href="{$smarty.const.BG_URL_HELP}ctl.php?mod={$key_sub}">{$value_sub}</a>
										</li>
									{/foreach}
								</ul>
							</li>
						{else}
							<li{if $tplData.config.active == $key} class="active"{/if}><a href="{$smarty.const.BG_URL_HELP}ctl.php?mod={$key}">{$value.title}</a></li>
						{/if}
					{/foreach}
				</ul>
			</nav>
		</div>
	</header>


	<div class="container">
		<h2 class="page-header">{$tplData.config.title}</h2>
		<div class="row">
			<div class="col-md-10">
				{$tplData.content}
				{if $tplData.mod == "api" && $tplData.act_get == "alert"}
					<div class="panel panel-default">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th class="text-nowrap">代码</th>
										<th>具体描述</th>
									</tr>
								</thead>
								<tbody>
									{foreach $alert as $key=>$value}
										<tr>
											<td>{$key}</td>
											<td>{$value}</td>
										</tr>
									{/foreach}
								</tbody>
							</table>
						</div>
					</div>
				{/if}
			</div>
			<div class="col-md-2">
				<ul class="nav nav-pills nav-stacked nav_sso">
					{foreach $tplData.config.menu as $key=>$value}
						<li{if $tplData.act_get == $key} class="active"{/if}><a href="{$smarty.const.BG_URL_HELP}ctl.php?mod={$tplData.mod}&act_get={$key}">{$value}</a></li>
					{/foreach}
				</ul>
			</div>
		</div>
	</div>

	<footer class="container">
		<hr>
		<ul class="list-inline">
			{if $config.ui == "default"}
				<li><a href="http://www.baigo.net/" target="_blank">baigo Studio</a></li>
				<li><a href="http://www.baigo.net/cms/" target="_blank">baigo CMS</a></li>
				<li><a href="http://www.baigo.net/sso/" target="_blank">baigo SSO</a></li>
				<li><a href="http://www.baigo.net/ads/" target="_blank">baigo ADS</a></li>
			{else}
				<li>{$config.ui} SSO</li>
			{/if}
		</ul>
	</footer>

	<!--bootstrap-->
	<script src="{$smarty.const.BG_URL_STATIC}js/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="{$smarty.const.BG_URL_STATIC}js/prism/prism.min.js" type="text/javascript"></script>

    <!-- {$smarty.const.PRD_SSO_POWERED} {if $config.ui == "default"}{$smarty.const.PRD_SSO_NAME}{else}{$config.ui} SSO{/if} {$smarty.const.PRD_SSO_VER} -->

</body>
</html>
