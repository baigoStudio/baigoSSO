<?php if ($pluginRow['plugin_status'] == 'enable') {
  $title_sub    = $lang->get('Edit');
  $str_sub      = 'index';
} else {
  $title_sub    = $lang->get('Install');
  $str_sub      = 'form';
}

$cfg = array(
  'title'             => $lang->get('Plugin management', 'console.common') . ' &raquo; ' . $title_sub,
  'menu_active'       => 'plugin',
  'sub_active'        => $str_sub,
  'baigoValidate'     => 'true',
  'baigoSubmit'       => 'true',
  'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

  <nav class="nav mb-3">
    <a href="<?php echo $route_console; ?>plugin/" class="nav-link">
      <span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'chevron-left' . BG_EXT_SVG); ?></span>
      <?php echo $lang->get('Back'); ?>
    </a>
  </nav>

  <form name="plugin_form" id="plugin_form" action="<?php echo $route_console; ?>plugin/submit/">
    <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">

    <div class="row">
      <div class="col-xl-9">
        <div class="card mb-3">
          <?php include($cfg['pathInclude'] . 'plugin_menu' . GK_EXT_TPL); ?>
          <div class="card-body">
            <?php include($cfg['pathInclude'] . 'plugin_detail' . GK_EXT_TPL); ?>

            <div class="form-group">
              <label><?php echo $lang->get('Directory'); ?></label>
              <input type="text" name="plugin_dir" id="plugin_dir" readonly value="<?php echo $pluginRow['plugin_dir']; ?>" class="form-control">
              <small class="form-text" id="msg_plugin_dir"></small>
            </div>

            <div class="form-group">
              <label><?php echo $lang->get('Note'); ?></label>
              <input type="text" name="plugin_note" id="plugin_note" value="<?php echo $pluginRow['plugin_note']; ?>" class="form-control">
              <small class="form-text" id="msg_plugin_note"></small>
            </div>

            <div class="bg-validate-box"></div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">
              <?php echo $lang->get('Save'); ?>
            </button>
          </div>
        </div>
      </div>

      <?php include($cfg['pathInclude'] . 'plugin_side' . GK_EXT_TPL); ?>
    </div>
  </form>

<?php include($cfg['pathInclude'] . 'console_foot' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  var opts_validate_form = {
    rules: {
      plugin_dir: {
        length: '1,300'
      },
      plugin_note: {
        max: 300
      }
    },
    attr_names: {
      plugin_dir: '<?php echo $lang->get('Directory'); ?>',
      plugin_note: '<?php echo $lang->get('Note'); ?>'
    },
    type_msg: {
      require: '<?php echo $lang->get('{:attr} require'); ?>',
      max: '<?php echo $lang->get('Max size of {:attr} must be {:rule}'); ?>',
      length: '<?php echo $lang->get('Size of {:attr} must be {:rule}'); ?>'
    },
    msg: {
      loading: '<?php echo $lang->get('Loading'); ?>'
    },
    box: {
      msg: '<?php echo $lang->get('Input error'); ?>'
    }
  };

  $(document).ready(function(){
    var obj_validate_form  = $('#plugin_form').baigoValidate(opts_validate_form);
    var obj_submit_form    = $('#plugin_form').baigoSubmit(opts_submit);

    $('#plugin_form').submit(function(){
      if (obj_validate_form.verify()) {
        obj_submit_form.formSubmit();
      }
    });
  });
  </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);
