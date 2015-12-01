{* upgrade_dbconfig.tpl 登录界面 *}
{$cfg = [
	sub_title  => $lang.page.installExt,
	mod_help   => "upgrade",
	act_help   => "ext"
]}

{include "{$smarty.const.BG_PATH_TPL}install/default/include/upgrade_head.tpl" cfg=$cfg}

		{foreach $type.ext as $key=>$value}
			<div class="form-group">
				<label class="control-label">{$value}</label>
				<p class="form-control-static label_baigo">
					{if $key|in_array:$tplData.extRow}
						<span class="label label-success">{$status.ext.installed}</span>
					{else}
						<span class="label label-danger">{$status.ext.uninstall}</span>
					{/if}
				</p>
			</div>
		{/foreach}

		{if $tplData.errCount > 0}
			<div class="alert alert-danger">{$lang.text.extErr}</div>
			<div class="form-group">
				<a class="btn btn-primary btn-lg disabled">{$lang.btn.stepNext}</a>
			</div>
		{else}
			<div class="alert alert-success">{$lang.text.extOk}</div>

			<div class="form-group">
				<div class="btn-group">
					<a id="go_next" class="btn btn-primary btn-lg" href="{$smarty.const.BG_URL_INSTALL}ctl.php?mod=upgrade&act_get=dbconfig">{$lang.btn.stepNext}</a>
					{include "{$smarty.const.BG_PATH_TPL}install/default/include/upgrade_drop.tpl" cfg=$cfg}
				</div>
			</div>
		{/if}

{include "{$smarty.const.BG_PATH_TPL}install/default/include/install_foot.tpl" cfg=$cfg}
{include "{$smarty.const.BG_PATH_TPL}install/default/include/html_foot.tpl" cfg=$cfg}