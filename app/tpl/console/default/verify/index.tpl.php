<?php $cfg = array(
    'title'             => $lang->get('Validation token', 'console.common'),
    'menu_active'       => 'verify',
    'sub_active'        => 'index',
    'baigoValidate'    => 'true',
    'baigoSubmit'       => 'true',
    'baigoCheckall'     => 'true',
    'baigoDialog'       => 'true',
    'tooltip'           => 'true',
    'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

    <form name="verify_list" id="verify_list" action="<?php echo $route_console; ?>verify/status/">
        <input type="hidden" name="__token__" value="<?php echo $token; ?>">

        <div class="table-responsive">
            <table class="table table-striped border bg-white">
                <thead>
                    <tr>
                        <th class="text-nowrap bg-td-xs">
                            <div class="form-check">
                                <input type="checkbox" name="chk_all" id="chk_all" data-parent="first" class="form-check-input">
                                <label for="chk_all" class="form-check-label">
                                    <small><?php echo $lang->get('ID'); ?></small>
                                </label>
                            </div>
                        </th>
                        <th>
                            <?php echo $lang->get('Operator'); ?>
                        </th>
                        <th class="d-none d-lg-table-cell bg-td-md">
                            <small><?php echo $lang->get('Time'); ?></small>
                        </th>
                        <th class="d-none d-lg-table-cell bg-td-md text-right">
                            <small>
                                <?php echo $lang->get('Status'); ?>
                                /
                                <?php echo $lang->get('Type'); ?>
                            </small>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($verifyRows as $key=>$value) { ?>
                        <tr class="bg-manage-tr">
                            <td class="text-nowrap bg-td-xs">
                                <div class="form-check">
                                    <input type="checkbox" name="verify_ids[]" value="<?php echo $value['verify_id']; ?>" id="verify_id_<?php echo $value['verify_id']; ?>" data-parent="chk_all" data-validate="verify_ids" class="form-check-input verify_id">
                                    <label for="verify_id_<?php echo $value['verify_id']; ?>" class="form-check-label">
                                        <small><?php echo $value['verify_id']; ?></small>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <a class="dropdown-toggle float-right d-block d-lg-none" data-toggle="collapse" href="#td-collapse-<?php echo $value['verify_id']; ?>">
                                    <span class="sr-only">Collapse</span>
                                </a>
                                <div class="mb-2 text-wrap text-break">
                                    <?php if (isset($value['userRow']['user_name'])) {
                                        echo $value['userRow']['user_name'];
                                    } else {
                                        echo $lang->get('Unknown');
                                    } ?>
                                </div>
                                <div class="bg-manage-menu">
                                    <div class="d-flex flex-wrap">
                                        <a href="#verify_modal" data-toggle="modal" data-id="<?php echo $value['verify_id']; ?>" class="mr-2">
                                            <span class="fas fa-eye"></span>
                                            <?php echo $lang->get('Show'); ?>
                                        </a>
                                        <a href="javascript:void(0);" data-id="<?php echo $value['verify_id']; ?>" class="verify_delete text-danger">
                                            <span class="fas fa-trash-alt"></span>
                                            <?php echo $lang->get('Delete'); ?>
                                        </a>
                                    </div>
                                </div>
                                <dl class="row collapse mt-3 mb-0" id="td-collapse-<?php echo $value['verify_id']; ?>">
                                    <dt class="col-3">
                                        <small><?php echo $lang->get('Time'); ?></small>
                                    </dt>
                                    <dd class="col-9">
                                        <small data-toggle="tooltip" data-placement="bottom" title="<?php echo $value['verify_time_refresh_format']['date_time']; ?>"><?php echo $value['verify_time_refresh_format']['date_time_short']; ?></small>
                                    </dd>
                                    <dt class="col-3">
                                        <small><?php echo $lang->get('Status'); ?></small>
                                    </dt>
                                    <dd class="col-9">
                                        <?php $str_status = $value['verify_status'];
                                        include($cfg['pathInclude'] . 'status_process' . GK_EXT_TPL); ?>
                                    </dd>
                                    <dt class="col-3">
                                        <small><?php echo $lang->get('Type'); ?></small>
                                    </dt>
                                    <dd class="col-9">
                                        <small><?php echo $lang->get($value['verify_type']); ?></small>
                                    </dd>
                                </dl>
                            </td>
                            <td class="d-none d-lg-table-cell bg-td-md">
                                <small data-toggle="tooltip" data-placement="bottom" title="<?php echo $value['verify_time_refresh_format']['date_time']; ?>"><?php echo $value['verify_time_refresh_format']['date_time_short']; ?></small>
                            </td>
                            <td class="d-none d-lg-table-cell bg-td-md text-right">
                                <div>
                                    <?php $str_status = $value['verify_status'];
                                    include($cfg['pathInclude'] . 'status_process' . GK_EXT_TPL); ?>
                                </div>
                                <div>
                                    <small><?php echo $lang->get($value['verify_type']); ?></small>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="mb-3">
            <small class="form-text" id="msg_verify_ids"></small>
        </div>

        <div class="clearfix">
            <div class="float-left">
                <div class="input-group mb-3">
                    <select name="act" id="act" class="custom-select">
                        <option value=""><?php echo $lang->get('Bulk actions'); ?></option>
                        <?php foreach ($status as $key=>$value) { ?>
                            <option value="<?php echo $value; ?>">
                                <?php echo $lang->get($value); ?>
                            </option>
                        <?php } ?>
                        <option value="delete"><?php echo $lang->get('Delete'); ?></option>
                    </select>
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <?php echo $lang->get('Apply'); ?>
                        </button>
                    </span>
                </div>
                <small id="msg_act" class="form-text"></small>
            </div>
            <div class="float-right">
                <?php include($cfg['pathInclude'] . 'pagination' . GK_EXT_TPL); ?>
            </div>
        </div>
    </form>

    <div class="modal fade" id="verify_modal">
        <div class="modal-dialog">
            <div class="modal-content">

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

    var opts_validate_list = {
        rules: {
            verify_ids: {
                checkbox: '1'
            },
            act: {
                require: true
            }
        },
        attr_names: {
            verify_ids: '<?php echo $lang->get('Verify'); ?>',
            act: '<?php echo $lang->get('Action'); ?>'
        },
        type_msg: {
            require: '<?php echo $lang->get('Choose at least one {:attr}'); ?>',
            checkbox: '<?php echo $lang->get('Choose at least one {:attr}'); ?>'
        },
        selector_types: {
            verify_ids: 'validate'
        }
    };

    var opts_submit_list = {
        modal: {
            btn_text: {
                close: '<?php echo $lang->get('Close'); ?>',
                ok: '<?php echo $lang->get('OK'); ?>'
            }
        },
        msg_text: {
            submitting: '<?php echo $lang->get('Submitting'); ?>'
        }
    };

    $(document).ready(function(){
        $('#verify_modal').on('shown.bs.modal',function(event){
            var _obj_button = $(event.relatedTarget);
            var _verify_id      = _obj_button.data('id');
            $('#verify_modal .modal-content').load('<?php echo $route_console; ?>verify/show/id/' + _verify_id + '/view/modal/');
    	}).on('hidden.bs.modal', function(){
        	$('#verify_modal .modal-content').empty();
    	});

        var obj_dialog          = $.baigoDialog(opts_dialog);
        var obj_validate_list  = $('#verify_list').baigoValidate(opts_validate_list);
        var obj_submit_list     = $('#verify_list').baigoSubmit(opts_submit_list);

        $('#verify_list').submit(function(){
            var _act = $('#act').val();
            if (obj_validate_list.verify()) {
                switch (_act) {
                    case 'delete':
                        obj_dialog.confirm('<?php echo $lang->get('Are you sure to delete?'); ?>', function(result){
                            if (result) {
                                obj_submit_list.formSubmit('<?php echo $route_console; ?>verify/delete/');
                            }
                        });
                    break;

                    default:
                        obj_submit_list.formSubmit('<?php echo $route_console; ?>verify/status/');
                    break;
                }
            }
        });

        $('.verify_delete').click(function(){
            var _verify_id = $(this).data('id');
            $('.verify_id').prop('checked', false);
            $('#verify_id_' + _verify_id).prop('checked', true);
            $('#act').val('delete');
            $('#verify_list').submit();
        });

        $('#verify_list').baigoCheckall();
    });
    </script>
<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);