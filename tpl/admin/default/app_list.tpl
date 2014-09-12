{* admin_list.tpl 管理员列表 *}
{$cfg = [
	title          => $adminMod.app.main.title,
	menu_active    => "app",
	sub_active     => "list",
	baigoCheckall  => "true",
	baigoValidator => "true",
	baigoSubmit    => "true",
	str_url        => "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=app&{$tplData.query}"
]}

{include "include/admin_head.tpl" cfg=$cfg}

	<li>{$adminMod.app.main.title}</li>

	{include "include/admin_left.tpl" cfg=$cfg}

	<div class="form-group">
		<div class="pull-left">
			<ul class="list-inline">
				<li>
					<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=app&act_get=form">
						<span class="glyphicon glyphicon-plus"></span>
						{$lang.href.add}
					</a>
				</li>
				<li>
					<a href="{$smarty.const.BG_URL_HELP}?lang=zh_CN&mod=help&act=app" target="_blank">
						<span class="glyphicon glyphicon-question-sign"></span>
						{$lang.href.help}
					</a>
				</li>
			</ul>
		</div>
		<div class="pull-right">
			<form name="app_search" id="app_search" action="{$smarty.const.BG_URL_ADMIN}ctl.php" method="get" class="form-inline">
				<input type="hidden" name="mod" value="app">
				<select name="status" class="form-control input-sm">
					<option value="">{$lang.option.allStatus}</option>
					{foreach $status.app as $key=>$value}
						<option {if $tplData.search.status == $key}selected{/if} value="{$key}">{$value}</option>
					{/foreach}
				</select>
				<input type="text" name="key" value="{$tplData.search.key}" placeholder="{$lang.label.key}" class="form-control input-sm">
				<button type="submit" class="btn btn-default btn-sm">{$lang.btn.filter}</button>
			</form>
		</div>
		<div class="clearfix"></div>
	</div>

	<div class="panel panel-default">

		<form name="app_list" id="app_list" class="form-inline">

			<input type="hidden" name="token_session" value="{$common.token_session}">
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
							<th>{$lang.label.appName}</th>
							<th class="td_md">{$lang.label.note}</th>
							<th class="td_sm">{$lang.label.status}</th>
						</tr>
					</thead>
					<tbody>
						{foreach $tplData.appRows as $value}
							{if $value.app_status == "enable"}
								{$_css_status = "success"}
							{else}
								{$_css_status = "danger"}
							{/if}
							<tr>
								<td class="td_mn"><input type="checkbox" name="app_id[]" value="{$value.app_id}" id="app_id_{$value.app_id}" group="app_id" class="validate chk_all"></td>
								<td class="td_mn">{$value.app_id}</td>
								<td>
									<div>{$value.app_name}</div>
									<div>
										<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=app&act_get=show&app_id={$value.app_id}">{$lang.href.show}</a>
										&nbsp;|&nbsp;
										<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=app&act_get=form&app_id={$value.app_id}">{$lang.href.edit}</a>
										&nbsp;|&nbsp;
										<a href="javascript:void(0);" class="go_notice" id="{$value.app_id}">{$lang.href.noticeTest}</a>
									</div>
								</td>
								<td class="td_md">
									{$value.app_note}
								</td>
								<td class="td_sm">
									<span class="label label-{$_css_status}">{$status.app[$value.app_status]}</span>
								</td>
							</tr>
						{/foreach}
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2"><span id="msg_app_id"></span></td>
							<td colspan="3">
								<select name="act_post" id="act_post" class="validate form-control input-sm">
									<option value="">{$lang.option.batch}</option>
									{foreach $status.app as $key=>$value}
										<option value="{$key}">{$value}</option>
									{/foreach}
									<option value="del">{$lang.option.del}</option>
								</select>
								<button type="button" id="go_list" class="btn btn-sm btn-primary">{$lang.btn.submit}</button>
								<span id="msg_act_post"></span>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</form>
	</div>

	<div class="text-right">
		{include "include/page.tpl" cfg=$cfg}
	</div>

	<form id="app_notice">
		<input type="hidden" name="act_post" id="act_post" value="notice">
		<input type="hidden" name="app_id_notice" id="app_id_notice" value="">
	</form>

{include "include/admin_foot.tpl" cfg=$cfg}

	<script type="text/javascript">
	var opts_validator_list = {
		app_id: {
			length: { min: 1, max: 0 },
			validate: { type: "checkbox" },
			msg: { id: "msg_app_id", too_few: "{$alert.x030202}" }
		},
		act_post: {
			length: { min: 1, max: 0 },
			validate: { type: "select" },
			msg: { id: "msg_act_post", too_few: "{$alert.x030203}" }
		}
	};

	var opts_submit_list = {
		ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=app",
		confirm_id: "act_post",
		confirm_val: "del",
		confirm_msg: "{$lang.confirm.del}",
		btn_text: "{$lang.btn.ok}",
		btn_close: "{$lang.btn.close}",
		btn_url: "{$cfg.str_url}"
	};

	var opts_submit_notice = {
		ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=app",
		btn_text: "{$lang.btn.ok}",
		btn_close: "{$lang.btn.close}",
		btn_url: "{$cfg.str_url}"
	};

	$(document).ready(function(){
		var obj_validator_list = $("#app_list").baigoValidator(opts_validator_list);
		var obj_submit_list = $("#app_list").baigoSubmit(opts_submit_list);
		$("#go_list").click(function(){
			if (obj_validator_list.validateSubmit()) {
				obj_submit_list.formSubmit();
			}
		});

		var obj_notice = $("#app_notice").baigoSubmit(opts_submit_notice);
		$(".go_notice").click(function(){
			var __id = $(this).attr("id");
			$("#app_id_notice").val(__id);
			obj_notice.formSubmit();
		});
		$("#app_list").baigoCheckall();
	})
	</script>

{include "include/html_foot.tpl" cfg=$cfg}