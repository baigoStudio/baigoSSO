{* pm_send.tpl 管理员编辑界面 *}
{$cfg = [
    title          => "{$adminMod.pm.main.title} - {$adminMod.pm.sub.send.title}",
    menu_active    => "pm",
    sub_active     => "send",
    baigoCheckall  => "true",
    baigoValidator => "true",
    baigoSubmit    => "true",
    tokenReload    => "true",
    datetimepicker => "true",
    str_url        => "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=pm"
]}

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_head.tpl" cfg=$cfg}

    <li><a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=pm&act_get=list">{$adminMod.pm.main.title}</a></li>
    <li>{$adminMod.pm.sub.send.title}</li>

    {include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_left.tpl" cfg=$cfg}

    <div class="form-group">
        <ul class="nav nav-pills nav_baigo">
            <li>
                <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=pm&act_get=list">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    {$lang.href.back}
                </a>
            </li>
            <li>
                <a href="{$smarty.const.BG_URL_HELP}ctl.php?mod=admin&act_get=pm#form" target="_blank">
                    <span class="glyphicon glyphicon-question-sign"></span>
                    {$lang.href.help}
                </a>
            </li>
        </ul>
    </div>

    <form name="pm_send" id="pm_send">
        <input type="hidden" name="token_session" class="token_session" value="{$common.token_session}">
        <input type="hidden" name="act_post" value="send">

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <div id="group_pm_to">
                        <label class="control-label">{$lang.label.pmTo}<span id="msg_pm_to">*</span></label>
                        <input type="text" name="pm_to" id="pm_to" data-validate class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <div id="group_pm_title">
                        <label class="control-label">{$lang.label.title}<span id="msg_pm_title"></span></label>
                        <input type="text" name="pm_title" id="pm_title" data-validate class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <div id="group_pm_content">
                        <label class="control-label">{$lang.label.content}<span id="msg_pm_content">*</span></label>
                        <textarea name="pm_content" id="pm_content" data-validate class="form-control text_md"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <button type="button" class="go_form btn btn-primary">{$lang.btn.save}</button>
                </div>
            </div>
        </div>
    </form>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_foot.tpl" cfg=$cfg}

    <script type="text/javascript">
    var opts_validator_form = {
        pm_title: {
            len: { min: 0, max: 90 },
            validate: { type: "str", format: "text", group: "#group_pm_title" },
            msg: { selector: "#msg_pm_title", too_long: "{$alert.x110202}" }
        },
        pm_content: {
            len: { min: 1, max: 900 },
            validate: { type: "str", format: "text", group: "#group_pm_content" },
            msg: { selector: "#msg_pm_content", too_short: "{$alert.x110201}", too_long: "{$alert.x110203}" }
        },
        pm_to: {
            len: { min: 1, max: 0 },
            validate: { type: "ajax", format: "text", group: "#group_pm_to" },
            msg: { selector: "#msg_pm_to", too_short: "{$alert.x110205}", ajaxIng: "{$alert.x030401}", ajax_err: "{$alert.x030402}" },
            ajax: { url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=user&act_get=getname", key: "user_name", type: "str" }
        }
    };

    var opts_submit_form = {
        ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=pm",
        text_submitting: "{$lang.label.submitting}",
        btn_text: "{$lang.btn.ok}",
        btn_close: "{$lang.btn.close}",
        btn_url: "{$cfg.str_url}"
    };

    $(document).ready(function(){
        var obj_validator_form  = $("#pm_send").baigoValidator(opts_validator_form);
        var obj_submit_form     = $("#pm_send").baigoSubmit(opts_submit_form);
        $(".go_form").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    });
    </script>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/html_foot.tpl" cfg=$cfg}
