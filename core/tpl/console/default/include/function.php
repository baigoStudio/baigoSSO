<?php function admin_status_process($str_status, $status_admin) {
    $_str_css = '';

    switch ($str_status) {
        case 'enable':
            $_str_css = 'success';
        break;

        default:
            $_str_css = 'secondary';
        break;
    }

    if (isset($status_admin[$str_status])) {
        ?><span class="badge badge-<?php echo $_str_css; ?>"><?php echo $status_admin[$str_status]; ?></span><?php
    }
}

function app_status_process($str_status, $status_app) {
    $_str_css = '';

    switch ($str_status) {
        case 'enable':
            $_str_css = 'success';
        break;

        default:
            $_str_css = 'secondary';
        break;
    }

    if (isset($status_app[$str_status])) {
        ?><span class="badge badge-<?php echo $_str_css; ?>"><?php echo $status_app[$str_status]; ?></span><?php
    }
}

function app_sync_process($str_sync, $sync_app) {
    $_str_css = '';

    switch ($str_sync) {
        case 'on':
            $_str_css = 'success';
        break;

        default:
            $_str_css = 'secondary';
        break;
    }

    if (isset($sync_app[$str_sync])) {
        ?><span class="badge badge-<?php echo $_str_css; ?>"><?php echo $sync_app[$str_sync]; ?></span><?php
    }
}


function pm_status_process($str_status, $status_pm) {
    $_str_css = '';

    switch ($str_status) {
        case 'read':
            $_str_css = 'success';
        break;

        default:
            $_str_css = 'secondary';
        break;
    }

    if (isset($status_pm[$str_status])) {
        ?><span class="badge badge-<?php echo $_str_css; ?>"><?php echo $status_pm[$str_status]; ?></span><?php
    }
}


function user_status_process($str_status, $status_user) {
    $_str_css = '';

    switch ($str_status) {
        case 'enable':
            $_str_css = 'success';
        break;

        case 'wait':
            $_str_css = 'warning';
        break;

        default:
            $_str_css = 'secondary';
        break;
    }

    if (isset($status_user[$str_status])) {
        ?><span class="badge badge-<?php echo $_str_css; ?>"><?php echo $status_user[$str_status]; ?></span><?php
    }
}


function verify_status_process($str_status, $status_verify) {
    $_str_css = '';

    switch ($str_status) {
        case 'enable':
            $_str_css = 'success';
        break;

        default:
            $_str_css = 'secondary';
        break;
    }

    if (isset($status_verify[$str_status])) {
        ?><span class="badge badge-<?php echo $_str_css; ?>"><?php echo $status_verify[$str_status]; ?></span><?php
    }
}


