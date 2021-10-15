<style type="text/css">
h5 {
  font-weight: 500;
  line-height: 1.2;
  margin: 0 0 0.5rem 0;
  font-size: 1.25rem;
}

.list-group {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-direction: column;
  flex-direction: column;
  padding: 0;
  margin: 1px 0 0 0;
}

.list-group-item {
  position: relative;
  display: block;
  padding: 3rem 1.25rem;
  background-color: #fff;
  border-bottom: 1px solid #ccc;
}

.list-group-item:last-child {
  border-bottom-width: 0;
}

.card {
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

.pre-scrollable {
  padding: 15px;
  max-height: 340px;
  overflow-y: scroll;
  border: 1px solid #ccc;
  border-radius: 0.25rem;
}

.badge {
  display: inline-block;
  padding: 0.25em 0.4em;
  font-size: 75%;
  font-weight: 700;
  line-height: 1;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  border-radius: 0.25rem;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.badge-primary {
  color: #fff;
  background-color: #007bff;
}

.badge-secondary {
  color: #fff;
  background-color: #6c757d;
}

.nav {
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  padding-left: 0;
  margin-bottom: 15px;
  list-style: none;
  background: #eee;
  border: 1px solid #ccc;
  border-radius: 0.25rem;
  position: -webkit-sticky;
  position: sticky;
  top: 0;
  z-index: 1020;
}

.nav-link {
  display: block;
  padding: 0.5rem 1rem;
  border-radius: 0.25rem;
}

.nav-link:hover, .nav-link:focus {
  text-decoration: none;
}

.args-block {
  margin: 15px;
}
</style>

<?php if (!function_exists('listDetail')) {
  function listDetail($detail, $key, $key_class = '', $item_class = '') { ?>
    <h5>
      <span class="<?php echo $key_class; ?>">
          <strong>No. : </strong>
          <?php echo $key; ?>
      </span>
    </h5>
    <?php if (is_array($detail) && !empty($detail)) {
      foreach ($detail as $_key=>$_value) {
        if ($_key == 'args') {
          if (!empty($_value)) { ?>
            <div>
              <strong>args</strong>
              +
            </div>
            <div class="args-block">
              <div class="text-break">
                <?php if (is_array($_value) && !empty($_value)) {
                  foreach($_value as $_key_sub=>$_value_sub) {
                    listDetail($_value_sub, $_key_sub, 'badge badge-secondary', 'text-muted');
                  }
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
    } else { ?>
      <div class="<?php echo $item_class; ?>">
        <pre class="pre-scrollable"><code><?php echo htmlentities($detail); ?></code></pre>
      </div>
    <?php } ?>
    <div>&nbsp;</div>
  <?php }
} ?>

<div class="container">
  <nav class="nav">
      <a class="nav-link" href="#base">
          <?php echo $lang->get('Base'); ?>
      </a>
      <a class="nav-link" href="#error">
          <?php echo $lang->get('Error'); ?>
      </a>
      <a class="nav-link" href="#files">
          <?php echo $lang->get('Include files'); ?>
      </a>
      <a class="nav-link" href="#sql">
          <?php echo $lang->get('SQL'); ?>
      </a>
      <a class="nav-link" href="#backtrace">
          <?php echo $lang->get('Debug backtrace'); ?>
      </a>
  </nav>

  <div class="card">
    <ul class="list-group list-group-flush">
      <li class="list-group-item" id="base">
        <h4><?php echo $lang->get('Base'); ?></h4>
        <?php if (isset($trace['base']) && !empty($trace['base'])) {
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

      <li class="list-group-item" id="error">
        <h4><?php echo $lang->get('Error'); ?></h4>
        <?php if (isset($trace['error']) && !empty($trace['error'])) {
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

      <li class="list-group-item" id="files">
        <h4><?php echo $lang->get('Include files'); ?></h4>
        <?php if (isset($trace['files']) && !empty($trace['files'])) {
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

      <li class="list-group-item" id="sql">
        <h4><?php echo $lang->get('SQL'); ?></h4>
        <?php if (isset($trace['sql']) && !empty($trace['sql'])) {
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

      <li class="list-group-item" id="backtrace">
        <h4><?php echo $lang->get('Debug backtrace'); ?></h4>
        <?php if (isset($trace['backtrace']) && !empty($trace['backtrace'])) {
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
