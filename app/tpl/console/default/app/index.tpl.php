<?php $cfg = array(
    'title'             => $lang->get('App', 'console.common'),
    'menu_active'       => 'app',
    'sub_active'        => 'index',
    'baigoValidate'    => 'true',
    'baigoSubmit'       => 'true',
    'baigoCheckall'     => 'true',
    'baigoQuery'        => 'true',
    'baigoDialog'       => 'true',
    'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

    <div class="d-flex justify-content-between">
        <nav class="nav mb-3">
            <a href="<?php echo $route_console; ?>app/form/" class="nav-link">
                <span class="fas fa-plus"></span>
                <?php echo $lang->get('Add'); ?>
            </a>
        </nav>
        <form name="app_search" id="app_search" class="d-none d-lg-inline-block" action="<?php echo $route_console; ?>app/index/">
            <div class="input-group mb-3">
                <input type="text" name="key" value="<?php echo $search['key']; ?>" placeholder="<?php echo $lang->get('Key word'); ?>" class="form-control">
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
                    <select name="sync" class="custom-select">
                        <option value=""><?php echo $lang->get('All sync status'); ?></option>
                        <?php foreach ($sync as $key=>$value) { ?>
                            <option <?php if ($search['sync'] == $value) { ?>selected<?php } ?> value="<?php echo $value; ?>">
                                <?php echo $lang->get($value); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <?php if (!empty($search['key']) || !empty($search['status']) || !empty($search['sync'])) { ?>
        <div class="mb-3 text-right">
            <?php if (!empty($search['key'])) { ?>
                <span class="badge badge-info">
                    <?php echo $lang->get('Key word'); ?>:
                    <?php echo $search['key']; ?>
                </span>
            <?php }

            if (!empty($search['status'])) { ?>
                <span class="badge badge-info">
                    <?php echo $lang->get('Status'); ?>:
                    <?php echo $lang->get($search['status']); ?>
                </span>
            <?php }

            if (!empty($search['sync'])) { ?>
                <span class="badge badge-info">
                    <?php echo $lang->get('Sync status'); ?>:
                    <?php echo $lang->get($search['sync']); ?>
                </span>
            <?php } ?>

            <a href="<?php echo $route_console; ?>app/index/" class="badge badge-danger badge-pill">
                <span class="fas fa-times-circle"></span>
                <?php echo $lang->get('Reset'); ?>
            </a>
        </div>
    <?php } ?>

    <form name="app_list" id="app_list" action="<?php echo $route_console; ?>app/status/">
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
                            <?php echo $lang->get('App'); ?>
                        </th>
                        <th class="d-none d-lg-table-cell bg-td-md">
                            <small><?php echo $lang->get('Note'); ?></small>
                        </th>
                        <th class="d-none d-lg-table-cell bg-td-md text-right">
                            <small>
                                <?php echo $lang->get('Status'); ?>
                                /
                                <?php echo $lang->get('Sync status'); ?>
                            </small>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appRows as $key=>$value) { ?>
                        <tr class="bg-manage-tr">
                            <td class="text-nowrap bg-td-xs">
                                <div class="form-check">
                                    <input type="checkbox" name="app_ids[]" value="<?php echo $value['app_id']; ?>" id="app_id_<?php echo $value['app_id']; ?>" data-parent="chk_all" data-validate="app_ids" class="form-check-input app_id">
                                    <label for="app_id_<?php echo $value['app_id']; ?>" class="form-check-label">
                                        <small><?php echo $value['app_id']; ?></small>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <a class="dropdown-toggle float-right d-block d-lg-none" data-toggle="collapse" href="#td-collapse-<?php echo $value['app_id']; ?>">
                                    <span class="sr-only">Collapse</span>
                                </a>
                                <div class="mb-2 text-wrap text-break">
                                    <a href="<?php echo $route_console; ?>app/form/id/<?php echo $value['app_id']; ?>/">
                                        <?php echo $value['app_name']; ?>
                                    </a>
                                </div>
                                <div class="bg-manage-menu">
                                    <div class="d-flex flex-wrap">
                                        <a href="<?php echo $route_console; ?>app/show/id/<?php echo $value['app_id']; ?>/" class="mr-2">
                                            <span class="fas fa-eye"></span>
                                            <?php echo $lang->get('Show'); ?>
                                        </a>
                                        <a href="<?php echo $route_console; ?>app/form/id/<?php echo $value['app_id']; ?>/" class="mr-2">
                                            <span class="fas fa-edit"></span>
                                            <?php echo $lang->get('Edit'); ?>
                                        </a>
                                        <a href="<?php echo $route_console; ?>app/show/id/<?php echo $value['app_id']; ?>/" class="mr-2">
                                            <span class="fas fa-key"></span>
                                            <?php echo $lang->get('View key'); ?>
                                        </a>
                                        <a href="<?php echo $route_console; ?>app-belong/index/id/<?php echo $value['app_id']; ?>/" class="mr-2">
                                            <span class="fas fa-users"></span>
                                            <?php echo $lang->get('Authorize users'); ?>
                                        </a>
                                        <a href="javascript:void(0);" data-id="<?php echo $value['app_id']; ?>" class="app_delete text-danger mr-2">
                                            <span class="fas fa-trash-alt"></span>
                                            <?php echo $lang->get('Delete'); ?>
                                        </a>
                                        <a href="<?php echo $route_console; ?>app/show/id/<?php echo $value['app_id']; ?>/">
                                            <span class="fas fa-flag-checkered"></span>
                                            <?php echo $lang->get('Notification test'); ?>
                                        </a>
                                    </div>
                                </div>
                                <dl class="row collapse mt-3 mb-0" id="td-collapse-<?php echo $value['app_id']; ?>">
                                    <dt class="col-3">
                                        <small><?php echo $lang->get('Note'); ?></small>
                                    </dt>
                                    <dd class="col-9">
                                        <small><?php echo $value['app_note']; ?></small>
                                    </dd>
                                    <dt class="col-3">
                                        <small><?php echo $lang->get('Status'); ?></small>
                                    </dt>
                                    <dd class="col-9">
                                        <?php $str_status = $value['app_status'];
                                        include($cfg['pathInclude'] . 'status_process' . GK_EXT_TPL); ?>
                                    </dd>
                                    <dt class="col-3">
                                        <small><?php echo $lang->get('Sync status'); ?></small>
                                    </dt>
                                    <dd class="col-9">
                                        <?php $str_status = $value['app_sync'];
                                        include($cfg['pathInclude'] . 'status_process' . GK_EXT_TPL); ?>
                                    </dd>
                                </dl>
                            </td>
                            <td class="d-none d-lg-table-cell bg-td-md">
                                <small><?php echo $value['app_note']; ?></small>
                            </td>
                            <td class="d-none d-lg-table-cell bg-td-md text-right">
                                <div>
                                    <?php $str_status = $value['app_status'];
                                    include($cfg['pathInclude'] . 'status_process' . GK_EXT_TPL); ?>
                                </div>
                                <div>
                                    <?php $str_status = $value['app_sync'];
                                    include($cfg['pathInclude'] . 'status_process' . GK_EXT_TPL); ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="mb-3">
            <small class="form-text" id="msg_app_ids"></small>
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
            app_ids: {
                checkbox: '1'
            },
            act: {
                require: true
            }
        },
        attr_names: {
            app_ids: '<?php echo $lang->get('App'); ?>',
            act: '<?php echo $lang->get('Action'); ?>'
        },
        type_msg: {
            require: '<?php echo $lang->get('Choose at least one {:attr}'); ?>',
            checkbox: '<?php echo $lang->get('Choose at least one {:attr}'); ?>'
        },
        selector_types: {
            app_ids: 'validate'
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
        var obj_dialog          = $.baigoDialog(opts_dialog);
        var obj_validate_list  = $('#app_list').baigoValidate(opts_validate_list);
        var obj_submit_list     = $('#app_list').baigoSubmit(opts_submit_list);

        //console.log(obj_submit_list);

        $('#app_list').submit(function(){
            var _act = $('#act').val();
            if (obj_validate_list.verify()) {
                switch (_act) {
                    case 'delete':
                        obj_dialog.confirm('<?php echo $lang->get('Are you sure to delete?'); ?>', function(result){
                            if (result) {
                                obj_submit_list.formSubmit('<?php echo $route_console; ?>app/delete/');
                            }
                        });
                    break;

                    default:
                        obj_submit_list.formSubmit('<?php echo $route_console; ?>app/status/');
                    break;
                }
            }
        });

        $('.app_delete').click(function(){
            var _app_id = $(this).data('id');
            $('.app_id').prop('checked', false);
            $('#app_id_' + _app_id).prop('checked', true);
            $('#act').val('delete');
            $('#app_list').submit();
        });

        $('#app_list').baigoCheckall();

        var obj_query = $('#app_search').baigoQuery();

        $('#app_search').submit(function(){
            obj_query.formSubmit();
        });
    });
    </script>
<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);