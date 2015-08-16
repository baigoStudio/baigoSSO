	{foreach $opt[$cfg.sub_active] as $key=>$value}
		{if $smarty.const[$key]}
			{$_this_value = $smarty.const[$key]}
		{else}
			{$_this_value = $value.default}
		{/if}

		<div class="form-group">
			<div id="group_{$key}">
				<label for="opt_{$key}" class="control-label">{$value.label}<span id="msg_{$key}">{if $value.min > 0}*{/if}</span></label>

				{if $value.type == "select"}
					<select name="opt[{$key}]" id="opt_{$key}" class="validate form-control">
						{foreach $value.option as $key_opt=>$value_opt}
							<option {if $_this_value == $key_opt}selected{/if} value="{$key_opt}">{$value_opt}</option>
						{/foreach}
					</select>
				{else if $value.type == "radio"}
					<dl>
						{foreach $value.option as $key_opt=>$value_opt}
							<dt>
								<div class="radio">
									<label for="opt_{$key}_{$key_opt}">
										<input type="radio" {if $_this_value == $key_opt}checked{/if} value="{$key_opt}" class="validate" group="opt_{$key}" name="opt[{$key}]" id="opt_{$key}_{$key_opt}">
										{$value_opt.value}
									</label>
								</div>
							</dt>
							<dd>{if isset($value_opt.note)}{$value_opt.note}{/if}</dd>
						{/foreach}
					</dl>
				{else if $value.type == "textarea"}
					<textarea name="opt[{$key}]" id="opt_{$key}" class="validate form-control text_md">{$_this_value}</textarea>
				{else}
					<input type="text" value="{$_this_value}" name="opt[{$key}]" id="opt_{$key}" class="validate form-control">
				{/if}

				<p class="help-block">{if isset($value.note)}{$value.note}{/if}</p>
			</div>
		</div>
	{/foreach}

	<script type="text/javascript">
	var opts_validator_form = {
		{foreach $opt[$cfg.sub_active] as $key=>$value}
			{if $value.type == "str" || $value.type == "textarea"}
				{$str_msg_min = "too_short"}
				{$str_msg_max = "too_long"}
			{else}
				{$str_msg_min = "too_few"}
				{$str_msg_max = "too_many"}
			{/if}
			"opt_{$key}": {
				length: { min: {$value.min}, max: 900 },
				validate: { type: "{$value.type}", {if isset($value.format)}format: "{$value.format}", {/if}group: "group_{$key}" },
				msg: { id: "msg_{$key}", {$str_msg_min}: "{$alert.x040201}{$value.label}", {$str_msg_max}: "{$value.label}{$alert.x040202}", format_err: "{$value.label}{$alert.x040203}" }
			}{if !$value@last},{/if}
		{/foreach}
	};
	</script>