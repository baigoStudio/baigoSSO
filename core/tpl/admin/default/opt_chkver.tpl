{* opt_chkver.tpl 系统设置界面 *}
{$cfg = [
    title          => "{$lang.page.opt} - {$lang.page.chkver}",
    menu_active    => "opt",
    sub_active     => "chkver",
    baigoValidator => "true",
    baigoSubmit    => "true",
    tokenReload    => "true",
    str_url        => "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=opt&act_get=chkver"
]}

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_head.tpl" cfg=$cfg}

    <li><a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=opt&act_get=chkver">{$lang.page.opt}</a></li>
    <li>{$lang.page.chkver}</li>

    {include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_left.tpl" cfg=$cfg}

    <div class="form-group">
        <ul class="nav nav-pills nav_baigo">
            <li>
                <a href="{$smarty.const.BG_URL_HELP}ctl.php?mod=admin&act_get=opt#chkver" target="_blank">
                    <span class="glyphicon glyphicon-question-sign"></span>
                    {$lang.href.help}
                </a>
            </li>
        </ul>
    </div>

    <form name="opt_chkver" id="opt_chkver">

        <input type="hidden" name="{$common.tokenRow.name_session}" value="{$common.tokenRow.token}">
        <input type="hidden" name="act_post" value="chkver">
        <p>
            <button type="button" class="btn btn-info" id="go_form">{$lang.btn.chkver}</button>
        </p>
    </form>

    {if $smarty.const.BG_INSTALL_PUB < $tplData.latest_ver.prd_pub}
        <p class="alert alert-danger">
            {$lang.text.haveNewVer}
        </p>
    {else}
        <p class="alert alert-success">
            {$lang.text.isNewVer}
        </p>
    {/if}

    <div class="panel panel-default">
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">{$lang.label.installVer}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="nowrap td_bg">{$lang.label.installVer}</td>
                    <td>{$smarty.const.BG_INSTALL_VER}</td>
                </tr>
                <tr>
                    <td class="nowrap td_bg">{$lang.label.pubTime}</td>
                    <td>{$tplData.install_pub|date_format:$smarty.const.BG_SITE_DATE}</td>
                </tr>
                <tr>
                    <td class="nowrap td_bg">{$lang.label.installTime}</td>
                    <td>{$smarty.const.BG_INSTALL_TIME|date_format:"{$smarty.const.BG_SITE_DATE} {$smarty.const.BG_SITE_TIMESHORT}"}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {if $smarty.const.BG_INSTALL_PUB < $tplData.latest_ver.prd_pub}
        <div class="panel panel-default">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2">{$lang.label.latestVer}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="nowrap td_bg">{$lang.label.latestVer}</td>
                        <td>{$tplData.latest_ver.prd_ver}</td>
                    </tr>
                    <tr>
                        <td class="nowrap td_bg">{$lang.label.pubTime}</td>
                        <td>{$tplData.latest_ver.prd_pub}</td>
                    </tr>
                    <tr>
                        <td class="nowrap td_bg">{$lang.label.announcement}</td>
                        <td><a href="{$tplData.latest_ver.prd_announcement}" target="_blank">{$tplData.latest_ver.prd_announcement}</a></td>
                    </tr>
                    <tr>
                        <td class="nowrap td_bg">{$lang.label.downloadUrl}</td>
                        <td><a href="{$tplData.latest_ver.prd_download}" target="_blank">{$tplData.latest_ver.prd_download}</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    {/if}

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_foot.tpl" cfg=$cfg}

    <script type="text/javascript">
    var opts_validator_form = {
        db_host: {
            len: { min: 1, max: 900 },
            validate: { type: "str", format: "text", group: "#group_db_host" },
            msg: { selector: "#msg_db_host", too_short: "{$alert.x040204}", too_long: "{$alert.x040205}" }
        },
        db_name: {
            len: { min: 1, max: 900 },
            validate: { type: "str", format: "text", group: "#group_db_name" },
            msg: { selector: "#msg_db_name", too_short: "{$alert.x040206}", too_long: "{$alert.x040207}" }
        },
        db_port: {
            len: { min: 1, max: 900 },
            validate: { type: "str", format: "text", group: "#group_db_port" },
            msg: { selector: "#msg_db_port", too_short: "{$alert.x040208}", too_long: "{$alert.x040209}" }
        },
        db_user: {
            len: { min: 1, max: 900 },
            validate: { type: "str", format: "text", group: "#group_db_user" },
            msg: { selector: "#msg_db_user", too_short: "{$alert.x040210}", too_long: "{$alert.x040211}" }
        },
        db_pass: {
            len: { min: 1, max: 900 },
            validate: { type: "str", format: "text", group: "#group_db_pass" },
            msg: { selector: "#msg_db_pass", too_short: "{$alert.x040212}", too_long: "{$alert.x040213}" }
        },
        db_charset: {
            len: { min: 1, max: 900 },
            validate: { type: "str", format: "text", group: "#group_db_charset" },
            msg: { selector: "#msg_db_charset", too_short: "{$alert.x040214}", too_long: "{$alert.x040215}" }
        },
        db_table: {
            len: { min: 1, max: 900 },
            validate: { type: "str", format: "text", group: "#group_db_table" },
            msg: { selector: "#msg_db_table", too_short: "{$alert.x040216}", too_long: "{$alert.x040217}" }
        }
    };

    var opts_submit_form = {
        ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=opt",
        text_submitting: "{$lang.label.submitting}",
        btn_text: "{$lang.btn.ok}",
        btn_text: "{$lang.btn.ok}",
        btn_close: "{$lang.btn.close}",
        btn_url: "{$cfg.str_url}"
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#opt_chkver").baigoValidator(opts_validator_form);
        var obj_submit_form       = $("#opt_chkver").baigoSubmit(opts_submit_form);
        $("#go_form").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    });
    </script>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/html_foot.tpl" cfg=$cfg}
