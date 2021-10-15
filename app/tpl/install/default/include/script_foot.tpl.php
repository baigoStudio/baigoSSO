  <!--jQuery 库-->
  <script src="{:DIR_STATIC}lib/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
  <!--bootstrap-->
  <script src="{:DIR_STATIC}lib/bootstrap/4.6.0/js/bootstrap.bundle.min.js" type="text/javascript"></script>

  <!--表单验证 js-->
  <script src="{:DIR_STATIC}lib/baigoValidate/3.1.1/baigoValidate.min.js" type="text/javascript"></script>

  <!--表单 ajax 提交 js-->
  <script src="{:DIR_STATIC}lib/baigoSubmit/2.1.4/baigoSubmit.min.js" type="text/javascript"></script>

  <script type="text/javascript">
  var opts_dialog = {
    btn_text: {
      cancel: '<?php echo $lang->get('Cancel', 'install.common'); ?>',
      confirm: '<?php echo $lang->get('Confirm', 'install.common'); ?>',
      ok: '<?php echo $lang->get('OK', 'install.common'); ?>'
    }
  };

  var opts_submit = {
    modal: {
      btn_text: {
        close: '<?php echo $lang->get('Close', 'install.common'); ?>',
        ok: '<?php echo $lang->get('OK', 'install.common'); ?>'
      }
    },
    msg_text: {
      submitting: '<?php echo $lang->get('Submitting', 'install.common'); ?>'
    }
  }

  $(document).ready(function(){
    $('#loading_mask').fadeOut();
  });
  </script>
