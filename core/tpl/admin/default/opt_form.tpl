{* opt_form.tpl 系统设置界面 *}
{$cfg = [
    title          => "{$lang.page.opt} - {$opt[$tplData.act_get].title}",
    menu_active    => "opt",
    sub_active     => $tplData.act_get,
    baigoValidator => "true",
    baigoSubmit    => "true",
    tokenReload    => "true",
    str_url        => "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=opt"
]}

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_head.tpl" cfg=$cfg}

    <li><a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=opt&act_get=base">{$lang.page.opt}</a></li>
    <li>{$opt[$tplData.act_get].title}</li>

    {include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_left.tpl" cfg=$cfg}

    <div class="form-group">
        <ul class="nav nav-pills nav_baigo">
            <li>
                <a href="{$smarty.const.BG_URL_HELP}ctl.php?mod=admin&act_get=opt" target="_blank">
                    <span class="glyphicon glyphicon-question-sign"></span>
                    {$lang.href.help}
                </a>
            </li>
        </ul>
    </div>

    <form name="opt_form" id="opt_form">

        <input type="hidden" name="token_session" class="token_session" value="{$common.token_session}">
        <input type="hidden" name="act_post" value="{$tplData.act_get}">

        <div class="panel panel-default">
            <div class="panel-body">
                {foreach $opt[$tplData.act_get].list as $key=>$value}
                    {if $smarty.const[$key]}
                        {$this_value = $smarty.const[$key]}
                    {else}
                        {$this_value = $value.default}
                    {/if}

                    <div class="form-group">
                        <div id="group_{$tplData.act_get}_{$key}">
                            <label for="opt_{$tplData.act_get}_{$key}" class="control-label">{$value.label}<span id="msg_{$tplData.act_get}_{$key}">{if $value.min > 0}*{/if}</span></label>

                            {if $value.type == "select"}
                                <select name="opt[{$tplData.act_get}][{$key}]" id="opt_{$tplData.act_get}_{$key}" data-validate="opt_{$tplData.act_get}_{$key}" class="form-control">
                                    {foreach $value.option as $key_opt=>$value_opt}
                                        <option {if $this_value == $key_opt}selected{/if} value="{$key_opt}">{$value_opt}</option>
                                    {/foreach}
                                </select>
                            {else if $value.type == "radio"}
                                {foreach $value.option as $key_opt=>$value_opt}
                                    <div class="radio">
                                        <label for="opt_{$tplData.act_get}_{$key}_{$key_opt}">
                                            <input type="radio" {if $this_value == $key_opt}checked{/if} value="{$key_opt}" data-validate="opt_{$tplData.act_get}_{$key}" name="opt[{$tplData.act_get}][{$key}]" id="opt_{$tplData.act_get}_{$key}_{$key_opt}">
                                            {$value_opt.value}
                                        </label>
                                    </div>
                                    {if isset($value_opt.note)}<p class="help-block">{$value_opt.note}</p>{/if}
                                {/foreach}
                            {else if $value.type == "textarea"}
                                <textarea name="opt[{$tplData.act_get}][{$key}]" id="opt_{$tplData.act_get}_{$key}" data-validate="opt_{$tplData.act_get}_{$key}" class="form-control text_md">{$this_value}</textarea>
                            {else}
                                <input type="text" value="{$this_value}" name="opt[{$tplData.act_get}][{$key}]" id="opt_{$tplData.act_get}_{$key}" data-validate="opt_{$tplData.act_get}_{$key}" class="form-control">
                            {/if}

                            {if isset($value.note)}<p class="help-block">{$value.note}</p>{/if}
                        </div>
                    </div>
                {/foreach}

                {if $tplData.act_get == "base"}
                    <div class="form-group">
                        <div id="group_base_BG_SITE_TPL">
                            <label class="control-label">{$lang.label.tpl}<span id="msg_base_BG_SITE_TPL">*</span></label>
                            <select name="opt[base][BG_SITE_TPL]" id="opt_base_BG_SITE_TPL" data-validate class="form-control">
                                {foreach $tplData.tplRows as $key=>$value}
                                    {if $value["type"] == "dir"}
                                        <option {if $smarty.const.BG_SITE_TPL == $value.name}selected{/if} value="{$value.name}">{$value.name}</option>
                                    {/if}
                                {/foreach}
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div id="group_base_BG_SITE_TIMEZONE">
                            <label class="control-label">{$lang.label.timezone}<span id="msg_base_BG_SITE_TIMEZONE">*</span></label>
                            <div class="form-inline">
                                <select name="timezone_type" id="timezone_type" class="form-control">
                                    {foreach $tplData.timezoneRows as $key=>$value}
                                        <option {if $tplData.timezoneType == $key}selected{/if} value="{$key}">{$value.title}</option>
                                    {/foreach}
                                </select>

                                <select name="opt[base][BG_SITE_TIMEZONE]" id="opt_base_BG_SITE_TIMEZONE" data-validate class="form-control">
                                    {foreach $tplData.timezoneRows[$tplData.timezoneType].sub as $key=>$value}
                                        <option {if $smarty.const.BG_SITE_TIMEZONE == $key}selected{/if} value="{$key}">{$value}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                {/if}

                <div class="form-group">
                    <button type="button" id="go_form" class="btn btn-primary">{$lang.btn.save}</button>
                </div>
            </div>
        </div>
    </form>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_foot.tpl" cfg=$cfg}

    <script type="text/javascript">
    var opts_validator_form = {
        {foreach $opt[$tplData.act_get].list as $key=>$value}
            {if $value.type == "str" || $value.type == "textarea"}
                {$str_msg_min       = "too_short"}
                {$str_msg_please    = $alert.x040201}
            {else}
                {$str_msg_min       = "too_few"}
                {$str_msg_please    = $alert.x040218}
            {/if}
            "opt_{$cfg.sub_active}_{$key}": {
                len: { min: {$value.min}, max: 0 },
                validate: { selector: "[data-validate='opt_{$tplData.act_get}_{$key}']", type: "{$value.type}", {if isset($value.format)}format: "{$value.format}", {/if}group: "#group_{$tplData.act_get}_{$key}" },
                msg: { selector: "#msg_{$tplData.act_get}_{$key}", {$str_msg_min}: "{$str_msg_please}{$value.label}", format_err: "{$value.label}{$alert.x040203}" }
            }{if !$value@last},{/if}
        {/foreach}
    };

    {if $tplData.act_get == "base"}
        opts_validator_form.opt_base_BG_SITE_TPL = {
            len: { min: 1, max: 0 },
            validate: { type: "select", group: "#group_base_BG_SITE_TPL" },
            msg: { selector: "#msg_base_BG_SITE_TPL", too_few: "{$alert.x060201}{$lang.label.tpl}" }
        };

        opts_validator_form.opt_base_BG_SITE_TIMEZONE = {
            len: { min: 1, max: 0 },
            validate: { type: "select", group: "#group_base_BG_SITE_TIMEZONE" },
            msg: { selector: "#msg_base_BG_SITE_TIMEZONE", too_few: "{$alert.x060201}{$lang.label.timezone}" }
        };
        var _timezoneJson = {$tplData.timezoneJson};
    {/if}

    var opts_submit_form = {
        ajax_url: "{$smarty.const.BG_URL_ADMIN}ajax.php?mod=opt",
        text_submitting: "{$lang.label.submitting}",
        btn_text: "{$lang.btn.ok}",
        btn_text: "{$lang.btn.ok}",
        btn_close: "{$lang.btn.close}",
        btn_url: "{$cfg.str_url}"
    };

    $(document).ready(function(){
        var obj_validator_form    = $("#opt_form").baigoValidator(opts_validator_form);
        var obj_submit_form       = $("#opt_form").baigoSubmit(opts_submit_form);
        $("#go_form").click(function(){
            if (obj_validator_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
        {if $tplData.act_get == "base"}
            $("#timezone_type").change(function(){
                var _type = $(this).val();
                var _str_appent;
                $.each(_timezoneJson[_type].sub, function(_key, _value){
                    _str_appent += "<option";
                    if (_key == "{$smarty.const.BG_SITE_TIMEZONE}") {
                        _str_appent += " selected";
                    }
                    _str_appent += " value='" + _key + "'>" + _value + "</option>";
                });
                $("#opt_base_BG_SITE_TIMEZONE").empty();
                $("#opt_base_BG_SITE_TIMEZONE").append(_str_appent);
            });
        {/if}
    })
    </script>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/html_foot.tpl" cfg=$cfg}
