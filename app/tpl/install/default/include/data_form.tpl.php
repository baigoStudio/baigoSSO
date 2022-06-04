  <div class="table-responsive">
    <?php foreach ($config['install']['data'][$route['ctrl']] as $key=>$value) { ?>
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
                    <span class="spinner-grow spinner-grow-sm"></span>
                  </span>
                  <small>
                    <?php echo $lang->get('Submitting', 'install.common'); ?>
                  </small>
                </div>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } ?>
  </div>
