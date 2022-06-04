<?php $cfg = array(
  'title'             => $lang->get('System settings', 'console.common') . ' &raquo; ' . $lang->get('Database settings', 'console.common'),
  'menu_active'       => 'opt',
  'sub_active'        => 'dbconfig',
  'baigoValidate'     => 'true',
  'baigoSubmit'       => 'true',
  'baigoDialog'       => 'true',
);

include($tpl_include . 'console_head' . GK_EXT_TPL); ?>

  <div class="row">
    <div class="col-md-3">
      <h5><?php echo $lang->get('Upgrade data'); ?></h5>
      <div class="alert alert-warning">
        <span class="bg-icon"><?php include($tpl_icon . 'exclamation-triangle' . BG_EXT_SVG); ?></span>
        <?php echo $lang->get('Warning! Please backup the data before upgrading.'); ?>
      </div>
      <a href="#upgrade_modal" class="btn btn-primary" data-toggle="modal">
        <span class="bg-icon"><?php include($tpl_icon . 'database' . BG_EXT_SVG); ?></span>
        <?php echo $lang->get('Upgrade'); ?>
      </a>
    </div>
    <div class="col-md-9">
      <form name="opt_form" id="opt_form" action="<?php echo $hrefRow['dbconfig-submit']; ?>">
        <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">

        <div class="card">
          <div class="card-body">
            <?php include($tpl_include . 'dbconfig' . GK_EXT_TPL); ?>

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
          <button type="button" class="close" data-dismiss="modal">
            &times;
          </button>
        </div>
        <div class="modal-body">
          <div class="alert alert-warning">
            <span class="bg-icon"><?php include($tpl_icon . 'exclamation-triangle' . BG_EXT_SVG); ?></span>
            <?php echo $lang->get('Warning! Please backup the data before upgrading.'); ?>
          </div>
        </div>
        <div class="table-responsive">
          <?php foreach ($config_upgrade as $key=>$value) { ?>
            <table class="table mb-5">
              <thead>
                <tr>
                  <th class="border-top-0"><?php echo $lang->get($value['title']); ?></th>
                  <th class="border-top-0 text-right"><?php echo $lang->get('Status'); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($value['lists'] as $key_data=>$value_data) { ?>
                  <tr>
                    <td>
                      <?php echo $value_data; ?>
                    </td>
                    <td id="<?php echo $key; ?>_<?php echo $value_data; ?>" class="text-right text-nowrap">
                      <div class="text-info">
                        <span class="bg-icon">
                          <?php include($tpl_icon . 'clock' . BG_EXT_SVG); ?>
                        </span>
                        <small>
                          <?php echo $lang->get('Waiting'); ?>
                        </small>
                      </div>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          <?php } ?>
        </div>
        <div class="modal-footer">
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

<?php include($tpl_include . 'console_foot' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  function update_do() {
    <?php foreach ($config_upgrade as $key=>$value) {
      foreach ($value['lists'] as $key_data=>$value_data) { ?>
        $.ajax({
          url: '<?php echo $hrefRow['data-upgrade']; ?>' + new Date().getTime() + '/' + Math.random() + '/', //url
          //async: false, //设置为同步
          type: 'post',
          dataType: 'json',
          data: {
            type: '<?php echo $key; ?>',
            model: '<?php echo $value_data; ?>',
            <?php echo $token['name']; ?>: '<?php echo $token['value']; ?>'
          },
          timeout: 30000,
          error: function (result) {
            $('#<?php echo $key; ?>_<?php echo $value_data; ?> div').attr('class', 'text-danger');
            $('#<?php echo $key; ?>_<?php echo $value_data; ?> .bg-icon').html('<?php include($tpl_icon . 'times-circle' . BG_EXT_SVG); ?>');
            $('#<?php echo $key; ?>_<?php echo $value_data; ?> small').text(result.statusText);
          },
          beforeSend: function(){
            $('#<?php echo $key; ?>_<?php echo $value_data; ?> div').attr('class', 'text-info');
            $('#<?php echo $key; ?>_<?php echo $value_data; ?> .bg-icon').html('<span class="spinner-grow spinner-grow-sm"></span>');
            $('#<?php echo $key; ?>_<?php echo $value_data; ?> small').text('<?php echo $lang->get('Submitting', 'console.common'); ?>');
          },
          success: function(_result){ //读取返回结果
            _rcode_status  = _result.rcode.substr(0, 1);

            switch (_rcode_status) {
              case 'y':
                _class  = 'text-success';
                _icon   = '<?php include($tpl_icon . 'check-circle' . BG_EXT_SVG); ?>';
              break;

              default:
                _class  = 'text-danger';
                _icon   = '<?php include($tpl_icon . 'times-circle' . BG_EXT_SVG); ?>';
              break;
            }

            $('#<?php echo $key; ?>_<?php echo $value_data; ?> div').attr('class', _class);
            $('#<?php echo $key; ?>_<?php echo $value_data; ?> .bg-icon').html(_icon);
            $('#<?php echo $key; ?>_<?php echo $value_data; ?> small').text(_result.msg);
          }
        });
      <?php }
    } ?>
  }

  $(document).ready(function(){
    $('#upgrade_confirm').click(function(){
      update_do();
    });

    var obj_validate_form   = $('#opt_form').baigoValidate(opts_validate_form);
    var obj_submit_form     = $('#opt_form').baigoSubmit(opts_submit);

    $('#opt_form').submit(function(){
      if (obj_validate_form.verify()) {
        obj_submit_form.formSubmit();
      }
    });
  });
  </script>

<?php include($tpl_include . 'html_foot' . GK_EXT_TPL);
