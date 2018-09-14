            </div>

            <div class="card-footer">
                <div class="text-right">
                    <?php echo PRD_SSO_POWERED, ' ';
                    if (BG_DEFAULT_UI == 'default') { ?>
                        <a href="<?php echo PRD_SSO_URL; ?>" target="_blank"><?php echo PRD_SSO_NAME; ?></a>
                    <?php } else {
                        echo BG_DEFAULT_UI, ' SSO ';
                    }
                    echo PRD_SSO_VER; ?>
                </div>
            </div>
        </div>

    </div>

    <script src="<?php echo BG_URL_STATIC; ?>lib/baigoSubmit/2.0.5/baigoSubmit.min.js" type="text/javascript"></script>
    <script src="<?php echo BG_URL_STATIC; ?>lib/baigoValidator/2.2.5/baigoValidator.min.js" type="text/javascript"></script>
    <script src="<?php echo BG_URL_STATIC; ?>lib/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

    <script type="text/javascript">
    $(document).ready(function(){
        $(".captchaBtn").click(function(){
            var imgSrc = "<?php echo BG_URL_MISC; ?>index.php?m=captcha&a=make&" + new Date().getTime() + "at" + Math.random();
            $(".captchaImg").attr('src', imgSrc);
        });
    });
    </script>

    <!--
        <?php echo PRD_SSO_POWERED, ' ';
        if (BG_DEFAULT_UI == 'default') {
            echo PRD_SSO_NAME;
        } else {
            echo BG_DEFAULT_UI, ' SSO ';
        }
        echo PRD_SSO_VER; ?>
    -->
