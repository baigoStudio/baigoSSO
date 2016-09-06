{$cfg = [
    title  => "{$lang.page.confirm} - {$lang.page.verify}"
]}

{include "{$smarty.const.BG_PATH_TPL}user/default/include/logon_head.tpl" cfg=$cfg}

    <form name="confirm_form" id="confirm_form">
        <input type="hidden" name="act_post" value="confirm">
        <input type="hidden" name="verify_id" value="{$tplData.verifyRow.verify_id}">
        <input type="hidden" name="verify_token" value="{$tplData.verifyRow.verify_token}">
        <input type="hidden" name="{$common.tokenRow.name_session}" value="{$common.tokenRow.token}">

        <div class="form-group">
            <label class="control-label">{$lang.label.username}</label>
            <input type="text" name="user_name" id="user_name" value="{$tplData.userRow.user_name}" readonly class="form-control input-lg">
        </div>

        <div class="form-group">
            <div id="group_seccode">
                <label class="control-label">{$lang.label.seccode}<span id="msg_seccode">*</span></label>
                <div class="input-group">
                    <input type="text" name="seccode" id="seccode" placeholder="{$alert.x030201}" data-validate class="form-control input-lg">
                    <span class="input-group-addon">
                        <a href="javascript:reloadImg('seccodeImg','{$smarty.const.BG_URL_MISC}ctl.php?mod=seccode&act_get=make');" title="{$lang.alt.seccode}">
                            <img src="{$smarty.const.BG_URL_MISC}ctl.php?mod=seccode&act_get=make" id="seccodeImg" alt="{$lang.alt.seccode}" height="32">
                        </a>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <button type="button" class="btn btn-primary btn-lg btn-block" id="go_confirm">{$lang.btn.submit}</button>
        </div>

    </form>

{include "{$smarty.const.BG_PATH_TPL}user/default/include/logon_foot.tpl" cfg=$cfg}

    <script type="text/javascript">
    var opts_validator_form = {
        seccode: {
            len: { min: 4, max: 4 },
            validate: { type: "ajax", format: "text", group: "#group_seccode" },
            msg: { selector: "#msg_seccode", too_short: "{$alert.x030201}", too_long: "{$alert.x030201}", ajaxIng: "{$alert.x030401}", ajax_err: "{$alert.x030402}" },
            ajax: { url: "{$smarty.const.BG_URL_MISC}ajax.php?mod=seccode&act_get=chk", key: "seccode", type: "str" }
        }
    };

    var opts_submit_form = {
        ajax_url: "{$smarty.const.BG_URL_USER}ajax.php?mod=reg",
        text_submitting: "{$lang.label.submitting}",
        btn_text: "{$lang.btn.ok}",
        btn_close: "{$lang.btn.close}",
        btn_url: "{$smarty.const.BG_URL_USER}ctl.php"
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#confirm_form").baigoValidator(opts_validator_form);
        var obj_submit_form       = $("#confirm_form").baigoSubmit(opts_submit_form);
        $("#go_confirm").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    });
    </script>

</body>
</html>
