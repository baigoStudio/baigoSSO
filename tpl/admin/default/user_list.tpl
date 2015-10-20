{* user_list.tpl 管理员列表 *}
{if $smarty.const.BG_REG_NEEDMAIL == "on"}
	{$str_mailNeed = "*"}
	{$num_mailMin  = 1}
{else}
	{$str_mailNeed = ""}
	{$num_mailMin  = 0}
{/if}

{$cfg = [
	title          => $adminMod.user.main.title,
	menu_active    => "user",
	sub_active     => "list",
	baigoCheckall  => "true",
	baigoValidator => "true",
	baigoSubmit    => "true",
	tokenReload    => "true",
	str_url        => "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=user&{$tplData.query}"
]}

{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_head.tpl" cfg=$cfg}

	<li>{$adminMod.user.main.title}</li>

	{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_left.tpl" cfg=$cfg}

	<div class="form-group">
		<div class="pull-left">
			<ul class="nav nav-pills nav_baigo">
				<li>
					<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=user&act_get=form">
						<span class="glyphicon glyphicon-plus"></span>
						{$lang.href.add}
					</a>
				</li>
				<li>
					<a href="{$smarty.const.BG_URL_HELP}ctl.php?mod=admin&act_get=user" target="_blank">
						<span class="glyphicon glyphicon-question-sign"></span>
						{$lang.href.help}
					</a>
				</li>
			</ul>
		</div>

		<div class="pull-right">
			<form name="user_search" id="user_search" action="{$smarty.const.BG_URL_ADMIN}ctl.php" method="get" class="form-inline">
				<input type="hidden" name="mod" value="user">
				<input type="hidden" name="act_get" value="list">
				<div class="form-group">
					<select name="status" class="form-control input-sm">
						<option value="">{$lang.option.allStatus}</option>
						{foreach $status.user as $key=>$value}
							<option {if $tplData.search.status == $key}selected{/if} value="{$key}">{$value}</option>
						{/foreach}
					</select>
				</div>
				<div class="form-group">
					<input type="text" name="key" value="{$tplData.search.key}" placeholder="{$lang.label.key}" class="form-control input-sm">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-default btn-sm">
						<span class="glyphicon glyphicon-search"></span>
					</button>
				</div>
			</form>
		</div>
		<div class="clearfix"></div>
	</div>

	{if $tplData.search.act_get == "form"}
		<div class="row">
			<div class="col-md-3">
				<div class="well">
					<form name="user_form" id="user_form">
						<input type="hidden" name="token_session" class="token_session" value="{$common.token_session}">
						<input type="hidden" name="act_post" value="submit">
						<input type="hidden" name="user_id" id="user_id" value="{$tplData.userRow.user_id}">

						{if $tplData.userRow.user_id > 0}
							<div class="form-group">
								<label class="control-label">{$lang.label.id}</label>
								<p class="form-control-static">{$tplData.userRow.user_id}</p>
							</div>

							<div class="form-group">
								<label for="user_name" class="control-label">{$lang.label.username}<span id="msg_user_name">*</span></label>
								<input type="text" name="user_name" id="user_name" readonly value="{$tplData.userRow.user_name}" class="form-control">
							</div>

							<div class="form-group">
								<label for="user_pass" class="control-label">{$lang.label.password}</label>
								<input type="text" name="user_pass" id="user_pass" class="form-control" placeholder="{$lang.label.modOnly}">
							</div>
						{else}
							<div class="form-group">
								<label for="user_name" class="control-label">{$lang.label.username}<span id="msg_user_name">*</span></label>
								<input type="text" name="user_name" id="user_name" class="validate form-control">
							</div>

							<div class="form-group">
								<label for="user_pass" class="control-label">{$lang.label.password}<span id="msg_user_pass">*</span></label>
								<input type="text" name="user_pass" id="user_pass" class="validate form-control">
							</div>
						{/if}

						<div class="form-group">
							<label for="user_mail" class="control-label">{$lang.label.email}<span id="msg_user_mail">{$str_mailNeed}</span></label>
							<input type="text" name="user_mail" id="user_mail" value="{$tplData.userRow.user_mail}" class="validate form-control">
						</div>

						<div class="form-group">
							<label for="user_nick" class="control-label">{$lang.label.nick}<span id="msg_user_nick"></span></label>
							<input type="text" name="user_nick" id="user_nick" value="{$tplData.userRow.user_nick}" class="validate form-control">
						</div>

						<div class="form-group">
							<label for="user_note" class="control-label">{$lang.label.note}<span id="msg_user_note"></span></label>
							<input type="text" name="user_note" id="user_note" value="{$tplData.userRow.user_note}" class="validate form-control">
						</div>

						<label class="control-label">{$lang.label.status}<span id="msg_user_status">*</span></label>
						<div class="form-group">
							{foreach $status.user as $key=>$value}
								<div class="radio_baigo">
									<label for="user_status_{$key}">
										<input type="radio" name="user_status" id="user_status_{$key}" value="{$key}" class="validate" {if $tplData.userRow.user_status == $key}checked{/if} group="user_status">
										{$value}
									</label>
								</div>
							{/foreach}
						</div>

						<div class="form-group">
							<button type="button" id="go_form" class="btn btn-primary">{$lang.btn.save}</button>
							<a class="btn btn-default" href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=user&act_get=list">{$lang.btn.close}</a>
						</div>
					</form>
				</div>
			</div>

			<div class="col-md-9">
	{/if}

		<form name="user_list" id="user_list" class="form-inline">
			<input type="hidden" name="token_session" class="token_session" value="{$common.token_session}">

			<div class="panel panel-default">
				<div class="table-responsive">
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th class="td_mn">
									<label for="chk_all" class="checkbox-inline">
										<input type="checkbox" name="chk_all" id="chk_all" class="first">
										{$lang.label.all}
									</label>
								</th>
								<th class="td_mn">{$lang.label.id}</th>
								<th>{$lang.label.user}</th>
								<th class="td_md">{$lang.label.status} / {$lang.label.note}</th>
							</tr>
						</thead>
						<tbody>
							{foreach $tplData.userRows as $value}
								{if $value.user_status == "enable"}
									{$_css_status = "success"}
								{else if $value.user_status == "wait"}
									{$_css_status = "warning"}
								{else}
									{$_css_status = "danger"}
								{/if}
								<tr>
									<td class="td_mn"><input type="checkbox" name="user_id[]" value="{$value.user_id}" id="user_id_{$value.user_id}" group="user_id" class="validate chk_all"></td>
									<td class="td_mn">{$value.user_id}</td>
									<td>
										<ul class="list-unstyled">
											<li>
												{$value.user_name}
												{if $value.user_nick}[ {$value.user_nick} ]{/if}
											</li>
											<li>
												<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=user&act_get=form&user_id={$value.user_id}">{$lang.href.edit}</a>
											</li>
										</ul>
									</td>
									<td class="td_sm">
										<ul class="list-unstyled">
											<li class="label_baigo">
												<span class="label label-{$_css_status}">{$status.user[$value.user_status]}</span>
											</li>
											<li>{$value.user_note}</li>
										</ul>
									</td>
								</tr>
							{/foreach}
						</tbody>
						<tfoot>
							<tr>
								<td colspan="2"><span id="msg_user_id"></span></td>
								<td colspan="2">
									<div class="form-group">
										<select name="act_post" id="act_post" class="form-control input-sm">
											<option value="">{$lang.option.batch}</option>
											{foreach $status.user as $key=>$value}
												<option value="{$key}">{$value}</option>
											{/foreach}
											<option value="del">{$lang.option.del}</option>
										</select>
									</div>
									<div class="form-group">
										<button type="button" id="go_list" class="btn btn-primary btn-sm">{$lang.btn.submit}</button>
									</div>
									<div class="form-group">
										<span id="msg_act_post"></span>
									</div>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>

		</form>

	{if $tplData.search.act_get == "form"}
			</div>
		</div>
	{/if}

	<div class="text-right">
		{include "{$smarty.const.BG_PATH_TPL}admin/default/include/page.tpl" cfg=$cfg}
	</div>

{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_foot.tpl" cfg=$cfg}

	<script type="text/javascript">
	var opts_validator_list = {
		user_id: {
			length: { min: 1, max: 0 },
			validate: { type: "checkbox" },
			msg: { id: "msg_user_id", too_few: "{$alert.x030202}" }
		},
		act_post: {
			length: { min: 1, max: 0 },
			validate: { type: "select" },
			msg: { id: "msg_act_post", too_few: "{$alert.x030203}" }
		}
	};

	var opts_validator_form = {
		user_name: {
			length: { min: 1, max: 30 },
			validate: { type: "ajax", format: "strDigit" },
			msg: { id: "msg_user_name", too_short: "{$alert.x010201}", too_long: "{$alert.x010202}", format_err: "{$alert.x010203}" },
			ajax: { url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=user&act_get=chkname", key: "user_name", type: "str", attach_selectors: ["#user_id"], attach_keys: ["user_id"] }
		},
		user_mail: {
			length: { min: {$num_mailMin}, max: 300 },
			validate: { type: "ajax", format: "email" },
			msg: { id: "msg_user_mail", too_short: "{$alert.x010206}", too_long: "{$alert.x010207}", format_err: "{$alert.x010208}" },
			ajax: { url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=user&act_get=chkmail", key: "user_mail", type: "str", attach_selectors: ["#user_id"], attach_keys: ["user_id"] }
		},
		user_pass: {
			length: { min: 1, max: 0 },
			validate: { type: "str", format: "text" },
			msg: { id: "msg_user_pass", too_short: "{$alert.x010212}" }
		},
		user_nick: {
			length: { min: 0, max: 30 },
			validate: { type: "str", format: "text" },
			msg: { id: "msg_user_nick", too_long: "{$alert.x010214}" }
		},
		user_note: {
			length: { min: 0, max: 30 },
			validate: { type: "str", format: "text" },
			msg: { id: "msg_user_note", too_long: "{$alert.x020206}" }
		},
		user_status: {
			length: { min: 1, max: 0 },
			validate: { type: "radio" },
			msg: { id: "msg_user_status", too_few: "{$alert.x020203}" }
		}
	};

	var opts_submit_list = {
		ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=user",
		confirm_id: "act_post",
		confirm_val: "del",
		confirm_msg: "{$lang.confirm.del}",
		text_submitting: "{$lang.label.submitting}",
		btn_text: "{$lang.btn.ok}",
		btn_close: "{$lang.btn.close}",
		btn_url: "{$cfg.str_url}"
	};

	var opts_submit_form = {
		ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=user",
		text_submitting: "{$lang.label.submitting}",
		btn_text: "{$lang.btn.ok}",
		btn_close: "{$lang.btn.close}",
		btn_url: "{$cfg.str_url}"
	};

	$(document).ready(function(){
		var obj_validate_list = $("#user_list").baigoValidator(opts_validator_list);
		var obj_submit_list   = $("#user_list").baigoSubmit(opts_submit_list);
		$("#go_list").click(function(){
			if (obj_validate_list.validateSubmit()) {
				obj_submit_list.formSubmit();
			}
		});

		var obj_validate_form = $("#user_form").baigoValidator(opts_validator_form);
		var obj_submit_form   = $("#user_form").baigoSubmit(opts_submit_form);
		$("#go_form").click(function(){
			if (obj_validate_form.validateSubmit()) {
				obj_submit_form.formSubmit();
			}
		});
		$("#user_list").baigoCheckall();
	})
	</script>

{include "{$smarty.const.BG_PATH_TPL}admin/default/include/html_foot.tpl" cfg=$cfg}
