{* install_1.tpl 登录界面 *}
{$cfg = [
	sub_title  => $lang.page.installAdmin,
	mod_help   => "install",
	act_help   => "admin"
]}
{include "{$smarty.const.BG_PATH_TPL}install/default/include/install_head.tpl" cfg=$cfg}

	<form name="install_form_admin" id="install_form_admin">
		<input type="hidden" name="token_session" class="token_session" value="{$common.token_session}">
		<input type="hidden" name="act_post" value="admin">
		<input type="hidden" name="admin_status" value="enable">

		{foreach $adminMod as $key_m=>$value_m}
			{foreach $value_m.allow as $key_s=>$value_s}
				<input type="hidden" name="admin_allow[{$key_m}][{$key_s}]" value="1">
			{/foreach}
		{/foreach}

		<div class="form-group">
			<label class="control-label">{$lang.label.username}<span id="msg_admin_name">*</span></label>
			<input type="text" name="admin_name" id="admin_name" class="validate form-control input-lg">
		</div>

		<div class="form-group">
			<label class="control-label">{$lang.label.password}<span id="msg_admin_pass">*</span></label>
			<input type="password" name="admin_pass" id="admin_pass" class="validate form-control input-lg">
		</div>

		<div class="form-group">
			<label class="control-label">{$lang.label.passConfirm}<span id="msg_admin_pass_confirm">*</span></label>
			<input type="password" name="admin_pass_confirm" id="admin_pass_confirm" class="validate form-control input-lg">
		</div>

		<div class="form-group">
			<label class="control-label">{$lang.label.nick}<span id="msg_admin_nick"></span></label>
			<input type="text" name="admin_nick" id="admin_nick" class="validate form-control input-lg">
		</div>

		<div class="form-group">
			<div class="btn-group">
				<button type="button" id="go_next" class="btn btn-primary btn-lg">{$lang.btn.submit}</button>
				{include "{$smarty.const.BG_PATH_TPL}install/default/include/install_drop.tpl" cfg=$cfg}
			</div>
		</div>
	</form>

{include "{$smarty.const.BG_PATH_TPL}install/default/include/install_foot.tpl" cfg=$cfg}

	<script type="text/javascript">
	var opts_validator_form = {
		admin_name: {
			length: { min: 1, max: 30 },
			validate: { type: "text", format: "strDigit" },
			msg: { id: "msg_admin_name", too_short: "{$alert.x020201}", too_long: "{$alert.x020202}", format_err: "{$alert.x020203}" },
		},
		admin_pass: {
			length: { min: 1, max: 0 },
			validate: { type: "str", format: "text" },
			msg: { id: "msg_admin_pass", too_short: "{$alert.x020205}" }
		},
		admin_pass_confirm: {
			length: { min: 1, max: 0 },
			validate: { type: "confirm", target: "admin_pass" },
			msg: { id: "msg_admin_pass_confirm", too_short: "{$alert.x020211}", not_match: "{$alert.x020206}" }
		},
		admin_nick: {
			length: { min: 0, max: 30 },
			validate: { type: "str", format: "text" },
			msg: { id: "msg_admin_nick", too_short: "{$alert.x020212}" }
		}
	};
	var opts_submit_form = {
		ajax_url: "{$smarty.const.BG_URL_INSTALL}ajax.php?mod=install",
		text_submitting: "{$lang.label.submitting}",
		btn_text: "{$lang.btn.stepNext}",
		btn_close: "{$lang.btn.close}",
		btn_url: "{$smarty.const.BG_URL_INSTALL}ctl.php?mod=install&act_get=over"
	};

	$(document).ready(function(){
		var obj_validator_form    = $("#install_form_admin").baigoValidator(opts_validator_form);
		var obj_submit_form       = $("#install_form_admin").baigoSubmit(opts_submit_form);
		$("#go_next").click(function(){
			if (obj_validator_form.validateSubmit()) {
				obj_submit_form.formSubmit();
			}
		});
	})
	</script>

{include "{$smarty.const.BG_PATH_TPL}install/default/include/html_foot.tpl" cfg=$cfg}