            </div>
            <div class="col-md-2 col-md-pull-10">
                <div class="panel panel-success">
                    <div class="list-group">
                        {foreach $adminMod as $key_m=>$value_m}
                            <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod={$value_m.main.mod}" class="list-group-item{if $cfg.menu_active == $key_m} list-group-item-success active{/if}">
                                <span class="glyphicon glyphicon-{$value_m.main.icon}"></span>
                                {$value_m.main.title}
                                {if isset($value_m.sub) && $value_m.sub}
                                    <span class="caret"></span>
                                {/if}
                            </a>
                            {if isset($cfg.menu_active) && $cfg.menu_active == $key_m && isset($value_m.sub) && $value_m.sub}
                                {foreach $value_m.sub as $key_s=>$value_s}
                                    <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod={$value_s.mod}&act_get={$value_s.act_get}" class="list-group-item {if $cfg.sub_active == $key_s}list-group-item-success{else}sub_normal{/if}">{$value_s.title}</a>
                                {/foreach}
                            {/if}
                        {/foreach}

                        <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=opt&act_get=dbconfig" class="list-group-item{if isset($cfg.menu_active) && $cfg.menu_active == "opt"} list-group-item-success active{/if}">
                            <span class="glyphicon glyphicon-cog"></span>
                            {$lang.href.opt}
                            <span class="caret"></span>
                        </a>
                        {if isset($cfg.menu_active) && $cfg.menu_active == "opt"}
                            <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=opt&act_get=chkver" class="list-group-item {if isset($cfg.sub_active) && $cfg.sub_active == "chkver"}list-group-item-success{else}sub_normal{/if}">{$lang.page.chkver}</a>
                            <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=opt&act_get=dbconfig" class="list-group-item {if isset($cfg.sub_active) && $cfg.sub_active == "dbconfig"}list-group-item-success{else}sub_normal{/if}">{$lang.page.installDbConfig}</a>
                            {foreach $opt as $key_opt=>$value_opt}
                                <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=opt&act_get={$key_opt}" class="list-group-item {if isset($cfg.sub_active) && $cfg.sub_active == $key_opt}list-group-item-success{else}sub_normal{/if}">{$value_opt.title}</a>
                            {/foreach}
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-success page_foot">
        <div class="pull-left foot_logo">
            {if $smarty.const.BG_DEFAULT_UI == "default"}
                <a href="{$smarty.const.PRD_SSO_URL}" target="_blank">{$smarty.const.PRD_SSO_POWERED} {$smarty.const.PRD_SSO_NAME} {$smarty.const.PRD_SSO_VER}</a>
            {else}
                <a href="javascript:void(0);">{$smarty.const.BG_DEFAULT_UI} SSO</a>
            {/if}
        </div>
        <div class="pull-right foot_power">
            {$smarty.const.PRD_SSO_POWERED}
            {if $smarty.const.BG_DEFAULT_UI == "default"}
                <a href="{$smarty.const.PRD_SSO_URL}" target="_blank">{$smarty.const.PRD_SSO_NAME}</a>
            {else}
                {$smarty.const.BG_DEFAULT_UI} SSO
            {/if}
            {$smarty.const.PRD_SSO_VER}
        </div>
        <div class="clearfix"></div>
    </footer>
