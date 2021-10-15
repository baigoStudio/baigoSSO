<?php $cfg = array(
  'title'             => $lang->get('Shortcut', 'console.common'),
  'menu_active'       => 'shortcut',
  'baigoSubmit'       => 'true',
  'dad'               => 'true',
  'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

  <nav class="nav mb-3">
    <a href="<?php echo $route_console; ?>" class="nav-link">
      <span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'chevron-left' . BG_EXT_SVG); ?></span>
      <?php echo $lang->get('Back'); ?>
    </a>
  </nav>

  <form name="shortcut_setting_form" id="shortcut_setting_form" action="<?php echo $route_console; ?>index/submit/">
    <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">
    <div class="card-group">
      <div class="card">
        <div class="card-header">
          <?php echo $lang->get('Shortcut', 'console.common'); ?>
        </div>
        <div class="card-body">
          <div class="bg-drag" id="shortcut_list">
            <?php foreach ($adminLogged['admin_shortcut'] as $key_m=>$value_m) { ?>
              <div id="shortcut_list_<?php echo $key_m; ?>" data-key="<?php echo $key_m; ?>" class="alert alert-secondary alert-dismissible">
                <input type="hidden" name="admin_shortcut[<?php echo $key_m; ?>][ctrl]" id="admin_shortcut_<?php echo $key_m; ?>_ctrl" value="<?php echo $value_m['ctrl']; ?>">
                <input type="hidden" name="admin_shortcut[<?php echo $key_m; ?>][act]" id="admin_shortcut_<?php echo $key_m; ?>_act" value="<?php echo $value_m['act']; ?>">
                <input type="hidden" name="admin_shortcut[<?php echo $key_m; ?>][title]" id="admin_shortcut_<?php echo $key_m; ?>_title" value="<?php echo $value_m['title']; ?>">

                <ul class="list-inline mb-0 bg-cursor-move">
                  <li class="list-inline-item">
                    <span class="bg-icon bg-fw"><?php include($cfg_global['pathIcon'] . 'ellipsis-v' . BG_EXT_SVG); ?></span>
                  </li>
                  <li class="list-inline-item">
                    <?php echo $value_m['title']; ?>
                  </li>
                </ul>
                <button type="button" class="close" data-dismiss="alert">
                  &times;
                </button>
              </div>
            <?php } ?>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary"><?php echo $lang->get('Save'); ?></button>
          <button type="button" class="btn btn-outline-secondary bg-empty"><?php echo $lang->get('Empty'); ?></button>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <?php echo $lang->get('Option'); ?>
        </div>
        <div class="card-body">
          <?php foreach ($config['console']['console_mod'] as $key_m=>$value_m) { ?>
            <div class="form-group">
              <div class="form-check">
                <input type="checkbox" class="form-check-input shortcut_option" <?php if (isset($adminLogged['admin_shortcut'][$key_m])) { ?>checked<?php } ?> id="shortcut_option_<?php echo $key_m; ?>" value="<?php echo $key_m; ?>" data-ctrl="<?php echo $value_m['main']['ctrl']; ?>" data-act="index" data-title="<?php echo $lang->get($value_m['main']['title'], 'console.common'); ?>">
                <label class="form-check-label" for="shortcut_option_<?php echo $key_m; ?>">
                  <?php echo $lang->get($value_m['main']['title'], 'console.common'); ?>
                </label>
              </div>
            </div>

            <?php if (isset($value_m['lists']) && !empty($value_m['lists'])) {
              foreach ($value_m['lists'] as $key_s=>$value_s) { ?>
                <div class="form-group ml-3">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input shortcut_option" <?php if (isset($adminLogged['admin_shortcut'][$key_m . '_' . $key_s])) { ?>checked<?php } ?> id="shortcut_option_<?php echo $key_m; ?>_<?php echo $key_s; ?>" value="<?php echo $key_m; ?>_<?php echo $key_s; ?>" data-ctrl="<?php echo $value_s['ctrl']; ?>" data-act="<?php echo $value_s['act']; ?>" data-title="<?php echo $lang->get($value_s['title'], 'console.common'); ?>">
                    <label class="form-check-label" for="shortcut_option_<?php echo $key_m; ?>_<?php echo $key_s; ?>">
                      <?php echo $lang->get($value_s['title'], 'console.common'); ?>
                    </label>
                  </div>
                </div>
              <?php }
            }
          } ?>
        </div>
      </div>
    </div>
  </form>

<?php include($cfg['pathInclude'] . 'console_foot' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  $(document).ready(function(){
    $('.bg-drag').dad({
      draggable: '.alert',
      cloneClass: 'bg-dad-clone',
      placeholderClass: 'bg-dad-placeholder'
    });

    $('.bg-drag .alert').on('closed.bs.alert', function() {
      var _target = $(this).data('key');
      $('#shortcut_option_' + _target).prop('checked', false);
    });

    $('.shortcut_option').click(function(){
      var _bool_check     = $(this).prop('checked');
      var _key            = $(this).val();
      var _ctrl           = $(this).data('ctrl');
      var _act            = $(this).data('act');
      var _title          = $(this).data('title');
      var _append_html    = '<div id="shortcut_list_' + _key + '" data-key="' + _key + '" class="alert alert-secondary alert-dismissible">' +
        '<input type="hidden" name="admin_shortcut[' + _key + '][ctrl]" id="admin_shortcut_' + _key + '_ctrl" value="' + _ctrl + '">' +
        '<input type="hidden" name="admin_shortcut[' + _key + '][act]" id="admin_shortcut_' + _key + '_act" value="' + _act + '">' +
        '<input type="hidden" name="admin_shortcut[' + _key + '][title]" id="admin_shortcut_' + _key + '_title" value="' + _title + '">' +
        '<ul class="list-inline mb-0 bg-cursor-move">' +
          '<li class="list-inline-item">' +
            '<span class="bg-icon bg-fw"><?php include($cfg_global['pathIcon'] . 'ellipsis-v' . BG_EXT_SVG); ?></span>' +
          '</li>' +
          '<li class="list-inline-item">' +
            _title +
          '</li>' +
        '</ul>' +
        '<button type="button" class="close" data-dismiss="alert">' +
          '&times;' +
        '</button>' +
      '</div>';

      if (_bool_check) {
        if ($('#shortcut_list_' + _key).length < 1) {
          $('#shortcut_list').append(_append_html);
        }
      } else {
        $('#shortcut_list_' + _key).remove();
      }
    });

    var obj_submit_form    = $('#shortcut_setting_form').baigoSubmit(opts_submit);
    $('#shortcut_setting_form').submit(function(){
      obj_submit_form.formSubmit();
    });

    $('.bg-empty').click(function(){
      $('#shortcut_list').empty();
      $('.shortcut_option').prop('checked', false);
    });
  });
  </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);
