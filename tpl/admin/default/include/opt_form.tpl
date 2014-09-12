	{foreach $opt[$cfg.sub_active] as $key=>$value}
		{if $smarty.const[$key]}
			{$_this_value = $smarty.const[$key]}
		{else if $tplData.optRows[$key].opt_value}
			{$_this_value = $tplData.optRows[$key].opt_value}
		{else}
			{$_this_value = $value.default}
		{/if}

		<div class="form-group">
			<label for="opt_{$key}" class="control-label">{$value.label}<span id="msg_{$key}">{if $value.min > 0}*{/if}</span></label>

			{if $value.type == "select"}
				<select name="opt[{$key}]" id="opt_{$key}" class="validate form-control">
					{foreach $value.option as $_key=>$_value}
						<option {if $_this_value == $_key}selected{/if} value="{$_key}">{$_value}</option>
					{/foreach}
				</select>
			{else if $value.type == "radio"}
				<dl>
					{foreach $value.option as $_key=>$_value}
						<dt>
							<div class="radio">
								<label for="opt_{$key}_{$_key}">
									<input type="radio" {if $_this_value == $_key}checked{/if} value="{$_key}" class="validate" group="opt_{$key}" name="opt[{$key}]" id="opt_{$key}_{$_key}">
									{$_value.value}
								</label>
							</div>
						</dt>
						<dd>{$_value.note}</dd>
					{/foreach}
				</dl>
			{else if $value.type == "textarea"}
				<textarea name="opt[{$key}]" id="opt_{$key}" class="validate form-control text_md">{$_this_value}</textarea>
			{else}
				<input type="text" value="{$_this_value}" name="opt[{$key}]" id="opt_{$key}" class="validate form-control">
			{/if}

			<p class="help-block">{$value.note}</p>
		</div>
	{/foreach}
