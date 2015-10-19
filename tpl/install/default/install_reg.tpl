{* install_1.tpl 登录界面 *}
{$cfg = [
	sub_title  => $lang.page.installReg,
	sub_active => "reg",
	mod_help   => "upgrade",
	act_help   => "reg"
]}
{include "{$smarty.const.BG_PATH_TPL}install/default/include/install_head.tpl" cfg=$cfg}

	<form name="install_form_reg" id="install_form_reg">
		<input type="hidden" name="token_session" class="token_session" value="{$common.token_session}">
		<input type="hidden" name="act_post" value="reg">

		{include "{$smarty.const.BG_PATH_TPL}install/default/include/install_form.tpl" cfg=$cfg}

		<div class="form-group">
			<div class="btn-group">
				<button type="button" id="go_next" class="btn btn-primary btn-lg">{$lang.btn.save}</button>
				{include "{$smarty.const.BG_PATH_TPL}install/default/include/install_drop.tpl" cfg=$cfg}
			</div>
		</div>
	</form>

{include "{$smarty.const.BG_PATH_TPL}install/default/include/install_foot.tpl" cfg=$cfg}

	<script type="text/javascript">
	var opts_submit_form = {
		ajax_url: "{$smarty.const.BG_URL_INSTALL}ajax.php?mod=install",
		text_submitting: "{$lang.label.submitting}",
		btn_text: "{$lang.btn.stepNext}",
		btn_close: "{$lang.btn.close}",
		btn_url: "{$smarty.const.BG_URL_INSTALL}ctl.php?mod=install&act_get=admin"
	};

	$(document).ready(function(){
		var obj_validator_form    = $("#install_form_reg").baigoValidator(opts_validator_form);
		var obj_submit_form       = $("#install_form_reg").baigoSubmit(opts_submit_form);
		$("#go_next").click(function(){
			if (obj_validator_form.validateSubmit()) {
				obj_submit_form.formSubmit();
			}
		});
	})
	</script>

</html>
