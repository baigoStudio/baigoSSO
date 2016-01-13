    <ul class="dropdown-menu">
        <li{if $tplData.act_get == "ext"} class="active"{/if}><a href="{$smarty.const.BG_URL_INSTALL}ctl.php?mod=upgrade&act_get=ext">{$lang.page.installExt}</a></li>
        <li class="divider"></li>

        <li{if $tplData.act_get == "dbconfig"} class="active"{/if}><a href="{$smarty.const.BG_URL_INSTALL}ctl.php?mod=upgrade&act_get=dbconfig">{$lang.page.installDbConfig}</a></li>
        <li{if $tplData.act_get == "dbtable"} class="active"{/if}><a href="{$smarty.const.BG_URL_INSTALL}ctl.php?mod=upgrade&act_get=dbtable">{$lang.page.upgradeDbTable}</a></li>
        <li class="divider"></li>

        {foreach $opt as $key_opt=>$value_opt}
            <li{if $tplData.act_get == $key_opt} class="active"{/if}><a href="{$smarty.const.BG_URL_INSTALL}ctl.php?mod=upgrade&act_get={$key_opt}">{$value_opt.title}</a>
            </li>
        {/foreach}

        <li class="divider"></li>

        <li{if $tplData.act_get == "over"} class="active"{/if}><a href="{$smarty.const.BG_URL_INSTALL}ctl.php?mod=upgrade&act_get=over">{$lang.page.upgradeOver}</a></li>
    </ul>
