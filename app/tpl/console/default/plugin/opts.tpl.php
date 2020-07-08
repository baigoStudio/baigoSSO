<?php $cfg = array(
    'title'             => $lang->get('Plugin management', 'console.common') . ' &raquo; ' . $lang->get('Option'),
    'menu_active'       => 'plugin',
    'sub_active'        => 'index',
    'baigoValidate'    => 'true',
    'baigoSubmit'       => 'true',
    'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

    <nav class="nav mb-3">
        <a href="<?php echo $route_console; ?>plugin/" class="nav-link">
            <span class="fas fa-chevron-left"></span>
            <?php echo $lang->get('Back'); ?>
        </a>
    </nav>

    <form name="plugin_opts" id="plugin_opts" action="<?php echo $route_console; ?>plugin/opts-submit/">
        <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">
        <input type="hidden" name="plugin_dir" id="plugin_dir" value="<?php echo $pluginRow['plugin_dir']; ?>">

        <div class="row">
            <div class="col-md-9">
                <div class="card mb-3">
                    <?php include($cfg['pathInclude'] . 'plugin_menu' . GK_EXT_TPL); ?>
                    <div class="card-body">
                        <?php
                        $_arr_rule      = array();
                        $_arr_attr      = array();
                        $_arr_selector  = array();

                        if (!empty($pluginOpts)) {
                            foreach ($pluginOpts as $_key=>$_value) {
                                if (isset($_value['require'])) {
                                    $_arr_rule[$_key]['require'] = $_value['require'];
                                }

                                if (isset($_value['format'])) {
                                    $_arr_rule[$_key]['format'] = $_value['format'];
                                }

                                $_arr_attr[$_key]  = $_value['title']; ?>
                                <div class="form-group">
                                    <?php if ($_value['type'] != 'switch') { ?>
                                        <label>
                                            <?php echo $_value['title']; ?>
                                        </label>
                                    <?php }

                                    switch ($_value['type']) {
                                        case 'select': ?>
                                            <select name="<?php echo $_key; ?>" id="<?php echo $_key; ?>" class="form-control">
                                                <?php foreach ($_value['option'] as $_key_opt=>$_value_opt) { ?>
                                                    <option<?php if ($optsVar[$_key] == $_key_opt) { ?> selected<?php } ?> value="<?php echo $_key_opt; ?>">
                                                        <?php echo $_value_opt; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        <?php break;

                                        case 'select_input': ?>
                                            <div class="input-group">
                                                <input type="text" value="<?php echo $optsVar[$_key]; ?>" name="<?php echo $_key; ?>" id="<?php echo $_key; ?>" class="form-control">
                                                <span class="input-group-append">
                                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                                        <?php echo $lang->get('Please select'); ?>
                                                    </button>

                                                    <div class="dropdown-menu">
                                                        <?php foreach ($_value['option'] as $_key_opt=>$_value_opt) { ?>
                                                            <button class="dropdown-item bg-select-input" data-value="<?php echo $_key_opt; ?>" data-target="#<?php echo $_key; ?>" type="button">
                                                                <?php echo $_value_opt; ?>
                                                            </button>
                                                        <?php } ?>
                                                    </div>
                                                </span>
                                            </div>
                                        <?php break;

                                        case 'radio': ?>
                                            <div>
                                                <?php foreach ($_value['option'] as $_key_opt=>$_value_opt) { ?>
                                                    <div class="form-check <?php if (!isset($_value_opt['note'])) { ?>form-check-inline<?php } ?>">
                                                        <input type="radio"<?php if ($optsVar[$_key] == $_key_opt) { ?> checked<?php } ?> value="<?php echo $_key_opt; ?>" name="<?php echo $_key; ?>" id="<?php echo $_key; ?>_<?php echo $_key_opt; ?>" class="form-check-input">
                                                        <label for="<?php echo $_key; ?>_<?php echo $_key_opt; ?>" class="form-check-label">
                                                            <?php echo $_value_opt['value']; ?>
                                                        </label>

                                                        <?php if (isset($_value_opt['note']) && !empty($_value_opt['note'])) { ?>
                                                            <small class="form-text"><?php echo $_value_opt['note']; ?></small>
                                                        <?php } ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <?php $_arr_selector[$_key]   = 'name';
                                        break;

                                        case 'switch': ?>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" id="<?php echo $_key; ?>" name="<?php echo $_key; ?>" <?php if ($optsVar[$_key] === 'on') { ?>checked<?php } ?> value="on" class="custom-control-input">
                                                <label for="<?php echo $_key; ?>" class="custom-control-label">
                                                    <?php echo $lang->get($_value['title']); ?>
                                                </label>
                                            </div>
                                        <?php break;

                                        case 'textarea': ?>
                                            <textarea name="<?php echo $_key; ?>" id="<?php echo $_key; ?>" class="form-control bg-textarea-md"><?php echo $optsVar[$_key]; ?></textarea>
                                        <?php break;

                                        default: ?>
                                            <input type="text" value="<?php echo $optsVar[$_key]; ?>" name="<?php echo $_key; ?>" id="<?php echo $_key; ?>" class="form-control">
                                        <?php break;
                                    }

                                    if (isset($_value['note']) && !empty($_value['note'])) { ?>
                                        <small class="form-text"><?php echo $_value['note']; ?></small>
                                    <?php } ?>
                                    <small class="form-text" id="msg_<?php echo $_key; ?>"></small>
                                </div>

                             <?php }
                        } ?>

                        <div class="bg-validate-box"></div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><?php echo $lang->get('Save'); ?></button>
                    </div>
                </div>
            </div>

            <?php include($cfg['pathInclude'] . 'plugin_side' . GK_EXT_TPL); ?>
        </div>
    </form>

<?php include($cfg['pathInclude'] . 'console_foot' . GK_EXT_TPL); ?>

    <script type="text/javascript">
    var opts_validate_form = {
        rules: <?php echo json_encode($_arr_rule); ?>,
        attr_names: <?php echo json_encode($_arr_attr); ?>,
        selector_types: <?php echo json_encode($_arr_selector); ?>,
        type_msg: {
            require: '<?php echo $lang->get('{:attr} require'); ?>'
        },
        format_msg: {
            'int': '<?php echo $lang->get('{:attr} must be numeric'); ?>',
            url: '<?php echo $lang->get('{:attr} not a valid url'); ?>'
        },
        box: {
            msg: '<?php echo $lang->get('Input error'); ?>'
        }
    };

    var opts_submit_form = {
        modal: {
            btn_text: {
                close: '<?php echo $lang->get('Close'); ?>',
                ok: '<?php echo $lang->get('OK'); ?>'
            }
        },
        msg_text: {
            submitting: '<?php echo $lang->get('Saving'); ?>'
        }
    };

    $(document).ready(function(){
        var obj_validate_form    = $('#plugin_opts').baigoValidate(opts_validate_form);
        var obj_submit_form       = $('#plugin_opts').baigoSubmit(opts_submit_form);
        $('#plugin_opts').submit(function(){
            if (obj_validate_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });

        $('.bg-select-input').click(function(){
            var _val    = $(this).data('value');
            var _target = $(this).data('target');
            $(_target).val(_val);
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);