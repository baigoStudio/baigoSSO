{* opt_form.tpl 系统设置界面 *}
{$cfg = [
	title          => "{$adminMod.opt.main.title} - {$adminMod.opt.sub.base.title}",
	menu_active    => "opt",
	sub_active     => "base",
	baigoValidator => "true",
	baigoSubmit    => "true",
	str_url        => "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=opt"
]}

{include "include/admin_head.tpl" cfg=$cfg}

	<li><a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=opt&act_get=base">{$adminMod.opt.main.title}</a></li>
	<li>{$adminMod.opt.sub.base.title}</li>

	{include "include/admin_left.tpl" cfg=$cfg}

	<div class="form-group">
		<a href="{$smarty.const.BG_URL_HELP}?lang=zh_CN&mod=help&act=opt" target="_blank">
			<span class="glyphicon glyphicon-question-sign"></span>
			{$lang.href.help}
		</a>
	</div>

	<form name="opt_form" id="opt_form">

		<input type="hidden" name="token_session" value="{$common.token_session}">
		<input type="hidden" name="act_post" value="base">

		<div class="panel panel-default">
			<div class="panel-body">
				{include "include/opt_form.tpl" cfg=$cfg}

				<div class="form-group">
					<button type="button" id="go_form" class="btn btn-primary">{$lang.btn.save}</button>
				</div>
			</div>
		</div>
	</form>

{include "include/admin_foot.tpl" cfg=$cfg}

	<script type="text/javascript">
	var opts_validator_form = {
		{foreach $opt["base"] as $key=>$value}
			{if $value.type == "str" || $value.type == "textarea"}
				{$str_msg_min = "too_short"}
				{$str_msg_max = "too_long"}
			{else}
				{$str_msg_min = "too_few"}
				{$str_msg_max = "too_many"}
			{/if}
			"opt_{$key}": {
				length: { min: {$value.min}, max: 900 },
				validate: { type: "{$value.type}", format: "{$value.format}" },
				msg: { id: "msg_{$key}", {$str_msg_min}: "{$alert.x040201}{$value.label}", {$str_msg_max}: "{$value.label}{$alert.x040202}", format_err: "{$value.label}{$alert.x040203}" }
			}{if !$value@last},{/if}
		{/foreach}
	};

	var opts_submit_form = {
		ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=opt",
		btn_text: "{$lang.btn.ok}",
		btn_close: "{$lang.btn.close}",
		btn_url: "{$cfg.str_url}"
	};

	$(document).ready(function(){
		var obj_validator_form = $("#opt_form").baigoValidator(opts_validator_form);
		var obj_submit_form = $("#opt_form").baigoSubmit(opts_submit_form);
		$("#go_form").click(function(){
			if (obj_validator_form.validateSubmit()) {
				obj_submit_form.formSubmit();
			}
		});
	})
	</script>

{include "include/html_foot.tpl" cfg=$cfg}
