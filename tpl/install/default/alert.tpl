{* install_1.tpl 登录界面 *}
{$cfg = [
	sub_title => $lang.page.alert
]}
{include "{$smarty.const.BG_PATH_TPL}install/default/include/install_head.tpl" cfg=$cfg}

	<div class="alert alert-{if $tplData.status == "y"}success{else}danger{/if}">
		<span class="glyphicon glyphicon-{if $tplData.status == "y"}ok-circle{else}remove-circle{/if}"></span>
		{$alert[$tplData.alert]}
	</div>

	{$install[$tplData.alert]}

{include "{$smarty.const.BG_PATH_TPL}install/default/include/install_foot.tpl" cfg=$cfg}
{include "{$smarty.const.BG_PATH_TPL}install/default/include/html_foot.tpl" cfg=$cfg}