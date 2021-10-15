
  <div class="modal-header">
    <h4 class="modal-title"><?php echo $lang->get('Charset encoding help'); ?></h4>
    <button type="button" class="close" data-dismiss="modal">
      &times;
    </button>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <?php foreach ($charsetRows as $key=>$value) { ?>
        <thead>
          <tr>
            <th colspan="3"><?php echo $lang->get($value['title'], 'console.charset'); ?></th>
          </tr>
          <tr>
            <th class="text-nowrap"><?php echo $lang->get('Coding'); ?></th>
            <th class="text-nowrap"><?php echo $lang->get('Description'); ?></th>
            <th><?php echo $lang->get('Note'); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($value['lists'] as $key_sub=>$value_sub) { ?>
            <tr>
              <td class="text-nowrap"><?php echo $key_sub; ?></td>
              <td class="text-nowrap">
                <?php echo $lang->get($value_sub['title'], 'console.charset'); ?>
              </td>
              <td><?php echo $lang->get($value_sub['note'], 'console.charset'); ?></td>
            </tr>
          <?php } ?>
        </tbody>
      <?php } ?>
    </table>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">
      <?php echo $lang->get('Close', 'console.common'); ?>
    </button>
  </div>
