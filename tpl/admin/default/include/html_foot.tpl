{* html_foot.tpl HTML 底部通用 *}

	{if isset($cfg.baigoValidator)}
		<!--表单验证 js-->
		<script src="{$smarty.const.BG_URL_STATIC}js/baigoValidator/baigoValidator.js" type="text/javascript"></script>
	{/if}

	{if isset($cfg.baigoSubmit)}
		<!--表单 ajax 提交 js-->
		<script src="{$smarty.const.BG_URL_STATIC}js/baigoSubmit/baigoSubmit.js" type="text/javascript"></script>
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
		<script src="{$smarty.const.BG_URL_STATIC}js/baigoCheckall.js" type="text/javascript"></script>
	{/if}

	{if isset($cfg.upload)}
		<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
		<script src="{$smarty.const.BG_URL_STATIC}js/jQuery-File-Upload/jquery.ui.widget.js" type="text/javascript"></script>
		<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
		<script src="{$smarty.const.BG_URL_STATIC}js/jQuery-File-Upload/jquery.iframe-transport.js" type="text/javascript"></script>
		<!-- The basic File Upload plugin -->
		<script src="{$smarty.const.BG_URL_STATIC}js/jQuery-File-Upload/jquery.fileupload.js" type="text/javascript"></script>
	{/if}

	{if isset($cfg.tokenReload)}
		<script type="text/javascript">
		function tokenReload() {
			$.getJSON("{$smarty.const.BG_URL_ADMIN}ajax.php?mod=token&act_get=make", function(result){
				var _token = $("form input.token_session").val();
				if (result.alert == "y030102") {
					if (_token != result.token) {
						//alert(result.str_alert);
						$("form input.token_session").val(result.token);
					}
				} else {
					alert(result.msg);
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

    <!-- {$smarty.const.PRD_SSO_POWERED} {if $config.ui == "default"}{$smarty.const.PRD_SSO_NAME}{else}{$config.ui} SSO{/if} {$smarty.const.PRD_SSO_VER} -->

</body>
</html>