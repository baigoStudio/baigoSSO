{* admin_form.tpl 管理员编辑界面 *}
{$cfg = [
    title          => "{$adminMod.app.main.title} - {$lang.page.detail}",
    menu_active    => "app",
    sub_active     => "list",
    baigoValidator => "true",
    baigoSubmit    => "true"
]}

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_head.tpl" cfg=$cfg}

    <li><a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=app&act_get=list">{$adminMod.app.main.title}</a></li>
    <li>{$lang.page.detail}</li>

    {include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_left.tpl" cfg=$cfg}

    <div class="form-group">
        <ul class="nav nav-pills nav_baigo">
            <li>
                <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=app&act_get=list">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    {$lang.href.back}
                </a>
            </li>
        </ul>
    </div>

    <form name="app_form" id="app_form">
        <input type="hidden" name="token_session" class="token_session" value="{$common.token_session}">
        <input type="hidden" name="act_post" id="act_post" value="reset">
        <input type="hidden" name="app_id" value="{$tplData.appRow.app_id}">

        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label static_label">{$lang.label.appName}</label>
                            <p class="form-control-static input-lg">{$tplData.appRow.app_name}</p>
                        </div>

                        <div class="form-group">
                            <label class="control-label static_label">{$lang.label.apiUrl}</label>
                            <p class="form-control-static">{$smarty.const.BG_SITE_URL}{$smarty.const.BG_URL_API}api.php</p>
                        </div>

                        <div class="form-group">
                            <label class="control-label static_label">{$lang.label.appId}</label>
                            <p class="form-control-static input-lg">{$tplData.appRow.app_id}</p>
                        </div>

                        <div class="form-group">
                            <label class="control-label static_label">{$lang.label.appKey}</label>
                            <p class="form-control-static">{$tplData.appRow.app_key}</p>
                        </div>

                        <div class="form-group">
                            <button type="button" class="go_reset btn btn-primary">{$lang.btn.resetKey}</button>
                            <p class="help-block">{$lang.label.appKeyNote}</p>
                        </div>

                        <div class="form-group">
                            <label class="control-label static_label">{$lang.label.appUrlNotice}</label>
                            <p class="form-control-static">{$tplData.appRow.app_url_notice}</p>
                        </div>

                        <div class="form-group">
                            <label class="control-label static_label">{$lang.label.appUrlSync}</label>
                            <p class="form-control-static">{$tplData.appRow.app_url_sync}</p>
                        </div>

                        <div class="form-group">
                            <label class="control-label static_label">{$lang.label.allow}</label>
                            <dl class="list_dl">
                                {foreach $allow as $key_m=>$value_m}
                                    <dt>{$value_m.title}</dt>
                                    <dd>
                                        <ul class="list-inline">
                                            {foreach $value_m.allow as $key_s=>$value_s}
                                                <li>
                                                    <span class="glyphicon glyphicon-{if isset($tplData.appRow.app_allow[$key_m][$key_s])}ok-circle text-success{else}remove-circle text-danger{/if}"></span>
                                                    {$value_s}
                                                </li>
                                            {/foreach}
                                        </ul>
                                    </dd>
                                {/foreach}
                            </dl>
                        </div>

                        <div class="form-group">
                            <label class="control-label static_label">{$lang.label.ipAllow}</label>
                            <p class="form-control-static">
                                <pre>{$tplData.appRow.app_ip_allow}</pre>
                            </p>
                        </div>

                        <div class="form-group">
                            <label class="control-label static_label">{$lang.label.ipBad}</label>
                            <p class="form-control-static">
                                <pre>{$tplData.appRow.app_ip_bad}</pre>
                            </p>
                        </div>

                        <div class="form-group">
                            <label class="control-label static_label">{$lang.label.note}</label>
                            <p class="form-control-static input-lg">{$tplData.appRow.app_note}</p>
                        </div>

                        <div class="form-group">
                            <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=app&act_get=form&app_id={$tplData.appRow.app_id}">
                                <span class="glyphicon glyphicon-edit"></span>
                                {$lang.href.edit}
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="well">
                    <div class="form-group">
                        <label class="control-label static_label">{$lang.label.id}</label>
                        <p class="form-control-static">{$tplData.appRow.app_id}</p>
                    </div>

                    {if $tplData.appRow.app_status == "enable"}
                        {$css_status = "success"}
                    {else}
                        {$css_status = "default"}
                    {/if}

                    <div class="form-group">
                        <label class="control-label">{$lang.label.status}</label>
                        <p class="form-control-static label_baigo">
                            <span class="label label-{$css_status}">{$status.app[$tplData.appRow.app_status]}</span>
                        </p>
                    </div>

                    {if $tplData.appRow.app_sync == "on"}
                        {$css_status = "success"}
                    {else}
                        {$css_status = "default"}
                    {/if}

                    <div class="form-group">
                        <label class="control-label">{$lang.label.sync}</label>
                        <p class="form-control-static label_baigo">
                            <span class="label label-{$css_status}">{$status.appSync[$tplData.appRow.app_sync]}</span>
                        </p>
                    </div>

                    <div class="form-group">
                        <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=app&act_get=form&app_id={$tplData.appRow.app_id}">
                            <span class="glyphicon glyphicon-edit"></span>
                            {$lang.href.edit}
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </form>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_foot.tpl" cfg=$cfg}

    <script type="text/javascript">
    var opts_submit_form = {
        ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=app",
        confirm_selector: "#act_post",
        confirm_val: "reset",
        confirm_msg: "{$lang.confirm.resetKey}",
        btn_text: "{$lang.btn.ok}",
        btn_close: "{$lang.btn.close}",
        btn_url: "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=app&act_get=show&app_id={$tplData.appRow.app_id}"
    };

    $(document).ready(function(){
        var obj_submit_form = $("#app_form").baigoSubmit(opts_submit_form);
        $(".go_reset").click(function(){
            obj_submit_form.formSubmit();
        });
    });
    </script>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/html_foot.tpl" cfg=$cfg}
