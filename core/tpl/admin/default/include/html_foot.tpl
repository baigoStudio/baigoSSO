{* html_foot.tpl HTML 底部通用 *}
    <div class="modal fade" id="msg_token">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p id="msg_token_content"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{$lang.btn.ok}</button>
                </div>
            </div>
        </div>
    </div>

    {if isset($cfg.baigoValidator)}
        <!--表单验证 js-->
        <script src="{$smarty.const.BG_URL_STATIC}js/baigoValidator/baigoValidator.min.js" type="text/javascript"></script>
    {/if}

    {if isset($cfg.baigoSubmit)}
        <!--表单 ajax 提交 js-->
        <script src="{$smarty.const.BG_URL_STATIC}js/baigoSubmit/baigoSubmit.min.js" type="text/javascript"></script>
    {/if}

    {if isset($cfg.reloadImg)}
        <!--重新载入图片 js-->
        <script src="{$smarty.const.BG_URL_STATIC}js/reloadImg.js" type="text/javascript"></script>
    {/if}

    {if isset($cfg.md5)}
        <!--重新载入图片 js-->
        <script src="{$smarty.const.BG_URL_STATIC}js/md5.js" type="text/javascript"></script>
    {/if}

    {if isset($cfg.baigoCheckall)}
        <!--全选 js-->
        <script src="{$smarty.const.BG_URL_STATIC}js/baigoCheckall/baigoCheckall.min.js" type="text/javascript"></script>
    {/if}

    {if isset($cfg.upload)}
        <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
        <script src="{$smarty.const.BG_URL_STATIC}js/jQuery-File-Upload/jquery.ui.widget.js" type="text/javascript"></script>
        <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
        <script src="{$smarty.const.BG_URL_STATIC}js/jQuery-File-Upload/jquery.iframe-transport.js" type="text/javascript"></script>
        <!-- The basic File Upload plugin -->
        <script src="{$smarty.const.BG_URL_STATIC}js/jQuery-File-Upload/jquery.fileupload.js" type="text/javascript"></script>
    {/if}

    {if isset($cfg.tooltip)}
        <script type="text/javascript">
        $(document).ready(function(){
            $("[data-toggle='tooltip']").tooltip({
                html: true,
                template: "<div class='tooltip tooltip_baigo'><div class='tooltip-arrow'></div><div class='tooltip-inner'></div></div>"
            });
        });
        </script>
    {/if}

    {if isset($cfg.datetimepicker)}
        <script src="{$smarty.const.BG_URL_STATIC}js/datetimepicker/jquery.datetimepicker.js" type="text/javascript"></script>
        <script type="text/javascript">
        var opts_datetimepicker = {
            lang: "{$config.lang}",
            i18n: {
                {$config.lang}: {
                    months: ["{$lang.digit.1}{$lang.label.month}", "{$lang.digit.2}{$lang.label.month}", "{$lang.digit.3}{$lang.label.month}", "{$lang.digit.4}{$lang.label.month}", "{$lang.digit.5}{$lang.label.month}", "{$lang.digit.6}{$lang.label.month}", "{$lang.digit.7}{$lang.label.month}", "{$lang.digit.8}{$lang.label.month}", "{$lang.digit.9}{$lang.label.month}", "{$lang.digit.10}{$lang.label.month}", "{$lang.digit.10}{$lang.digit.1}{$lang.label.month}", "{$lang.digit.10}{$lang.digit.2}{$lang.label.month}"],
            monthsShort: ["{$lang.digit.1}", "{$lang.digit.2}", "{$lang.digit.3}", "{$lang.digit.4}", "{$lang.digit.5}", "{$lang.digit.6}", "{$lang.digit.7}", "{$lang.digit.8}", "{$lang.digit.9}", "{$lang.digit.10}", "{$lang.digit.10}{$lang.digit.1}", "{$lang.digit.10}{$lang.digit.2}"],
                    dayOfWeek: ["{$lang.digit.0}", "{$lang.digit.1}", "{$lang.digit.2}", "{$lang.digit.3}", "{$lang.digit.4}", "{$lang.digit.5}", "{$lang.digit.6}"]
                }
            },
            //timepicker: false,
            format: "Y-m-d H:i",
            step: 30,
            mask: true
        };
        var opts_datepicker = {
            lang: "{$config.lang}",
            i18n: {
                {$config.lang}: {
                    months: ["{$lang.digit.1}{$lang.label.month}", "{$lang.digit.2}{$lang.label.month}", "{$lang.digit.3}{$lang.label.month}", "{$lang.digit.4}{$lang.label.month}", "{$lang.digit.5}{$lang.label.month}", "{$lang.digit.6}{$lang.label.month}", "{$lang.digit.7}{$lang.label.month}", "{$lang.digit.8}{$lang.label.month}", "{$lang.digit.9}{$lang.label.month}", "{$lang.digit.10}{$lang.label.month}", "{$lang.digit.10}{$lang.digit.1}{$lang.label.month}", "{$lang.digit.10}{$lang.digit.2}{$lang.label.month}"],
            monthsShort: ["{$lang.digit.1}", "{$lang.digit.2}", "{$lang.digit.3}", "{$lang.digit.4}", "{$lang.digit.5}", "{$lang.digit.6}", "{$lang.digit.7}", "{$lang.digit.8}", "{$lang.digit.9}", "{$lang.digit.10}", "{$lang.digit.10}{$lang.digit.1}", "{$lang.digit.10}{$lang.digit.2}"],
                    dayOfWeek: ["{$lang.digit.0}", "{$lang.digit.1}", "{$lang.digit.2}", "{$lang.digit.3}", "{$lang.digit.4}", "{$lang.digit.5}", "{$lang.digit.6}"]
                }
            },
            timepicker: false,
            format: "Y-m-d",
            mask: true
        };
        </script>
    {/if}

    {if isset($cfg.tokenReload)}
        <script type="text/javascript">
        function tokenReload() {
            $.getJSON("{$smarty.const.BG_URL_ADMIN}ajax.php?mod=token&act_get=make", function(result){
                if (result.alert != "y030102") {
                    $("#msg_token_content").text(result.msg);
                    $("#msg_token").modal({ show: true, backdrop: "static" });
                }
            });
            setTimeout("tokenReload();", 300000);
        }

        $(document).ready(function(){
            setTimeout("tokenReload();", 300000);
        });
        </script>
    {/if}

    <script src="{$smarty.const.BG_URL_STATIC}js/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

    <!-- {$smarty.const.PRD_SSO_POWERED} {if $smarty.const.BG_DEFAULT_UI == "default"}{$smarty.const.PRD_SSO_NAME}{else}{$smarty.const.BG_DEFAULT_UI} SSO{/if} {$smarty.const.PRD_SSO_VER} -->

</body>
</html>