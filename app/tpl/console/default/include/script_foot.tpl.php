  <!--jQuery 库-->
  <script src="{:DIR_STATIC}lib/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
  <!--bootstrap-->
  <script src="{:DIR_STATIC}lib/bootstrap/4.6.0/js/bootstrap.bundle.min.js" type="text/javascript"></script>

  <?php if (isset($cfg['baigoClear'])) { ?>
    <!--表单 ajax 提交 js-->
    <script src="{:DIR_STATIC}lib/baigoClear/1.0.8/baigoClear.min.js" type="text/javascript"></script>
  <?php }

  if (isset($cfg['baigoValidate'])) { ?>
    <!--表单验证 js-->
    <script src="{:DIR_STATIC}lib/baigoValidate/3.1.1/baigoValidate.min.js" type="text/javascript"></script>
  <?php }

  if (isset($cfg['baigoSubmit'])) { ?>
    <!--表单 ajax 提交 js-->
    <script src="{:DIR_STATIC}lib/baigoSubmit/2.1.4/baigoSubmit.min.js" type="text/javascript"></script>
  <?php }

  if (isset($cfg['baigoDialog'])) { ?>
    <script src="{:DIR_STATIC}lib/baigoDialog/1.1.0/baigoDialog.min.js" type="text/javascript"></script>
  <?php }

  if (isset($cfg['baigoQuery'])) { ?>
    <script src="{:DIR_STATIC}lib/baigoQuery/1.0.0/baigoQuery.min.js" type="text/javascript"></script>
  <?php }

  if (isset($cfg['baigoCheckall'])) { ?>
    <!--全选 js-->
    <script src="{:DIR_STATIC}lib/baigoCheckall/2.0.0/baigoCheckall.min.js" type="text/javascript"></script>
  <?php }

  if (isset($cfg['md5'])) { ?>
    <script src="{:DIR_STATIC}lib/md5/md5.js" type="text/javascript"></script>
  <?php }

  if (isset($cfg['dad'])) { ?>
    <!--拖放 js-->
    <script src="{:DIR_STATIC}lib/dad/2.0.3/js/jquery.dad.min.js" type="text/javascript"></script>
  <?php }

  if (isset($cfg['upload'])) { ?>
    <script src="{:DIR_STATIC}lib/webuploader/0.1.5/webuploader.html5only.min.js" type="text/javascript"></script>
  <?php }

  if (isset($cfg['datetimepicker'])) { ?>
    <!--日历插件-->
    <script src="{:DIR_STATIC}lib/datetimepicker/2.3.0/jquery.datetimepicker.js" type="text/javascript"></script>
  <?php } ?>

  <script type="text/javascript">
  <?php if (isset($cfg['datetimepicker'])) { ?>
    var opts_datetimepicker = {
      lang: '<?php echo $lang->getCurrent(); ?>',
      i18n: {
        <?php echo $lang->getCurrent(); ?>: {
          months: [
            '<?php echo $lang->get('Jan', 'console.common'); ?>',
            '<?php echo $lang->get('Feb', 'console.common'); ?>',
            '<?php echo $lang->get('Mar', 'console.common'); ?>',
            '<?php echo $lang->get('Apr', 'console.common'); ?>',
            '<?php echo $lang->get('May', 'console.common'); ?>',
            '<?php echo $lang->get('Jun', 'console.common'); ?>',
            '<?php echo $lang->get('Jul', 'console.common'); ?>',
            '<?php echo $lang->get('Aug', 'console.common'); ?>',
            '<?php echo $lang->get('Sep', 'console.common'); ?>',
            '<?php echo $lang->get('Oct', 'console.common'); ?>',
            '<?php echo $lang->get('Nov', 'console.common'); ?>',
            '<?php echo $lang->get('Dec', 'console.common'); ?>'
          ],
          monthsShort: [
            '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'
          ],
          dayOfWeek: [
            '<?php echo $lang->get('Sun', 'console.common'); ?>',
            '<?php echo $lang->get('Mon', 'console.common'); ?>',
            '<?php echo $lang->get('Tue', 'console.common'); ?>',
            '<?php echo $lang->get('Wed', 'console.common'); ?>',
            '<?php echo $lang->get('Thu', 'console.common'); ?>',
            '<?php echo $lang->get('Fri', 'console.common'); ?>',
            '<?php echo $lang->get('Sat', 'console.common'); ?>'
          ]
        }
      },
      //timepicker: false,
      format: 'Y-m-d H:i',
      step: 30,
      mask: true
    };
  <?php }

  if (isset($cfg['captchaReload'])) { ?>
    function captchaReload(img_src) {
      img_src += '?' + new Date().getTime() + '=' + Math.random();

      $('.bg-captcha-img').attr('src', img_src);
    }
  <?php }

  if (isset($cfg['baigoDialog']) || isset($cfg['upload'])) { ?>
    var opts_dialog = {
      btn_text: {
        cancel: '<?php echo $lang->get('Cancel', 'console.common'); ?>',
        confirm: '<?php echo $lang->get('Confirm', 'console.common'); ?>',
        ok: '<?php echo $lang->get('OK', 'console.common'); ?>'
      }
    };
  <?php }

  if (isset($cfg['baigoSubmit'])) { ?>
    var opts_submit = {
      modal: {
        btn_text: {
          close: '<?php echo $lang->get('Close', 'console.common'); ?>',
          ok: '<?php echo $lang->get('OK', 'console.common'); ?>'
        }
      },
      msg_text: {
        submitting: '<?php echo $lang->get('Submitting', 'console.common'); ?>'
      }
    };
  <?php }

  if (isset($cfg['baigoClear'])) { ?>
    var opts_clear = {
      msg: {
        loading: '<?php echo $lang->get('Submitting', 'console.common'); ?>',
        complete: '<?php echo $lang->get('Complete', 'console.common'); ?>'
      }
    };
  <?php } ?>

  $(document).ready(function(){
    <?php if (isset($cfg['captchaReload'])) { ?>
      $('.bg-captcha-img').click(function(){
        var _src = $(this).data('src');
        captchaReload(_src);
      });
    <?php }

    if (isset($cfg['popover'])) { ?>
      $('[data-toggle="popover"]').popover({
        html: true,
        template: '<div class="popover bg-popover"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
      });
    <?php }

    if (isset($cfg['tooltip'])) { ?>
      $('[data-toggle="tooltip"]').tooltip({
        html: true,
        template: '<div class="tooltip bg-tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
      });
    <?php }

    if (isset($cfg['selectInput'])) { ?>
      $('.bg-select-input').click(function(){
        var _val    = $(this).data('value');
        var _target = $(this).data('target');
        $(_target).val(_val);
      });
    <?php }

    if (!isset($cfg['no_loading'])) { ?>
      $('#loading_mask').fadeOut();
    <?php } ?>
  });
  </script>
