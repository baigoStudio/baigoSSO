{* pm_form.tpl 管理员编辑界面 *}
{$cfg = [
    title          => "{$adminMod.pm.main.title} - {$adminMod.pm.sub.bulk.title}",
    menu_active    => "pm",
    sub_active     => "bulk",
    baigoCheckall  => "true",
    baigoValidator => "true",
    baigoSubmit    => "true",
    tokenReload    => "true",
    datetimepicker => "true",
    str_url        => "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=pm"
]}

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_head.tpl" cfg=$cfg}

    <li><a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=pm&act_get=list">{$adminMod.pm.main.title}</a></li>
    <li>{$adminMod.pm.sub.bulk.title}</li>

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

    <form name="pm_form" id="pm_form">
        <input type="hidden" name="{$common.tokenRow.name_session}" value="{$common.tokenRow.token}">
        <input type="hidden" name="act_post" value="bulk">

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <div id="group_pm_bulk_type">
                        <label class="control-label">{$lang.label.pmBulkType}<span id="msg_pm_bulk_type">*</span></label>
                        <select name="pm_bulk_type" id="pm_bulk_type" data-validate class="form-control">
                            <option value="bulkUsers">{$lang.label.bulkUsers}</option>
                            <option value="bulkKeyName">{$lang.label.bulkKeyName}</option>
                            <option value="bulkKeyMail">{$lang.label.bulkKeyMail}</option>
                            <option value="bulkRangeId">{$lang.label.bulkRangeId}</option>
                            <option value="bulkRangeTime">{$lang.label.bulkRangeTime}</option>
                            <option value="bulkRangeLogin">{$lang.label.bulkRangeLogin}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div id="bulkUsers" class="bulk_types">
                        <div id="group_pm_to_users">
                            <label class="control-label">{$lang.label.pmTo}<span id="msg_pm_to_users">*</span></label>
                            <input type="text" name="pm_to_users" id="pm_to_users" data-validate class="form-control">
                            <p class="help-block">{$lang.label.toNote}</p>
                        </div>
                    </div>

                    <div id="bulkKeyName" class="bulk_types">
                        <div id="group_pm_to_key_name">
                            <label class="control-label">{$lang.label.key}<span id="msg_pm_to_key_name">*</span></label>
                            <input type="text" name="pm_to_key_name" id="pm_to_key_name" data-validate class="form-control">
                            <p class="help-block">{$lang.label.keyNameNote}</p>
                        </div>
                    </div>

                    <div id="bulkKeyMail" class="bulk_types">
                        <div id="group_pm_to_key_mail">
                            <label class="control-label">{$lang.label.key}<span id="msg_pm_to_key_mail">*</span></label>
                            <input type="text" name="pm_to_key_mail" id="pm_to_key_mail" data-validate class="form-control">
                            <p class="help-block">{$lang.label.keyMailNote}</p>
                        </div>
                    </div>

                    <div id="bulkRangeId" class="bulk_types">
                        <div id="group_pm_to_range_id">
                            <label class="control-label">{$lang.label.id}<span id="msg_pm_to_range_id">*</span></label>
                            <div class="input-group">
                                <input type="text" name="pm_to_min_id" id="pm_to_min_id" data-validate class="form-control">
                                <span class="input-group-addon input_range">{$lang.label.to}</span>
                                <input type="text" name="pm_to_max_id" id="pm_to_max_id" data-validate class="form-control">
                            </div>
                            <p class="help-block">{$lang.label.rangeIdNote}</p>
                        </div>
                    </div>

                    <div id="bulkRangeTime" class="bulk_types">
                        <div id="group_pm_to_range_time">
                            <label class="control-label">{$lang.label.timeReg}<span id="msg_pm_to_range_time">*</span></label>
                            <div class="input-group">
                                <input type="text" name="pm_to_begin_time" id="pm_to_begin_time" data-validate class="form-control input_date" value="{$tplData.begin_time|date_format:"{$smarty.const.BG_SITE_DATE} {$smarty.const.BG_SITE_TIME}"}">
                                <span class="input-group-addon input_range">{$lang.label.to}</span>
                                <input type="text" name="pm_to_end_time" id="pm_to_end_time" data-validate class="form-control input_date" value="{$tplData.end_time|date_format:"{$smarty.const.BG_SITE_DATE} {$smarty.const.BG_SITE_TIME}"}">
                            </div>
                            <p class="help-block">{$lang.label.rangeTimeNote}</p>
                        </div>
                    </div>

                    <div id="bulkRangeLogin" class="bulk_types">
                        <div id="group_pm_to_range_login">
                            <label class="control-label">{$lang.label.timeLogin}<span id="msg_pm_to_range_login">*</span></label>
                            <div class="input-group">
                                <input type="text" name="pm_to_begin_login" id="pm_to_begin_login" data-validate class="form-control input_date" value="{$tplData.begin_time|date_format:"{$smarty.const.BG_SITE_DATE} {$smarty.const.BG_SITE_TIME}"}">
                                <span class="input-group-addon input_range">{$lang.label.to}</span>
                                <input type="text" name="pm_to_end_login" id="pm_to_end_login" data-validate class="form-control input_date" value="{$tplData.end_time|date_format:"{$smarty.const.BG_SITE_DATE} {$smarty.const.BG_SITE_TIME}"}">
                            </div>
                            <p class="help-block">{$lang.label.rangeLoginNote}</p>
                        </div>
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
        pm_bulk_type: {
            len: { min: 1, max: 0 },
            validate: { type: "select", group: "#group_pm_bulk_type" },
            msg: { selector: "#msg_pm_bulk_type", too_short: "{$alert.x110204}" }
        },
        pm_to_users: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text", group: "#group_pm_to_users" },
            msg: { selector: "#msg_pm_to_users", too_short: "{$alert.x110205}" }
        },
        pm_to_key_name: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text", group: "#group_pm_to_key_name" },
            msg: { selector: "#msg_pm_to_key_name", too_short: "{$alert.x110206}" }
        },
        pm_to_key_mail: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "text", group: "#group_pm_to_key_mail" },
            msg: { selector: "#msg_pm_to_key_mail", too_short: "{$alert.x110207}" }
        },
        pm_to_min_id: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "int", group: "#pm_to_min_id" },
            msg: { selector: "#msg_pm_to_min_id", too_short: "{$alert.x110208}", format_err: "{$alert.x110209}" }
        },
        pm_to_max_id: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "int", group: "#pm_to_max_id" },
            msg: { selector: "#msg_pm_to_max_id", too_short: "{$alert.x110210}", format_err: "{$alert.x110209}" }
        },
        pm_to_begin_time: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "datetime", group: "#pm_to_begin_time" },
            msg: { selector: "#msg_pm_to_begin_time", too_short: "{$alert.x110212}", format_err: "{$alert.x110213}" }
        },
        pm_to_end_time: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "datetime", group: "#pm_to_end_time" },
            msg: { selector: "#msg_pm_to_end_time", too_short: "{$alert.x110214}", format_err: "{$alert.x110213}" }
        },
        pm_to_begin_login: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "datetime", group: "#pm_to_begin_login" },
            msg: { selector: "#msg_pm_to_begin_login", too_short: "{$alert.x110215}", format_err: "{$alert.x110216}" }
        },
        pm_to_end_login: {
            len: { min: 1, max: 0 },
            validate: { type: "str", format: "datetime", group: "#pm_to_end_login" },
            msg: { selector: "#msg_pm_to_end_login", too_short: "{$alert.x110217}", format_err: "{$alert.x110216}" }
        }
    };

    var opts_submit_form = {
        ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=pm",
        text_submitting: "{$lang.label.submitting}",
        btn_text: "{$lang.btn.ok}",
        btn_close: "{$lang.btn.close}",
        btn_url: "{$cfg.str_url}"
    };

    function bulk_type(_type_id) {
        $(".bulk_types").hide();
        $("#" + _type_id).show();
    }

    $(document).ready(function(){
        bulk_type("bulkUsers");
        var obj_validator_form    = $("#pm_form").baigoValidator(opts_validator_form);
        var obj_submit_form       = $("#pm_form").baigoSubmit(opts_submit_form);
        $(".go_form").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
        $("#pm_bulk_type").change(function(){
            var _type_id = $(this).val();
            bulk_type(_type_id);
        });
        $(".input_date").datetimepicker(opts_datetimepicker);
    });
    </script>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/html_foot.tpl" cfg=$cfg}
