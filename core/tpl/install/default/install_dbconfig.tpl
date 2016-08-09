{* install_1.tpl 登录界面 *}
{$cfg = [
    sub_title  => $lang.page.installDbConfig,
    mod_help   => "install",
    act_help   => "dbconfig"
]}
{include "{$smarty.const.BG_PATH_TPLSYS}install/default/include/install_head.tpl" cfg=$cfg}

    <form name="install_dbconfig" id="install_dbconfig">
        <input type="hidden" name="{$common.tokenRow.name_session}" value="{$common.tokenRow.token}">
        <input type="hidden" name="act_post" value="dbconfig">

        <div class="form-group">
            <div id="group_db_host">
                <label class="control-label">{$lang.label.dbHost}<span id="msg_db_host">*</span></label>
                <input type="text" value="{$smarty.const.BG_DB_HOST}" name="db_host" id="db_host" data-validate class="form-control input-lg">
            </div>
        </div>

        <div class="form-group">
            <div id="group_db_name">
                <label class="control-label">{$lang.label.dbName}<span id="msg_db_name">*</span></label>
                <input type="text" value="{$smarty.const.BG_DB_NAME}" name="db_name" id="db_name" data-validate class="form-control input-lg">
            </div>
        </div>

        <div class="form-group">
            <div id="group_db_port">
                <label class="control-label">{$lang.label.dbPort}<span id="msg_db_port">*</span></label>
                <input type="text" value="{$smarty.const.BG_DB_PORT}" name="db_port" id="db_port" data-validate class="form-control input-lg">
            </div>
        </div>

        <div class="form-group">
            <div id="group_db_user">
                <label class="control-label">{$lang.label.dbUser}<span id="msg_db_user">*</span></label>
                <input type="text" value="{$smarty.const.BG_DB_USER}" name="db_user" id="db_user" data-validate class="form-control input-lg">
            </div>
        </div>

        <div class="form-group">
            <div id="group_db_pass">
                <label class="control-label">{$lang.label.dbPass}<span id="msg_db_pass">*</span></label>
                <input type="text" value="{$smarty.const.BG_DB_PASS}" name="db_pass" id="db_pass" data-validate class="form-control input-lg">
            </div>
        </div>

        <div class="form-group">
            <div id="group_db_charset">
                <label class="control-label">{$lang.label.dbCharset}<span id="msg_db_charset">*</span></label>
                <input type="text" value="{$smarty.const.BG_DB_CHARSET}" name="db_charset" id="db_charset" data-validate class="form-control input-lg">
            </div>
        </div>

        <div class="form-group">
            <div id="group_db_table">
                <label class="control-label">{$lang.label.dbTable}<span id="msg_db_table">*</span></label>
                <input type="text" value="{$smarty.const.BG_DB_TABLE}" name="db_table" id="db_table" data-validate class="form-control input-lg">
            </div>
        </div>

        <div class="form-group">
            <div class="btn-group">
                <button type="button" id="go_next" class="btn btn-primary btn-lg">{$lang.btn.save}</button>
                {include "{$smarty.const.BG_PATH_TPLSYS}install/default/include/install_drop.tpl" cfg=$cfg}
            </div>
        </div>
    </form>

{include "{$smarty.const.BG_PATH_TPLSYS}install/default/include/install_foot.tpl" cfg=$cfg}

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
        ajax_url: "{$smarty.const.BG_URL_INSTALL}ajax.php?mod=install",
        text_submitting: "{$lang.label.submitting}",
        btn_text: "{$lang.btn.stepNext}",
        btn_close: "{$lang.btn.close}",
        btn_url: "{$smarty.const.BG_URL_INSTALL}ctl.php?mod=install&act_get=dbtable"
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#install_dbconfig").baigoValidator(opts_validator_form);
        var obj_submit_form       = $("#install_dbconfig").baigoSubmit(opts_submit_form);
        $("#go_next").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    });
    </script>

{include "{$smarty.const.BG_PATH_TPLSYS}install/default/include/html_foot.tpl" cfg=$cfg}
