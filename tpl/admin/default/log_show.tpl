{* admin_form.tpl 管理员编辑界面 *}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    {$adminMod.log.main.title} - {$lang.page.detail}
</div>
<div class="modal-body">

    <div class="form-group">
        <label class="control-label static_label">{$lang.label.id}</label>
        <p class="form-control-static input-lg">{$tplData.logRow.log_id}</p>
    </div>

    {if $tplData.logRow.log_status == "read"}
        {$_css_status = "default"}
    {else}
        {$_css_status = "warning"}
    {/if}

    <div class="form-group">
        <label class="control-label static_label">{$lang.label.status}</label>
        <p class="form-control-static label_baigo">
            <span class="label label-{$_css_status}">{$status.log[$tplData.logRow.log_status]}</span>
        </p>
    </div>

    <div class="form-group">
        <label class="control-label static_label">{$lang.label.content}</label>
        <p class="form-control-static input-lg">{$tplData.logRow.log_title}</p>
    </div>

    <div class="form-group">
        <label class="control-label static_label">{$lang.label.target}</label>
        <p class="form-control-static">
            {foreach $tplData.logRow.log_targets as $_key=>$_value}
                <div>
                    {if $tplData.logRow.log_target_type == "admin"}
                        {if isset($_value.adminRow.admin_name)}
                            <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=admin&act_get=show&admin_id={$_value.adminRow.admin_id}">{$_value.adminRow.admin_name}</a>
                        {else}
                            {$lang.label.unknow}
                        {/if}
                        -> ID: {$_value["admin_id"]}
                    {else if $tplData.logRow.log_target_type == "user"}
                        {if isset($_value.userRow.user_name)}
                            <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=user&act_get=show&user_id={$_value.userRow.user_id}">{$_value.userRow.user_name}</a>
                        {else}
                            {$lang.label.unknow}
                        {/if}
                        -> ID: {$_value["user_id"]}
                    {else if $tplData.logRow.log_target_type == "app"}
                        {if isset($_value.appRow.app_name)}
                            <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=app&act_get=show&app_id={$_value.appRow.app_id}">{$_value.appRow.app_name}</a>
                        {else}
                            {$lang.label.unknow}
                        {/if}
                        -> ID: {$_value["app_id"]}
                    {else if $tplData.logRow.log_target_type == "verify"}
                        {if isset($_value.verifyRow.verify_user_id)}
                            <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=verify&act_get=show&verify_id={$_value.verifyRow.verify_id}">{$_value.verifyRow.verify_user_id}</a>
                        {else}
                            {$lang.label.unknow}
                        {/if}
                        -> ID: {$_value["verify_id"]}
                    {else if $tplData.logRow.log_target_type == "log"}
                        {if isset($_value.logRow.log_id)}
                            {$_value.logRow.log_id}
                        {else}
                            {$lang.label.unknow}
                        {/if}
                    {else}
                        {$type.logTarget[$tplData.logRow.log_target_type]}: {$_value}
                    {/if}
                </div>
            {/foreach}
        </p>
    </div>

    <div class="form-group">
        <label class="control-label static_label">{$lang.label.type}</label>
        <p class="form-control-static input-lg">{$type.log[$tplData.logRow.log_type]}</p>
    </div>

    <div class="form-group">
        <label class="control-label static_label">{$lang.label.operator}</label>
        <p class="form-control-static input-lg">
            {if $tplData.logRow.log_type == "admin"}
                {if isset($tplData.logRow.adminRow.admin_name)}
                    <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=admin&act_get=show&admin_id={$tplData.logRow.adminRow.admin_id}">{$tplData.logRow.adminRow.admin_name}</a>
                {else}
                    {$lang.label.unknow}
                {/if}
            {else if $tplData.logRow.log_type == "app"}
                {if isset($tplData.logRow.appRow.app_name)}
                     <a href="{$smarty.const.BG_URL_ADMIN}ctl.php?mod=app&act_get=show&app_id={$tplData.logRow.appRow.app_id}">{$tplData.logRow.appRow.app_name}</a>
                {else}
                    {$lang.label.unknow}
                {/if}
            {else}
                {$type.log[$value.log_type]}
            {/if}
        </p>
    </div>

    <div class="form-group">
        <label class="control-label static_label">{$lang.label.result}</label>
        <p class="form-control-static input-lg">
            {$tplData.logRow.log_result}
        </p>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">{$lang.btn.close}</button>
</div>