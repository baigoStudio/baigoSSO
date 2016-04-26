{* verify_list.tpl 管理员列表 *}
{$cfg = [
    title          => "{$adminMod.log.main.title} - {$adminMod.log.sub.verify.title}",
    menu_active    => "log",
    sub_active     => "verify",
    baigoCheckall  => "true",
    baigoValidator => "true",
    baigoSubmit    => "true",
    tokenReload    => "true",
    str_url        => "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=verify&act_get=list"
]}

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_head.tpl" cfg=$cfg}

    <li><a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=log&act_get=list">{$adminMod.log.main.title}</a></li>
    <li>{$adminMod.log.sub.verify.title}</li>

    {include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_left.tpl" cfg=$cfg}

    <div class="form-group">
        <ul class="nav nav-pills nav_baigo">
            <li>
                <a href="{$smarty.const.BG_URL_HELP}ctl.php?mod=admin&act_get=log" target="_blank">
                    <span class="glyphicon glyphicon-question-sign"></span>
                    {$lang.href.help}
                </a>
            </li>
        </ul>
    </div>

    <form name="verify_list" id="verify_list" class="form-inline">
        <input type="hidden" name="token_session" class="token_session" value="{$common.token_session}">

        <div class="panel panel-default">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-nowrap td_mn">
                                <label for="chk_all" class="checkbox-inline">
                                    <input type="checkbox" name="chk_all" id="chk_all" data-parent="first">
                                    {$lang.label.all}
                                </label>
                            </th>
                            <th class="text-nowrap td_mn">{$lang.label.id}</th>
                            <th>{$lang.label.operator}</th>
                            <th class="text-nowrap td_bg">{$lang.label.status} / {$lang.label.timeInit}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $tplData.verifyRows as $key=>$value}
                            {if $value.verify_status == "enable"}
                                {$css_status = "success"}
                                {$str_status = $status.verify[$value.verify_status]}
                            {else if $value.verify_status == "expired"}
                                {$css_status = "default"}
                                {$str_status = $lang.label.expired}
                            {else}
                                {$css_status = "default"}
                                {$str_status = $status.verify[$value.verify_status]}
                            {/if}
                            <tr>
                                <td class="text-nowrap td_mn"><input type="checkbox" name="verify_ids[]" value="{$value.verify_id}" id="verify_id_{$value.verify_id}" data-validate="verify_id" data-parent="chk_all"></td>
                                <td class="text-nowrap td_mn">{$value.verify_id}</td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li>
                                            {if isset($value.userRow.user_name)}
                                                <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=verify&act_get=list&user_id={$value.userRow.user_id}">{$value.userRow.user_name}</a>
                                            {else}
                                                {$lang.label.unknow}
                                            {/if}
                                        </li>
                                        <li>
                                            <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=verify&act_get=show&verify_id={$value.verify_id}&view=iframe" data-toggle="modal" data-target="#verify_modal">{$lang.href.show}</a>
                                        </li>
                                    </ul>
                                </td>
                                <td class="text-nowrap td_bg">
                                    <ul class="list-unstyled">
                                        <li class="label_baigo">
                                            <span class="label label-{$css_status}">{$str_status}</span>
                                        </li>
                                        <li>{$value.verify_time_refresh|date_format:"{$smarty.const.BG_SITE_DATE} {$smarty.const.BG_SITE_TIMESHORT}"}</li>
                                    </ul>
                                </td>
                            </tr>
                        {/foreach}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><span id="msg_verify_id"></span></td>
                            <td colspan="3">
                                <div class="form-group">
                                    <div id="group_act_post">
                                        <select name="act_post" id="act_post" data-validate class="form-control input-sm">
                                            <option value="">{$lang.option.batch}</option>
                                            {foreach $status.verify as $key=>$value}
                                                <option value="{$key}">{$value}</option>
                                            {/foreach}
                                            <option value="del">{$lang.option.del}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="button" id="go_list" class="btn btn-sm btn-primary">{$lang.btn.submit}</button>
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

    <div class="text-right">
        {include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/page.tpl" cfg=$cfg}
    </div>

    <div class="modal fade" id="verify_modal">
        <div class="modal-dialog">
            <div class="modal-content"></div>
        </div>
    </div>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_foot.tpl" cfg=$cfg}

    <script type="text/javascript">
    var opts_validator_list = {
        verify_id: {
            len: { min: 1, max: 0 },
            validate: { selector: "[data-validate='verify_id']", type: "checkbox" },
            msg: { selector: "#msg_verify_id", too_few: "{$alert.x030202}" }
        },
        act_post: {
            len: { min: 1, max: 0 },
            validate: { type: "select", group: "#group_act_post" },
            msg: { selector: "#msg_act_post", too_few: "{$alert.x030203}" }
        }
    };
    var opts_submit_list = {
        ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=verify",
        confirm_selector: "#act_post",
        confirm_val: "del",
        confirm_msg: "{$lang.confirm.del}",
        text_submitting: "{$lang.label.submitting}",
        btn_text: "{$lang.btn.ok}",
        btn_close: "{$lang.btn.close}",
        btn_url: "{$cfg.str_url}"
    };

    $(document).ready(function(){
        $("#verify_modal").on("hidden.bs.modal", function() {
            $(this).removeData("bs.modal");
        });
        var obj_validator_form    = $("#verify_list").baigoValidator(opts_validator_list);
        var obj_submit_form       = $("#verify_list").baigoSubmit(opts_submit_list);
        $("#go_list").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
        $("#verify_list").baigoCheckall();
    })
    </script>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/html_foot.tpl" cfg=$cfg}
