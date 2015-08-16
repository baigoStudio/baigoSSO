{* html_foot.tpl HTML 底部通用 *}

			</div>

			<div class="panel-footer">
				<div class="pull-left">
					{if $config.ui == "default"}
						<a href="{$smarty.const.PRD_SSO_URL}" target="_blank">{$smarty.const.PRD_SSO_POWERED} {$smarty.const.PRD_SSO_NAME}</a>
					{else}
						{$config.ui} SSO
					{/if}
				</div>
				<div class="pull-right">
					<a href="{$smarty.const.BG_URL_HELP}ctl.php?mod={$cfg.mod_help}&act_get={$cfg.act_help}" target="_blank">
						<span class="glyphicon glyphicon-question-sign"></span>
						{$lang.href.help}
					</a>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>

</body>
