  <div class="form-group">
    <label><?php echo $lang->get('Database host'); ?> <span class="text-danger">*</span></label>
    <input type="text" value="<?php echo $config['dbconfig']['host']; ?>" name="host" id="host" class="form-control">
    <small class="form-text" id="msg_host"></small>
  </div>

  <div class="form-group">
    <label><?php echo $lang->get('Host port'); ?> <span class="text-danger">*</span></label>
    <input type="text" value="<?php echo $config['dbconfig']['port']; ?>" name="port" id="port" class="form-control">
    <small class="form-text" id="msg_port"></small>
  </div>

  <div class="form-group">
    <label><?php echo $lang->get('Database'); ?> <span class="text-danger">*</span></label>
    <input type="text" value="<?php echo $config['dbconfig']['name']; ?>" name="name" id="name" class="form-control">
    <small class="form-text" id="msg_name"></small>
  </div>

  <div class="form-group">
    <label><?php echo $lang->get('Username'); ?> <span class="text-danger">*</span></label>
    <input type="text" value="<?php echo $config['dbconfig']['user']; ?>" name="user" id="user" class="form-control">
    <small class="form-text" id="msg_user"></small>
  </div>

  <div class="form-group">
    <label><?php echo $lang->get('Password'); ?> <span class="text-danger">*</span></label>
    <input type="text" value="<?php echo $config['dbconfig']['pass']; ?>" name="pass" id="pass" class="form-control">
    <small class="form-text" id="msg_pass"></small>
  </div>

  <div class="form-group">
    <label><?php echo $lang->get('Charset'); ?> <span class="text-danger">*</span></label>
    <input type="text" value="<?php echo $config['dbconfig']['charset']; ?>" name="charset" id="charset" class="form-control">
    <small class="form-text" id="msg_charset"></small>
  </div>

  <div class="form-group">
    <label><?php echo $lang->get('Prefix'); ?> <span class="text-danger">*</span></label>
    <input type="text" value="<?php echo $config['dbconfig']['prefix']; ?>" name="prefix" id="prefix" class="form-control">
    <small class="form-text" id="msg_prefix"></small>
  </div>

  <script type="text/javascript">
  var opts_validate_form = {
    rules: {
      host: {
        require: true
      },
      port: {
        require: true,
        format: 'int'
      },
      name: {
        require: true
      },
      user: {
        require: true
      },
      pass: {
        require: true
      },
      charset: {
        require: true
      },
      prefix: {
        require: true
      }
    },
    attr_names: {
      host: '<?php echo $lang->get('Database host'); ?>',
      port: '<?php echo $lang->get('Host port'); ?>',
      name: '<?php echo $lang->get('Database'); ?>',
      user: '<?php echo $lang->get('Username'); ?>',
      pass: '<?php echo $lang->get('Password'); ?>',
      charset: '<?php echo $lang->get('Charset'); ?>',
      prefix: '<?php echo $lang->get('Prefix'); ?>'
    },
    type_msg: {
      require: '<?php echo $lang->get('{:attr} require'); ?>'
    },
    format_msg: {
      'int': '<?php echo $lang->get('{:attr} must be numeric'); ?>'
    },
    box: {
      msg: '<?php echo $lang->get('Input error'); ?>'
    }
  };
  </script>
