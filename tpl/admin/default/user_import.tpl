{* user_list.tpl 管理员列表 *}
{$cfg = [
	title          => "{$adminMod.user.main.title} - {$adminMod.user.sub.import.title}",
	menu_active    => "user",
	sub_active     => "import",
	baigoCheckall  => "true",
	baigoValidator => "true",
	baigoSubmit    => "true",
	tokenReload    => "true",
	upload         => "true",
	md5            => "true",
	str_url        => "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=user"
]}

{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_head.tpl" cfg=$cfg}

	<li><a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=user">{$adminMod.user.main.title}</a></li>
	<li>{$adminMod.user.sub.import.title}</li>

	{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_left.tpl" cfg=$cfg}

	<div class="form-group">
		<div class="pull-left">
			<ul class="nav nav-pills nav_baigo">
				<li>
					<a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=user&act_get=list">
						<span class="glyphicon glyphicon-chevron-left"></span>
						{$lang.href.back}
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
		<div class="clearfix"></div>
	</div>

    <div class="row">
    	<div class="col-md-6">
    		<div class="panel panel-default">
				<div class="panel-body">
					{if $tplData.csvRows}
						<form name="csv_convert" id="csv_convert">
							<input type="hidden" name="token_session" class="token_session" value="{$common.token_session}">
							<input type="hidden" name="act_post" value="convert">

							<table class="table_convert">
								<thead>
									<tr>
										<th>{$lang.label.source}</th>
										<th> </th>
										<th>{$lang.label.convert}</th>
									</tr>
								</thead>
								<tbody>
									{foreach $tplData.csvRows.0 as $key=>$value}
										{if $value}
											<tr>
												<td>{$value}</td>
												<td>
													<span class="glyphicon glyphicon-arrow-right"></span>
												</td>
												<td>
													<select name="user_convert[{$key}]" class="form-control">
														<option {if $key == 0}selected{/if} value="user_name">{$lang.label.username}</option>
														<option {if $key == 1}selected{/if} value="user_pass">{$lang.label.password}</option>
														<option {if $key == 2}selected{/if} value="user_nick">{$lang.label.nick}</option>
														<option value="abort">{$lang.option.abort}</option>
													</select>
												</td>
											</tr>
										{/if}
									{/foreach}
								</tbody>
								<tfoot>
									<tr>
										<td colspan="3">
											<button type="button" class="btn btn-primary" id="go_convert">{$lang.btn.convert}</button>
										</td>
									</tr>
								</tfoot>
							</table>
						</form>
					{/if}
				</div>
			</div>
    	</div>
    	<div class="col-md-6">
            <div class="well">
				<div class="alert alert-warning">
					{$lang.text.refreshImport}
				</div>
				<form name="csv_import" id="csv_import">
					<div class="form-group">
						<a class="btn btn-success fileinput-button" id="upload_select">
							<span class="glyphicon glyphicon-upload"></span>
							{$lang.btn.uploadCsv}
							<!-- The file input field used as target for the file upload widget -->
							<input id="csv_files" type="file" name="csv_files">
						</a>
					</div>

					<div id="progress_upload" class="progress">
						<div class="progress-bar progress-bar-info progress-bar-striped active"></div>
					</div>

					<div id="csv_uploads" class="csv_uploads"></div>
				</form>
				<form name="csv_del" id="csv_del">
					<input type="hidden" name="token_session" class="token_session" value="{$common.token_session}">
					<input type="hidden" name="act_post" value="csvDel">
					<div class="form-group">
    					<button class="btn btn-primary" type="button" id="go_del">{$lang.btn.delCsv}</button>
					</div>
				</form>
				<hr>
				<div class="form-group">
    				<label class="control-label">{$lang.label.md5tool}</label>
    				<input type="text" id="pass_src" class="form-control" placeholder="{$lang.label.password}">
				</div>
				<div class="form-group">
    				<label class="control-label">{$lang.label.md5result}</label>
    				<input type="text" id="pass_result" class="form-control" placeholder="{$lang.label.md5result}">
				</div>
				<div class="form-group">
					<button type="button" id="md5_do" class="btn btn-primary">{$lang.btn.md5gen}</button>
				</div>
			</div>
    	</div>
    </div>

	<div class="panel panel-default">
		<div class="panel-body">
			<h3>{$lang.label.preview}</h3>
		</div>
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					{foreach $tplData.csvRows.0 as $key=>$value}
						<th>{$value}</th>
					{/foreach}
				</tr>
			</thead>
			<tbody>
				{foreach $tplData.csvRows as $key=>$value}
					{if $key > 0}
						<tr>
							{foreach $value as $key_s=>$value_s}
								<td>{$value_s}</td>
							{/foreach}
						</tr>
					{/if}
				{/foreach}
			</tbody>
		</table>
	</div>

{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_foot.tpl" cfg=$cfg}

	<script type="text/javascript">
	var opts_submit_convert = {
		ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=user",
		text_submitting: "{$lang.label.submitting}",
		btn_text: "{$lang.btn.ok}",
		btn_url: "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=user&act_get=list",
		btn_close: "{$lang.btn.close}"
	};

	var opts_submit_del = {
		ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=user",
		text_submitting: "{$lang.label.submitting}",
		btn_text: "{$lang.btn.ok}",
		btn_url: "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=user&act_get=import",
		btn_close: "{$lang.btn.close}"
	};

	function upload_msg(_upload_name, _upload_msg) {
		_str_msg = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">" +
			"<span aria-hidden=\"true\">&times;</span>" +
			"<span class=\"sr-only\">Close</span>" +
		"</button>" +
		"<div class=\"alert_overflow\">" + _upload_name + "</div>" +
		"<div>" + _upload_msg + "</div>";

		return _str_msg;
	}

	$(document).ready(function(){
		$("#csv_files").fileupload({
			formData: [
				{ name: "token_session", value: "{$common.token_session}" },
				{ name: "act_post", value: "import" },
			],
			dataType: "json",
			url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=user",
			add: function(e, data) {
				var goUpload    = true;
				var obj_file    = data.files[0];
				var _str_name   = obj_file.name.toLocaleLowerCase()
				if (obj_file.type == "text/csv" || obj_file.type == "application/vnd.ms-excel" || _str_name.indexOf(".csv") > 0) {
					_str_msg = upload_msg(obj_file.name, "{$lang.label.uploading}");
					data.context = $("<div/>").html(_str_msg).appendTo("#csv_uploads");
					data.context.attr("class", "alert alert-info alert-dismissible");
				} else {
					goUpload = false;
					_str_msg = upload_msg(obj_file.name, "{$alert.x010219}");
					data.context = $("<div/>").html(_str_msg).appendTo("#csv_uploads");
					data.context.attr("class", "alert alert-danger alert-dismissible");
				}
				if (goUpload) {
					data.submit();
				}
				setTimeout("$('#csv_uploads').empty();", 20000);
			},
			done: function(e, data) {
				obj_data = data.result;
				if (obj_data.alert != "y010403") {
					_str_msg = upload_msg(obj_data.file_name, obj_data.msg);
					data.context.html(_str_msg);
					data.context.attr("class", "alert alert-danger alert-dismissible");
				} else {
					_str_msg = upload_msg(obj_data.file_name, "{$lang.label.uploadSucc}");
					data.context.html(_str_msg);
					data.context.attr("class", "alert alert-success alert-dismissible");
				}
				setTimeout("$('#csv_uploads').empty();", 5000);
			},
			progressall: function(e, data) {
				var _progress_percent = parseInt(data.loaded / data.total * 100, 10);
				$("#progress_upload .progress-bar").text(_progress_percent + "%");
				$("#progress_upload .progress-bar").css("min-width", "20%");
				$("#progress_upload .progress-bar").css("width", _progress_percent + "%");
			}
		});
		var obj_submit_convert = $("#csv_convert").baigoSubmit(opts_submit_convert);
		$("#go_convert").click(function(){
			obj_submit_convert.formSubmit();
		});
		var obj_submit_del = $("#csv_del").baigoSubmit(opts_submit_del);
		$("#go_del").click(function(){
			obj_submit_del.formSubmit();
		});
		$("#md5_do").click(function(){
			var _src = $("#pass_src").val();
			$("#pass_result").val($.md5(_src));
		});

	});
	</script>

{include "{$smarty.const.BG_PATH_TPL}admin/default/include/html_foot.tpl" cfg=$cfg}