function opt_form_process($arr_formList, $lang_opt, $tplRows = array(), $timezoneRows = array(), $timezoneLang = array(), $timezoneType = '', $lang_label = array(), $rcode = array()) {
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
            <label>
                <?php if (isset($lang_opt[$_key]['label'])) {
                    $_label = $lang_opt[$_key]['label'];
                } else {
                    $_label = $_key;
                }

                echo $_label; ?>
                <span class="text-danger">
                    <?php if ($_value['min'] > 0) { ?>*<?php } ?>
                </span>
            </label>

            <?php switch ($_value['type']) {
                case "select": ?>
                    <select name="opt[<?php echo $GLOBALS['route']['bg_act']; ?>][<?php echo $_key; ?>]" id="opt_<?php echo $GLOBALS['route']['bg_act']; ?>_<?php echo $_key; ?>" data-validate="opt_<?php echo $GLOBALS['route']['bg_act']; ?>_<?php echo $_key; ?>" class="form-control">
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
                        <div class="form-check">
                            <label for="opt_<?php echo $GLOBALS['route']['bg_act']; ?>_<?php echo $_key; ?>_<?php echo $_key_opt; ?>" class="form-check-label">
                                <input type="radio"<?php if ($_this_value == $_key_opt) { ?> checked<?php } ?> value="<?php echo $_key_opt; ?>" data-validate="opt_<?php echo $GLOBALS['route']['bg_act']; ?>_<?php echo $_key; ?>" name="opt[<?php echo $GLOBALS['route']['bg_act']; ?>][<?php echo $_key; ?>]" id="opt_<?php echo $GLOBALS['route']['bg_act']; ?>_<?php echo $_key; ?>_<?php echo $_key_opt; ?>" class="form-check-input">
                                <?php if (isset($lang_opt[$_key]['option'][$_key_opt]['value'])) {
                                    echo $lang_opt[$_key]['option'][$_key_opt]['value'];
                                } else {
                                    echo $_value_opt['value'];
                                } ?>
                            </label>
                        </div>
                        <?php
                            if (isset($lang_opt[$_key]['option'][$_key_opt]['note']) && !fn_isEmpty($lang_opt[$_key]['option'][$_key_opt]['note'])) { ?>
                            <small class="form-text"><?php echo $lang_opt[$_key]['option'][$_key_opt]['note']; ?></small>
                        <?php }
                    }
                break;

                case "textarea":  ?>
                    <textarea name="opt[<?php echo $GLOBALS['route']['bg_act']; ?>][<?php echo $_key; ?>]" id="opt_<?php echo $GLOBALS['route']['bg_act']; ?>_<?php echo $_key; ?>" data-validate="opt_<?php echo $GLOBALS['route']['bg_act']; ?>_<?php echo $_key; ?>" class="form-control bg-textarea-md"><?php echo $_this_value; ?></textarea>
                <?php break;

                default:  ?>
                    <input type="text" value="<?php echo $_this_value; ?>" name="opt[<?php echo $GLOBALS['route']['bg_act']; ?>][<?php echo $_key; ?>]" id="opt_<?php echo $GLOBALS['route']['bg_act']; ?>_<?php echo $_key; ?>" data-validate="opt_<?php echo $GLOBALS['route']['bg_act']; ?>_<?php echo $_key; ?>" class="form-control">
                <?php break;
            }

            if (isset($lang_opt[$_key]['note']) && !fn_isEmpty($lang_opt[$_key]['note'])) { ?>
                <small class="form-text"><?php echo $lang_opt[$_key]['note']; ?></small>
            <?php } ?>
            <small class="form-text" id="msg_<?php echo $GLOBALS['route']['bg_act']; ?>_<?php echo $_key; ?>"></small>
        </div>

        <?php //json
        if ($_value['type'] == 'str' || $_value['type'] == 'textarea') {
            $str_msg_min = 'too_short';
            $str_msg_max = 'too_long';
        } else {
            $str_msg_min = 'too_few';
            $str_msg_max = 'too_many';
        }
        $_str_json .= 'opt_' . $GLOBALS['route']['bg_act'] . '_' . $_key . ': {
            len: { min: ' . $_value['min'] . ', max: 900 },
            validate: { selector: "[data-validate=\'opt_' . $GLOBALS['route']['bg_act'] . '_' . $_key . '\']", type: "' . $_value['type'] . '",';
            if (isset($_value['format'])) {
                $_str_json .= ' format: "' . $_value['format'] . '",';
            }
            $_str_json .= ' group: "#group_' . $GLOBALS['route']['bg_act'] . '_' . $_key . '" },
            msg: { selector: "#msg_' . $GLOBALS['route']['bg_act'] . '_' . $_key . '", ' . $str_msg_min . ': "' . $rcode['x040201'] . $_label . '", ' . $str_msg_max . ': "' . $_label . $rcode['x040202'] . '", format_err: "' . $_label . $rcode['x040203'] . '" }
        }';
        if ($_count < count($arr_formList)) {
            $_str_json .= ',';
        }

        $_count++;
    }

    $_str_json .= '};';

    if ($GLOBALS['route']['bg_act'] == 'base') { ?>
        <div class="form-group">
            <label><?php echo $lang_label['tpl']; ?> <span class="text-danger">*</span></label>
            <select name="opt[base][BG_SITE_TPL]" id="opt_base_BG_SITE_TPL" data-validate class="form-control">
                <?php foreach ($tplRows as $_key=>$_value) {
                    if ($_value['type'] == "dir") { ?>
                        <option<?php if (BG_SITE_TPL == $_value['name']) { ?> selected<?php } ?> value="<?php echo $_value['name']; ?>"><?php echo $_value['name']; ?></option>
                    <?php }
               } ?>
            </select>
            <small class="form-text" id="msg_base_BG_SITE_TPL"></small>
        </div>

        <div class="form-group">
            <label><?php echo $lang_label['timezone']; ?> <span class="text-danger">*</span></label>
            <div class="form-row">
                <div class="col">
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

                <div class="col">
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
            <small class="form-text" id="msg_base_BG_SITE_TIMEZONE"></small>
        </div>

        <?php
        $_str_json .= 'opts_validator_form.opt_base_BG_SITE_TPL = {
            len: { min: 1, max: 0 },
            validate: { type: "select" },
            msg: { too_few: "' . $rcode['x040201'] . $lang_label['tpl'] . '" }
        };
        opts_validator_form.opt_base_BG_SITE_TIMEZONE = {
            len: { min: 1, max: 0 },
            validate: { type: "select" },
            msg: { too_few: "' . $rcode['x040201'] . $lang_label['timezone'] . '" }
        };';
    }

    return array(
        'json' => $_str_json,
    );
}


