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

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_head.tpl" cfg=$cfg}

    <li>{$adminMod.user.main.title}</li>

    {include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_left.tpl" cfg=$cfg}

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
        <input type="hidden" name="user_id" value="{$tplData.userRow.user_id}">

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
                                <input type="text" name="user_pass" id="user_pass" class="form-control" placeholder="{$lang.label.onlyModi}">
                            </div>
                        {else}
                            <div class="form-group">
                                <div id="group_user_name">
                                    <label class="control-label">{$lang.label.username}<span id="msg_user_name">*</span></label>
                                    <input type="text" name="user_name" id="user_name" data-validate class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <div id="group_user_pass">
                                    <label class="control-label">{$lang.label.password}<span id="msg_user_pass">*</span></label>
                                    <input type="text" name="user_pass" id="user_pass" data-validate class="form-control">
                                </div>
                            </div>
                        {/if}

                        <div class="form-group">
                            <div id="group_user_mail">
                                <label class="control-label">{$lang.label.mail}<span id="msg_user_mail">{$str_mailNeed}</span></label>
                                <input type="text" name="user_mail" id="user_mail" value="{$tplData.userRow.user_mail}" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div id="group_user_nick">
                                <label class="control-label">{$lang.label.nick}<span id="msg_user_nick"></span></label>
                                <input type="text" name="user_nick" id="user_nick" value="{$tplData.userRow.user_nick}" data-validate class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div id="group_user_note">
                                <label class="control-label">{$lang.label.note}<span id="msg_user_note"></span></label>
                                <input type="text" name="user_note" id="user_note" value="{$tplData.userRow.user_note}" data-validate class="form-control">
                            </div>
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

                    <div class="form-group">
                        <label class="control-label">{$lang.label.status}<span id="msg_user_status">*</span></label>
                        {foreach $status.user as $key=>$value}
                            <div class="radio_baigo">
                                <label for="user_status_{$key}">
                                    <input type="radio" name="user_status" id="user_status_{$key}" value="{$key}" {if $tplData.userRow.user_status == $key}checked{/if} data-validate="user_status">
                                    {$value}
                                </label>
                            </div>
                        {/foreach}
                    </div>

                    {foreach $tplData.userRow.user_contact as $key=>$value}
                        <div class="form-group">
                            <div id="group_user_contact_{$key}">
                                <label class="control-label">{$value.key}</label>
                                <input type="hidden" name="user_contact[{$key}][key]" value="{$tplData.userRow.user_contact[$key].key}">
                                <input type="text" name="user_contact[{$key}][value]" value="{$tplData.userRow.user_contact[$key].value}" class="form-control">
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>
    </form>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_foot.tpl" cfg=$cfg}

    <script type="text/javascript">
    var opts_validator_form = {
        user_name: {
            len: { min: 1, max: 30 },
            validate: { type: "ajax", format: "strDigit", group: "#group_user_name" },
            msg: { selector: "#msg_user_name", too_short: "{$alert.x010201}", too_long: "{$alert.x010202}", format_err: "{$alert.x010203}", ajaxIng: "{$alert.x030401}", ajax_err: "{$alert.x030402}" },
            ajax: { url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=user&act_get=chkname", key: "user_name", type: "str", attach_selectors: ["#user_id"], attach_keys: ["user_id"] }
        },
        user_mail: {
            len: { min: {$num_mailMin}, max: 300 },
            validate: { type: "ajax", format: "email", group: "#group_user_mail" },
            msg: { selector: "#msg_user_mail", too_short: "{$alert.x010206}", too_long: "{$alert.x010207}", format_err: "{$alert.x010208}", ajaxIng: "{$alert.x030401}", ajax_err: "{$alert.x030402}" },
            ajax: { url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=user&act_get=chkmail", key: "user_mail", type: "str", attach_selectors: ["#user_id"], attach_keys: ["user_id"] }
        },
        user_pass: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text", group: "#group_user_pass" },
            msg: { selector: "#msg_user_pass", too_short: "{$alert.x010212}" }
        },
        user_nick: {
            len: { min: 0, max: 30 },
            validate: { type: "str", format: "text", group: "#group_user_nick" },
            msg: { selector: "#msg_user_nick", too_long: "{$alert.x010214}" }
        },
        user_note: {
            len: { min: 0, max: 30 },
            validate: { type: "str", format: "text", group: "#group_user_note" },
            msg: { selector: "#msg_user_note", too_long: "{$alert.x020208}" }
        },
        user_status: {
            len: { min: 1, max: 0 },
            validate: { selector: "[name='user_status']", type: "radio" },
            msg: { selector: "#msg_user_status", too_few: "{$alert.x020203}" }
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
            if (obj_validate_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    })
    </script>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/html_foot.tpl" cfg=$cfg}
