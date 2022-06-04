<?php
if (!function_exists('extend_list')) {
  function extend_list($arr_extend, $target, $lang) {
    $_key = 0;
    if (!empty($arr_extend)) {
      foreach ($arr_extend as $_key=>$_value) { ?>
        <div id="<?php echo $target; ?>_group_<?php echo $_key; ?>">
          <div class="input-group mb-2">
            <span class="input-group-prepend">
              <span class="input-group-text"><?php echo $lang->get('Name'); ?></span>
            </span>
            <input type="text" name="user_<?php echo $target; ?>[<?php echo $_key; ?>][key]" id="user_<?php echo $target; ?>_<?php echo $_key; ?>_key" value="<?php if (isset($_value['key'])) { echo $_value['key']; } ?>" class="form-control">
            <span class="input-group-append">
              <button type="button" class="btn btn-info extend_del" data-target="<?php echo $target; ?>" data-count="<?php echo $_key; ?>">
                <span class="bg-icon"><?php include($tpl_icon . 'trash-alt' . BG_EXT_SVG); ?></span>
              </button>
            </span>
          </div>

          <div class="input-group">
            <span class="input-group-prepend">
              <span class="input-group-text"><?php echo $lang->get('Content'); ?></span>
            </span>
            <input type="text" name="user_<?php echo $target; ?>[<?php echo $_key; ?>][value]" id="user_<?php echo $target; ?>_<?php echo $_key; ?>_value" value="<?php if (isset($_value['value'])) { echo $_value['value']; } ?>" class="form-control">
          </div>
          <hr>
        </div>
      <?php }
    }

    return $_key;
  }
}

if ($userRow['user_id'] > 0) {
  $title_sub    = $lang->get('Edit');
  $str_sub      = 'index';
} else {
  $title_sub    = $lang->get('Add');
  $str_sub      = 'form';
}

$cfg = array(
  'title'             => $lang->get('User management', 'console.common') . ' &raquo; ' . $title_sub,
  'menu_active'       => 'user',
  'sub_active'        => $str_sub,
  'baigoValidate'    => 'true',
  'baigoSubmit'       => 'true',
);

include($tpl_include . 'console_head' . GK_EXT_TPL); ?>

  <nav class="nav mb-3">
    <a href="<?php echo $hrefRow['index']; ?>" class="nav-link">
      <span class="bg-icon"><?php include($tpl_icon . 'chevron-left' . BG_EXT_SVG); ?></span>
      <?php echo $lang->get('Back'); ?>
    </a>
  </nav>

  <form name="user_form" id="user_form" autocomplete="off" action="<?php echo $hrefRow['submit']; ?>">
    <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">
    <input type="hidden" name="user_id" id="user_id" value="<?php echo $userRow['user_id']; ?>">

    <div class="row">
      <div class="col-xl-9">
        <div class="card mb-3">
          <div class="card-body">
            <?php if ($userRow['user_id'] > 0) { ?>
              <div class="form-group">
                <label><?php echo $lang->get('Username'); ?></label>
                <input type="text" name="user_name" id="user_name" value="<?php echo $userRow['user_name']; ?>" class="form-control" readonly>
              </div>

              <div class="form-group">
                <label><?php echo $lang->get('Password'); ?></label>
                <input type="text" name="user_pass" id="user_pass" class="form-control" placeholder="<?php echo $lang->get('Enter only when you need to modify'); ?>">
              </div>
            <?php } else { ?>
              <div class="form-group">
                <label><?php echo $lang->get('Username'); ?> <span class="text-danger">*</span></label>
                <input type="text" name="user_name" id="user_name" class="form-control">
                <small class="form-text" id="msg_user_name"></small>
              </div>

              <div class="form-group">
                <label><?php echo $lang->get('Password'); ?> <span class="text-danger">*</span></label>
                <input type="text" name="user_pass" id="user_pass" class="form-control">
                <small class="form-text" id="msg_user_pass"></small>
              </div>
            <?php } ?>

            <div class="form-group">
              <label><?php echo $lang->get('Email'); ?></label>
              <input type="text" name="user_mail" id="user_mail" value="<?php echo $userRow['user_mail']; ?>" class="form-control">
              <small class="form-text" id="msg_user_mail"></small>
            </div>

            <div class="form-group">
              <label><?php echo $lang->get('Nickname'); ?></label>
              <input type="text" name="user_nick" id="user_nick" value="<?php echo $userRow['user_nick']; ?>" class="form-control">
              <small class="form-text" id="msg_user_nick"></small>
            </div>

            <div class="form-group">
              <label><?php echo $lang->get('Note'); ?></label>
              <input type="text" name="user_note" id="user_note" value="<?php echo $userRow['user_note']; ?>" class="form-control">
              <small class="form-text" id="msg_user_note"></small>
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

      <div class="col-xl-3">
        <div class="card bg-light">
          <div class="card-body">
            <?php if ($userRow['user_id'] > 0) { ?>
              <div class="form-group">
                <label class="text-muted font-weight-light"><?php echo $lang->get('ID'); ?></label>
                <div class="form-text font-weight-bolder"><?php echo $userRow['user_id']; ?></div>
              </div>
            <?php } ?>

            <div class="form-group">
              <label><?php echo $lang->get('Status'); ?> <span class="text-danger">*</span></label>
              <?php foreach ($status as $key=>$value) { ?>
                <div class="form-check">
                  <input type="radio" name="user_status" id="user_status_<?php echo $value; ?>" value="<?php echo $value; ?>" <?php if ($userRow['user_status'] == $value) { ?>checked<?php } ?> class="form-check-input">
                  <label for="user_status_<?php echo $value; ?>" class="form-check-label">
                    <?php echo $lang->get($value); ?>
                  </label>
                </div>
              <?php } ?>
              <small class="form-text" id="msg_user_status"></small>
            </div>

            <div class="form-group">
              <label><?php echo $lang->get('Contact'); ?></label>
              <div id="contact_list">
                <?php $key_contact = extend_list($userRow['user_contact'], 'contact', $lang); ?>
              </div>

              <button type="button" class="btn btn-info extend_add" data-target="contact">
                <span class="bg-icon"><?php include($tpl_icon . 'plus' . BG_EXT_SVG); ?></span>
              </button>
            </div>

            <div class="form-group">
              <label><?php echo $lang->get('Extend'); ?></label>
              <div id="extend_list">
                  <?php $key_extend = extend_list($userRow['user_extend'], 'extend', $lang); ?>
              </div>

              <button type="button" class="btn btn-info extend_add" data-target="extend">
                <span class="bg-icon"><?php include($tpl_icon . 'plus' . BG_EXT_SVG); ?></span>
              </button>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">
              <?php echo $lang->get('Save'); ?>
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>

