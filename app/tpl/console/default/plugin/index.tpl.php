<?php $cfg = array(
  'title'             => $lang->get('Plugin management', 'console.common'),
  'menu_active'       => 'plugin',
  'sub_active'        => 'index',
  'baigoValidate'    => 'true',
  'baigoSubmit'       => 'true',
  'baigoCheckall'     => 'true',
  'baigoDialog'       => 'true',
);

include($tpl_include . 'console_head' . GK_EXT_TPL); ?>

  <nav class="nav mb-3">
    <a href="<?php echo $hrefRow['index']; ?>" class="nav-link">
      <span class="bg-icon"><?php include($tpl_icon . 'list' . BG_EXT_SVG); ?></span>
      <?php echo $lang->get('All'); ?>
    </a>
    <a href="<?php echo $hrefRow['index']; ?>installable" class="nav-link">
      <span class="bg-icon"><?php include($tpl_icon . 'hammer' . BG_EXT_SVG); ?></span>
      <?php echo $lang->get('Installable'); ?>
    </a>
  </nav>

  <form name="plugin_list" id="plugin_list" action="<?php echo $hrefRow['uninstall']; ?>">
    <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">

    <div class="table-responsive">
      <table class="table table-striped border bg-white">
        <thead>
          <tr>
            <th class="text-nowrap bg-td-xs">
              <div class="form-check">
                <input type="checkbox" name="chk_all" id="chk_all" data-parent="first" class="form-check-input position-static">
              </div>
            </th>
            <th>
              <?php echo $lang->get('Plugin'); ?>
            </th>
            <th class="d-none d-lg-table-cell bg-td-md">
              <small><?php echo $lang->get('Directory'); ?></small>
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
          <?php foreach ($pluginRows as $key=>$value) { ?>
            <tr class="bg-manage-tr">
              <td class="text-nowrap bg-td-xs">
                <?php if (isset($value['name'])) { ?>
                  <div class="form-check">
                    <input type="checkbox" name="plugin_dirs[]" value="<?php echo $value['name']; ?>" id="plugin_id_<?php echo $value['name']; ?>" data-parent="chk_all" data-validate="plugin_dirs" class="form-check-input position-static plugin_id">
                  </div>
                <?php } ?>
              </td>
              <td>
                <a class="dropdown-toggle float-right d-block d-lg-none" data-toggle="collapse" href="#td-collapse-<?php echo $value['plugin_config']['name']; ?>">
                  <span class="sr-only">Dropdown</span>
                </a>
                <div class="mb-2 text-wrap text-break">
                  <?php echo $value['plugin_config']['name']; ?>
                </div>
                <div class="bg-manage-menu">
                  <div class="d-flex flex-wrap">
                    <?php if ($value['plugin_status'] == 'enable') { ?>
                      <a href="<?php echo $hrefRow['show'], $value['name']; ?>" class="mr-2">
                        <span class="bg-icon"><?php include($tpl_icon . 'eye' . BG_EXT_SVG); ?></span>
                        <?php echo $lang->get('Show'); ?>
                      </a>
                      <a href="<?php echo $hrefRow['edit'], $value['name']; ?>" class="mr-2">
                        <span class="bg-icon"><?php include($tpl_icon . 'edit' . BG_EXT_SVG); ?></span>
                        <?php echo $lang->get('Edit'); ?>
                      </a>
                      <?php if ($value['plugin_opts']) { ?>
                        <a href="<?php echo $hrefRow['opts'], $value['name']; ?>" class="mr-2">
                          <span class="bg-icon"><?php include($tpl_icon . 'wrench' . BG_EXT_SVG); ?></span>
                          <?php echo $lang->get('Option'); ?>
                        </a>
                      <?php } ?>
                      <a href="javascript:void(0);" data-id="<?php echo $value['name']; ?>" class="plugin_uninstall text-danger">
                        <span class="bg-icon"><?php include($tpl_icon . 'times-circle' . BG_EXT_SVG); ?></span>
                        <?php echo $lang->get('Uninstall'); ?>
                      </a>
                    <?php } else if ($value['plugin_status'] == 'wait') { ?>
                      <a href="<?php echo $hrefRow['show'], $value['name']; ?>" class="mr-2">
                        <span class="bg-icon"><?php include($tpl_icon . 'eye' . BG_EXT_SVG); ?></span>
                        <?php echo $lang->get('Show'); ?>
                      </a>
                      <a href="<?php echo $hrefRow['edit'], $value['name']; ?>" class="mr-2">
                        <span class="bg-icon"><?php include($tpl_icon . 'hammer' . BG_EXT_SVG); ?></span>
                        <?php echo $lang->get('Install'); ?>
                      </a>
                    <?php } ?>
                  </div>
                </div>
                <dl class="row collapse mt-3 mb-0" id="td-collapse-<?php echo $value['plugin_config']['name']; ?>">
                  <dt class="col-3">
                    <small><?php echo $lang->get('Directory'); ?></small>
                  </dt>
                  <dd class="col-9">
                    <?php echo $value['name']; ?>
                  </dd>
                  <dt class="col-3">
                    <small><?php echo $lang->get('Status'); ?></small>
                  </dt>
                  <dd class="col-9">
                    <?php $str_status = $value['plugin_status'];
                    include($tpl_include . 'status_process' . GK_EXT_TPL); ?>
                  </dd>
                  <dt class="col-3">
                    <small><?php echo $lang->get('Type'); ?></small>
                  </dt>
                  <dd class="col-9">
                    <small><?php echo $lang->get($value['plugin_note']); ?></small>
                  </dd>
                </dl>
              </td>
              <td class="d-none d-lg-table-cell bg-td-md">
                <?php echo $value['name']; ?>
              </td>
              <td class="d-none d-lg-table-cell bg-td-md text-right">
                <div class="mb-2">
                  <?php $str_status = $value['plugin_status'];
                  include($tpl_include . 'status_process' . GK_EXT_TPL); ?>
                </div>
                <div>
                  <small><?php echo $lang->get($value['plugin_note']); ?></small>
                </div>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <div class="mb-3">
      <small class="form-text" id="msg_plugin_dirs"></small>
    </div>

    <div>
      <button type="submit" class="btn btn-primary">
        <?php echo $lang->get('Uninstall'); ?>
      </button>
    </div>
  </form>

<?php include($tpl_include . 'console_foot' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  var opts_validate_list = {
    rules: {
      plugin_dirs: {
        checkbox: '1'
      }
    },
    attr_names: {
      plugin_dirs: '<?php echo $lang->get('Plugin'); ?>'
    },
    type_msg: {
      checkbox: '<?php echo $lang->get('Choose at least one {:attr}'); ?>'
    },
    selector_types: {
      plugin_dirs: 'validate'
    }
  };

  $(document).ready(function(){
    var obj_dialog          = $.baigoDialog(opts_dialog);
    var obj_validate_list   = $('#plugin_list').baigoValidate(opts_validate_list);
    var obj_submit_list     = $('#plugin_list').baigoSubmit(opts_submit);

    $('#plugin_list').submit(function(){
      if (obj_validate_list.verify()) {
        obj_dialog.confirm('<?php echo $lang->get('Are you sure to uninstall?'); ?>', function(result){
          if (result) {
            obj_submit_list.formSubmit();
          }
        });
      }
    });

    $('.plugin_uninstall').click(function(){
      var _plugin_id = $(this).data('id');
      $('.plugin_id').prop('checked', false);
      $('#plugin_id_' + _plugin_id).prop('checked', true);
      $('#act').val('uninstall');
      $('#plugin_list').submit();
    });

    $('#plugin_list').baigoCheckall();
  });
  </script>

<?php include($tpl_include . 'html_foot' . GK_EXT_TPL);
