{* install_1.tpl 登录界面 *}
{$cfg = [
	sub_title => $lang.page.installAuto,
	mod_help   => "install",
	act_help   => "auto"
]}
{include "{$smarty.const.BG_PATH_TPL_INSTALL}default/include/install_head.tpl" cfg=$cfg}

	<form name="install_form_dbtable" id="install_form_dbtable">
		<input type="hidden" name="token_session" class="token_session" value="{$common.token_session}">
		<input type="hidden" name="pathParent" value="{$tplData.path}">
		<input type="hidden" name="target" value="{$tplData.target}">
		<input type="hidden" name="act_post" value="auto">

		<div class="alert alert-warning">
			<h4>
				<span class="glyphicon glyphicon-warning-sign"></span>
				{$lang.label.installAuto}
			</h4>
		</div>

		<div class="form-group">
			<div class="btn-group">
				<button type="button" id="go_next" class="btn btn-primary btn-lg">{$lang.btn.submit}</button>
				{include "{$smarty.const.BG_PATH_TPL_INSTALL}default/include/install_drop.tpl" cfg=$cfg}
			</div>
		</div>
	</form>

{include "{$smarty.const.BG_PATH_TPL_INSTALL}default/include/install_foot.tpl" cfg=$cfg}

	<script type="text/javascript">
	var opts_submit_form = {
		ajax_url: "{$smarty.const.BG_URL_INSTALL}ajax.php?mod=install",
		btn_text: "{$lang.btn.stepNext}",
		btn_close: "{$lang.btn.close}",
		btn_url: "{$tplData.url}ctl.php?mod=install&act_get=ssoAdmin"
	};

	$(document).ready(function(){
		var obj_submit_form = $("#install_form_dbtable").baigoSubmit(opts_submit_form);
		$("#go_next").click(function(){
			obj_submit_form.formSubmit();
		});
	})
	</script>

</html>