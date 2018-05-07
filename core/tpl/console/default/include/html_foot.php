    <?php if (isset($cfg['baigoValidator'])) { ?>
        <!--表单验证 js-->
        <script src="<?php echo BG_URL_STATIC; ?>lib/baigoValidator/2.2.5/baigoValidator.min.js" type="text/javascript"></script>
    <?php }

    if (isset($cfg['baigoSubmit'])) { ?>
        <!--表单 ajax 提交 js-->
        <script src="<?php echo BG_URL_STATIC; ?>lib/baigoSubmit/2.0.5/baigoSubmit.min.js" type="text/javascript"></script>
    <?php }

    if (isset($cfg['md5'])) { ?>
        <!--重新载入图片 js-->
        <script src="<?php echo BG_URL_STATIC; ?>lib/md5/md5.js" type="text/javascript"></script>
    <?php }

    if (isset($cfg['baigoCheckall'])) { ?>
        <!--全选 js-->
        <script src="<?php echo BG_URL_STATIC; ?>lib/baigoCheckall/1.0.3/baigoCheckall.min.js" type="text/javascript"></script>
    <?php }

    if (isset($cfg['upload'])) { ?>
        <script src="<?php echo BG_URL_STATIC; ?>lib/webuploader/0.1.5/webuploader.html5only.min.js" type="text/javascript"></script>
    <?php }

    if (isset($cfg['reloadImg'])) { ?>
        <script type="text/javascript">
        $(document).ready(function(){
            $(".captchaBtn").click(function(){
                var imgSrc = "<?php echo BG_URL_MISC; ?>index.php?m=captcha&a=make&" + new Date().getTime() + "at" + Math.random();
                $(".captchaImg").attr('src', imgSrc);
            });
        });
        </script>
    <?php }

    if (isset($cfg['tooltip'])) { ?>
        <script type="text/javascript">
        $(document).ready(function(){
            $("[data-toggle='tooltip']").tooltip({
                html: true,
                template: "<div class='tooltip bg-tooltip'><div class='tooltip-arrow'></div><div class='tooltip-inner'></div></div>"
            });
        });
        </script>
    <?php }

    if (isset($cfg['datetimepicker'])) { ?>
        <script src="<?php echo BG_URL_STATIC; ?>lib/datetimepicker/2.3.0/jquery.datetimepicker.js" type="text/javascript"></script>
        <script type="text/javascript">
        var opts_datetimepicker = {
            lang: "<?php echo $this->config['lang']; ?>",
            i18n: {
                <?php echo $this->config['lang']; ?>: {
                    months: ["<?php echo $this->lang['common']['date'][1] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][2] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][3] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][4] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][5] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][6] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][7] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][8] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][9] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][10] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][10] . $this->lang['common']['date'][1] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][10] . $this->lang['common']['date'][2] . $this->lang['common']['label']['month']; ?>"],
            monthsShort: ["<?php echo $this->lang['common']['date'][1]; ?>", "<?php echo $this->lang['common']['date'][2]; ?>", "<?php echo $this->lang['common']['date'][3]; ?>", "<?php echo $this->lang['common']['date'][4]; ?>", "<?php echo $this->lang['common']['date'][5]; ?>", "<?php echo $this->lang['common']['date'][6]; ?>", "<?php echo $this->lang['common']['date'][7]; ?>", "<?php echo $this->lang['common']['date'][8]; ?>", "<?php echo $this->lang['common']['date'][9]; ?>", "<?php echo $this->lang['common']['date'][10]; ?>", "<?php echo $this->lang['common']['date'][10] . $this->lang['common']['date'][1]; ?>", "<?php echo $this->lang['common']['date'][10] . $this->lang['common']['date'][2]; ?>"],
                    dayOfWeek: ["<?php echo $this->lang['common']['date'][0]; ?>", "<?php echo $this->lang['common']['date'][1]; ?>", "<?php echo $this->lang['common']['date'][2]; ?>", "<?php echo $this->lang['common']['date'][3]; ?>", "<?php echo $this->lang['common']['date'][4]; ?>", "<?php echo $this->lang['common']['date'][5]; ?>", "<?php echo $this->lang['common']['date'][6]; ?>"]
                }
            },
            //timepicker: false,
            format: "Y-m-d H:i",
            step: 30,
            mask: true
        };
        var opts_datepicker = {
            lang: "<?php echo $this->config['lang']; ?>",
            i18n: {
                <?php echo $this->config['lang']; ?>: {
                    months: ["<?php echo $this->lang['common']['date'][1] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][2] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][3] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][4] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][5] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][6] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][7] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][8] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][9] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][10] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][10] . $this->lang['common']['date'][1] . $this->lang['common']['label']['month']; ?>", "<?php echo $this->lang['common']['date'][10] . $this->lang['common']['date'][2] . $this->lang['common']['label']['month']; ?>"],
            monthsShort: ["<?php echo $this->lang['common']['date'][1]; ?>", "<?php echo $this->lang['common']['date'][2]; ?>", "<?php echo $this->lang['common']['date'][3]; ?>", "<?php echo $this->lang['common']['date'][4]; ?>", "<?php echo $this->lang['common']['date'][5]; ?>", "<?php echo $this->lang['common']['date'][6]; ?>", "<?php echo $this->lang['common']['date'][7]; ?>", "<?php echo $this->lang['common']['date'][8]; ?>", "<?php echo $this->lang['common']['date'][9]; ?>", "<?php echo $this->lang['common']['date'][10]; ?>", "<?php echo $this->lang['common']['date'][10] . $this->lang['common']['date'][1]; ?>", "<?php echo $this->lang['common']['date'][10] . $this->lang['common']['date'][2]; ?>"],
                    dayOfWeek: ["<?php echo $this->lang['common']['date'][0]; ?>", "<?php echo $this->lang['common']['date'][1]; ?>", "<?php echo $this->lang['common']['date'][2]; ?>", "<?php echo $this->lang['common']['date'][3]; ?>", "<?php echo $this->lang['common']['date'][4]; ?>", "<?php echo $this->lang['common']['date'][5]; ?>", "<?php echo $this->lang['common']['date'][6]; ?>"]
                }
            },
            timepicker: false,
            format: "Y-m-d",
            mask: true
        };
        </script>
    <?php } ?>

    <script type="text/javascript">
    $(document).ready(function(){
        $(".bg-accordion .collapse").on("shown.bs.collapse", function(){
            var _key = $(this).data("key");
            $("#bg-caret-" + _key).attr("class", "oi oi-chevron-right");
        });

        $(".bg-accordion .collapse").on("hidden.bs.collapse", function(){
            var _key = $(this).data("key");
            $("#bg-caret-" + _key).attr("class", "oi oi-chevron-bottom");
        });
    });
    </script>

    <script src="<?php echo BG_URL_STATIC; ?>lib/popper/1.12.9/popper.min.js" type="text/javascript"></script>
    <script src="<?php echo BG_URL_STATIC; ?>lib/bootstrap/4.0.0/js/bootstrap.min.js" type="text/javascript"></script>

    <!--
        <?php echo PRD_SSO_POWERED, ' ';
        if (BG_DEFAULT_UI == 'default') {
            echo PRD_SSO_NAME;
        } else {
            echo BG_DEFAULT_UI, ' SSO ';
        }
        echo PRD_SSO_VER; ?>
    -->

</body>
</html>