function allow_list($arr_consoleMod, $lang_consoleMod = array(), $arr_opt = array(), $lang_opt = array(), $lang_label = array(), $lang_common_page = array(), $arr_allow = array(), $admin_type = '', $is_edit = true) { ?>
    <dl>
        <?php if ($is_edit) { ?>
            <dd>
                <div class="form-check">
                    <label for="chk_all" class="form-check-label">
                        <input type="checkbox" id="chk_all" data-parent="first" class="form-check-input">
                        <?php echo $lang_label['all']; ?>
                    </label>
                </div>
            </dd>
        <?php }

        foreach ($arr_consoleMod as $_key_m=>$_value_m) { ?>
            <dt>
                <?php if (isset($lang_consoleMod[$_key_m]['main']['title'])) {
                    echo $lang_consoleMod[$_key_m]['main']['title'];
                } else {
                    echo $_value_m['main']['title'];
                } ?>
            </dt>
            <dd>
                <?php if ($is_edit) { ?>
                    <div class="form-check form-check-inline">
                        <label for="allow_<?php echo $_key_m; ?>">
                            <input type="checkbox" id="allow_<?php echo $_key_m; ?>" data-parent="chk_all" class="form-check-input">
                            <?php echo $lang_label['all']; ?>
                        </label>
                    </div>
                <?php }

                foreach ($_value_m['allow'] as $_key_s=>$_value_s) {
                    if ($is_edit) { ?>
                        <div class="form-check form-check-inline">
                            <label for="allow_<?php echo $_key_m; ?>_<?php echo $_key_s; ?>" class="form-check-label">
                                <input type="checkbox" name="admin_allow[<?php echo $_key_m; ?>][<?php echo $_key_s; ?>]" value="1" id="allow_<?php echo $_key_m; ?>_<?php echo $_key_s; ?>" <?php if (isset($arr_allow[$_key_m][$_key_s]) || $admin_type == 'super') { ?>checked<?php } ?> data-parent="allow_<?php echo $_key_m; ?>" class="form-check-input">
                                <?php if (isset($lang_consoleMod[$_key_m]['allow'][$_key_s])) {
                                    echo $lang_consoleMod[$_key_m]['allow'][$_key_s];
                                } else {
                                    echo $_value_s;
                                } ?>
                            </label>
                        </div>
                    <?php } else { ?>
                        <span>
                            <span class="oi oi-<?php if (isset($arr_allow[$_key_m][$_key_s]) || $admin_type == 'super') { ?>circle-check text-success<?php } else { ?>circle-x text-danger<?php } ?>"></span>
                            <?php if (isset($lang_consoleMod[$_key_m]['allow'][$_key_s])) {
                                echo $lang_consoleMod[$_key_m]['allow'][$_key_s];
                            } else {
                                echo $_value_s;
                            } ?>
                        </span>
                    <?php }
                } ?>
            </dd>
        <?php } ?>

        <dt><?php echo $lang_common_page['opt']; ?></dt>
        <dd>
            <?php if ($is_edit) { ?>
                <div class="form-check form-check-inline">
                    <label for="allow_opt" class="form-check-label">
                        <input type="checkbox" id="allow_opt" data-parent="chk_all" class="form-check-input">
                        <?php echo $lang_label['all']; ?>
                    </label>
                </div>
            <?php }

            foreach ($arr_opt as $_key_s=>$_value_s) {
                if ($is_edit) { ?>
                    <div class="form-check form-check-inline">
                        <label for="allow_opt_<?php echo $_key_s; ?>" class="form-check-label">
                            <input type="checkbox" name="admin_allow[opt][<?php echo $_key_s; ?>]" value="1" id="allow_opt_<?php echo $_key_s; ?>" data-parent="allow_opt" <?php if (isset($arr_allow['opt'][$_key_s]) || $admin_type == 'super') { ?>checked<?php } ?> class="form-check-input">
                            <?php if (isset($lang_opt[$_key_s]['title'])) {
                                echo $lang_opt[$_key_s]['title'];
                            } else {
                                echo $_value_s['title'];
                            } ?>
                        </label>
                    </div>
                <?php } else { ?>
                    <span>
                        <span class="oi oi-<?php if (isset($arr_allow['opt'][$_key_s]) || $admin_type == 'super') { ?>circle-check text-success<?php } else { ?>circle-x text-danger<?php } ?>"></span>
                        <?php if (isset($lang_opt[$_key_s]['title'])) {
                            echo $lang_opt[$_key_s]['title'];
                        } else {
                            echo $_value_s['title'];
                        } ?>
                    </span>
                <?php }
            }

            if ($is_edit) { ?>
                <div class="form-check form-check-inline">
                    <label for="allow_opt_dbconfig" class="form-check-label">
                        <input type="checkbox" name="admin_allow[opt][dbconfig]" value="1" id="allow_opt_dbconfig" data-parent="allow_opt" <?php if (isset($arr_allow['opt']['dbconfig']) || $admin_type == 'super') { ?>checked<?php } ?> class="form-check-input">
                        <?php echo $lang_common_page['dbconfig']; ?>
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label for="allow_opt_chkver" class="form-check-label">
                        <input type="checkbox" name="admin_allow[opt][chkver]" value="1" id="allow_opt_chkver" data-parent="allow_opt" <?php if (isset($arr_allow['opt']['chkver']) || $admin_type == 'super') { ?>checked<?php } ?> class="form-check-input">
                        <?php echo $lang_common_page['chkver']; ?>
                    </label>
                </div>
            <?php } else { ?>
                <span>
                    <span class="oi oi-<?php if (isset($arr_allow['opt']['dbconfig']) || $admin_type == 'super') { ?>circle-check text-success<?php } else { ?>circle-x text-danger<?php } ?>"></span>
                    <?php echo $lang_common_page['dbconfig']; ?>
                </span>
                <span>
                    <span class="oi oi-<?php if (isset($arr_allow['opt']['chkver']) || $admin_type == 'super') { ?>circle-check text-success<?php } else { ?>circle-x text-danger<?php } ?>"></span>
                    <?php echo $lang_common_page['chkver']; ?>
                </span>
            <?php } ?>
        </dd>
    </dl>
<?php }