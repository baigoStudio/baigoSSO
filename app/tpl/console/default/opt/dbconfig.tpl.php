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
        <span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'database' . BG_EXT_SVG); ?></span>
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
            &times;
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
  $(document).ready(function(){
    $('#upgrade_foot').on('click', '#upgrade_confirm', function(){
      $('#upgrade_modal #upgrade_content').load('<?php echo $route_console; ?>opt/data-upgrade/view/modal/');
    });

    $('#upgrade_modal').on('hidden.bs.modal', function(){
      $('#upgrade_modal #upgrade_content').empty();
    });

    var obj_validate_form   = $('#opt_form').baigoValidate(opts_validate_form);
    var obj_submit_form     = $('#opt_form').baigoSubmit(opts_submit);

    $('#opt_form').submit(function(){
        if (obj_validate_form.verify()) {
            obj_submit_form.formSubmit();
        }
    });

    var obj_dialog = $.baigoDialog(opts_dialog);
  });
  </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);
