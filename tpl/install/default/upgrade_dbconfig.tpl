{* upgrade_dbconfig.tpl 登录界面 *}
{$cfg = [
	sub_title => $lang.page.installDbconfig,
	mod_help   => "upgrade",
	act_help   => "dbconfig"
]}

{include "{$smarty.const.BG_PATH_TPL}install/default/include/upgrade_head.tpl" cfg=$cfg}

	<form name="upgrade_form_dbconfig" id="upgrade_form_dbconfig">
		<input type="hidden" name="token_session" class="token_session" value="{$common.token_session}">
		<input type="hidden" name="act_post" value="dbconfig">

		<div class="form-group">
			<label class="control-label">{$lang.label.dbHost}<span id="msg_db_host">*</span></label>
			<input type="text" name="db_host" id="db_host" class="validate form-control input-lg" value="{if $smarty.const.BG_DB_HOST}{$smarty.const.BG_DB_HOST}{else}localhost{/if}">
		</div>

		<div class="form-group">
			<div id="group_db_port">
				<label class="control-label">{$lang.label.dbPort}<span id="msg_db_port">*</span></label>
				<input type="text" name="db_port" id="db_port" class="validate form-control input-lg" value="{if $smarty.const.BG_DB_PORT}{$smarty.const.BG_DB_PORT}{else}3306{/if}">
			</div>
		</div>

		<div class="form-group">
			<label class="control-label">{$lang.label.dbName}<span id="msg_db_name">*</span></label>
			<input type="text" name="db_name" id="db_name" class="validate form-control input-lg" value="{if $smarty.const.BG_DB_NAME}{$smarty.const.BG_DB_NAME}{else}baigo_sso{/if}">
		</div>

		<div class="form-group">
			<label class="control-label">{$lang.label.dbUser}<span id="msg_db_user">*</span></label>
			<input type="text" name="db_user" id="db_user" class="validate form-control input-lg" value="{if $smarty.const.BG_DB_USER}{$smarty.const.BG_DB_USER}{else}baigo_sso{/if}">
		</div>

		<div class="form-group">
			<label class="control-label">{$lang.label.dbPass}<span id="msg_db_pass">*</span></label>
			<input type="text" name="db_pass" id="db_pass" class="validate form-control input-lg" value="{if $smarty.const.BG_DB_PASS}{$smarty.const.BG_DB_PASS}{/if}">
		</div>

		<div class="form-group">
			<label class="control-label">{$lang.label.dbCharset}<span id="msg_db_charset">*</span></label>
			<input type="text" name="db_charset" id="db_charset" class="validate form-control input-lg" value="{if $smarty.const.BG_DB_CHARSET}{$smarty.const.BG_DB_CHARSET}{else}utf8{/if}">
		</div>

		<div class="form-group">
			<label class="control-label">{$lang.label.dbTable}<span id="msg_db_table">*</span></label>
			<input type="text" name="db_table" id="db_table" class="validate form-control input-lg" value="{if $smarty.const.BG_DB_TABLE}{$smarty.const.BG_DB_TABLE}{else}sso_{/if}">
		</div>

		<div class="form-group">
			<div class="btn-group">
				<button type="button" id="go_next" class="btn btn-primary btn-lg">{$lang.btn.save}</button>
				{include "{$smarty.const.BG_PATH_TPL}install/default/include/upgrade_drop.tpl" cfg=$cfg}
			</div>
		</div>
	</form>

{include "{$smarty.const.BG_PATH_TPL}install/default/include/install_foot.tpl" cfg=$cfg}

	<script type="text/javascript">
	var opts_validator_form = {
		db_host: {
			length: { min: 1, max: 0 },
			validate: { type: "str", format: "text" },
			msg: { id: "msg_db_host", too_short: "{$alert.x030204}" }
		},
		db_port: {
			length: { min: 1, max: 0 },
			validate: { type: "str", format: "text", group: "group_db_port" },
			msg: { id: "msg_db_port", too_short: "{$alert.x030211}" }
		},
		db_name: {
			length: { min: 1, max: 0 },
			validate: { type: "str", format: "text" },
			msg: { id: "msg_db_name", too_short: "{$alert.x030205}" }
		},
		db_user: {
			length: { min: 1, max: 0 },
			validate: { type: "str", format: "text" },
			msg: { id: "msg_db_user", too_short: "{$alert.x030206}" }
		},
		db_pass: {
			length: { min: 1, max: 0 },
			validate: { type: "str", format: "text" },
			msg: { id: "msg_db_pass", too_short: "{$alert.x030207}" }
		},
		db_charset: {
			length: { min: 1, max: 0 },
			validate: { type: "str", format: "text" },
			msg: { id: "msg_db_charset", too_short: "{$alert.x030208}" }
		},
		db_table: {
			length: { min: 1, max: 0 },
			validate: { type: "str", format: "text" },
			msg: { id: "msg_db_table", too_short: "{$alert.x030209}" }
		}
	};
	var opts_submit_form = {
		ajax_url: "{$smarty.const.BG_URL_INSTALL}ajax.php?mod=upgrade",
		text_submitting: "{$lang.label.submitting}",
		btn_text: "{$lang.btn.stepNext}",
		btn_close: "{$lang.btn.close}",
		btn_url: "{$smarty.const.BG_URL_INSTALL}ctl.php?mod=upgrade&act_get=dbtable"
	};

	$(document).ready(function(){
		var obj_validator_form    = $("#upgrade_form_dbconfig").baigoValidator(opts_validator_form);
		var obj_submit_form       = $("#upgrade_form_dbconfig").baigoSubmit(opts_submit_form);
		$("#go_next").click(function(){
			if (obj_validator_form.validateSubmit()) {
				obj_submit_form.formSubmit();
			}
		});
	})
	</script>

</html>