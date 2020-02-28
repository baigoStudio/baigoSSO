<?php $cfg = array(
    'title'         => $lang->get('Installer'),
    'btn'           => $lang->get('Complete'),
    'sub_title'     => $lang->get('Complete installation'),
    'active'        => 'over',
    'pathInclude'   => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'index_head' . GK_EXT_TPL); ?>

    <form name="over_form" id="over_form" action="<?php echo $route_install; ?>index/over-submit/">
        <input type="hidden" name="__token__" value="<?php echo $token; ?>">
        <div class="alert alert-success">
            <span class="fas fa-check-circle"></span>
            <?php echo $lang->get('Last step, complete the installation'); ?>
        </div>

        <?php include($cfg['pathInclude'] . 'install_btn' . GK_EXT_TPL) ?>
    </form>

<?php include($cfg['pathInclude'] . 'install_foot' . GK_EXT_TPL); ?>

    <script type="text/javascript">
    var opts_submit_form = {
        modal: {
            btn_text: {
                close: '<?php echo $lang->get('Close'); ?>',
                ok: '<?php echo $lang->get('OK'); ?>'
            }
        },
        msg_text: {
            submitting: '<?php echo $lang->get('Submitting'); ?>'
        },
        jump: {
            url: '<?php echo $route_console; ?>',
            text: '<?php echo $lang->get('Redirecting'); ?>'
        }
    };

    $(document).ready(function(){
        var obj_submit_form       = $('#over_form').baigoSubmit(opts_submit_form);
        $('#over_form').submit(function(){
            obj_submit_form.formSubmit();
        });
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);