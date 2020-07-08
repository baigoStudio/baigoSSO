<?php $cfg = array(
    'title'             => $lang->get('System settings', 'console.common') . ' &raquo; ' . $lang->get('Database settings', 'console.common'),
    'menu_active'       => 'opt',
    'sub_active'        => 'dbconfig',
    'baigoValidate'     => 'true',
    'baigoSubmit'       => 'true',
    'baigoDialog'       => 'true',
    'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

    <div class="row">
        <div class="col-md-3">
            <h5><?php echo $lang->get('Upgrade data'); ?></h5>
            <div class="alert alert-warning">
                <?php echo $lang->get('Warning! Please backup the data before upgrading.'); ?>
            </div>
            <a href="#upgrade_modal" class="btn btn-primary" data-toggle="modal">
                <span class="fas fa-database"></span>
                <?php echo $lang->get('Upgrade'); ?>
            </a>
        </div>
        <div class="col-md-9">
            <form name="opt_form" id="opt_form" action="<?php echo $route_console; ?>opt/dbconfig-submit/">
                <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">

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
        </div>
    </div>

    <div class="modal fade" id="upgrade_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <?php echo $lang->get('Warning! Please backup the data before upgrading.'); ?>
                    </div>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="upgrade_content">
                </div>
                <div class="modal-footer" id="upgrade_foot">
                    <button type="button" class="btn btn-primary btn-sm" id="upgrade_confirm">
                        <?php echo $lang->get('Confirm upgrade'); ?>
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">
                        <?php echo $lang->get('Close', 'console.common'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

<?php include($cfg['pathInclude'] . 'console_foot' . GK_EXT_TPL); ?>

    <script type="text/javascript">
    var opts_dialog = {
        btn_text: {
            cancel: '<?php echo $lang->get('Cancel'); ?>',
            confirm: '<?php echo $lang->get('Confirm'); ?>'
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
        $('#upgrade_foot').on('click', '#upgrade_confirm', function(){
            $('#upgrade_modal #upgrade_content').load('<?php echo $route_console; ?>opt/data-upgrade/view/modal/');
    	});

        $('#upgrade_modal').on('hidden.bs.modal', function(){
        	$('#upgrade_modal #upgrade_content').empty();
    	});

        var obj_validate_form   = $('#opt_form').baigoValidate(opts_validate_form);
        var obj_submit_form     = $('#opt_form').baigoSubmit(opts_submit_form);

        $('#opt_form').submit(function(){
            if (obj_validate_form.verify()) {
                obj_submit_form.formSubmit();
            }
        });

        var obj_dialog = $.baigoDialog(opts_dialog);
    });
    </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);