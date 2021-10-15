  <ul class="list-group list-group-flush bg-list-group">
    <?php foreach ($phplib as $key=>$value) { ?>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <span>
          <div>
            <?php echo $lang->get($value['title'], $route['mod'] . '.common'); ?>
          </div>
          <small>
            <?php echo $value['title']; ?>
          </small>
        </span>
        <?php if ($value['installed']) { ?>
          <span class="badge badge-success"><?php echo $lang->get('Installed'); ?></span>
        <?php } else { ?>
          <span class="badge badge-danger"><?php echo $lang->get('Not installed'); ?></span>
        <?php } ?>
      </li>
    <?php } ?>
  </ul>
