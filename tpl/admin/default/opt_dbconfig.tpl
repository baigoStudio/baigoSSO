{* opt_dbconfig.tpl 系统设置界面 *}
{$cfg = [
	title          => "{$lang.page.opt} - {$lang.page.installDbConfig}",
	menu_active    => "opt",
	baigoValidator => "true",
	baigoSubmit    => "true",
	tokenReload    => "true",
	str_url        => "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=opt"
]}

{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_head.tpl" cfg=$cfg}

	<li><a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=opt&act_get=base">{$lang.page.opt}</a></li>
	<li>{$lang.page.installDbConfig}</li>

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

	<form name="opt_dbconfig" id="opt_dbconfig">

		<input type="hidden" name="token_session" class="token_session" value="{$common.token_session}">
		<input type="hidden" name="act_post" value="{$tplData.act_get}">

		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
        			<div id="group_db_host">
        				<label class="control-label">{$lang.label.dbHost}<span id="msg_db_host">*</span></label>
        				<input type="text" value="{$smarty.const.BG_DB_HOST}" name="db_host" id="db_host" class="validate form-control">
        			</div>
        		</div>

        		<div class="form-group">
        			<div id="group_db_name">
        				<label class="control-label">{$lang.label.dbName}<span id="msg_db_name">*</span></label>
        				<input type="text" value="{$smarty.const.BG_DB_NAME}" name="db_name" id="db_name" class="validate form-control">
        			</div>
        		</div>

        		<div class="form-group">
        			<div id="group_db_port">
        				<label class="control-label">{$lang.label.dbPort}<span id="msg_db_port">*</span></label>
        				<input type="text" value="{$smarty.const.BG_DB_PORT}" name="db_port" id="db_port" class="validate form-control">
        			</div>
        		</div>

        		<div class="form-group">
        			<div id="group_db_user">
        				<label class="control-label">{$lang.label.dbUser}<span id="msg_db_user">*</span></label>
        				<input type="text" value="{$smarty.const.BG_DB_USER}" name="db_user" id="db_user" class="validate form-control">
        			</div>
        		</div>

        		<div class="form-group">
        			<div id="group_db_pass">
        				<label class="control-label">{$lang.label.dbPass}<span id="msg_db_pass">*</span></label>
        				<input type="text" value="{$smarty.const.BG_DB_PASS}" name="db_pass" id="db_pass" class="validate form-control">
        			</div>
        		</div>

        		<div class="form-group">
        			<div id="group_db_charset">
        				<label class="control-label">{$lang.label.dbCharset}<span id="msg_db_charset">*</span></label>
        				<input type="text" value="{$smarty.const.BG_DB_CHARSET}" name="db_charset" id="db_charset" class="validate form-control">
        			</div>
        		</div>

        		<div class="form-group">
        			<div id="group_db_table">
        				<label class="control-label">{$lang.label.dbTable}<span id="msg_db_table">*</span></label>
        				<input type="text" value="{$smarty.const.BG_DB_TABLE}" name="db_table" id="db_table" class="validate form-control">
        			</div>
        		</div>

				<div class="form-group">
					<button type="button" id="go_form" class="btn btn-primary">{$lang.btn.save}</button>
				</div>
			</div>
		</div>
	</form>

{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_foot.tpl" cfg=$cfg}

	<script type="text/javascript">
	var opts_validator_form = {
		db_host: {
			length: { min: 1, max: 900 },
			validate: { type: "str", format: "text", group: "group_db_host" },
			msg: { id: "msg_db_host", too_short: "{$alert.x040204}", too_long: "{$alert.x040205}" }
		},
		db_name: {
			length: { min: 1, max: 900 },
			validate: { type: "str", format: "text", group: "group_db_name" },
			msg: { id: "msg_db_name", too_short: "{$alert.x040206}", too_long: "{$alert.x040207}" }
		},
		db_port: {
			length: { min: 1, max: 900 },
			validate: { type: "str", format: "text", group: "group_db_port" },
			msg: { id: "msg_db_port", too_short: "{$alert.x040208}", too_long: "{$alert.x040209}" }
		},
		db_user: {
			length: { min: 1, max: 900 },
			validate: { type: "str", format: "text", group: "group_db_user" },
			msg: { id: "msg_db_user", too_short: "{$alert.x040210}", too_long: "{$alert.x040211}" }
		},
		db_pass: {
			length: { min: 1, max: 900 },
			validate: { type: "str", format: "text", group: "group_db_pass" },
			msg: { id: "msg_db_pass", too_short: "{$alert.x040212}", too_long: "{$alert.x040213}" }
		},
		db_charset: {
			length: { min: 1, max: 900 },
			validate: { type: "str", format: "text", group: "group_db_charset" },
			msg: { id: "msg_db_charset", too_short: "{$alert.x040214}", too_long: "{$alert.x040215}" }
		},
		db_table: {
			length: { min: 1, max: 900 },
			validate: { type: "str", format: "text", group: "group_db_table" },
			msg: { id: "msg_db_table", too_short: "{$alert.x040216}", too_long: "{$alert.x040217}" }
		}
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
		var obj_validator_form    = $("#opt_dbconfig").baigoValidator(opts_validator_form);
		var obj_submit_form       = $("#opt_dbconfig").baigoSubmit(opts_submit_form);
		$("#go_form").click(function(){
			if (obj_validator_form.validateSubmit()) {
				obj_submit_form.formSubmit();
			}
		});
	})
	</script>

{include "{$smarty.const.BG_PATH_TPL}admin/default/include/html_foot.tpl" cfg=$cfg}
