{* app_form.tpl 管理员编辑界面 *}
{if $tplData.appRow.app_id < 1}
    {$title_sub = $lang.page.add}
    {$str_sub = "form"}
{else}
    {$title_sub = $lang.page.edit}
    {$str_sub = "list"}
{/if}

{$cfg = [
    title          => "{$adminMod.app.main.title} - {$title_sub}",
    menu_active    => "app",
    sub_active     => $str_sub,
    baigoCheckall  => "true",
    baigoValidator => "true",
    baigoSubmit    => "true",
    tokenReload    => "true",
    str_url        => "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=app"
]}

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_head.tpl" cfg=$cfg}

    <li><a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=app&act_get=list">{$adminMod.app.main.title}</a></li>
    <li>{$title_sub}</li>

    {include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_left.tpl" cfg=$cfg}

    <div class="form-group">
        <ul class="nav nav-pills nav_baigo">
            <li>
                <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=app&act_get=list">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    {$lang.href.back}
                </a>
            </li>
            <li>
                <a href="{$smarty.const.BG_URL_HELP}ctl.php?mod=admin&act_get=app#form" target="_blank">
                    <span class="glyphicon glyphicon-question-sign"></span>
                    {$lang.href.help}
                </a>
            </li>
        </ul>
    </div>

    <form name="app_form" id="app_form">
        <input type="hidden" name="{$common.tokenRow.name_session}" value="{$common.tokenRow.token}">
        <input type="hidden" name="act_post" value="submit">
        <input type="hidden" name="app_id" value="{$tplData.appRow.app_id}">

        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <div id="group_app_name">
                                <label class="control-label">{$lang.label.appName}<span id="msg_app_name">*</span></label>
                                <input type="text" name="app_name" id="app_name" value="{$tplData.appRow.app_name}" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div id="group_app_url_notify">
                                <label class="control-label">{$lang.label.appUrlNotify}<span id="msg_app_url_notify">*</span></label>
                                <input type="text" name="app_url_notify" id="app_url_notify" value="{$tplData.appRow.app_url_notify}" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div id="group_app_url_sync">
                                <label class="control-label">{$lang.label.appUrlSync}<span id="msg_app_url_sync">*</span></label>
                                <input type="text" name="app_url_sync" id="app_url_sync" value="{$tplData.appRow.app_url_sync}" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">{$lang.label.allow}<span id="msg_app_allow">*</span></label>
                            <dl class="list_dl">
                                <dd>
                                    <div class="checkbox_baigo">
                                        <label for="chk_all">
                                            <input type="checkbox" id="chk_all" data-parent="first">
                                            {$lang.label.all}
                                        </label>
                                    </div>
                                </dd>
                                {foreach $allow as $key_m=>$value_m}
                                    <dt>{$value_m.title}</dt>
                                    <dd>
                                        <label for="allow_{$key_m}" class="checkbox-inline">
                                            <input type="checkbox" id="allow_{$key_m}" data-parent="chk_all">
                                            {$lang.label.all}
                                        </label>
                                        {foreach $value_m.allow as $key_s=>$value_s}
                                            <label for="allow_{$key_m}_{$key_s}" class="checkbox-inline">
                                                <input type="checkbox" name="app_allow[{$key_m}][{$key_s}]" value="1" id="allow_{$key_m}_{$key_s}" data-parent="allow_{$key_m}" {if isset($tplData.appRow.app_allow[$key_m][$key_s])}checked{/if}>
                                                {$value_s}
                                            </label>
                                        {/foreach}
                                    </dd>
                                {/foreach}
                            </dl>
                        </div>

                        <div class="form-group">
                            <div id="group_app_ip_allow">
                                <label class="control-label">{$lang.label.ipAllow}<span id="msg_app_ip_allow"></span></label>
                                <textarea name="app_ip_allow" id="app_ip_allow" data-validate class="form-control text_md">{$tplData.appRow.app_ip_allow}</textarea>
                            </div>
                            <p class="help-block">{$lang.label.ipNote}</p>
                        </div>

                        <div class="form-group">
                            <div id="group_app_ip_bad">
                                <label class="control-label">{$lang.label.ipBad}<span id="msg_app_ip_bad"></span></label>
                                <textarea name="app_ip_bad" id="app_ip_bad" data-validate class="form-control text_md">{$tplData.appRow.app_ip_bad}</textarea>
                            </div>
                            <p class="help-block">{$lang.label.ipNote}</p>
                        </div>

                        <div class="form-group">
                            <div id="group_app_note">
                                <label class="control-label">{$lang.label.note}<span id="msg_app_note"></span></label>
                                <input type="text" name="app_note" id="app_note" value="{$tplData.appRow.app_note}" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="button" class="go_form btn btn-primary">{$lang.btn.save}</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="well">
                    {if $tplData.appRow.app_id > 0}
                        <div class="form-group">
                            <label class="control-label">{$lang.label.id}</label>
                            <p class="form-control-static">{$tplData.appRow.app_id}</p>
                        </div>
                    {/if}

                    <div class="form-group">
                        <div id="group_app_status">
                            <label class="control-label">{$lang.label.status}<span id="msg_app_status">*</span></label>
                            {foreach $status.app as $key=>$value}
                                <div class="radio_baigo">
                                    <label for="app_status_{$key}">
                                        <input type="radio" name="app_status" id="app_status_{$key}" value="{$key}" {if $tplData.appRow.app_status == $key}checked{/if} data-validate="app_status">
                                        {$value}
                                    </label>
                                </div>
                            {/foreach}
                        </div>
                    </div>

                    <div class="form-group">
                        <div id="group_app_sync">
                            <label class="control-label">{$lang.label.sync}<span id="msg_app_sync">*</span></label>
                            {foreach $status.appSync as $key=>$value}
                                <div class="radio_baigo">
                                    <label for="app_sync_{$key}">
                                        <input type="radio" name="app_sync" id="app_sync_{$key}" value="{$key}" {if $tplData.appRow.app_sync == $key}checked{/if} data-validate="app_sync">
                                        {$value}
                                    </label>
                                </div>
                            {/foreach}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_foot.tpl" cfg=$cfg}

    <script type="text/javascript">
    var opts_validator_form = {
        app_name: {
            len: { min: 1, max: 30 },
            validate: { type: "str", format: "text", group: "#group_app_name" },
            msg: { selector: "#msg_app_name", too_short: "{$alert.x050201}", too_long: "{$alert.x050202}" }
        },
        app_url_notify: {
            len: { min: 1, max: 3000 },
            validate: { type: "str", format: "url", group: "#group_app_url_notify" },
            msg: { selector: "#msg_app_url_notify", too_short: "{$alert.x050207}", too_long: "{$alert.x050208}", format_err: "{$alert.x050209}" }
        },
        app_url_sync: {
            len: { min: 1, max: 3000 },
            validate: { type: "str", format: "url", group: "#group_app_url_sync" },
            msg: { selector: "#msg_app_url_sync", too_short: "{$alert.x050219}", too_long: "{$alert.x050220}", format_err: "{$alert.x050221}" }
        },
        app_ip_allow: {
            len: { min: 0, max: 3000 },
            validate: { type: "str", format: "text", group: "#group_app_ip_allow" },
            msg: { selector: "#msg_app_ip_allow", too_long: "{$alert.x050210}" }
        },
        app_ip_bad: {
            len: { min: 0, max: 3000 },
            validate: { type: "str", format: "text", group: "#group_app_ip_bad" },
            msg: { selector: "#msg_app_ip_bad", too_long: "{$alert.x050211}" }
        },
        app_note: {
            len: { min: 0, max: 30 },
            validate: { type: "str", format: "text", group: "#group_app_note" },
            msg: { selector: "#msg_app_note", too_long: "{$alert.x050205}" }
        },
        app_status: {
            len: { min: 1, max: 0 },
            validate: { selector: "[name='app_status']", type: "radio", group: "#group_app_status" },
            msg: { selector: "#msg_app_status", too_few: "{$alert.x050206}" }
        },
        app_sync: {
            len: { min: 1, max: 0 },
            validate: { selector: "[name='app_sync']", type: "radio", group: "#group_app_sync" },
            msg: { selector: "#msg_app_sync", too_few: "{$alert.x050218}" }
        }
    };

    var opts_submit_form = {
        ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=app",
        text_submitting: "{$lang.label.submitting}",
        btn_text: "{$lang.btn.ok}",
        btn_close: "{$lang.btn.close}",
        btn_url: "{$cfg.str_url}"
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#app_form").baigoValidator(opts_validator_form);
        var obj_submit_form       = $("#app_form").baigoSubmit(opts_submit_form);
        $(".go_form").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
        $("#app_form").baigoCheckall();
    });
    </script>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/html_foot.tpl" cfg=$cfg}
