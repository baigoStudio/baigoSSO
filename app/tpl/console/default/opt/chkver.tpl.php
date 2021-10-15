<?php $cfg = array(
  'title'             => $lang->get('System settings', 'console.common') . ' &raquo; ' . $lang->get('Check for updates', 'console.common'),
  'menu_active'       => 'opt',
  'sub_active'        => 'chkver',
  'baigoSubmit'       => 'true',
  'pathInclude'       => $path_tpl . 'include' . DS,
);

include($cfg['pathInclude'] . 'console_head' . GK_EXT_TPL); ?>

  <form name="opt_form" id="opt_form" action="<?php echo $route_console; ?>opt/chkver-submit/" class="mb-3">
    <input type="hidden" name="<?php echo $token['name']; ?>" value="<?php echo $token['value']; ?>">

    <button type="submit" class="btn btn-primary">
      <?php echo $lang->get('Check for updates', 'console.common'); ?>
    </button>
  </form>

  <?php if (isset($latest['prd_pub']) && $installed['prd_installed_pub'] < $latest['prd_pub']) { ?>
    <div class="alert alert-warning">
      <span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'exclamation-triangle' . BG_EXT_SVG); ?></span>
      <?php echo $lang->get('There are new versions, this is the latest version of the issues and help.'); ?>
    </div>
  <?php } else { ?>
    <div class="alert alert-success">
      <span class="bg-icon"><?php include($cfg_global['pathIcon'] . 'heart' . BG_EXT_SVG); ?></span>
      <?php echo $lang->get('Your version is the latest!'); ?>
    </div>
  <?php }

  if (isset($latest['prd_pub_datetime']) && $installed['prd_installed_pub'] < $latest['prd_pub']) { ?>
    <div class="card border-warning  mb-3">
      <div class="card-header">
        <?php echo $lang->get('Latest version'); ?>
      </div>
      <table class="table">
        <tbody>
          <tr>
            <td class="nowrap bg-td-lg"><?php echo $lang->get('Version'); ?></td>
            <td><?php echo $latest['prd_ver']; ?></td>
          </tr>
          <tr>
            <td class="nowrap bg-td-lg"><?php echo $lang->get('Issues time'); ?></td>
            <td><?php echo $latest['prd_pub_datetime']; ?></td>
          </tr>
          <tr>
            <td class="nowrap bg-td-lg"><?php echo $lang->get('Announcement'); ?></td>
            <td><a href="<?php echo $latest['prd_announcement']; ?>" target="_blank"><?php echo $latest['prd_announcement']; ?></a></td>
          </tr>
          <tr>
            <td class="nowrap bg-td-lg"><?php echo $lang->get('Download'); ?></td>
            <td><a href="<?php echo $latest['prd_download']; ?>" target="_blank"><?php echo $latest['prd_download']; ?></a></td>
          </tr>
        </tbody>
      </table>
    </div>
  <?php } ?>

  <div class="card">
    <div class="card-header">
      <?php echo $lang->get('Current version'); ?>
    </div>
    <table class="table">
      <tbody>
        <tr>
          <td class="nowrap bg-td-lg"><?php echo $lang->get('Version'); ?></td>
          <td><?php echo $installed['prd_installed_ver']; ?></td>
        </tr>
        <tr>
          <td class="nowrap bg-td-lg"><?php echo $lang->get('Issues time'); ?></td>
          <td><?php echo $installed['prd_installed_pub_datetime']; ?></td>
        </tr>
        <tr>
          <td class="nowrap bg-td-lg"><?php echo $lang->get('Installation time'); ?></td>
          <td><?php echo $installed['prd_installed_datetime']; ?></td>
        </tr>
      </tbody>
    </table>
  </div>

<?php include($cfg['pathInclude'] . 'console_foot' . GK_EXT_TPL); ?>

  <script type="text/javascript">
  $(document).ready(function(){
    var obj_submit_form     = $('#opt_form').baigoSubmit(opts_submit);

    $('#opt_form').submit(function(){
      obj_submit_form.formSubmit();
    });
  });
  </script>

<?php include($cfg['pathInclude'] . 'html_foot' . GK_EXT_TPL);
