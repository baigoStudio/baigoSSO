  <!--jQuery 库-->
  <script src="{:DIR_STATIC}lib/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
  <!--bootstrap-->
  <script src="{:DIR_STATIC}lib/bootstrap/4.6.0/js/bootstrap.bundle.min.js" type="text/javascript"></script>

  <?php  if (isset($cfg['baigoValidate'])) { ?>
  <!--表单验证 js-->
  <script src="{:DIR_STATIC}lib/baigoValidate/3.1.1/baigoValidate.min.js" type="text/javascript"></script>
  <?php }

  if (isset($cfg['baigoSubmit'])) { ?>
  <!--表单 ajax 提交 js-->
  <script src="{:DIR_STATIC}lib/baigoSubmit/2.1.4/baigoSubmit.min.js" type="text/javascript"></script>
  <?php }

  if (isset($cfg['baigoQuery'])) { ?>
  <!--全选 js-->
  <script src="{:DIR_STATIC}lib/baigoQuery/1.0.0/baigoQuery.min.js" type="text/javascript"></script>
  <?php } ?>

  <script type="text/javascript">
  <?php if (isset($cfg['captchaReload'])) { ?>
    function captchaReload(img_src) {
      img_src += '?' + new Date().getTime() + '=' + Math.random();

      $('.bg-captcha-img').attr('src', img_src);
    }
  <?php }

  if (isset($cfg['baigoDialog'])) { ?>
    var opts_dialog = {
      btn_text: {
        cancel: '<?php echo $lang->get('Cancel', 'personal.common'); ?>',
        confirm: '<?php echo $lang->get('Confirm', 'personal.common'); ?>',
        ok: '<?php echo $lang->get('OK', 'personal.common'); ?>'
      }
    };
  <?php }

  if (isset($cfg['baigoSubmit'])) { ?>
    var opts_submit = {
      modal: {
        btn_text: {
          close: '<?php echo $lang->get('Close', 'personal.common'); ?>',
          ok: '<?php echo $lang->get('OK', 'personal.common'); ?>'
        }
      },
      msg_text: {
        submitting: '<?php echo $lang->get('Submitting', 'personal.common'); ?>'
      }
    };
  <?php } ?>

  $(document).ready(function(){
    <?php if (isset($cfg['captchaReload'])) { ?>
      /*重新载入图片 js*/
      $('.bg-captcha-img').click(function(){
        var _src = $(this).data('src');

        captchaReload(_src);
      });
    <?php } ?>

    $('#loading_mask').fadeOut();
  });
  </script>
