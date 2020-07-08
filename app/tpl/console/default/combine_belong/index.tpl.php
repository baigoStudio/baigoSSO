<?php $cfg = array(
    'title'             => $lang->get('Sync combine', 'console.common') . ' &raquo; ' . $combineRow['combine_name'] . ' &raquo; ' . $lang->get('Choose Apps'),
    'menu_active'       => 'app',
    'sub_active'        => 'combine',
    'baigoValidate'     => 'true',
    'baigoSubmit'       => 'true',
    'baigoCheckall'     => 'true',
    'baigoQuery'        => 'true',
    'baigoDialog'       => 'true',
    'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

    <div class="d-flex justify-content-between">
        <nav class="nav mb-3">
            <a href="<?php echo $route_console; ?>combine/" class="nav-link">
                <span class="fas fa-chevron-left"></span>
                <?php echo $lang->get('Back'); ?>
            </a>
        </nav>

        <form name="app_search" id="app_search" class="d-none d-lg-inline-block" action="<?php echo $route_console; ?>combine-belong/index/id/<?php echo $combineRow['combine_id']; ?>/">
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
                </div>
            </div>
        </form>
    </div>

    <?php if (!empty($search['key']) || !empty($search['status'])) { ?>
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
            <?php } ?>

            <a href="<?php echo $route_console; ?>combine-belong/index/id/<?php echo $search['id']; ?>/" class="badge badge-danger badge-pill">
                <span class="fas fa-times-circle"></span>
                <?php echo $lang->get('Reset'); ?>
            </a>
        </div>
    <?php } ?>

    <div class="card-group">
        <div class="card">
            <form name="app_list_belong" id="app_list_belong" action="<?php echo $route_console; ?>combine-belong/remove/">
                <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">
                <input type="hidden" name="combine_id" value="<?php echo $combineRow['combine_id']; ?>">

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-nowrap bg-td-xs">
                                    <div class="form-check">
                                        <input type="checkbox" name="chk_all_belong" id="chk_all_belong" data-parent="first" class="form-check-input">
                                        <label for="chk_all_belong" class="form-check-label">
                                            <small><?php echo $lang->get('ID'); ?></small>
                                        </label>
                                    </div>
                                </th>
                                <th>
                                    <?php echo $lang->get('Chosen Apps'); ?>
                                </th>
                                <th class="d-none d-lg-table-cell bg-td-md text-right">
                                    <small>
                                        <?php echo $lang->get('Status'); ?>
                                        /
                                        <?php echo $lang->get('Note'); ?>
                                    </small>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appRowsBelong as $key=>$value) { ?>
                                <tr class="bg-manage-tr">
                                    <td class="text-nowrap bg-td-xs">
                                        <div class="form-check">
                                            <input type="checkbox" name="app_ids_belong[]" value="<?php echo $value['app_id']; ?>" id="app_id_belong_<?php echo $value['app_id']; ?>" data-parent="chk_all_belong" data-validate="app_ids_belong" class="form-check-input app_id_belong">
                                            <label for="app_id_belong_<?php echo $value['app_id']; ?>" class="form-check-label">
                                                <small><?php echo $value['app_id']; ?></small>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <a class="dropdown-toggle float-right d-block d-lg-none" data-toggle="collapse" href="#belong-collapse-<?php echo $value['app_id']; ?>">
                                            <span class="sr-only">Collapse</span>
                                        </a>
                                        <div class="mb-2 text-wrap text-break">
                                            <?php echo $value['app_name']; ?>
                                        </div>
                                        <div class="bg-manage-menu">
                                            <div class="d-flex flex-wrap">
                                                <a href="<?php echo $route_console; ?>app/show/id/<?php echo $value['app_id']; ?>/" class="mr-2">
                                                    <span class="fas fa-eye"></span>
                                                    <?php echo $lang->get('Show'); ?>
                                                </a>
                                                <a href="javascript:void(0);" data-id="<?php echo $value['app_id']; ?>" class="app_remove text-danger">
                                                    <span class="fas fa-trash-alt"></span>
                                                    <?php echo $lang->get('Remove'); ?>
                                                </a>
                                            </div>
                                        </div>
                                        <dl class="row collapse mt-3 mb-0" id="belong-collapse-<?php echo $value['app_id']; ?>">
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
                                        </dl>
                                    </td>
                                    <td class="d-none d-lg-table-cell bg-td-md text-right">
                                        <div>
                                            <?php $str_status = $value['app_status'];
                                            include($cfg['pathInclude'] . 'status_process' . GK_EXT_TPL); ?>
                                        </div>
                                        <div>
                                            <small><?php echo $value['app_note']; ?></small>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <small class="form-text" id="msg_app_ids_belong"></small>
                    </div>

                    <div class="clearfix">
                        <div class="float-left">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-danger">
                                    <?php echo $lang->get('Remove'); ?>
                                </button>
                            </div>
                        </div>
                        <div class="float-right">
                            <?php $pageRow = $pageRowBelong;
                            $pageParam = $pageParamBelong;
                            include($cfg['pathInclude'] . 'pagination' . GK_EXT_TPL); ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card">
            <form name="app_list" id="app_list" action="<?php echo $route_console; ?>combine-belong/submit/">
                <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">
                <input type="hidden" name="combine_id" value="<?php echo $combineRow['combine_id']; ?>">

                <div class="table-responsive">
                    <table class="table">
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
                                    <?php echo $lang->get('Waiting for choose'); ?>
                                </th>
                                <th class="d-none d-lg-table-cell bg-td-md text-right">
                                    <small>
                                        <?php echo $lang->get('Status'); ?>
                                        /
                                        <?php echo $lang->get('Note'); ?>
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
                                            <?php echo $value['app_name']; ?>
                                        </div>
                                        <div class="bg-manage-menu">
                                            <div class="d-flex flex-wrap">
                                                <a href="<?php echo $route_console; ?>app/show/id/<?php echo $value['app_id']; ?>/" class="mr-2">
                                                    <span class="fas fa-eye"></span>
                                                    <?php echo $lang->get('Show'); ?>
                                                </a>
                                                <a href="javascript:void(0);" data-id="<?php echo $value['app_id']; ?>" class="app_choose">
                                                    <span class="fas fa-check"></span>
                                                    <?php echo $lang->get('Choose'); ?>
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
                                        </dl>
                                    </td>
                                    <td class="d-none d-lg-table-cell bg-td-md text-right">
                                        <div>
                                            <?php $str_status = $value['app_status'];
                                            include($cfg['pathInclude'] . 'status_process' . GK_EXT_TPL); ?>
                                        </div>
                                        <div>
                                            <small><?php echo $value['app_note']; ?></small>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <small class="form-text" id="msg_app_ids"></small>
                    </div>

                    <div class="clearfix">
                        <div class="float-left">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo $lang->get('Choose'); ?>
                                </button>
                            </div>
                        </div>
                        <div class="float-right">
                            <?php $pageRow = $pageRowApp;
                            $pageParam = 'page';
                            include($cfg['pathInclude'] . 'pagination' . GK_EXT_TPL); ?>
                        </div>
                    </div>
                </div>
            </form>
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

    var opts_validate_belong = {
        rules: {
            app_ids_belong: {
                checkbox: '1'
            }
        },
        attr_names: {
            app_ids_belong: '<?php echo $lang->get('Apps'); ?>'
        },
        type_msg: {
            checkbox: '<?php echo $lang->get('Choose at least one {:attr}'); ?>'
        },
        selector_types: {
            app_ids_belong: 'validate'
        }
    };

    var opts_submit_belong = {
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

    var opts_validate_list = {
        rules: {
            app_ids: {
                checkbox: '1'
            }
        },
        attr_names: {
            app_ids: '<?php echo $lang->get('Apps'); ?>'
        },
        type_msg: {
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
        var obj_dialog            = $.baigoDialog(opts_dialog);
        var obj_validate_belong   = $('#app_list_belong').baigoValidate(opts_validate_belong);
        var obj_submit_belong     = $('#app_list_belong').baigoSubmit(opts_submit_belong);

        //console.log(obj_submit_belong);

        $('#app_list_belong').submit(function(){
            if (obj_validate_belong.verify()) {
                obj_dialog.confirm('<?php echo $lang->get('Are you sure to remove?'); ?>', function(result){
                    if (result) {
                        obj_submit_belong.formSubmit();
                    }
                });
            }
        });

        $('#app_list_belong').baigoCheckall();

        var obj_validate_list  = $('#app_list').baigoValidate(opts_validate_list);
        var obj_submit_list     = $('#app_list').baigoSubmit(opts_submit_list);

        //console.log(obj_submit_list);

        $('#app_list').submit(function(){
            if (obj_validate_list.verify()) {
                obj_dialog.confirm('<?php echo $lang->get('Are you sure to choose?'); ?>', function(result){
                    if (result) {
                        obj_submit_list.formSubmit();
                    }
                });
            }
        });

        $('.app_remove').click(function(){
            var _app_id = $(this).data('id');
            $('.app_id_belong').prop('checked', false);
            $('#app_id_belong_' + _app_id).prop('checked', true);
            $('#app_list_belong').submit();
        });


        $('.app_choose').click(function(){
            var _app_id = $(this).data('id');
            $('.app_id').prop('checked', false);
            $('#app_id_' + _app_id).prop('checked', true);
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