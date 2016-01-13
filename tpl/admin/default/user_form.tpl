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
    baigoValidator => "true",
    baigoSubmit    => "true",
    tokenReload    => "true",
    str_url        => "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=user"
]}

{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_head.tpl" cfg=$cfg}

    <li>{$adminMod.user.main.title}</li>

    {include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_left.tpl" cfg=$cfg}

    <div class="form-group">
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

    <form name="user_form" id="user_form">
        <input type="hidden" name="token_session" class="token_session" value="{$common.token_session}">
        <input type="hidden" name="act_post" value="submit">
        <input type="hidden" name="user_id" id="user_id" value="{$tplData.userRow.user_id}">

        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-body">
                        {if $tplData.userRow.user_id > 0}
                            <div class="form-group">
                                <label class="control-label">{$lang.label.username}<span id="msg_user_name">*</span></label>
                                <input type="text" name="user_name" id="user_name" readonly value="{$tplData.userRow.user_name}" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="control-label">{$lang.label.password}</label>
                                <input type="text" name="user_pass" id="user_pass" class="form-control" placeholder="{$lang.label.modOnly}">
                            </div>
                        {else}
                            <div class="form-group">
                                <label class="control-label">{$lang.label.username}<span id="msg_user_name">*</span></label>
                                <input type="text" name="user_name" id="user_name" class="validate form-control">
                            </div>

                            <div class="form-group">
                                <label class="control-label">{$lang.label.password}<span id="msg_user_pass">*</span></label>
                                <input type="text" name="user_pass" id="user_pass" class="validate form-control">
                            </div>
                        {/if}

                        <div class="form-group">
                            <label class="control-label">{$lang.label.mail}<span id="msg_user_mail">{$str_mailNeed}</span></label>
                            <input type="text" name="user_mail" id="user_mail" value="{$tplData.userRow.user_mail}" class="validate form-control">
                        </div>

                        <div class="form-group">
                            <label class="control-label">{$lang.label.nick}<span id="msg_user_nick"></span></label>
                            <input type="text" name="user_nick" id="user_nick" value="{$tplData.userRow.user_nick}" class="validate form-control">
                        </div>

                        <div class="form-group">
                            <label class="control-label">{$lang.label.note}<span id="msg_user_note"></span></label>
                            <input type="text" name="user_note" id="user_note" value="{$tplData.userRow.user_note}" class="validate form-control">
                        </div>

                        <div class="form-group">
                            <button type="button" id="go_form" class="btn btn-primary">{$lang.btn.save}</button>
                            <a class="btn btn-default" href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=user&act_get=list">{$lang.btn.close}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="well">
                    {if $tplData.userRow.user_id > 0}
                        <div class="form-group">
                            <label class="control-label">{$lang.label.id}</label>
                            <p class="form-control-static">{$tplData.userRow.user_id}</p>
                        </div>
                    {/if}

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
                </div>
            </div>
        </div>
    </form>

{include "{$smarty.const.BG_PATH_TPL}admin/default/include/admin_foot.tpl" cfg=$cfg}

    <script type="text/javascript">
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
            msg: { id: "msg_user_note", too_long: "{$alert.x020208}" }
        },
        user_status: {
            length: { min: 1, max: 0 },
            validate: { type: "radio" },
            msg: { id: "msg_user_status", too_few: "{$alert.x020203}" }
        }
    };

    var opts_submit_form = {
        ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=user",
        text_submitting: "{$lang.label.submitting}",
        btn_text: "{$lang.btn.ok}",
        btn_close: "{$lang.btn.close}",
        btn_url: "{$cfg.str_url}"
    };

    $(document).ready(function(){
        var obj_validate_form = $("#user_form").baigoValidator(opts_validator_form);
        var obj_submit_form   = $("#user_form").baigoSubmit(opts_submit_form);
        $("#go_form").click(function(){
            if (obj_validate_form.validateSubmit()) {
                obj_submit_form.formSubmit();
            }
        });
    })
    </script>

{include "{$smarty.const.BG_PATH_TPL}admin/default/include/html_foot.tpl" cfg=$cfg}
