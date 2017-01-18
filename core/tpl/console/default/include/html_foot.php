    <?php if (isset($cfg["baigoValidator"])) { ?>
        <!--表单验证 js-->
        <script src="<?php echo BG_URL_STATIC; ?>lib/baigoValidator/baigoValidator.min.js" type="text/javascript"></script>
    <?php } ?>

    <?php if (isset($cfg["baigoSubmit"])) { ?>
        <!--表单 ajax 提交 js-->
        <script src="<?php echo BG_URL_STATIC; ?>lib/baigoSubmit/baigoSubmit.min.js" type="text/javascript"></script>
    <?php } ?>

    <?php if (isset($cfg["reloadImg"])) { ?>
        <!--重新载入图片 js-->
        <script src="<?php echo BG_URL_STATIC; ?>lib/reloadImg.js" type="text/javascript"></script>
    <?php } ?>

    <?php if (isset($cfg["md5"])) { ?>
        <!--重新载入图片 js-->
        <script src="<?php echo BG_URL_STATIC; ?>lib/md5.js" type="text/javascript"></script>
    <?php } ?>

    <?php if (isset($cfg["baigoCheckall"])) { ?>
        <!--全选 js-->
        <script src="<?php echo BG_URL_STATIC; ?>lib/baigoCheckall/baigoCheckall.min.js" type="text/javascript"></script>
    <?php } ?>

    <?php if (isset($cfg["upload"])) { ?>
        <script src="<?php echo BG_URL_STATIC; ?>lib/webuploader/webuploader.html5only.min.js" type="text/javascript"></script>
    <?php } ?>

    <?php if (isset($cfg["tooltip"])) { ?>
        <script type="text/javascript">
        $(document).ready(function(){
            $("[data-toggle='tooltip']").tooltip({
                html: true,
                template: "<div class='tooltip bg-tooltip'><div class='tooltip-arrow'></div><div class='tooltip-inner'></div></div>"
            });
        });
        </script>
    <?php } ?>

    <?php if (isset($cfg["datetimepicker"])) { ?>
        <script src="<?php echo BG_URL_STATIC; ?>lib/datetimepicker/jquery.datetimepicker.js" type="text/javascript"></script>
        <script type="text/javascript">
        var opts_datetimepicker = {
            lang: "<?php echo $this->config["lang"]; ?>",
            i18n: {
                <?php echo $this->config["lang"]; ?>: {
                    months: ["<?php echo $this->lang["digit"]["1"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["2"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["3"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["4"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["5"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["6"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["7"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["8"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["9"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["10"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["10"]; ?><?php echo $this->lang["digit"]["1"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["10"]; ?><?php echo $this->lang["digit"]["2"]; ?><?php echo $this->lang["label"]["month"]; ?>"],
            monthsShort: ["<?php echo $this->lang["digit"]["1"]; ?>", "<?php echo $this->lang["digit"]["2"]; ?>", "<?php echo $this->lang["digit"]["3"]; ?>", "<?php echo $this->lang["digit"]["4"]; ?>", "<?php echo $this->lang["digit"]["5"]; ?>", "<?php echo $this->lang["digit"]["6"]; ?>", "<?php echo $this->lang["digit"]["7"]; ?>", "<?php echo $this->lang["digit"]["8"]; ?>", "<?php echo $this->lang["digit"]["9"]; ?>", "<?php echo $this->lang["digit"]["10"]; ?>", "<?php echo $this->lang["digit"]["10"]; ?><?php echo $this->lang["digit"]["1"]; ?>", "<?php echo $this->lang["digit"]["10"]; ?><?php echo $this->lang["digit"]["2"]; ?>"],
                    dayOfWeek: ["<?php echo $this->lang["digit"]["0"]; ?>", "<?php echo $this->lang["digit"]["1"]; ?>", "<?php echo $this->lang["digit"]["2"]; ?>", "<?php echo $this->lang["digit"]["3"]; ?>", "<?php echo $this->lang["digit"]["4"]; ?>", "<?php echo $this->lang["digit"]["5"]; ?>", "<?php echo $this->lang["digit"]["6"]; ?>"]
                }
            },
            //timepicker: false,
            format: "Y-m-d H:i",
            step: 30,
            mask: true
        };
        var opts_datepicker = {
            lang: "<?php echo $this->config["lang"]; ?>",
            i18n: {
                <?php echo $this->config["lang"]; ?>: {
                    months: ["<?php echo $this->lang["digit"]["1"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["2"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["3"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["4"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["5"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["6"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["7"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["8"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["9"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["10"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["10"]; ?><?php echo $this->lang["digit"]["1"]; ?><?php echo $this->lang["label"]["month"]; ?>", "<?php echo $this->lang["digit"]["10"]; ?><?php echo $this->lang["digit"]["2"]; ?><?php echo $this->lang["label"]["month"]; ?>"],
            monthsShort: ["<?php echo $this->lang["digit"]["1"]; ?>", "<?php echo $this->lang["digit"]["2"]; ?>", "<?php echo $this->lang["digit"]["3"]; ?>", "<?php echo $this->lang["digit"]["4"]; ?>", "<?php echo $this->lang["digit"]["5"]; ?>", "<?php echo $this->lang["digit"]["6"]; ?>", "<?php echo $this->lang["digit"]["7"]; ?>", "<?php echo $this->lang["digit"]["8"]; ?>", "<?php echo $this->lang["digit"]["9"]; ?>", "<?php echo $this->lang["digit"]["10"]; ?>", "<?php echo $this->lang["digit"]["10"]; ?><?php echo $this->lang["digit"]["1"]; ?>", "<?php echo $this->lang["digit"]["10"]; ?><?php echo $this->lang["digit"]["2"]; ?>"],
                    dayOfWeek: ["<?php echo $this->lang["digit"]["0"]; ?>", "<?php echo $this->lang["digit"]["1"]; ?>", "<?php echo $this->lang["digit"]["2"]; ?>", "<?php echo $this->lang["digit"]["3"]; ?>", "<?php echo $this->lang["digit"]["4"]; ?>", "<?php echo $this->lang["digit"]["5"]; ?>", "<?php echo $this->lang["digit"]["6"]; ?>"]
                }
            },
            timepicker: false,
            format: "Y-m-d",
            mask: true
        };
        </script>
    <?php } ?>

    <script src="<?php echo BG_URL_STATIC; ?>lib/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo BG_URL_STATIC; ?>lib/baigoAccordion/baigoAccordion.min.js" type="text/javascript"></script>

    <!-- <?php echo PRD_SSO_POWERED; ?> <?php if (BG_DEFAULT_UI == "default") { ?><?php echo PRD_SSO_NAME; ?><?php } else { ?><?php echo BG_DEFAULT_UI; ?> SSO<?php } ?> <?php echo PRD_SSO_VER; ?> -->

</body>
</html>