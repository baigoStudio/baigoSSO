<?php function opt_form_process($arr_formList, $tplRows = array(), $timezoneRows = array(), $timezoneType = "", $timezoneJson = "", $lang = array(), $rcode = array()) {
    $_str_echo = "";
    $_str_json = "opts_validator_form = {";

    foreach ($arr_formList as $key=>$value) {
        //form
        if (defined($key)) {
            $this_value = constant($key);
        } else {
            $this_value = $value["default"];
        }
        $_str_echo .= "<div class=\"form-group\">
            <div id=\"group_" . $GLOBALS["act"] . "_" . $key . "\">
                <label class=\"control-label\">" . $value["label"] . "<span id=\"msg_" . $GLOBALS["act"] . "_" . $key . "\">";
                if ($value["min"] > 0) {
                    $_str_echo .= "*";
                }
                $_str_echo .= "</span></label>";

                switch($value["type"]) {
                    case "select":
                        $_str_echo .= "<select name=\"opt[" . $GLOBALS["act"] . "][" . $key . "]\" id=\"opt_" . $GLOBALS["act"] . "_" . $key . "\" data-validate=\"opt_" . $GLOBALS["act"] . "_" . $key . "\" class=\"form-control\">";
                            foreach ($value["option"] as $key_opt=>$value_opt) {
                                $_str_echo .= "<option";
                                if ($this_value == $key_opt) {
                                    $_str_echo .= " selected";
                                }
                                $_str_echo .= " value=\"" . $key_opt . "\">" . $value_opt . "</option>";
                            }
                        $_str_echo .= "</select>";
                    break;

                    case "radio":
                        foreach ($value["option"] as $key_opt=>$value_opt) {
                            $_str_echo .= "<div class=\"bg-radio\">
                                <label for=\"opt_" . $GLOBALS["act"] . "_" . $key . "_" . $key_opt . "\">
                                    <input type=\"radio\"";
                                    if ($this_value == $key_opt) {
                                        $_str_echo .= " checked";
                                    }
                                    $_str_echo .= " value=\"" . $key_opt . "\" data-validate=\"opt_" . $GLOBALS["act"] . "_" . $key . "\" name=\"opt[" . $GLOBALS["act"] . "][" . $key . "]\" id=\"opt_" . $GLOBALS["act"] . "_" . $key . "_" . $key_opt . "\">";
                                    $_str_echo .= $value_opt["value"];
                                $_str_echo .= "</label>
                            </div>";
                            if (isset($value_opt["note"]) && !fn_isEmpty($value_opt["note"])) {
                                $_str_echo .= "<p class=\"help-block\">" . $value_opt["note"] . "</p>";
                            }
                        }
                    break;

                    case "textarea":
                        $_str_echo .= "<textarea name=\"opt[" . $GLOBALS["act"] . "][" . $key . "]\" id=\"opt_" . $GLOBALS["act"] . "_" . $key . "\" data-validate=\"opt_" . $GLOBALS["act"] . "_" . $key . "\" class=\"form-control bg-textarea-md\">" . $this_value . "</textarea>";
                    break;

                    default:
                        $_str_echo .= "<input type=\"text\" value=\"" . $this_value . "\" name=\"opt[" . $GLOBALS["act"] . "][" . $key . "]\" id=\"opt_" . $GLOBALS["act"] . "_" . $key . "\" data-validate=\"opt_" . $GLOBALS["act"] . "_" . $key . "\" class=\"form-control\">";
                    break;
                }

                if (isset($value["note"]) && !fn_isEmpty($value["note"])) {
                    $_str_echo .= "<p class=\"help-block\">" . $value["note"] . "</p>";
                }
            $_str_echo .= "</div>
        </div>";

        //json
        if ($value["type"] == "str" || $value["type"] == "textarea") {
            $str_msg_min = "too_short";
            $str_msg_max = "too_long";
        } else {
            $str_msg_min = "too_few";
            $str_msg_max = "too_many";
        }
        $_str_json .= "opt_" . $GLOBALS["act"] . "_" . $key . ": {
            len: { min: " . $value["min"] . ", max: 900 },
            validate: { selector: \"[data-validate='opt_" . $GLOBALS["act"] . "_" . $key . "']\", type: \"" . $value["type"] . "\",";
            if (isset($value["format"])) {
                $_str_json .= " format: \"" . $value["format"] . "\",";
            }
            $_str_json .= " group: \"#opt_" . $GLOBALS["act"] . "_" . $key . "\" },
            msg: { selector: \"#msg_" . $GLOBALS["act"] . "_" . $key . "\", " . $str_msg_min . ": \"" . $rcode["x040201"] . $value["label"] . "\", " . $str_msg_max . ": \"" . $value["label"] . $rcode["x040202"] . "\", format_err: \"" . $value["label"] . $rcode["x040203"] . "\" }
        }";
        if ($value != end($arr_formList)) {
            $_str_json .= ",";
        }
    }

    $_str_json .= "};";

    if ($GLOBALS["act"] == "base") {
        $_str_echo .= "<div class=\"form-group\">
            <div id=\"group_base_BG_SITE_TPL\">
                <label class=\"control-label\">" . $lang["label"]["tpl"] . "<span id=\"msg_base_BG_SITE_TPL\">*</span></label>
                <select name=\"opt[base][BG_SITE_TPL]\" id=\"opt_base_BG_SITE_TPL\" data-validate class=\"form-control\">";
                    foreach ($tplRows as $key=>$value) {
                        if ($value["type"] == "dir") {
                            $_str_echo .= "<option";
                            if (BG_SITE_TPL == $value["name"]) {
                                $_str_echo .= " selected";
                            }
                            $_str_echo .= " value=\"" . $value["name"] . "\">" . $value["name"] . "</option>";
                        }
                   }
                $_str_echo .= "</select>
            </div>
        </div>

        <div class=\"form-group\">
            <div id=\"group_base_BG_SITE_TIMEZONE\">
                <label class=\"control-label\">" . $lang["label"]["timezone"] . "<span id=\"msg_base_BG_SITE_TIMEZONE\">*</span></label>
                <div class=\"row\">
                    <div class=\"col-xs-6\">
                        <select name=\"timezone_type\" id=\"timezone_type\" class=\"form-control\">";
                            foreach ($timezoneRows as $key=>$value) {
                                $_str_echo .= "<option";
                                if ($timezoneType == $key) {
                                    $_str_echo .= " selected";
                                }
                                $_str_echo .= " value=\"" . $key . "\">" . $value["title"] . "</option>";
                            }
                        $_str_echo .= "</select>
                    </div>

                    <div class=\"col-xs-6\">
                        <select name=\"opt[base][BG_SITE_TIMEZONE]\" id=\"opt_base_BG_SITE_TIMEZONE\" data-validate class=\"form-control\">";
                            foreach ($timezoneRows[$timezoneType]["sub"] as $key=>$value) {
                                $_str_echo .= "<option";
                                if (BG_SITE_TIMEZONE == $key) {
                                    $_str_echo .= " selected";
                                }
                                $_str_echo .= " value=\"" . $key . "\">" . $value . "</option>";
                            }
                        $_str_echo .= "</select>
                    </div>
                </div>
            </div>
        </div>";

        $_str_json .= "opts_validator_form.opt_base_BG_SITE_TPL = {
            len: { min: 1, max: 0 },
            validate: { type: \"select\", group: \"#opt_base_BG_SITE_TPL\" },
            msg: { selector: \"#msg_base_BG_SITE_TPL\", too_few: \"" . $rcode["x040201"] . $lang["label"]["tpl"] . "\" }
        };
        opts_validator_form.opt_base_BG_SITE_TIMEZONE = {
            len: { min: 1, max: 0 },
            validate: { type: \"select\", group: \"#opt_base_BG_SITE_TIMEZONE\" },
            msg: { selector: \"#msg_base_BG_SITE_TIMEZONE\", too_few: \"" . $rcode["x040201"] . $lang["label"]["timezone"] . "\" }
        };
        var _timezoneJson = " . $timezoneJson . ";";
    }

    return array(
        "form" => $_str_echo,
        "json" => $_str_json,
    );
} ?>