            </div>

            <div class="panel-footer">
                <div class="pull-left">
                    {$smarty.const.PRD_SSO_POWERED}
                    {if $smarty.const.BG_DEFAULT_UI == "default"}
                        <a href="{$smarty.const.PRD_SSO_URL}" target="_blank">{$smarty.const.PRD_SSO_NAME}</a>
                    {else}
                        {$smarty.const.BG_DEFAULT_UI} SSO
                    {/if}
                    {$smarty.const.PRD_SSO_VER}
                </div>
                <div class="pull-right foot_logo">
                    {if $smarty.const.BG_DEFAULT_UI == "default"}
                        <a href="{$smarty.const.PRD_SSO_URL}" target="_blank">{$smarty.const.PRD_SSO_POWERED} {$smarty.const.PRD_SSO_NAME} {$smarty.const.PRD_SSO_VER}</a>
                    {else}
                        <a href="javascript:void(0);">{$smarty.const.BG_DEFAULT_UI} SSO</a>
                    {/if}
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

    </div>

    <script src="{$smarty.const.BG_URL_STATIC}js/baigoSubmit/baigoSubmit.min.js" type="text/javascript"></script>
    <script src="{$smarty.const.BG_URL_STATIC}js/baigoValidator/baigoValidator.min.js" type="text/javascript"></script>
    <script src="{$smarty.const.BG_URL_STATIC}js/reloadImg.js" type="text/javascript"></script>
    <script src="{$smarty.const.BG_URL_STATIC}js/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

    <!-- {$smarty.const.PRD_SSO_POWERED} {if $smarty.const.BG_DEFAULT_UI == "default"}{$smarty.const.PRD_SSO_NAME}{else}{$smarty.const.BG_DEFAULT_UI} SSO{/if} {$smarty.const.PRD_SSO_VER} -->
