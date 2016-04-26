{* admin_list.tpl 管理员列表 *}
{$cfg = [
    title          => $adminMod.pm.main.title,
    menu_active    => "pm",
    sub_active     => "list",
    baigoCheckall  => "true",
    baigoValidator => "true",
    baigoSubmit    => "true",
    tokenReload    => "true",
    tooltip        => "true",
    str_url        => "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=pm&act_get=list&{$tplData.query}"
]}

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_head.tpl" cfg=$cfg}

    <li>{$adminMod.pm.main.title}</li>

    {include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_left.tpl" cfg=$cfg}

    <div class="form-group">
        <div class="pull-left">
            <ul class="nav nav-pills nav_baigo">
                <li>
                    <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=pm&act_get=send">
                        <span class="glyphicon glyphicon-send"></span>
                        {$lang.href.pmSend}
                    </a>
                </li>
                <li>
                    <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=pm&act_get=bulk">
                        <span class="glyphicon glyphicon-bullhorn"></span>
                        {$lang.href.pmBulk}
                    </a>
                </li>
                <li>
                    <a href="{$smarty.const.BG_URL_HELP}ctl.php?mod=admin&act_get=pm" target="_blank">
                        <span class="glyphicon glyphicon-question-sign"></span>
                        {$lang.href.help}
                    </a>
                </li>
            </ul>
        </div>
        <div class="pull-right">
            <form name="pm_search" id="pm_search" action="{$smarty.const.BG_URL_ADMIN}ctl.php" method="get" class="form-inline">
                <input type="hidden" name="mod" value="pm">
                <input type="hidden" name="act_get" value="list">
                <div class="form-group">
                    <select name="status" class="form-control input-sm">
                        <option value="">{$lang.option.allStatus}</option>
                        {foreach $status.pm as $key=>$value}
                            <option {if $tplData.search.status == $key}selected{/if} value="{$key}">{$value}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="form-group">
                    <select name="type" class="form-control input-sm">
                        <option value="">{$lang.option.allType}</option>
                        {foreach $type.pm as $key=>$value}
                            <option {if $tplData.search.type == $key}selected{/if} value="{$key}">{$value}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" name="key" value="{$tplData.search.key}" placeholder="{$lang.label.key}" class="form-control input-sm">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-default btn-sm">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                        </span>
                    </div>
                </div>
            </form>
        </div>
        <div class="clearfix"></div>
    </div>

    <form name="pm_list" id="pm_list" class="form-inline">
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
                            <th>{$lang.label.pm}</th>
                            <th>{$lang.label.pmFrom} / {$lang.label.pmTo}</th>
                            <th class="text-nowrap td_md">{$lang.label.time}</th>
                            <th class="text-nowrap td_sm">{$lang.label.status} / {$lang.label.type}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $tplData.pmRows as $key=>$value}
                            {if $value.pm_status == "read"}
                                {$css_status = "success"}
                            {else}
                                {$css_status = "warning"}
                            {/if}
                            {if $value.pm_type == "in"}
                                {$icon_type = "inbox"}
                            {else}
                                {$icon_type = "send"}
                            {/if}
                            <tr>
                                <td class="text-nowrap td_mn"><input type="checkbox" name="pm_ids[]" value="{$value.pm_id}" id="pm_id_{$value.pm_id}" data-validate="pm_id" data-parent="chk_all"></td>
                                <td class="text-nowrap td_mn">{$value.pm_id}</td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li>{$value.pm_title}</li>
                                        <li>
                                            <ul class="list_menu">
                                                <li>
                                                    <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=pm&act_get=show&pm_id={$value.pm_id}&view=iframe" data-toggle="modal" data-target="#pm_modal">{$lang.href.show}</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li>
                                            {if $value.pm_from == -1}
                                                {$lang.label.pmSys}
                                            {else if isset($value.fromUser.user_name)}
                                                <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=pm&act_get=list&pm_from={$value.pm_from}">{$value.fromUser.user_name}</a>
                                            {else}
                                                {$lang.label.unknow}
                                            {/if}
                                        </li>
                                        <li>
                                            {if isset($value.toUser.user_name)}
                                                <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=pm&act_get=list&pm_to={$value.pm_to}">{$value.toUser.user_name}</a>
                                            {else}
                                                {$lang.label.unknow}
                                            {/if}
                                        </li>
                                    </ul>
                                </td>
                                <td class="text-nowrap td_md">
                                    <abbr title="{$value.pm_time|date_format:"{$smarty.const.BG_SITE_DATE} {$smarty.const.BG_SITE_TIMESHORT}"}" data-toggle="tooltip" data-placement="bottom">
                                        {$value.pm_time|date_format:"{$smarty.const.BG_SITE_DATESHORT} {$smarty.const.BG_SITE_TIMESHORT}"}
                                    </abbr>
                                </td>
                                <td class="text-nowrap td_sm">
                                    <ul class="list-unstyled">
                                        <li class="label_baigo">
                                            <span class="label label-{$css_status}">{$status.pm[$value.pm_status]}</span>
                                        </li>
                                        <li class="label_baigo">
                                            <span class="glyphicon glyphicon-{$icon_type}"></span>
                                            {$type.pm[$value.pm_type]}
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        {/foreach}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><span id="msg_pm_id"></span></td>
                            <td colspan="4">
                                <div class="form-group">
                                    <div id="group_act_post">
                                        <select name="act_post" id="act_post" data-validate class="form-control input-sm">
                                            <option value="">{$lang.option.batch}</option>
                                            {foreach $status.pm as $key=>$value}
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

    <div class="modal fade" id="pm_modal">
        <div class="modal-dialog">
            <div class="modal-content"></div>
        </div>
    </div>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_foot.tpl" cfg=$cfg}

    <script type="text/javascript">
    var opts_validator_list = {
        pm_id: {
            len: { min: 1, max: 0 },
            validate: { selector: "[data-validate='pm_id']", type: "checkbox" },
            msg: { selector: "#msg_pm_id", too_few: "{$alert.x030202}" }
        },
        act_post: {
            len: { min: 1, max: 0 },
            validate: { type: "select", group: "#group_act_post" },
            msg: { selector: "#msg_act_post", too_few: "{$alert.x030203}" }
        }
    };

    var opts_submit_list = {
        ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=pm",
        confirm_selector: "#act_post",
        confirm_val: "del",
        confirm_msg: "{$lang.confirm.del}",
        text_submitting: "{$lang.label.submitting}",
        btn_text: "{$lang.btn.ok}",
        btn_close: "{$lang.btn.close}",
        btn_url: "{$cfg.str_url}"
    };

    var opts_submit_notice = {
        ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=pm",
        text_submitting: "{$lang.label.submitting}",
        btn_text: "{$lang.btn.ok}",
        btn_close: "{$lang.btn.close}",
        btn_url: "{$cfg.str_url}"
    };

    $(document).ready(function(){
        $("#pm_modal").on("hidden.bs.modal", function() {
            $(this).removeData("bs.modal");
        });
        var obj_validator_list    = $("#pm_list").baigoValidator(opts_validator_list);
        var obj_submit_list       = $("#pm_list").baigoSubmit(opts_submit_list);
        $("#go_list").click(function(){
            if (obj_validator_list.verify()) {
                obj_submit_list.formSubmit();
            }
        });
        $("#pm_list").baigoCheckall();
    })
    </script>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/html_foot.tpl" cfg=$cfg}