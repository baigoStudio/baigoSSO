<?php $cfg = array(
  'title'             => $lang->get('System settings', 'console.common') . ' &raquo; ' . $lang->get($config['console']['opt'][$route_orig['act']]['title'], 'console.common'),
  'menu_active'       => 'opt',
  'sub_active'        => $route_orig['act'],
  'baigoValidate'     => 'true',
  'baigoSubmit'       => 'true',
  'selectInput'       => 'true',
);

include($tpl_include . 'console_head' . GK_EXT_TPL); ?>

  <form name="opt_form" id="opt_form" action="<?php echo $hrefRow['submit']; ?>">
    <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">
    <input type="hidden" name="act" value="<?php echo $route_orig['act']; ?>">

    <div class="card">
      <div class="card-body">
        <?php
        $_arr_rule      = array();
        $_arr_attr      = array();
        $_arr_selector  = array();

        foreach ($consoleOpt as $_key=>$_value) {
          if (isset($_value['require'])) {
            $_arr_rule[$_key]['require'] = $_value['require'];
          }

          if (isset($_value['format'])) {
            $_arr_rule[$_key]['format'] = $_value['format'];
          }

          $_arr_attr[$_key]       = $lang->get($_value['title']);

          //form
          //$_this_value = $config['var_extra'][$route_orig['act']][$_key];

          //print_r($config[$route_orig['act']]);
          ?>
          <div class="form-group">
            <label>
              <?php echo $lang->get($_value['title']);

              if (isset($_value['require']) && ($_value['require'] == true || $_value['require'] == 'true')) { ?> <span class="text-danger">*</span><?php } ?>
            </label>

            <?php switch ($_value['type']) {
              case 'select': ?>
                <select name="<?php echo $_key; ?>" id="<?php echo $_key; ?>"  class="form-control">
                  <?php foreach ($_value['option'] as $_key_opt=>$_value_opt) { ?>
                    <option<?php if ($_value['this'] == $_key_opt) { ?> selected<?php } ?> value="<?php echo $_key_opt; ?>">
                      <?php echo $lang->get($_value_opt, '', $_value['lang_replace']); ?>
                    </option>
                  <?php } ?>
                </select>
              <?php break;

              case 'select_input': ?>
                <div class="input-group">
                  <input type="text" value="<?php echo $_value['this']; ?>" name="<?php echo $_key; ?>" id="<?php echo $_key; ?>" class="form-control">
                  <span class="input-group-append">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                      <?php echo $lang->get('Please select'); ?>
                    </button>

                    <div class="dropdown-menu">
                      <?php foreach ($_value['option'] as $_key_opt=>$_value_opt) { ?>
                        <button class="dropdown-item bg-select-input" data-value="<?php echo $_key_opt; ?>" data-target="#<?php echo $_key; ?>" type="button">
                          <?php echo $lang->get($_value_opt, '', $_value['lang_replace']); ?>
                        </button>
                      <?php } ?>
                    </div>
                  </span>
                </div>
              <?php break;

              case 'radio': ?>
                <div>
                  <?php foreach ($_value['option'] as $_key_opt=>$_value_opt) { ?>
                    <div class="form-check form-check-inline">
                      <input type="radio"<?php if ($_value['this'] == $_key_opt) { ?> checked<?php } ?> value="<?php echo $_key_opt; ?>" name="<?php echo $_key; ?>" id="<?php echo $_key; ?>_<?php echo $_key_opt; ?>" class="form-check-input">
                      <label for="<?php echo $_key; ?>_<?php echo $_key_opt; ?>" class="form-check-label">
                        <?php echo $lang->get($_value_opt['value']);

                        if (isset($_value_opt['note'])) { ?>
                          <span class="text-muted">(<?php echo $lang->get($_value_opt['note']); ?>)</span>
                        <?php } ?>
                      </label>
                    </div>
                  <?php } ?>
                </div>
                <?php $_arr_selector[$_key]   = 'name';
              break;

              case 'switch': ?>
                <div class="custom-control custom-switch">
                  <input type="checkbox" id="<?php echo $_key; ?>" name="<?php echo $_key; ?>" <?php if ($_value['this'] === 'on') { ?>checked<?php } ?> value="on" class="custom-control-input">
                  <label for="<?php echo $_key; ?>" class="custom-control-label">
                    <?php echo $lang->get($_value['title']); ?>
                  </label>
                </div>
              <?php break;

              case 'textarea': ?>
                <textarea name="<?php echo $_key; ?>" id="<?php echo $_key; ?>" class="form-control bg-textarea-md"><?php echo $_value['this']; ?></textarea>
              <?php break;

              default: ?>
                <input type="text" value="<?php echo $_value['this']; ?>" name="<?php echo $_key; ?>" id="<?php echo $_key; ?>" class="form-control">
              <?php break;
            } ?>

            <small class="form-text" id="msg_<?php echo $_key; ?>"></small>

            <?php if (isset($_value['note'])) { ?>
              <small class="form-text"><?php echo $lang->get($_value['note']); ?></small>
            <?php } ?>
          </div>
        <?php }

        if ($route_orig['act'] == 'base') { ?>
          <div class="form-group">
              <label><?php echo $lang->get('Template'); ?> <span class="text-danger">*</span></label>
              <select name="site_tpl" id="site_tpl" class="form-control">
                  <?php foreach ($tplRows as $_key=>$_value) {
                      if ($_value['type'] == 'dir') { ?>
                          <option<?php if ($config['var_extra']['base']['site_tpl'] == $_value['name']) { ?> selected<?php } ?> value="<?php echo $_value['name']; ?>"><?php echo $_value['name']; ?></option>
                      <?php }
                 } ?>
              </select>
              <small class="form-text" id="msg_site_tpl"></small>
          </div>

          <div class="form-group">
            <label><?php echo $lang->get('Timezone'); ?> <span class="text-danger">*</span></label>
            <div class="form-row">
              <div class="col">
                <select name="timezone_type" id="timezone_type" class="form-control">
                  <?php foreach ($timezoneRows as $_key=>$_value) { ?>
                    <option<?php if ($timezoneType == $_key) { ?> selected<?php } ?> value="<?php echo $_key; ?>">
                      <?php echo $lang->get($_value['title'], 'console.timezone'); ?>
                    </option>
                  <?php } ?>
                </select>
              </div>

              <div class="col">
                <select name="site_timezone" id="site_timezone" class="form-control">
                  <?php foreach ($timezoneRows[$timezoneType]['lists'] as $_key=>$_value) { ?>
                    <option<?php if ($config['var_extra']['base']['site_timezone'] == $_key) { ?> selected<?php } ?> value="<?php echo $_key; ?>">
                      <?php echo $lang->get($_value, 'console.timezone'); ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <small class="form-text" id="msg_site_timezone"></small>
          </div>
        <?php } ?>

        <div class="bg-validate-box"></div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">
          <?php echo $lang->get('Save'); ?>
        </button>
      </div>
    </div>
  </form>

<?php include($tpl_include . 'console_foot' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  var opts_validate_form = {
    rules: <?php echo json_encode($_arr_rule); ?>,
    attr_names: <?php echo json_encode($_arr_attr); ?>,
    selector_types: <?php echo json_encode($_arr_selector); ?>,
    type_msg: {
      require: '<?php echo $lang->get('{:attr} require'); ?>'
    },
    format_msg: {
      'int': '<?php echo $lang->get('{:attr} must be numeric'); ?>',
      url: '<?php echo $lang->get('{:attr} not a valid url'); ?>'
    },
    box: {
      msg: '<?php echo $lang->get('Input error'); ?>'
    }
  };

  $(document).ready(function(){
    var obj_validate_form  = $('#opt_form').baigoValidate(opts_validate_form);
    var obj_submit_form     = $('#opt_form').baigoSubmit(opts_submit);

    $('#opt_form').submit(function(){
      if (obj_validate_form.verify()) {
        obj_submit_form.formSubmit();
      }
    });

    <?php if ($route_orig['act'] == 'base') { ?>
      var _timezoneRowsJson = <?php echo $timezoneRowsJson; ?>;
      var _timezoneLangJson = <?php echo $timezoneLangJson; ?>;

      $('#timezone_type').change(function(){
        var _type = $(this).val();
        var _str_appent;
        $.each(_timezoneRowsJson[_type].lists, function(_key, _value){
          _str_appent += '<option';
          if (_key == '<?php echo $config['var_extra']['base']['site_timezone']; ?>') {
            _str_appent += ' selected';
          }
          _str_appent += ' value="' + _key + '">';
          if (typeof _timezoneLangJson[_value] != 'undefined') {
            _str_appent += _timezoneLangJson[_value];
          } else {
            _str_appent += _value;
          }
          _str_appent += '</option>';
        });
        $('#site_timezone').html(_str_appent);
      });
    <?php } ?>
  });
  </script>

<?php include($tpl_include . 'html_foot' . GK_EXT_TPL);
