{* admin_head.tpl 管理后台头部，包含菜单 *}
{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/html_head.tpl"}

    <header class="navbar navbar-inverse navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                    <span class="sr-only">nav</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                {if $smarty.const.BG_DEFAULT_UI == "default"}
                    <a class="navbar-brand" href="{$smarty.const.PRD_SSO_URL}" target="_blank">
                        <img src="{$smarty.const.BG_URL_STATIC}admin/{$smarty.const.BG_DEFAULT_UI}/image/admin_logo.png">
                    </a>
                {else}
                    <a class="navbar-brand" href="javascript:void(0);">
                        <img src="{$smarty.const.BG_URL_STATIC}admin/{$smarty.const.BG_DEFAULT_UI}/image/admin_logo.png">
                    </a>
                {/if}
            </div>
            <nav class="collapse navbar-collapse bs-navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="{$smarty.const.BG_URL_ADMIN}ctl.php">{$smarty.const.BG_SITE_NAME}</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown{if isset($cfg.menu_active) && $cfg.menu_active == "profile"} active{/if}">
                        <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=profile&act_get=info" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-user"></span>
                            {if isset($tplData.adminLogged.admin_nick) && $tplData.adminLogged.admin_nick}
                                {$tplData.adminLogged.admin_nick}
                            {else}
                                {$tplData.adminLogged.admin_name}
                            {/if}
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li{if isset($cfg.sub_active) && $cfg.sub_active == "info"} class="active"{/if}>
                                <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=profile&act_get=info">
                                    {$lang.href.infoModi}
                                </a>
                            </li>
                            <li{if isset($cfg.sub_active) && $cfg.sub_active == "pass"} class="active"{/if}>
                                <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=profile&act_get=pass">
                                    {$lang.href.passModi}
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=logon&act_get=logout">
                            <span class="glyphicon glyphicon-off"></span>
                            {$lang.href.logout}
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container-fluid">
        <ol class="breadcrumb">
            <li>
                <a href="{$smarty.const.BG_URL_ADMIN}ctl.php">
                    <span class="glyphicon glyphicon-home"></span>
                    {$lang.page.admin}
                </a>
            </li>