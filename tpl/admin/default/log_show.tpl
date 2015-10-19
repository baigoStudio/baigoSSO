{* admin_form.tpl 管理员编辑界面 *}
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	{$adminMod.log.main.title} - {$lang.page.detail}
</div>
<div class="modal-body">

	<div class="form-group">
		<label class="control-label static_label">{$lang.label.id}</label>
		<p class="form-control-static static_input">{$tplData.logRow.log_id}</p>
	</div>

	{if $tplData.logRow.log_status == "read"}
		{$_css_status = "success"}
	{else}
		{$_css_status = "warning"}
	{/if}

	<div class="form-group">
		<label class="control-label static_label">{$lang.label.status}</label>
		<p class="form-control-static label_baigo">
			<span class="label label-{$_css_status}">{$status.log[$tplData.logRow.log_status]}</span>
		</p>
	</div>

	<div class="form-group">
		<label class="control-label static_label">{$lang.label.content}</label>
		<p class="form-control-static static_input">{$tplData.logRow.log_title}</p>
	</div>

	<div class="form-group">
		<label class="control-label static_label">{$lang.label.target}</label>
		<p class="form-control-static">
			{foreach $tplData.logRow.log_targets as $_key=>$_value}
				<div>
					{if $tplData.logRow.log_target_type == "opt"}
						{$type.logTarget[$tplData.logRow.log_target_type]}: {$_value}
					{else}
						<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod={$tplData.logRow.log_target_type}&act_get=show&{$tplData.logRow.log_target_type}_id={$_value.target_id}">
							{$type.logTarget[$tplData.logRow.log_target_type]}: {$_value.target_name} [ {$lang.label.id}: {$_value.target_id} ]
						</a>
					{/if}
				</div>
			{/foreach}
		</p>
	</div>

	<div class="form-group">
		<label class="control-label static_label">{$lang.label.type}</label>
		<p class="form-control-static static_input">{$type.log[$tplData.logRow.log_type]}</p>
	</div>

	<div class="form-group">
		<label class="control-label static_label">{$lang.label.operator}</label>
		<p class="form-control-static">
			{if $tplData.logRow.log_type != "system"}
				<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod={$tplData.logRow.log_type}&act_get=show&{$tplData.logRow.log_type}_id={$tplData.logRow.log_operator_id}">{$tplData.logRow.log_operator_name}</a>
			{else}
				{$type.log[$value.log_type]}
			{/if}
		</p>
	</div>

	<div class="form-group">
		<label class="control-label static_label">{$lang.label.result}</label>
		<p class="form-control-static static_input">
			{$tplData.logRow.log_result}
		</p>
	</div>

</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">{$lang.btn.close}</button>
</div>