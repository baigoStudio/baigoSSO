{* admin_form.tpl 管理员编辑界面 *}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    {$adminMod.log.main.title} - {$lang.page.detail}
</div>
<div class="modal-body">

    <div class="form-group">
        <label class="control-label static_label">{$lang.label.id}</label>
        <p class="form-control-static input-lg">{$tplData.verifyRow.verify_id}</p>
    </div>

    {if $tplData.verifyRow.verify_status == "enable"}
        {$css_status = "sucess"}
        {$str_status = $status.verify[$tplData.verifyRow.verify_status]}
    {else if $tplData.verifyRow.verify_status == "expired"}
        {$css_status = "sucess"}
        {$str_status = $lang.label.expired}
    {else}
        {$css_status = "default"}
        {$str_status = $status.verify[$tplData.verifyRow.verify_status]}
    {/if}

    <div class="form-group">
        <label class="control-label static_label">{$lang.label.status}</label>
        <p class="form-control-static label_baigo">
            <span class="label label-{$css_status}">{$str_status}</span>
        </p>
    </div>

    <div class="form-group">
        <label class="control-label static_label">{$lang.label.operator}</label>
        <p class="form-control-static">
            {if isset($tplData.verifyRow.userRow.user_name)}
                {$tplData.verifyRow.userRow.user_name}
            {else}
                {$lang.label.unknow}
            {/if}
        </p>
    </div>

    <div class="form-group">
        <label class="control-label static_label">{$lang.label.timeExpired}</label>
        <p class="form-control-static">
            {$tplData.verifyRow.verify_token_expire|date_format:"{$smarty.const.BG_SITE_DATE} {$smarty.const.BG_SITE_TIMESHORT}"}
        </p>
    </div>

    <div class="form-group">
        <label class="control-label static_label">{$lang.label.timeInit}</label>
        <p class="form-control-static">
            {$tplData.verifyRow.verify_time_refresh|date_format:"{$smarty.const.BG_SITE_DATE} {$smarty.const.BG_SITE_TIMESHORT}"}
        </p>
    </div>

    <div class="form-group">
        <label class="control-label static_label">{$lang.label.timeDisable}</label>
        <p class="form-control-static">
            {$tplData.verifyRow.verify_time_disable|date_format:"{$smarty.const.BG_SITE_DATE} {$smarty.const.BG_SITE_TIMESHORT}"}
        </p>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">{$lang.btn.close}</button>
</div>