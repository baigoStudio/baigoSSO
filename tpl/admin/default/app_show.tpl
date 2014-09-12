{* admin_form.tpl 管理员编辑界面 *}
{$cfg = [
	title          => "{$adminMod.app.main.title} - {$lang.page.detail}",
	menu_active    => "app",
	sub_active     => "list"
]}

{include "include/admin_head.tpl" cfg=$cfg}

	<li><a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=app&act_get=list">{$adminMod.app.main.title}</a></li>
	<li>{$lang.page.detail}</li>

	{include "include/admin_left.tpl" cfg=$cfg}

	<div class="form-group">
		<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=app&act_get=list">
			<span class="glyphicon glyphicon-chevron-left"></span>
			{$lang.href.back}
		</a>
	</div>

	<div class="row">
		<div class="col-md-9">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="form-group">
						<label class="control-label static_label">{$lang.label.appName}</label>
						<p class="form-control-static static_input">{$tplData.appRow.app_name}</p>
					</div>

					<div class="form-group">
						<label class="control-label static_label">{$lang.label.apiUrl}</label>
						<p class="form-control-static">{$smarty.const.BG_SITE_URL}{$smarty.const.BG_URL_API}api.php</p>
					</div>

					<div class="form-group">
						<label class="control-label static_label">{$lang.label.appId}</label>
						<p class="form-control-static static_input">{$tplData.appRow.app_id}</p>
					</div>

					<div class="form-group">
						<label class="control-label static_label">{$lang.label.appKey}</label>
						<p class="form-control-static">{$tplData.appRow.app_key}</p>
					</div>

					<div class="form-group">
						<label class="control-label static_label">{$lang.label.appNotice}</label>
						<p class="form-control-static">{$tplData.appRow.app_notice}</p>
					</div>

					<div class="form-group">
						<label class="control-label static_label">{$lang.label.allow}</label>
						<dl>
							{foreach $allow as $key_m=>$value_m}
								<dt>{$value_m.title}</dt>
								<dd>
									<ul class="list-inline">
										{foreach $value_m.allow as $key_s=>$value_s}
											<li>
												<span class="glyphicon glyphicon-{if $tplData.appRow.app_allow[$key_m][$key_s] == 1}ok-circle text-success{else}remove-circle text-danger{/if}"></span>
												{$value_s}
											</li>
										{/foreach}
									</ul>
								</dd>
							{/foreach}
						</dl>
					</div>

					<div class="form-group">
						<label class="control-label static_label">{$lang.label.ipAllow}</label>
						<p class="form-control-static">
							<pre>{$tplData.appRow.app_ip_allow}</pre>
						</p>
					</div>

					<div class="form-group">
						<label class="control-label static_label">{$lang.label.ipBad}</label>
						<p class="form-control-static">
							<pre>{$tplData.appRow.app_ip_bad}</pre>
						</p>
					</div>

					<div class="form-group">
						<label class="control-label static_label">{$lang.label.note}</label>
						<p class="form-control-static static_input">{$tplData.appRow.app_note}</p>
					</div>

					<div class="form-group">
						<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=app&act_get=form&app_id={$tplData.appRow.app_id}">
							<span class="glyphicon glyphicon-edit"></span>
							{$lang.href.edit}
						</a>
					</div>

				</div>
			</div>
		</div>

		<div class="col-md-3">
			<div class="well">
				<div class="form-group">
					<label class="control-label">{$lang.label.id}</label>
					<p class="form-control-static">{$tplData.appRow.app_id}</p>
				</div>

				{if $tplData.appRow.app_status == "enable"}
					{$_css_status = "success"}
				{else}
					{$_css_status = "danger"}
				{/if}

				<div class="form-group">
					<label class="control-label">{$lang.label.status}</label>
					<p class="form-control-static">
						<span class="label label-{$_css_status}">{$status.app[$tplData.appRow.app_status]}</span>
					</p>
				</div>

				{if $tplData.appRow.app_sync == "on"}
					{$_css_status = "success"}
				{else}
					{$_css_status = "danger"}
				{/if}

				<div class="form-group">
					<label class="control-label">{$lang.label.sync}</label>
					<p class="form-control-static">
						<span class="label label-{$_css_status}">{$status.appSync[$tplData.appRow.app_sync]}</span>
					</p>
				</div>

				<div class="form-group">
					<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=app&act_get=form&app_id={$tplData.appRow.app_id}">
						<span class="glyphicon glyphicon-edit"></span>
						{$lang.href.edit}
					</a>
				</div>
			</div>
		</div>
	</div>

{include "include/admin_foot.tpl" cfg=$cfg}
{include "include/html_foot.tpl" cfg=$cfg}
