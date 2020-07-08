    <?php if ($combineRow['combine_id'] > 0) {
        $title_sub    = $lang->get('Edit');
    } else {
        $title_sub    = $lang->get('Add');
    } ?>

    <form name="combine_form" id="combine_form" action="<?php echo $route_console; ?>combine/submit/">
        <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">
        <input type="hidden" name="combine_id" id="combine_id" value="<?php echo $combineRow['combine_id']; ?>">

        <div class="modal-header">
            <div class="modal-title"><?php echo $lang->get('Sync combine', 'console.common'), ' &raquo; ', $title_sub; ?></div>
            <button type="button" class="close" data-dismiss="modal">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">

            <?php if ($combineRow['combine_id'] > 0) { ?>
                <div class="form-group">
                    <label><?php echo $lang->get('ID'); ?></label>
                    <input type="text" value="<?php echo $combineRow['combine_id']; ?>" class="form-control-plaintext" readonly>
                </div>
            <?php } ?>

            <div class="form-group">
                <label><?php echo $lang->get('Name'); ?> <span class="text-danger">*</span></label>
                <input value="<?php echo $combineRow['combine_name']; ?>" name="combine_name" id="combine_name" class="form-control">
            </div>

            <div class="bg-validate-box"></div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-sm">
                <?php echo $lang->get('Save'); ?>
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">
                <?php echo $lang->get('Close', 'console.common'); ?>
            </button>
        </div>
    </form>

    <script type="text/javascript">
    var opts_validate_modal = {
        rules: {
            combine_name: {
                length: '1,30'
            }
        },
        attr_names: {
            combine_name: '<?php echo $lang->get('Name'); ?>'
        },
        type_msg: {
            length: '<?php echo $lang->get('Size of {:attr} must be {:rule}'); ?>'
        },
        msg: {
            loading: '<?php echo $lang->get('Loading'); ?>'
        },
        box: {
            msg: '<?php echo $lang->get('Input error'); ?>'
        }
    };

    var opts_submit_modal = {
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
        var obj_validate_modal   = $('#combine_form').baigoValidate(opts_validate_modal);
        var obj_submit_modal     = $('#combine_form').baigoSubmit(opts_submit_modal);

        $('#combine_form').submit(function(){
            if (obj_validate_modal.verify()) {
                obj_submit_modal.formSubmit();
            }
        });
    });
    </script>