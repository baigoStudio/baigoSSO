{$cfg = [
    title  => $lang.page.alert
]}

{include "{$smarty.const.BG_PATH_TPL}user/default/include/logon_head.tpl" cfg=$cfg}

    <div class="form-group">
        {if $tplData.alert}
            <div class="alert alert-danger">{$alert[$tplData.alert]}</div>
        {/if}
    </div>

{include "{$smarty.const.BG_PATH_TPL}user/default/include/logon_foot.tpl" cfg=$cfg}

</body>
</html>
