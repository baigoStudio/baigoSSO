{* profile_form.tpl 管理员编辑界面 *}
{$cfg = [
    title          => $lang.page.profile,
    menu_active    => "profile",
    sub_active     => "info",
    baigoValidator => "true",
    baigoSubmit    => "true",
    tokenReload    => "true",
    str_url        => "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=profile&act_get=info"
]}

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_head.tpl" cfg=$cfg}

    <li>{$lang.page.profile}</li>

    {include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_left.tpl" cfg=$cfg}

    <form name="profile_form" id="profile_form">

        <input type="hidden" name="token_session" class="token_session" value="{$common.token_session}">
        <input type="hidden" name="act_post" value="info">

        <div class="row">
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="form-group">
                            <label class="control-label">{$lang.label.username}</label>
                            <input type="text" name="admin_name" id="admin_name" value="{$tplData.adminLogged.admin_name}" readonly class="form-control">
                        </div>

                        <div class="form-group">
                            <div id="group_admin_nick">
                                <label class="control-label">{$lang.label.nick}</label>
                                <input type="text" name="admin_nick" id="admin_nick" value="{$tplData.adminLogged.admin_nick}" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="button" id="go_form" class="btn btn-primary">{$lang.btn.save}</button>
                        </div>

                        <div class="form-group">
                            <label class="control-label">{$lang.label.allow}</label>
                            <dl class="list_dl">
                                {foreach $adminMod as $key_m=>$value_m}
                                    <dt>{$value_m.main.title}</dt>
                                    <dd>
                                        <ul class="list-inline">
                                            {foreach $value_m.allow as $key_s=>$value_s}
                                                <li>
                                                    <span class="glyphicon glyphicon-{if isset($tplData.adminLogged.admin_allow[$key_m][$key_s])}ok-circle text-success{else}remove-circle text-danger{/if}"></span>
                                                    {$value_s}
                                                </li>
                                            {/foreach}
                                        </ul>
                                    </dd>
                                {/foreach}
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            {include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/profile_left.tpl" cfg=$cfg}
        </div>

    </form>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_foot.tpl" cfg=$cfg}

    <script type="text/javascript">
    var opts_validator_form = {
        admin_nick: {
            len: { min: 0, max: 30 },
            validate: { type: "str", format: "text", group: "#group_admin_nick" },
            msg: { selector: "#msg_admin_nick", too_long: "{$alert.x020211}" }
        }
    };

    var opts_submit_form = {
        ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=profile",
        text_submitting: "{$lang.label.submitting}",
        btn_text: "{$lang.btn.ok}",
        btn_close: "{$lang.btn.close}",
        btn_url: "{$cfg.str_url}"
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#profile_form").baigoValidator(opts_validator_form);
        var obj_submit_form       = $("#profile_form").baigoSubmit(opts_submit_form);
        $("#go_form").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    })
    </script>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/html_foot.tpl" cfg=$cfg}
