<?php $cfg = array(
    'title'             => $lang->get('System settings', 'console.common') . ' &raquo; ' . $lang->get('Database settings', 'console.common'),
    'menu_active'       => 'opt',
    'sub_active'        => 'dbconfig',
    'baigoValidate'    => 'true',
    'baigoSubmit'       => 'true',
    'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

    <form name="opt_form" id="opt_form" action="<?php echo $route_console; ?>opt/dbconfig-submit/">
        <input type="hidden" name="__token__" value="<?php echo $token; ?>">

        <div class="card">
            <div class="card-body">
                <?php include($cfg['pathInclude'] . 'dbconfig' . GK_EXT_TPL); ?>

                <div class="bg-validate-box"></div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <?php echo $lang->get('Save'); ?>
                </button>
            </div>
        </div>
    </form>

<?php include($cfg['pathInclude'] . 'console_foot' . GK_EXT_TPL); ?>

    <script type="text/javascript">
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
        var obj_validate_form  = $('#opt_form').baigoValidate(opts_validate_form);
        var obj_submit_form     = $('#opt_form').baigoSubmit(opts_submit_form);

        $('#opt_form').submit(function(){
            if (obj_validate_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);