<?php include($tpl_include . 'console_foot' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  var opts_validate_form = {
    rules: {
      <?php if ($userRow['user_id'] < 1) { ?>
        user_name: {
          length: '1,30',
          format: 'alpha_dash',
          ajax: {
            url: '<?php echo $hrefRow['check']; ?>',
            attach: {
              selectors: ['#user_id'],
              keys: ['user_id']
            }
          }
        },
        user_pass: {
          require: true
        },
      <?php } ?>
      user_mail: {
        max: 300,
        format: 'email'
      },
      user_nick: {
        max: 30
      },
      user_note: {
        max: 30
      },
      user_status: {
        require: true
      }
    },
    attr_names: {
      user_name: '<?php echo $lang->get('Username'); ?>',
      user_mail: '<?php echo $lang->get('Email'); ?>',
      user_pass: '<?php echo $lang->get('Password'); ?>',
      user_nick: '<?php echo $lang->get('Nickname'); ?>',
      user_note: '<?php echo $lang->get('Note'); ?>',
      user_status: '<?php echo $lang->get('Status'); ?>'
    },
    selector_types: {
      user_status: 'name'
    },
    type_msg: {
      require: '<?php echo $lang->get('{:attr} require'); ?>',
      max: '<?php echo $lang->get('Max size of {:attr} must be {:rule}'); ?>',
      length: '<?php echo $lang->get('Size of {:attr} must be {:rule}'); ?>',
      checkbox: '<?php echo $lang->get('Choose at least one {:attr}'); ?>'
    },
    format_msg: {
      alpha_dash: '<?php echo $lang->get('{:attr} must be alpha-numeric, dash, underscore'); ?>',
      email: '<?php echo $lang->get('{:attr} not a valid email address'); ?>'
    },
    msg: {
      loading: '<?php echo $lang->get('Loading'); ?>',
      ajax_err: '<?php echo $lang->get('Server side error'); ?>'
    },
    box: {
      msg: '<?php echo $lang->get('Input error'); ?>'
    }
  };

  function extendDel(count, target) {
    $('#' + target + '_group_' + count).remove();
  }

  function extendAdd(count, target) {
    $('#' + target + '_list').append('<div id="' + target + '_group_' + count + '">' +
      '<div class="input-group mb-2">' +
        '<span class="input-group-prepend"><span class="input-group-text"><?php echo $lang->get('Name'); ?></span></span>' +
        '<input type="text" name="user_' + target + '[' + count + '][key]" id="user_' + target + '_' + count + '" class="form-control">' +
        '<span class="input-group-append">' +
          '<button type="button" class="btn btn-info extend_del" data-target="' + target + '" data-count="' + count + '">' +
            '<span class="bg-icon"><?php include($tpl_icon . 'trash-alt' . BG_EXT_SVG); ?></span>' +
          '</button>' +
        '</span>' +
      '</div>' +
      '<div class="input-group">' +
        '<span class="input-group-prepend"><span class="input-group-text"><?php echo $lang->get('Content'); ?></span></span>' +
        '<input type="text" name="user_' + target + '[' + count + '][value]" id="user_' + target + '_' + count + '" class="form-control">' +
      '</div>' +
      '<hr>' +
    '</div>');
  }

  $(document).ready(function(){
    var obj_validate_form   = $('#user_form').baigoValidate(opts_validate_form);
    var obj_submit_form     = $('#user_form').baigoSubmit(opts_submit);

    $('#user_form').submit(function(){
      if (obj_validate_form.verify()) {
        obj_submit_form.formSubmit();
      }
    });

    var _count = { contact: <?php echo ++$key_contact; ?>, extend: <?php echo ++$key_extend; ?> };

    $('.extend_add').click(function(){
      var _target = $(this).data('target');
      _count[_target]++;
      extendAdd(_count[_target], _target);
    });

    $('#contact_list, #extend_list').on('click', '.extend_del', function(){
      var _target = $(this).data('target');
      var _count  = $(this).data('count');
      extendDel(_count, _target);
    });
  });
  </script>

<?php include($tpl_include . 'html_foot' . GK_EXT_TPL);
