{* opt_form.tpl 系统设置界面 *}
{$cfg = [
	title          => "{$adminMod.opt.main.title} - {$adminMod.opt.sub.db.title}",
	menu_active    => "opt",
	sub_active     => "db",
	baigoValidator => "true",
	baigoSubmit    => "true",
	tokenReload    => "true",
	str_url        => "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=opt&act_get=db"
]}

{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_head.tpl" cfg=$cfg}

	<li><a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=opt&act_get=base">{$adminMod.opt.main.title}</a></li>
	<li>{$adminMod.opt.sub.db.title}</li>

	{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_left.tpl" cfg=$cfg}

	<div class="form-group">
		<ul class="nav nav-pills nav_baigo">
			<li>
				<a href="{$smarty.const.BG_URL_HELP}ctl.php?mod=admin&act_get=opt#db" target="_blank">
					<span class="glyphicon glyphicon-question-sign"></span>
					{$lang.href.help}
				</a>
			</li>
		</ul>
	</div>

	<form name="opt_form" id="opt_form">

		<input type="hidden" name="token_session" class="token_session" value="{$common.token_session}">
		<input type="hidden" name="act_post" value="db">

		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<div id="group_db_host">
						<label for="db_host" class="control-label">{$lang.label.dbHost}<span id="msg_db_host">*</span></label>
						<input type="text" name="db_host" id="db_host" class="validate form-control" value="{if $smarty.const.BG_DB_HOST}{$smarty.const.BG_DB_HOST}{else}localhost{/if}">
					</div>
				</div>

				<div class="form-group">
					<div id="group_db_port">
						<label class="control-label">{$lang.label.dbPort}<span id="msg_db_port">*</span></label>
						<input type="text" name="db_port" id="db_port" class="validate form-control" value="{if $smarty.const.BG_DB_PORT}{$smarty.const.BG_DB_PORT}{else}3306{/if}">
					</div>
				</div>

				<div class="form-group">
					<div id="group_db_name">
						<label for="db_name" class="control-label">{$lang.label.dbName}<span id="msg_db_name">*</span></label>
						<input type="text" name="db_name" id="db_name" class="validate form-control" value="{if $smarty.const.BG_DB_NAME}{$smarty.const.BG_DB_NAME}{else}baigo_sso{/if}">
					</div>
				</div>

				<div class="form-group">
					<div id="group_db_user">
						<label for="db_user" class="control-label">{$lang.label.dbUser}<span id="msg_db_user">*</span></label>
						<input type="text" name="db_user" id="db_user" class="validate form-control" value="{if $smarty.const.BG_DB_USER}{$smarty.const.BG_DB_USER}{else}baigo_sso{/if}">
					</div>
				</div>

				<div class="form-group">
					<div id="group_db_pass">
						<label for="db_pass" class="control-label">{$lang.label.dbPass}<span id="msg_db_pass">*</span></label>
						<input type="text" name="db_pass" id="db_pass" class="validate form-control" value="{if $smarty.const.BG_DB_PASS}{$smarty.const.BG_DB_PASS}{/if}">
					</div>
				</div>

				<div class="form-group">
					<div id="group_db_charset">
						<label for="db_charset" class="control-label">{$lang.label.dbCharset}<span id="msg_db_charset">*</span></label>
						<input type="text" name="db_charset" id="db_charset" class="validate form-control" value="{if $smarty.const.BG_DB_CHARSET}{$smarty.const.BG_DB_CHARSET}{else}utf8{/if}">
					</div>
				</div>

				<div class="form-group">
					<div id="group_db_table">
						<label for="db_table" class="control-label">{$lang.label.dbTable}<span id="msg_db_table">*</span></label>
						<input type="text" name="db_table" id="db_table" class="validate form-control" value="{if $smarty.const.BG_DB_TABLE}{$smarty.const.BG_DB_TABLE}{else}sso_{/if}">
					</div>
				</div>

				<div class="form-group">
					<button type="button" id="go_submit" class="btn btn-primary">{$lang.btn.save}</button>
				</div>
			</div>
		</div>
	</form>

{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_foot.tpl" cfg=$cfg}

	<script type="text/javascript">
	var opts_validator_form = {
		db_host: {
			length: { min: 1, max: 0 },
			validate: { type: "str", format: "text", group: "group_db_host" },
			msg: { id: "msg_db_host", too_short: "{$alert.x030204}" }
		},
		db_port: {
			length: { min: 1, max: 0 },
			validate: { type: "str", format: "text", group: "group_db_port" },
			msg: { id: "msg_db_port", too_short: "{$alert.x030211}" }
		},
		db_name: {
			length: { min: 1, max: 0 },
			validate: { type: "str", format: "text", group: "group_db_name" },
			msg: { id: "msg_db_name", too_short: "{$alert.x030205}" }
		},
		db_user: {
			length: { min: 1, max: 0 },
			validate: { type: "str", format: "text", group: "group_db_user" },
			msg: { id: "msg_db_user", too_short: "{$alert.x030206}" }
		},
		db_pass: {
			length: { min: 1, max: 0 },
			validate: { type: "str", format: "text", group: "group_db_pass" },
			msg: { id: "msg_db_pass", too_short: "{$alert.x030207}" }
		},
		db_charset: {
			length: { min: 1, max: 0 },
			validate: { type: "str", format: "text", group: "group_db_charset" },
			msg: { id: "msg_db_charset", too_short: "{$alert.x030208}" }
		},
		db_table: {
			length: { min: 1, max: 0 },
			validate: { type: "str", format: "text", group: "group_db_table" },
			msg: { id: "msg_db_table", too_short: "{$alert.x030209}" }
		}
	};
	var opts_submit_form = {
		ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=opt",
		text_submitting: "{$lang.label.submitting}",
		btn_text: "{$lang.btn.ok}",
		btn_close: "{$lang.btn.close}",
		btn_url: "{$cfg.str_url}"
	};

	$(document).ready(function(){
		var obj_validate_form = $("#opt_form").baigoValidator(opts_validator_form);
		var obj_submit_form   = $("#opt_form").baigoSubmit(opts_submit_form);
		$("#go_submit").click(function(){
			if (obj_validate_form.validateSubmit()) {
				obj_submit_form.formSubmit();
			}
		});
	})
	</script>

{include "{$smarty.const.BG_PATH_TPL}admin/default/include/html_foot.tpl" cfg=$cfg}

