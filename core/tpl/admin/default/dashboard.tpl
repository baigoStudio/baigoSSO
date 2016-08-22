{$cfg = [
    title          => $adminMod.dashboard.main.title,
    menu_active    => "dashboard",
    tokenReload    => "true",
    str_url        => "{$smarty.const.BG_URL_ADMIN}ctl.php?mod=user"
]}

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_head.tpl" cfg=$cfg}

    <li>{$adminMod.user.main.title}</li>

    {include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_left.tpl" cfg=$cfg}

    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="panel panel-default">
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="2">{$lang.label.userCount}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $status.user as $key=>$value}
                            {if $key == "enable"}
                                {$css_status = "success"}
                            {else if $key == "wait"}
                                {$css_status = "warning"}
                            {else}
                                {$css_status = "default"}
                            {/if}
                            <tr class="{$css_status}">
                                <td>{$value}</td>
                                <td class="text-right">{$tplData.userCount[$key]}</td>
                            </tr>
                        {/foreach}
                        <tr>
                            <td>{$lang.label.total}</td>
                            <td class="text-right">{$tplData.userCount.all}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="panel panel-default">
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="2">{$lang.label.appCount}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $status.app as $key=>$value}
                            {if $key == "enable"}
                                {$css_status = "success"}
                            {else}
                                {$css_status = "default"}
                            {/if}
                            <tr class="{$css_status}">
                                <td>{$value}</td>
                                <td class="text-right">{$tplData.appCount[$key]}</td>
                            </tr>
                        {/foreach}
                        <tr>
                            <td>{$lang.label.total}</td>
                            <td class="text-right">{$tplData.appCount.all}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/admin_foot.tpl" cfg=$cfg}
{include "{$smarty.const.BG_PATH_TPLSYS}admin/default/include/html_foot.tpl" cfg=$cfg}
