{* install_1.tpl 登录界面 *}
{$cfg = [
    sub_title => $lang.page.installDbTable,
    mod_help   => "install",
    act_help   => "dbtable"
]}
{include "{$smarty.const.BG_PATH_TPLSYS}install/default/include/install_head.tpl" cfg=$cfg}

    <div class="form-group">
        {foreach $tplData.db_alert as $key=>$value}
            {if $value.status == "y"}
                {$str_css   = "text-success"}
                {$str_icon  = "ok-sign"}
            {else}
                {$str_css   = "text-danger"}
                {$str_icon  = "remove-sign"}
            {/if}
            <p>
                <img src="{$smarty.const.BG_URL_STATIC}image/allow_{$value.status}.png">
                {$alert[$value.alert]}
                &nbsp;&nbsp;
                [ <span class="{$str_css}">{$value.alert}</span> ]
            </p>
        {/foreach}
    </div>

    <div class="form-group">
        <div class="btn-group">
            <a id="go_next" class="btn btn-primary btn-lg" href="{$smarty.const.BG_URL_INSTALL}ctl.php?mod=install&act_get=base">{$lang.btn.stepNext}</a>
            {include "{$smarty.const.BG_PATH_TPLSYS}install/default/include/install_drop.tpl" cfg=$cfg}
        </div>
    </div>

{include "{$smarty.const.BG_PATH_TPLSYS}install/default/include/install_foot.tpl" cfg=$cfg}
{include "{$smarty.const.BG_PATH_TPLSYS}install/default/include/html_foot.tpl" cfg=$cfg}