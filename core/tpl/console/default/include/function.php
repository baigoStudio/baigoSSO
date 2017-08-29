<?php function opt_form_process($arr_formList, $lang_opt, $tplRows = array(), $timezoneRows = array(), $timezoneLang = array(), $timezoneType = '', $lang_mod = array(), $rcode = array()) {
    $_str_json = "var opts_validator_form = {";

    $_count = 1;

    foreach ($arr_formList as $_key=>$_value) {
        //form
        if (defined($_key)) {
            $_this_value = constant($_key);
        } else {
            $_this_value = $_value['default'];
        } ?>
        <div class="form-group">
            <div id="group_<?php echo $GLOBALS['act']; ?>_<?php echo $_key; ?>">
                <label class="control-label">
                    <?php if (isset($lang_opt[$_key]['label'])) {
                        $_label = $lang_opt[$_key]['label'];
                    } else {
                        $_label = $_key;
                    }

                    echo $_label; ?>
                    <span id="msg_<?php echo $GLOBALS['act']; ?>_<?php echo $_key; ?>">
                        <?php if ($_value['min'] > 0) { ?>*<?php } ?>
                    </span>
                </label>

                <?php switch ($_value['type']) {
                    case "select": ?>
                        <select name="opt[<?php echo $GLOBALS['act']; ?>][<?php echo $_key; ?>]" id="opt_<?php echo $GLOBALS['act']; ?>_<?php echo $_key; ?>" data-validate="opt_<?php echo $GLOBALS['act']; ?>_<?php echo $_key; ?>" class="form-control">
                            <?php foreach ($_value['option'] as $_key_opt=>$_value_opt) { ?>
                                <option<?php if ($_this_value == $_key_opt) { ?> selected<?php } ?> value="<?php echo $_key_opt; ?>">
                                    <?php if (isset($lang_opt[$_key]['option'][$_key_opt])) {
                                        echo $lang_opt[$_key]['option'][$_key_opt];
                                    } else {
                                        echo $_value_opt;
                                    } ?>
                                </option>
                            <?php } ?>
                        </select>
                    <?php break;

                    case "radio":
                        foreach ($_value['option'] as $_key_opt=>$_value_opt) { ?>
                            <div class="bg-radio">
                                <label for="opt_<?php echo $GLOBALS['act']; ?>_<?php echo $_key; ?>_<?php echo $_key_opt; ?>">
                                    <input type="radio"<?php if ($_this_value == $_key_opt) { ?> checked<?php } ?> value="<?php echo $_key_opt; ?>" data-validate="opt_<?php echo $GLOBALS['act']; ?>_<?php echo $_key; ?>" name="opt[<?php echo $GLOBALS['act']; ?>][<?php echo $_key; ?>]" id="opt_<?php echo $GLOBALS['act']; ?>_<?php echo $_key; ?>_<?php echo $_key_opt; ?>">
                                    <?php if (isset($lang_opt[$_key]['option'][$_key_opt]['value'])) {
                                        echo $lang_opt[$_key]['option'][$_key_opt]['value'];
                                    } else {
                                        echo $_value_opt['value'];
                                    } ?>
                                </label>
                            </div>
                            <?php
                                if (isset($lang_opt[$_key]['option'][$_key_opt]['note']) && !fn_isEmpty($lang_opt[$_key]['option'][$_key_opt]['note'])) { ?>
                                <span class="help-block"><?php echo $lang_opt[$_key]['option'][$_key_opt]['note']; ?></span>
                            <?php }
                        }
                    break;

                    case "textarea":  ?>
                        <textarea name="opt[<?php echo $GLOBALS['act']; ?>][<?php echo $_key; ?>]" id="opt_<?php echo $GLOBALS['act']; ?>_<?php echo $_key; ?>" data-validate="opt_<?php echo $GLOBALS['act']; ?>_<?php echo $_key; ?>" class="form-control bg-textarea-md"><?php echo $_this_value; ?></textarea>
                    <?php break;

                    default:  ?>
                        <input type="text" value="<?php echo $_this_value; ?>" name="opt[<?php echo $GLOBALS['act']; ?>][<?php echo $_key; ?>]" id="opt_<?php echo $GLOBALS['act']; ?>_<?php echo $_key; ?>" data-validate="opt_<?php echo $GLOBALS['act']; ?>_<?php echo $_key; ?>" class="form-control">
                    <?php break;
                }

                if (isset($lang_opt[$_key]['note']) && !fn_isEmpty($lang_opt[$_key]['note'])) { ?>
                    <span class="help-block"><?php echo $lang_opt[$_key]['note']; ?></span>
                <?php } ?>
            </div>
        </div>

        <?php //json
        if ($_value['type'] == "str" || $_value['type'] == "textarea") {
            $str_msg_min = "too_short";
            $str_msg_max = "too_long";
        } else {
            $str_msg_min = "too_few";
            $str_msg_max = "too_many";
        }
        $_str_json .= "opt_" . $GLOBALS['act'] . '_' . $_key . ": {
            len: { min: " . $_value['min'] . ", max: 900 },
            validate: { selector: \"[data-validate='opt_" . $GLOBALS['act'] . '_' . $_key . "']\", type: \"" . $_value['type'] . "\",";
            if (isset($_value["format"])) {
                $_str_json .= " format: \"" . $_value["format"] . "\",";
            }
            $_str_json .= " group: \"#group_" . $GLOBALS['act'] . '_' . $_key . "\" },
            msg: { selector: \"#msg_" . $GLOBALS['act'] . '_' . $_key . "\", " . $str_msg_min . ": \"" . $rcode['x040201'] . $_label . "\", " . $str_msg_max . ": \"" . $_label . $rcode["x040202"] . "\", format_err: \"" . $_label . $rcode["x040203"] . "\" }
        }";
        if ($_count < count($arr_formList)) {
            $_str_json .= ",";
        }

        $_count++;
    }

    $_str_json .= "};";

    if ($GLOBALS['act'] == "base") { ?>
        <div class="form-group">
            <div id="group_base_BG_SITE_TPL">
                <label class="control-label"><?php echo $lang_mod['label']['tpl']; ?><span id="msg_base_BG_SITE_TPL">*</span></label>
                <select name="opt[base][BG_SITE_TPL]" id="opt_base_BG_SITE_TPL" data-validate class="form-control">
                    <?php foreach ($tplRows as $_key=>$_value) {
                        if ($_value['type'] == "dir") { ?>
                            <option<?php if (BG_SITE_TPL == $_value['name']) { ?> selected<?php } ?> value="<?php echo $_value['name']; ?>"><?php echo $_value['name']; ?></option>
                        <?php }
                   } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div id="group_base_BG_SITE_TIMEZONE">
                <label class="control-label"><?php echo $lang_mod['label']["timezone"]; ?><span id="msg_base_BG_SITE_TIMEZONE">*</span></label>
                <div class="row">
                    <div class="col-xs-6">
                        <select name="timezone_type" id="timezone_type" class="form-control">
                            <?php foreach ($timezoneRows as $_key=>$_value) { ?>
                                <option<?php if ($timezoneType == $_key) { ?> selected<?php } ?> value="<?php echo $_key; ?>">
                                    <?php if (isset($timezoneLang[$_key]['title'])) {
                                        echo $timezoneLang[$_key]['title'];
                                    } else {
                                        echo $_value['title'];
                                    } ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-xs-6">
                        <select name="opt[base][BG_SITE_TIMEZONE]" id="opt_base_BG_SITE_TIMEZONE" data-validate class="form-control">
                            <?php foreach ($timezoneRows[$timezoneType]['sub'] as $_key=>$_value) { ?>
                                <option<?php if (BG_SITE_TIMEZONE == $_key) { ?> selected<?php } ?> value="<?php echo $_key; ?>">
                                    <?php if (isset($timezoneLang[$timezoneType]['sub'][$_key])) {
                                        echo $timezoneLang[$timezoneType]['sub'][$_key];
                                    } else {
                                        echo $_value;
                                    } ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $_str_json .= "opts_validator_form.opt_base_BG_SITE_TPL = {
            len: { min: 1, max: 0 },
            validate: { type: \"select\", group: \"#group_base_BG_SITE_TPL\" },
            msg: { selector: \"#msg_base_BG_SITE_TPL\", too_few: \"" . $rcode['x040201'] . $lang_mod['label']['tpl'] . "\" }
        };
        opts_validator_form.opt_base_BG_SITE_TIMEZONE = {
            len: { min: 1, max: 0 },
            validate: { type: \"select\", group: \"#group_base_BG_SITE_TIMEZONE\" },
            msg: { selector: \"#msg_base_BG_SITE_TIMEZONE\", too_few: \"" . $rcode['x040201'] . $lang_mod['label']["timezone"] . "\" }
        };";
    }

    return array(
        "json" => $_str_json,
    );
}