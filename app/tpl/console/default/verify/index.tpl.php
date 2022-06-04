<?php $cfg = array(
  'title'             => $lang->get('Validation token', 'console.common'),
  'menu_active'       => 'verify',
  'sub_active'        => 'index',
  'baigoValidate'    => 'true',
  'baigoSubmit'       => 'true',
  'baigoCheckall'     => 'true',
  'baigoDialog'       => 'true',
  'tooltip'           => 'true',
);

include($tpl_include . 'console_head' . GK_EXT_TPL); ?>

  <form name="verify_list" id="verify_list" action="<?php echo $hrefRow['status']; ?>">
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
                    <a href="#modal_nm" data-toggle="modal" data-href="<?php echo $hrefRow['show'], $value['verify_id']; ?>" class="mr-2">
                      <span class="bg-icon"><?php include($tpl_icon . 'eye' . BG_EXT_SVG); ?></span>
                      <?php echo $lang->get('Show'); ?>
                    </a>
                    <a href="javascript:void(0);" data-id="<?php echo $value['verify_id']; ?>" class="verify_delete text-danger">
                      <span class="bg-icon"><?php include($tpl_icon . 'trash-alt' . BG_EXT_SVG); ?></span>
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
                    include($tpl_include . 'status_process' . GK_EXT_TPL); ?>
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
                  include($tpl_include . 'status_process' . GK_EXT_TPL); ?>
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
        <?php include($tpl_include . 'pagination' . GK_EXT_TPL); ?>
      </div>
    </div>
  </form>

<?php include($tpl_include . 'console_foot' . GK_EXT_TPL);

  include($tpl_include . 'modal_nm' . GK_EXT_TPL); ?>

  <script type="text/javascript">
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

  $(document).ready(function(){
    var obj_dialog          = $.baigoDialog(opts_dialog);
    var obj_validate_list   = $('#verify_list').baigoValidate(opts_validate_list);
    var obj_submit_list     = $('#verify_list').baigoSubmit(opts_submit);

    $('#verify_list').submit(function(){
      var _act = $('#act').val();
      if (obj_validate_list.verify()) {
        switch (_act) {
          case 'delete':
            obj_dialog.confirm('<?php echo $lang->get('Are you sure to delete?'); ?>', function(result){
              if (result) {
                obj_submit_list.formSubmit('<?php echo $hrefRow['delete']; ?>');
              }
            });
          break;

          default:
            obj_submit_list.formSubmit('<?php echo $hrefRow['status']; ?>');
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

<?php include($tpl_include . 'html_foot' . GK_EXT_TPL);
