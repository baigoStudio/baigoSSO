<!DOCTYPE html>
<html lang="{$config.lang|truncate:2:''}">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>{$lang.page.adminLogin} - {$lang.page.admin} - {$smarty.const.BG_SITE_NAME}</title>

	<!--jQuery 库-->
	<script src="{$smarty.const.BG_URL_JS}jquery.min.js" type="text/javascript"></script>
	<link href="{$smarty.const.BG_URL_STATIC_ADMIN}{$config.ui}/css/admin_logon.css" type="text/css" rel="stylesheet">
	<link href="{$smarty.const.BG_URL_JS}bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">
	<link href="{$smarty.const.BG_URL_JS}baigoValidator/baigoValidator.css" type="text/css" rel="stylesheet">

</head>


{$cfg = [
	title          => $lang.page.adminLogin,
	css            => "admin_logon",
	reloadImg      => "true",
	baigoValidator => "true"
]}

<body>

<div class="container global">

	<div class="panel panel-success">
		<div class="panel-heading">
			<h4>
				{$smarty.const.BG_SITE_NAME}
				<span class="label label-success">{$lang.page.adminLogin}</span>
			</h4>
		</div>

		<div class="panel-body">

			<form action="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=logon" method="post" id="login_form">
				<input type="hidden" name="act_post" value="login">
				<input type="hidden" name="token_session" class="token_session" value="{$common.token_session}">
				<input type="hidden" name="forward" value="{$tplData.forward}">

				<div class="form-group">
					{if $tplData.alert}
						<div class="alert alert-danger">{$alert[$tplData.alert]}</div>
					{/if}
				</div>

				<div class="form-group">
					<div id="group_admin_name">
						<label for="admin_name" class="control-label">{$lang.label.username}<span id="msg_admin_name">*</span></label>
						<input type="text" name="admin_name" id="admin_name" placeholder="{$alert.x020201}" class="validate form-control input-lg">
					</div>
				</div>

				<div class="form-group">
					<div id="group_admin_pass">
						<label for="admin_pass" class="control-label">{$lang.label.password}<span id="msg_admin_pass">*</span></label>
						<input type="password" name="admin_pass" id="admin_pass" placeholder="{$alert.x020205}" class="validate form-control input-lg">
					</div>
				</div>

				<div class="form-group">
					<div id="group_seccode">
						<label for="admin_name" class="control-label">{$lang.label.seccode}<span id="msg_seccode">*</span></label>
						<div class="input-group">
							<input type="text" name="seccode" id="seccode" placeholder="{$alert.x030201}" class="validate form-control input-lg">
							<span class="input-group-addon">
								<a href="javascript:reloadImg('seccodeImg', '{$smarty.const.BG_URL_ADMIN}ctl.php?mod=seccode&act_get=make');" title="{$lang.alt.seccode}">
									<img src="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=seccode&act_get=make" id="seccodeImg" alt="{$lang.alt.seccode}" height="32">
								</a>
							</span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<button type="button" class="btn btn-primary btn-lg btn-block" id="go_login">{$lang.btn.login}</button>
				</div>

				<div class="form-group">
				</div>

			</form>

		</div>

		<div class="panel-footer">
			<div class="pull-left">
				{$smarty.const.PRD_SSO_POWERED}
				{if $config.ui == "default"}
					<a href="{$smarty.const.PRD_SSO_URL}" target="_blank">{$smarty.const.PRD_SSO_NAME}</a>
				{else}
					{$config.ui} SSO
				{/if}
				{$smarty.const.PRD_SSO_VER}
			</div>
			<div class="pull-right foot_logo">
				{if $config.ui == "default"}
					<a href="{$smarty.const.PRD_SSO_URL}" target="_blank">{$smarty.const.PRD_SSO_POWERED} {$smarty.const.PRD_SSO_NAME} {$smarty.const.PRD_SSO_VER}</a>
				{else}
					<a href="#">{$config.ui} SSO</a>
				{/if}
			</div>
			<div class="clearfix"></div>
		</div>
	</div>

</body>

	<script type="text/javascript">
	var opts_validator_form = {
		admin_name: {
			length: { min: 1, max: 30 },
			validate: { type: "str", format: "text" },
			msg: { id: "msg_admin_name", too_short: "{$alert.x020201}", too_long: "{$alert.x020202}", format_err: "{$alert.x020203}" }
		},
		admin_pass: {
			length: { min: 1, max: 0 },
			validate: { type: "str", format: "text" },
			msg: { id: "msg_admin_pass", too_short: "{$alert.x020205}" }
		},
		seccode: {
			length: { min: 4, max: 4 },
			validate: { type: "ajax", format: "text" },
			msg: { id: "msg_seccode", too_short: "{$alert.x030201}", too_long: "{$alert.x030201}", ajaxIng: "{$alert.x030401}", ajax_err: "{$alert.x030402}" },
			ajax: { url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=seccode&act_get=chk", key: "seccode", type: "str" }
		}
	};

	function go_login() {
		var obj_validator_form = $("#go_login").baigoValidator(opts_validator_form);
		if (obj_validator_form.validateSubmit()) {
			$("#login_form").submit();
		}
	}

	$(document).ready(function(){
		$("#go_login").click(function(){
			go_login();
		});
		$("body").keydown(function(_e){
			if (_e.keyCode == 13) {
				go_login();
			}
		});
	})
	</script>

	<script src="{$smarty.const.BG_URL_JS}baigoValidator/baigoValidator.js" type="text/javascript"></script>
	<script src="{$smarty.const.BG_URL_JS}reloadImg.js" type="text/javascript"></script>
	<script src="{$smarty.const.BG_URL_JS}bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

</html>
