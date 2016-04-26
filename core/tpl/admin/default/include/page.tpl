{* html_foot.tpl HTML 底部通用 *}
    <ul class="pagination pagination-sm">
        {if $tplData.pageRow.page > 1}
            <li>
                <a href="{$cfg.str_url}&page=1" title="{$lang.href.pageFirst}">{$lang.href.pageFirst}</a>
            </li>
        {/if}

        {if $tplData.pageRow.p * 10 > 0}
            <li>
                <a href="{$cfg.str_url}&page={$tplData.pageRow.p * 10}" title="{$lang.href.pagePrevList}">{$lang.href.pagePrevList}</a>
            </li>
        {/if}

        <li class="{if $tplData.pageRow.page <= 1}disabled{/if}">
            {if $tplData.pageRow.page <= 1}
                <span title="{$lang.href.pagePrev}">&laquo;</span>
            {else}
                <a href="{$cfg.str_url}&page={$tplData.pageRow.page - 1}" title="{$lang.href.pagePrev}">&laquo;</a>
            {/if}
        </li>

        {for $iii = $tplData.pageRow.begin to $tplData.pageRow.end}
            <li class="{if $iii == $tplData.pageRow.page}active{/if}">
                {if $iii == $tplData.pageRow.page}
                    <span>{$iii}</span>
                {else}
                    <a href="{$cfg.str_url}&page={$iii}" title="{$iii}">{$iii}</a>
                {/if}
            </li>
        {/for}

        <li class="{if $tplData.pageRow.page >= $tplData.pageRow.total}disabled{/if}">
            {if $tplData.pageRow.page >= $tplData.pageRow.total}
                <span title="{$lang.href.pageNext}">&raquo;</span>
            {else}
                <a href="{$cfg.str_url}&page={$tplData.pageRow.page + 1}" title="{$lang.href.pageNext}">&raquo;</a>
            {/if}
        </li>

        {if $iii < $tplData.pageRow.total}
            <li>
                <a href="{$cfg.str_url}&page={$iii}" title="{$lang.href.pageNextList}">{$lang.href.pageNextList}</a>
            </li>
        {/if}

        {if $tplData.pageRow.page < $tplData.pageRow.total}
            <li>
                <a href="{$cfg.str_url}&page={$tplData.pageRow.total}" title="{$lang.href.pageLast}">{$lang.href.pageLast}</a>
            </li>
        {/if}
    </ul>
