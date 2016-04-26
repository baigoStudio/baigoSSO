{* pm_send.tpl 管理员编辑界面 *}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    {$adminMod.pm.main.title} - {$lang.page.show}
</div>
<div class="modal-body">

    {if $tplData.pmRow.pm_status == "read"}
        {$css_status = "success"}
    {else}
        {$css_status = "warning"}
    {/if}

    <div class="form-group">
        <label class="control-label static_label">{$lang.label.pmFrom}</label>
        <p class="form-control-static">
            {if $tplData.pmRow.pm_from == -1}
                {$lang.label.pmSys}
            {else if isset($tplData.pmRow.fromUser.user_name)}
                {$tplData.pmRow.fromUser.user_name}
            {else}
                {$lang.label.unknow}
            {/if}
        </p>
    </div>

    <div class="form-group">
        <label class="control-label static_label">{$lang.label.pmTo}</label>
        <p class="form-control-static">
            {if isset($tplData.pmRow.toUser.user_name)}
                {$tplData.pmRow.toUser.user_name}
            {else}
                {$lang.label.unknow}
            {/if}
        </p>
    </div>

    <div class="form-group">
        <label class="control-label static_label">{$lang.label.title}</label>
        <p class="form-control-static input-lg">{$tplData.pmRow.pm_title}</p>
    </div>

    <div class="form-group">
        <label class="control-label static_label">{$lang.label.content}</label>
        <p class="form-control-static input-lg">{$tplData.pmRow.pm_content}</p>
    </div>

    <div class="form-group">
        <p class="form-control-static label_baigo">
            <span class="label label-{$css_status}">{$status.pm[$tplData.pmRow.pm_status]}</span>
        </p>
    </div>

    <div class="form-group">
        <p class="form-control-static">
            <span class="glyphicon glyphicon-import"></span>
            {$type.pm[$tplData.pmRow.pm_type]}
        </p>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">{$lang.btn.close}</button>
</div>