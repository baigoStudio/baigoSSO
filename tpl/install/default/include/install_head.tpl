{include "{$smarty.const.BG_PATH_TPL}install/default/include/html_head.tpl" cfg=$cfg}

	<div class="container global">

		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<div class="navbar-header">
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
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle {if $tplData.errCount > 0}disabled{/if}" data-toggle="dropdown">
							{$lang.btn.jump}
							<span class="caret"></span>
						</a>
						{include "{$smarty.const.BG_PATH_TPL}install/default/include/install_menu.tpl" cfg=$cfg}
					</li>
				</ul>
			</div>
		</nav>

		<div class="panel panel-success">
			<div class="panel-heading">
				<h4>{$lang.page.install} <span class="label label-success">{$cfg.sub_title}</span></h4>
			</div>

			<div class="panel-body">
