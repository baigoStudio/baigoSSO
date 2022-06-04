  <?php if (!function_exists('listDetail')) {
    function listDetail($detail, $key, $key_class = '', $item_class = '') { ?>
      <h5>
        <span class="<?php echo $key_class; ?>">
          <strong>No. : </strong>
          <?php echo $key; ?>
        </span>
      </h5>
      <?php if (is_array($detail)) {
        if (!empty($detail)) {
          foreach ($detail as $_key=>$_value) {
            if ($_key == 'args') {
              if (is_array($_value) && !empty($_value)) { ?>
                <h5 class="args-header">
                  <a href="javascript:void(0);" class="badge badge-primary">
                    args
                    +
                  </a>
                </h5>
                <div class="args-body">
                  <div class="text-break">
                    <?php foreach($_value as $_key_sub=>$_value_sub) {
                      listDetail($_value_sub, $_key_sub, 'badge badge-secondary', 'text-muted');
                    } ?>
                  </div>
                </div>
              <?php }
            } else { ?>
              <div class="<?php echo $item_class; ?>">
                <strong><?php echo $_key; ?> : </strong>
                <?php echo $_value; ?>
              </div>
            <?php }
          }
        }
      } else { ?>
        <div class="<?php echo $item_class; ?>">
          <pre class="pre-scrollable"><code><?php echo htmlentities($detail); ?></code></pre>
        </div>
      <?php } ?>
      <div>&nbsp;</div>
    <?php }
  } ?>

  <style type="text/css">
  #debug-box {
    width: 100%;
    max-width: 1140px;
    padding-right: 15px;
    padding-left: 15px;
    margin: 15px auto;
  }

  #debug-box h5 {
    font-weight: 500;
    line-height: 1.2;
    margin: 0 0 0.5rem 0;
    font-size: 1.25rem;
  }

  #debug-box .text-break {
    word-break: break-word !important;
    overflow-wrap: break-word !important;
  }

  #debug-box .text-muted {
    color: #6c757d !important;
  }

  #debug-box .pre-scrollable {
    padding: 15px;
    max-height: 340px;
    overflow-y: scroll;
    border: 1px solid #ccc;
    border-radius: 0.25rem;
  }

  #debug-box .args-body {
    margin: 15px;
  }

  #debug-box #debug-card {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid #ccc;
    border-radius: 0.25rem;
  }

  #debug-box #debug-body {
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    min-height: 1px;
    padding: 1.25rem;
  }

  #debug-box #debug-header {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    padding-left: 0;
    margin-bottom: 0;
    list-style: none;
    padding: 0.75rem 1.25rem 0 1.25rem;
    margin-bottom: 0;
    background-color: rgba(0, 0, 0, 0.03);
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
  }

  #debug-box .debug-link {
    display: block;
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    margin-bottom: -1px;
    border: 1px solid transparent;
    text-decoration: none;
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
  }

  #debug-box .debug-link:hover,
  #debug-box .debug-link:focus {
    border-color: #e9ecef #e9ecef #dee2e6;
  }

  #debug-box .debug-link.active {
    color: #495057;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
  }

  #debug-box #debug-body .args-body,
  #debug-box #debug-body .debug-pane {
    display: none;
  }

  #debug-box #debug-body .args-body.active,
  #debug-box #debug-body .debug-pane.active {
    display: block;
  }

  #debug-box .badge {
    display: inline-block;
    padding: 0.25em 0.4em;
    font-weight: 500;
    line-height: 1.2em;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
    color: #fff;
    text-decoration: none;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  }

  #debug-box .badge-primary {
    background-color: #007bff;
  }

  #debug-box .badge-primary:hover {
    background-color: #0062cc;
  }

  #debug-box .badge-secondary {
    background-color: #6c757d;
  }
  </style>

  <div id="debug-box">
    <div id="debug-card">
      <nav id="debug-header">
        <a class="debug-link active" href="javascript:void(0);">
          <?php echo $lang->get('Base'); ?>
        </a>
        <a class="debug-link" href="javascript:void(0);">
          <?php echo $lang->get('Error'); ?>
        </a>
        <a class="debug-link" href="javascript:void(0);">
          <?php echo $lang->get('Include files'); ?>
        </a>
        <a class="debug-link" href="javascript:void(0);">
          <?php echo $lang->get('SQL'); ?>
        </a>
        <a class="debug-link" href="javascript:void(0);">
          <?php echo $lang->get('Debug backtrace'); ?>
        </a>
      </nav>

      <ul id="debug-body">
        <li class="debug-pane active">
          <h4><?php echo $lang->get('Base'); ?></h4>
          <?php if (isset($trace['base']) && is_array($trace['base']) && !empty($trace['base'])) {
            foreach($trace['base'] as $key=>$value) { ?>
              <div>
                <strong><?php echo $lang->get($key); ?> : </strong>
                <?php echo $value; ?>
              </div>
            <?php }
          } else {
            echo $lang->get('None');
          } ?>
        </li>

        <li class="debug-pane">
          <h4><?php echo $lang->get('Error'); ?></h4>
          <?php if (isset($trace['error']) && is_array($trace['error']) && !empty($trace['error'])) {
            foreach($trace['error'] as $key=>$value) {
              if (isset($value['err_message'])) { ?>
                <div class="text-break">
                  <strong><?php echo $lang->get('message'); ?> : </strong>
                  <?php echo $value['err_message']; ?>
                </div>
              <?php }

              if (isset($value['err_file'])) { ?>
                <div class="text-break">
                  <strong><?php echo $lang->get('file'); ?> : </strong>
                  <?php echo $value['err_file']; ?>
                </div>
              <?php }

              if (isset($value['err_line'])) { ?>
                <div class="text-break">
                  <strong><?php echo $lang->get('line'); ?> : </strong>
                  <?php echo $value['err_line']; ?>
                </div>
              <?php }

              if (isset($value['err_type'])) { ?>
                <div class="text-break">
                  <strong><?php echo $lang->get('type'); ?> : </strong>
                  <?php echo $value['err_type']; ?>
                </div>
              <?php } ?>

              <div>&nbsp;</div>
            <?php }
          } else {
            echo $lang->get('None');
          } ?>
        </li>

        <li class="debug-pane">
          <h4><?php echo $lang->get('Include files'); ?></h4>
          <?php if (isset($trace['files']) && is_array($trace['files']) && !empty($trace['files'])) {
            foreach($trace['files'] as $key=>$value) { ?>
              <h5>
                <span class="badge badge-primary"><?php echo $key; ?></span>
                <span class="badge badge-secondary"><?php echo $value['size']; ?></span>
              </h5>
              <div class="text-break">
                <?php echo $value['path']; ?>
              </div>
            <?php }
          } else {
            echo $lang->get('None');
          } ?>
        </li>

        <li class="debug-pane">
          <h4><?php echo $lang->get('SQL'); ?></h4>
          <?php if (isset($trace['sql']) && is_array($trace['sql']) && !empty($trace['sql'])) {
            foreach($trace['sql'] as $key=>$value) { ?>
              <h5>
                <div class="badge badge-primary"><?php echo $key; ?></span>
              </h5>
              <div class="text-break">
                <?php echo str_replace(PHP_EOL, '<br>' . PHP_EOL, $value); ?>
              </div>
            <?php }
          } else {
            echo $lang->get('None');
          } ?>
        </li>

        <li class="debug-pane">
          <h4><?php echo $lang->get('Debug backtrace'); ?></h4>
          <?php if (isset($trace['backtrace']) && is_array($trace['backtrace']) && !empty($trace['backtrace'])) {
            foreach($trace['backtrace'] as $key=>$value) {
              listDetail($value, $key, 'badge badge-primary');
            }
          } else {
            echo $lang->get('None');
          } ?>
        </li>
      </ul>
    </div>
  </div>

  <script type="text/javascript">
  window.onload = function() {
    let _debug_header   = document.querySelectorAll('#debug-header .debug-link');
    let _debug_body     = document.querySelectorAll('#debug-body .debug-pane');
    let _args_header    = document.querySelectorAll('.args-header .badge');
    let _args_body      = document.querySelectorAll('.args-body');

    for (let _key = 0; _key < _debug_header.length; ++_key) {
      if (typeof _debug_header[_key] != 'undefined' && typeof _debug_body[_key] != 'undefined') {
        // 准备索引值
        _debug_header[_key].setAttribute('data-target', _key);
        _debug_header[_key].onclick = function(evt) {
          evt.preventDefault();

          for (let _key_sub = 0; _key_sub < _debug_header.length; ++_key_sub) {
            if (typeof _debug_header[_key_sub] != 'undefined') {
              _debug_header[_key_sub].className = 'debug-link';
            }
          }
          this.className = 'debug-link active';

          // 获取索引值
          let _target = this.getAttribute('data-target');
          for (let _key_body = 0; _key_body < _debug_body.length; ++_key_body) {
            if (typeof _debug_body[_key_body] != 'undefined') {
              _debug_body[_key_body].className = 'debug-pane';
            }
          }
          if (typeof _debug_body[_target] != 'undefined') {
            _debug_body[_target].className = 'debug-pane active';
          }
        }
      }
    }

    for (let _key = 0; _key < _args_header.length; ++_key) {
      if (typeof _args_header[_key] != 'undefined' && typeof _args_header[_key] != 'undefined') {
        // 准备索引值
        _args_header[_key].setAttribute('data-target', _key);
        _args_header[_key].onclick = function(evt) {
          evt.preventDefault();

          // 获取索引值
          let _target = this.getAttribute('data-target');
          for (let _key_body = 0; _key_body < _args_body.length; ++_key_body) {
            if (typeof _args_body[_key_body] != 'undefined') {
              _args_body[_key_body].className = 'args-body';
            }
          }
          if (typeof _args_body[_target] != 'undefined') {
            _args_body[_target].className = 'args-body active';
          }
        }
      }
    }
  };
  </script>
