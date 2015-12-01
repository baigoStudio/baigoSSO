{* opt_form.tpl 系统设置界面 *}
{$cfg = [
	title          => "{$lang.page.opt} - {$opt[$tplData.act_get].title}",
	menu_active    => "opt",
	baigoValidator => "true",
	baigoSubmit    => "true",
	tokenReload    => "true",
	str_url        => "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=opt"
]}

{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_head.tpl" cfg=$cfg}

	<li><a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=opt&act_get=base">{$lang.page.opt}</a></li>
	<li>{$opt[$tplData.act_get].title}</li>

	{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_left.tpl" cfg=$cfg}

	<div class="form-group">
		<ul class="nav nav-pills nav_baigo">
			<li>
				<a href="{$smarty.const.BG_URL_HELP}ctl.php?mod=admin&act_get=opt" target="_blank">
					<span class="glyphicon glyphicon-question-sign"></span>
					{$lang.href.help}
				</a>
			</li>
		</ul>
	</div>

	<form name="opt_form" id="opt_form">

		<input type="hidden" name="token_session" class="token_session" value="{$common.token_session}">
		<input type="hidden" name="act_post" value="{$tplData.act_get}">

		<div class="panel panel-default">
			<div class="panel-body">
				{foreach $opt[$tplData.act_get].list as $key=>$value}
            		{if $smarty.const[$key]}
            			{$_this_value = $smarty.const[$key]}
            		{else}
            			{$_this_value = $value.default}
            		{/if}

            		<div class="form-group">
            			<div id="group_{$tplData.act_get}_{$key}">
            				<label for="opt_{$tplData.act_get}_{$key}" class="control-label">{$value.label}<span id="msg_{$tplData.act_get}_{$key}">{if $value.min > 0}*{/if}</span></label>

            				{if $value.type == "select"}
            					<select name="opt[{$tplData.act_get}][{$key}]" id="opt_{$tplData.act_get}_{$key}" class="validate form-control">
            						{foreach $value.option as $key_opt=>$value_opt}
            							<option {if $_this_value == $key_opt}selected{/if} value="{$key_opt}">{$value_opt}</option>
            						{/foreach}
            					</select>
            				{else if $value.type == "radio"}
            					<dl class="list_dl">
            						{foreach $value.option as $key_opt=>$value_opt}
            							<dt>
            								<div class="radio">
            									<label for="opt_{$tplData.act_get}_{$key}_{$key_opt}">
            										<input type="radio" {if $_this_value == $key_opt}checked{/if} value="{$key_opt}" class="validate" group="opt_{$tplData.act_get}_{$key}" name="opt[{$tplData.act_get}][{$key}]" id="opt_{$tplData.act_get}_{$key}_{$key_opt}">
            										{$value_opt.value}
            									</label>
            								</div>
            							</dt>
            							<dd>{if isset($value_opt.note)}{$value_opt.note}{/if}</dd>
            						{/foreach}
            					</dl>
            				{else if $value.type == "textarea"}
            					<textarea name="opt[{$tplData.act_get}][{$key}]" id="opt_{$tplData.act_get}_{$key}" class="validate form-control text_md">{$_this_value}</textarea>
            				{else}
            					<input type="text" value="{$_this_value}" name="opt[{$tplData.act_get}][{$key}]" id="opt_{$tplData.act_get}_{$key}" class="validate form-control">
            				{/if}

            				<p class="help-block">{if isset($value.note)}{$value.note}{/if}</p>
            			</div>
            		</div>
            	{/foreach}

				<div class="form-group">
					<button type="button" id="go_form" class="btn btn-primary">{$lang.btn.save}</button>
				</div>
			</div>
		</div>
	</form>

{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_foot.tpl" cfg=$cfg}

	<script type="text/javascript">
	var opts_validator_form = {
		{foreach $opt[$tplData.act_get].list as $key=>$value}
			{if $value.type == "str" || $value.type == "textarea"}
				{$str_msg_min = "too_short"}
			{else}
				{$str_msg_min = "too_few"}
			{/if}
			"opt_{$cfg.sub_active}_{$key}": {
				length: { min: {$value.min}, max: 0 },
				validate: { type: "{$value.type}", {if isset($value.format)}format: "{$value.format}", {/if}group: "group_{$tplData.act_get}_{$key}" },
				msg: { id: "msg_{$tplData.act_get}_{$key}", {$str_msg_min}: "{$alert.x040201}{$value.label}", format_err: "{$value.label}{$alert.x040203}" }
			}{if !$value@last},{/if}
		{/foreach}
	};

	var opts_submit_form = {
		ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=opt",
		text_submitting: "{$lang.label.submitting}",
		btn_text: "{$lang.btn.ok}",
		btn_text: "{$lang.btn.ok}",
		btn_close: "{$lang.btn.close}",
		btn_url: "{$cfg.str_url}"
	};

	$(document).ready(function(){
		var obj_validator_form    = $("#opt_form").baigoValidator(opts_validator_form);
		var obj_submit_form       = $("#opt_form").baigoSubmit(opts_submit_form);
		$("#go_form").click(function(){
			if (obj_validator_form.validateSubmit()) {
				obj_submit_form.formSubmit();
			}
		});
	})
	</script>

{include "{$smarty.const.BG_PATH_TPL}admin/default/include/html_foot.tpl" cfg=$cfg}
