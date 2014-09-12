{* html_foot.tpl HTML 底部通用 *}

	{if $cfg.baigoValidator}
		<!--表单验证 js-->
		<script src="{$smarty.const.BG_URL_JS}baigoValidator/baigoValidator.js" type="text/javascript"></script>
	{/if}

	{if $cfg.baigoSubmit}
		<!--表单 ajax 提交 js-->
		<script src="{$smarty.const.BG_URL_JS}baigoSubmit/baigoSubmit.js" type="text/javascript"></script>
	{/if}

	{if $cfg.reloadImg}
		<!--重新载入图片 js-->
		<script src="{$smarty.const.BG_URL_JS}reloadImg.js" type="text/javascript"></script>
	{/if}

	{if $cfg.baigoCheckall}
		<!--全选 js-->
		<script src="{$smarty.const.BG_URL_JS}baigoCheckall.js" type="text/javascript"></script>
	{/if}

	<script src="{$smarty.const.BG_URL_JS}bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

</html>