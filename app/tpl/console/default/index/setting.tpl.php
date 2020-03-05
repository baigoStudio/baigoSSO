<?php $cfg = array(
    'title'             => $lang->get('Shortcut', 'console.common'),
    'menu_active'       => 'shortcut',
    'baigoSubmit'       => 'true',
    'dad'               => 'true',
    'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

    <nav class="nav mb-3">
        <a href="<?php echo $route_console; ?>" class="nav-link">
            <span class="fas fa-chevron-left"></span>
            <?php echo $lang->get('Back'); ?>
        </a>
    </nav>

    <form name="shortcut_setting_form" id="shortcut_setting_form" action="<?php echo $route_console; ?>index/submit/">
        <input type="hidden" name="admin_shortcut" id="admin_shortcut" value="">
        <input type="hidden" name="__token__" value="<?php echo $token; ?>">
        <div class="card">
            <div class="card-header">
                <?php echo $lang->get('Shortcut', 'console.common'); ?>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col border-right">
                        <div class="bg-drag" id="shortcut_list">
                            <?php foreach ($adminLogged['admin_shortcut'] as $key_m=>$value_m) { ?>
                                <div id="shortcut_list_<?php echo $key_m; ?>" class="drag drag-box alert alert-secondary" data-key="<?php echo $key_m; ?>" data-ctrl="<?php echo $value_m['ctrl']; ?>" data-act="<?php echo $value_m['act']; ?>" data-title="<?php echo $value_m['title']; ?>">
                                    <?php echo $value_m['title']; ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col">
                        <h5><?php echo $lang->get('Option'); ?></h5>

                        <?php foreach ($config['console']['console_mod'] as $key_m=>$value_m) { ?>
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input shortcut_option" <?php if (array_key_exists($key_m, $adminLogged['admin_shortcut'])) { ?>checked<?php } ?> id="shortcut_option_<?php echo $key_m; ?>" value="<?php echo $key_m; ?>" data-ctrl="<?php echo $key_m; ?>" data-act="index" data-title="<?php echo $lang->get($value_m['main']['title'], 'console.common'); ?>">
                                    <label class="form-check-label" for="shortcut_option_<?php echo $key_m; ?>">
                                        <?php echo $lang->get($value_m['main']['title'], 'console.common'); ?>
                                    </label>
                                </div>
                            </div>

                            <?php foreach ($value_m['lists'] as $key_s=>$value_s) { ?>
                                <div class="form-group ml-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input shortcut_option" <?php if (array_key_exists($key_m . '_' . $key_s, $adminLogged['admin_shortcut'])) { ?>checked<?php } ?> id="shortcut_option_<?php echo $key_m; ?>_<?php echo $key_s; ?>" value="<?php echo $key_m; ?>_<?php echo $key_s; ?>" data-ctrl="<?php echo $key_m; ?>" data-act="<?php echo $key_s; ?>" data-title="<?php echo $lang->get($value_s['title'], 'console.common'); ?>">
                                        <label class="form-check-label" for="shortcut_option_<?php echo $key_m; ?>_<?php echo $key_s; ?>">
                                            <?php echo $lang->get($value_s['title'], 'console.common'); ?>
                                        </label>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><?php echo $lang->get('Save'); ?></button>
                <button type="button" class="btn btn-outline-secondary bg-empty"><?php echo $lang->get('Empty'); ?></button>
            </div>
        </div>
    </form>

    <script type="text/javascript">
    var opts_submit_form = {
        modal: {
            btn_text: {
                close: '<?php echo $lang->get('Close'); ?>',
                ok: '<?php echo $lang->get('OK'); ?>'
            }
        },
        msg_text: {
            submitting: "<?php echo $lang->get('submitting'); ?>"
        }
    };

    function tplProcess() {
        var _arr_tpl    = {};

        $('.bg-drag > .dads-children').each(function(_key, _value){
            var _key    = $(this).data('key');
            var _ctrl   = $(this).data('ctrl');
            var _act    = $(this).data('act');
            var _title  = $(this).data('title');
            _arr_tpl[_key] = { ctrl: _ctrl, act: _act, title: _title };
        });

        var _opts_tpl = JSON.stringify(_arr_tpl);

        $('#admin_shortcut').val(_opts_tpl);
    }

    $(document).ready(function(){
        $('.bg-drag').dad({
            target: '.drag-box',
            callback: function(_ele) {
                tplProcess();
            }
        });

        $('.shortcut_option').click(function(){
            var _bool_check     = $(this).prop('checked');
            var _key            = $(this).val();
            var _ctrl           = $(this).data('ctrl');
            var _act            = $(this).data('act');
            var _title          = $(this).data('title');
            var _append_html    = '';

            _append_html += '<div id="shortcut_list_' + _key + '" class="drag drag-box alert alert-secondary" data-ctrl="' + _ctrl + '" data-act="' + _act + '" data-key="' + _key + '" data-title="' + _title + '">';
                _append_html += _title;
            _append_html += '</div>';

            if (_bool_check) {
                if ($('#shortcut_list_' + _key).length < 1) {
                    $('#shortcut_list').append(_append_html);
                }
            } else {
                $('#shortcut_list_' + _key).remove();
            }
        });

        var obj_submit_form    = $('#shortcut_setting_form').baigoSubmit(opts_submit_form);
        $('#shortcut_setting_form').submit(function(){
            tplProcess();
            obj_submit_form.formSubmit();
        });

        $('.bg-empty').click(function(){
            $('#shortcut_list').empty();
            $('.shortcut_option').prop('checked', false);
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'console_foot' . GK_EXT_TPL);
include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);