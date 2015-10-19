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

	{if isset($cfg.baigoCheckall)}
		<!--全选 js-->
		<script src="{$smarty.const.BG_URL_STATIC}js/baigoCheckall.js" type="text/javascript"></script>
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

</html>