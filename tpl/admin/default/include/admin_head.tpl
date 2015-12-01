{* admin_head.tpl 管理后台头部，包含菜单 *}
{include "{$smarty.const.BG_PATH_TPL}admin/default/include/html_head.tpl"}

	<header class="navbar navbar-inverse navbar-static-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
					<span class="sr-only">nav</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				{if $config.ui == "default"}
					<a class="navbar-brand" href="{$smarty.const.PRD_SSO_URL}" target="_blank">
						<img src="{$smarty.const.BG_URL_STATIC}admin/{$config.ui}/image/admin_logo.png">
					</a>
				{else}
					<a class="navbar-brand" href="#">
						<img src="{$smarty.const.BG_URL_STATIC}admin/{$config.ui}/image/admin_logo.png">
					</a>
				{/if}
			</div>
			<nav class="collapse navbar-collapse bs-navbar-collapse">
				<ul class="nav navbar-nav">
					<li><a href="{$smarty.const.BG_URL_ADMIN}ctl.php">{$smarty.const.BG_SITE_NAME}</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown{if isset($cfg.menu_active) && $cfg.menu_active == "profile"} active{/if}">
						<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=profile&act_get=info" class="dropdown-toggle" data-toggle="dropdown">
							<span class="glyphicon glyphicon-user"></span>
							{if isset($tplData.adminLogged.admin_name)}
								{$tplData.adminLogged.admin_name}
							{/if}
							{if isset($tplData.adminLogged.admin_nick)}
								[ {$tplData.adminLogged.admin_nick} ]
							{/if}
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
    						<li{if $tplData.act_get == "info"} class="active"{/if}>
        						<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=profile&act_get=info">
        							{$lang.href.infoModi}
        						</a>
    						</li>
    						<li{if $tplData.act_get == "pass"} class="active"{/if}>
        						<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=profile&act_get=pass">
        							{$lang.href.passModi}
        						</a>
    						</li>
						</ul>
					</li>
					<li class="dropdown{if isset($cfg.menu_active) && $cfg.menu_active == "opt"} active{/if}">
						<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=profile&act_get=info" class="dropdown-toggle" data-toggle="dropdown">
							<span class="glyphicon glyphicon-cog"></span>
							{$lang.href.opt}
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
    						<li{if $tplData.act_get == "dbconfig"} class="active"{/if}>
        						<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=opt&act_get=dbconfig">
        							{$lang.page.installDbConfig}
        						</a>
    						</li>
            				{foreach $opt as $key_opt=>$value_opt}
        						<li{if $tplData.act_get == $key_opt} class="active"{/if}>
            						<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=opt&act_get={$key_opt}">
            							{$value_opt.title}
            						</a>
        						</li>
    						{/foreach}
						</ul>
					</li>
					<li>
						<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=logon&act_get=logout">
							<span class="glyphicon glyphicon-off"></span>
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