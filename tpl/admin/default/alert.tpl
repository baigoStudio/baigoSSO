{$cfg = [
	title  => $lang.page.alert
]}

{if $tplData.view == "iframe"}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		{$lang.page.alert}
	</div>
	<div class="modal-body">
{else}
	{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_head.tpl" cfg=$cfg}

	<li>{$lang.page.alert}</li>

	{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_left.tpl" cfg=$cfg}

	<div class="form-group">
		<a href="javascript:history.go(-1);">
			<span class="glyphicon glyphicon-chevron-left"></span>
			{$lang.href.back}
		</a>
	</div>
{/if}

	<div class="alert alert-{if $tplData.status == "y"}success{else}danger{/if}">
		<h3>
			<span class="glyphicon glyphicon-{if $tplData.status == "y"}ok-circle{else}remove-circle{/if}"></span>
			{$alert[$tplData.alert]}
		</h3>
		<p>
			{if isset($lang.text[$tplData.alert])}
				{$lang.text[$tplData.alert]}
			{/if}
		</p>
		<p>
			{$lang.label.alert}
			:
			{$tplData.alert}
		</p>
	</div>

{if $tplData.view == "iframe"}
	</div>
	<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">{$lang.btn.close}</button></div>
{else}
	{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_foot.tpl" cfg=$cfg}
	{include "{$smarty.const.BG_PATH_TPL}admin/default/include/html_foot.tpl" cfg=$cfg}
{/if}

