  <script type="text/javascript">
  $(document).ready(function(){
    <?php foreach ($config['install']['data'][$route['ctrl']] as $key=>$value) {
      foreach ($value['lists'] as $key_data=>$value_data) { ?>
        $.ajax({
          url: '<?php echo $hrefRow['data-submit']; ?>' + new Date().getTime() + '/' + Math.random() + '/', //url
          //async: false, //设置为同步
          type: 'post',
          dataType: 'json',
          data: {
            type: '<?php echo $key; ?>',
            model: '<?php echo $value_data; ?>',
            <?php echo $token['name']; ?>: '<?php echo $token['value']; ?>'
          },
          timeout: 30000,
          error: function (result) {
            $('#<?php echo $key; ?>_<?php echo $value_data; ?> div').attr('class', 'text-danger');
            $('#<?php echo $key; ?>_<?php echo $value_data; ?> .bg-icon').html('<?php include($tpl_icon . 'times-circle' . BG_EXT_SVG); ?>');
            $('#<?php echo $key; ?>_<?php echo $value_data; ?> small').text(result.statusText);
          },
          success: function(_result){ //读取返回结果
            _rcode_status  = _result.rcode.substr(0, 1);

            switch (_rcode_status) {
              case 'y':
                _class  = 'text-success';
                _icon   = '<?php include($tpl_icon . 'check-circle' . BG_EXT_SVG); ?>';
              break;

              default:
                _class  = 'text-danger';
                _icon   = '<?php include($tpl_icon . 'times-circle' . BG_EXT_SVG); ?>';
              break;
            }

            $('#<?php echo $key; ?>_<?php echo $value_data; ?> div').attr('class', _class);
            $('#<?php echo $key; ?>_<?php echo $value_data; ?> .bg-icon').html(_icon);
            $('#<?php echo $key; ?>_<?php echo $value_data; ?> small').text(_result.msg);
          }
        });
      <?php }
    } ?>
  });
  </script>
