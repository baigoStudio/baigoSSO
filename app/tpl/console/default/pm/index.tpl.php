<?php $cfg = array(
    'title'             => $lang->get('Private message', 'console.common'),
    'menu_active'       => 'pm',
    'sub_active'        => 'index',
    'baigoValidate'    => 'true',
    'baigoSubmit'       => 'true',
    'baigoCheckall'     => 'true',
    'baigoQuery'        => 'true',
    'baigoDialog'       => 'true',
    'tooltip'           => 'true',
    'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

    <div class="d-flex justify-content-between">
        <nav class="nav mb-3">
            <a href="<?php echo $route_console; ?>pm/bulk/" class="nav-link">
                <span class="fas fa-mail-bulk"></span>
                <?php echo $lang->get('Bulk'); ?>
            </a>
        </nav>
        <form name="pm_search" id="pm_search" class="d-none d-lg-inline-block" action="<?php echo $route_console; ?>pm/index/">
            <div class="input-group mb-3">
                <input type="text" name="key" value="<?php echo $search['key']; ?>" placeholder="<?php echo $lang->get('Keyword'); ?>" class="form-control">
                <span class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">
                        <span class="fas fa-search"></span>
                    </button>
                </span>
                <span class="input-group-append">
                    <button class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" type="button" data-toggle="collapse" data-target="#bg-search-more">
                        <span class="sr-only">Dropdown</span>
                    </button>
                </span>
            </div>
            <div class="collapse" id="bg-search-more">
                <div class="input-group mb-3">
                    <select name="status" class="custom-select">
                        <option value=""><?php echo $lang->get('All status'); ?></option>
                        <?php foreach ($status as $key=>$value) { ?>
                            <option <?php if ($search['status'] == $value) { ?>selected<?php } ?> value="<?php echo $value; ?>">
                                <?php echo $lang->get($value); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <select name="type" class="custom-select">
                        <option value=""><?php echo $lang->get('All type'); ?></option>
                        <?php foreach ($type as $key=>$value) { ?>
                            <option <?php if ($search['type'] == $value) { ?>selected<?php } ?> value="<?php echo $value; ?>">
                                <?php echo $lang->get($value); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <?php if (!empty($search['key']) || !empty($search['status']) || !empty($search['type'])) { ?>
        <div class="mb-3 text-right">
            <?php if (!empty($search['key'])) { ?>
                <span class="badge badge-info">
                    <?php echo $lang->get('Keyword'); ?>:
                    <?php echo $search['key']; ?>
                </span>
            <?php }

            if (!empty($search['status'])) { ?>
                <span class="badge badge-info">
                    <?php echo $lang->get('Status'); ?>:
                    <?php echo $lang->get($search['status']); ?>
                </span>
            <?php }

            if (!empty($search['type'])) { ?>
                <span class="badge badge-info">
                    <?php echo $lang->get('Type'); ?>:
                    <?php echo $lang->get($search['type']); ?>
                </span>
            <?php } ?>

            <a href="<?php echo $route_console; ?>pm/index/" class="badge badge-danger badge-pill">
                <span class="fas fa-times-circle"></span>
                <?php echo $lang->get('Reset'); ?>
            </a>
        </div>
    <?php } ?>

    <form name="pm_list" id="pm_list" action="<?php echo $route_console; ?>pm/status/">
        <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">

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
                            <?php echo $lang->get('Private message'); ?>
                        </th>
                        <th class="d-none d-lg-table-cell bg-td-md">

                        </th>
                        <th class="d-none d-lg-table-cell bg-td-md text-right">
                            <small>
                                <?php echo $lang->get('Status'); ?>
                                /
                                <?php echo $lang->get('Time'); ?>
                            </small>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pmRows as $key=>$value) { ?>
                        <tr class="bg-manage-tr">
                            <td class="text-nowrap bg-td-xs">
                                <div class="form-check">
                                    <input type="checkbox" name="pm_ids[]" value="<?php echo $value['pm_id']; ?>" id="pm_id_<?php echo $value['pm_id']; ?>" data-parent="chk_all" data-validate="pm_ids" class="form-check-input pm_id">
                                    <label for="pm_id_<?php echo $value['pm_id']; ?>" class="form-check-label">
                                        <small><?php echo $value['pm_id']; ?></small>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <a class="dropdown-toggle float-right d-block d-lg-none" data-toggle="collapse" href="#td-collapse-<?php echo $value['pm_id']; ?>">
                                    <span class="sr-only">Collapse</span>
                                </a>
                                <div class="mb-2 text-wrap text-break">
                                    <?php echo $value['pm_title']; ?>
                                </div>
                                <div class="bg-manage-menu">
                                    <div class="d-flex flex-wrap">
                                        <a href="#pm_modal" data-toggle="modal" data-id="<?php echo $value['pm_id']; ?>" class="mr-2">
                                            <span class="fas fa-eye"></span>
                                            <?php echo $lang->get('Show'); ?>
                                        </a>
                                        <a href="javascript:void(0);" data-id="<?php echo $value['pm_id']; ?>" class="pm_delete text-danger">
                                            <span class="fas fa-trash-alt"></span>
                                            <?php echo $lang->get('Delete'); ?>
                                        </a>
                                    </div>
                                </div>
                                <dl class="row collapse mt-3 mb-0" id="td-collapse-<?php echo $value['pm_id']; ?>">
                                    <dt class="col-3">
                                        <small><?php echo $lang->get('Sender'); ?></small>
                                    </dt>
                                    <dd class="col-9">
                                        <small>
                                            <a href="<?php echo $route_console; ?>pm/index/from/<?php echo $value['pm_from']; ?>/">
                                                <?php if ($value['pm_from'] == -1) {
                                                    echo $lang->get('System');
                                                } else if (isset($value['fromUser']['user_name'])) {
                                                    echo $value['fromUser']['user_name'];
                                                } else {
                                                    echo $lang->get('Unknown');
                                                } ?>
                                            </a>
                                        </small>
                                    </dd>
                                    <dt class="col-3">
                                        <small><?php echo $lang->get('Recipient'); ?></small>
                                    </dt>
                                    <dd class="col-9">
                                        <small>
                                            <a href="<?php echo $route_console; ?>pm/index/to/<?php echo $value['pm_to']; ?>/">
                                                <?php if (isset($value['toUser']['user_name'])) {
                                                    echo $value['toUser']['user_name'];
                                                } else {
                                                    echo $lang->get('Unknown');
                                                } ?>
                                            </a>
                                        </small>
                                    </dd>
                                    <dt class="col-3">
                                        <small><?php echo $lang->get('Status'); ?></small>
                                    </dt>
                                    <dd class="col-9">
                                        <?php $str_status = $value['pm_status'];
                                        include($cfg['pathInclude'] . 'status_process' . GK_EXT_TPL); ?>
                                    </dd>
                                    <dt class="col-3">
                                        <small><?php echo $lang->get('Time'); ?></small>
                                    </dt>
                                    <dd class="col-9">
                                        <small data-toggle="tooltip" data-placement="bottom" title="<?php echo $value['pm_time_format']['date_time']; ?>"><?php echo $value['pm_time_format']['date_time_short']; ?></small>
                                    </dd>
                                </dl>
                            </td>
                            <td class="d-none d-lg-table-cell bg-td-md">
                                <div>
                                    <small>
                                        <?php echo $lang->get('Sender'); ?>:
                                        <a href="<?php echo $route_console; ?>pm/index/from/<?php echo $value['pm_from']; ?>/">
                                            <?php if ($value['pm_from'] == -1) {
                                                echo $lang->get('System');
                                            } else if (isset($value['fromUser']['user_name'])) {
                                                echo $value['fromUser']['user_name'];
                                            } else {
                                                echo $lang->get('Unknown');
                                            } ?>
                                        </a>
                                    </small>
                                </div>

                                <div>
                                    <small>
                                        <?php echo $lang->get('Recipient'); ?>:
                                        <a href="<?php echo $route_console; ?>pm/index/to/<?php echo $value['pm_to']; ?>/">
                                            <?php if (isset($value['toUser']['user_name'])) {
                                                echo $value['toUser']['user_name'];
                                            } else {
                                                echo $lang->get('Unknown');
                                            } ?>
                                        </a>
                                    </small>
                                </div>
                            </td>
                            <td class="d-none d-lg-table-cell bg-td-md text-right">
                                <div>
                                    <?php $str_status = $value['pm_status'];
                                    include($cfg['pathInclude'] . 'status_process' . GK_EXT_TPL); ?>
                                </div>
                                <div>
                                    <small data-toggle="tooltip" data-placement="bottom" title="<?php echo $value['pm_time_format']['date_time']; ?>"><?php echo $value['pm_time_format']['date_time_short']; ?></small>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="mb-3">
            <small class="form-text" id="msg_pm_ids"></small>
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

    <div class="modal fade" id="pm_modal">
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
            pm_ids: {
                checkbox: '1'
            },
            act: {
                require: true
            }
        },
        attr_names: {
            pm_ids: '<?php echo $lang->get('Pm'); ?>',
            act: '<?php echo $lang->get('Action'); ?>'
        },
        type_msg: {
            require: '<?php echo $lang->get('Choose at least one {:attr}'); ?>',
            checkbox: '<?php echo $lang->get('Choose at least one {:attr}'); ?>'
        },
        selector_types: {
            pm_ids: 'validate'
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
        $('#pm_modal').on('shown.bs.modal',function(event){
            var _obj_button = $(event.relatedTarget);
            var _pm_id      = _obj_button.data('id');
            $('#pm_modal .modal-content').load('<?php echo $route_console; ?>pm/show/id/' + _pm_id + '/view/modal/');
    	}).on('hidden.bs.modal', function(){
        	$('#pm_modal .modal-content').empty();
    	});

        var obj_dialog          = $.baigoDialog(opts_dialog);
        var obj_validate_list  = $('#pm_list').baigoValidate(opts_validate_list);
        var obj_submit_list     = $('#pm_list').baigoSubmit(opts_submit_list);

        $('#pm_list').submit(function(){
            var _act = $('#act').val();
            switch (_act) {
                case 'delete':
                    obj_dialog.confirm('<?php echo $lang->get('Are you sure to delete?'); ?>', function(result){
                        if (result) {
                            obj_submit_list.formSubmit('<?php echo $route_console; ?>pm/delete/');
                        }
                    });
                break;

                default:
                    obj_submit_list.formSubmit('<?php echo $route_console; ?>pm/status/');
                break;
            }
        });

        $('.pm_delete').click(function(){
            var _pm_id = $(this).data('id');
            $('.pm_id').prop('checked', false);
            $('#pm_id_' + _pm_id).prop('checked', true);
            $('#act').val('delete');
            $('#pm_list').submit();
        });

        $('#pm_list').baigoCheckall();

        var obj_query = $('#pm_search').baigoQuery();

        $('#pm_search').submit(function(){
            obj_query.formSubmit();
        });
    });
    </script>
<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);