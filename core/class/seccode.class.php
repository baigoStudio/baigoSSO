<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

/*-------------验证码类-------------*/
class CLASS_SECCODE {
    private $chars = "abdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789";

    private $code; //验证码
    private $len; //长度

    private $fontFile; //字体
    private $fontSize; //字号
    private $width; //图片宽度
    private $height; //图片高度

    private $colorPix; //干扰颜色
    private $colorFont; //字体颜色
    private $colorShadow; //字体阴影

    private $image; //图形资源
    private $back; //背景资源

    //设置验证码
    public function secSet($sec_size = 20, $sec_len = 4, $sec_font = "FetteSteinschrift.ttf") {
        $this->len        = $sec_len;
        $this->fontSize   = $sec_size;
        $this->fontFile   = BG_PATH_FONT . $sec_font;
        $this->width      = $this->fontSize * ($this->len + 2);
        $this->height     = $this->fontSize * 2;
    }

    //生成验证码
    private function createCode() {
        $_len = strlen($this->chars) - 1;
        for ($_i=0;$_i<$this->len;$_i++) {
            $this->code .= $this->chars[mt_rand(0, $_len)];
        }
    }

    //生成图片及背景
    private function createBg() {
        $this->image  = imagecreate($this->width, $this->height);
        $this->back   = imagecolorallocate($this->image, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
        imagefilledrectangle($this->image, 0, $this->height, $this->width, 0, $this->back);
    }

    //生成文字
    private function createFont() {
        $_tmp = $this->width / $this->len;
        for ($_i=0;$_i<$this->len;$_i++) {
            $this->colorFont     = imagecolorallocate($this->image, mt_rand(0, 140), mt_rand(0, 140), mt_rand(0, 140));
            $this->colorShadow   = imagecolorallocate($this->image, 255, 255, 255);
            $_angle              = mt_rand(-15, 15);
            $_x                  = $_tmp * $_i + mt_rand(1, 5);
            $_y                  = $this->height / 1.4;
            imagettftext($this->image, $this->fontSize, $_angle, $_x + 1, $_y + 1, $this->colorShadow, $this->fontFile, $this->code[$_i]); //投影
            imagettftext($this->image, $this->fontSize, $_angle, $_x, $_y, $this->colorFont, $this->fontFile, $this->code[$_i]);
        }
    }

    //加入干扰
    private function createLine() {
        //干扰线条
        for ($_i=0;$_i<$this->len;$_i++) {
            $this->colorPix = imagecolorallocate($this->image, mt_rand(0, 150), mt_rand(0, 150), mt_rand(0, 150));
            imageline($this->image, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $this->colorPix);
        }

        //干扰象素
        for($_i=0;$_i<$this->width;$_i++) {
            $this->colorPix = imagecolorallocate($this->image, rand(0, 150), rand(0, 150), rand(0, 150));
            imagesetpixel($this->image, rand(0, $this->width), rand(0, $this->height), $this->colorPix);
        }
    }

    //输出
    private function secOutput() {
        imagepng($this->image);
        imagedestroy($this->image);
    }

    //对外生成
    public function secDo() {
        $this->createCode();
        $this->createBg();
        $this->createLine();
        $this->createFont();
        $this->secOutput();
        fn_session("seccode", "mk", strtolower($this->code));
    }
}
