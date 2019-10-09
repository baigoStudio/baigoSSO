<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------验证码类-------------*/
class Captcha {
    protected static $instance;
    private $chars     = 'abdefhijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    private $secKey    = 'ginkgo';
    private $secCode; //验证码
    private $offset = array(1, 2); //阴影偏移

    private $config = array(
        'length'    => 4, //长度
        'expire'    => 1800, //过期时间
        'font_file' => '', //字体
        'font_size' => 20, //字号
        'width'     => 0, //图片宽度
        'height'    => 0, //图片高度
        'reset'     => true, //验证成功后是否重置
        'noise'     => true, //是否加入干扰
    );

    private $image; //图形资源

    protected function __construct($config = array()) {
        if (!Func::isEmpty($config)) {
            $this->config = array_replace_recursive($this->config, $config);
        }
    }

    protected function __clone() {

    }

    public static function instance($config = array()) {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static($config);
        }
        return static::$instance;
    }

    //设置验证码
    public function set($font_size = 20, $length = 4) {
        $this->config['length']     = $length;
        $this->config['font_size']  = $font_size;

        if (Func::isEmpty($this->config['font_file'])) {
            $this->config['font_file']  = $this->fontProcess();
        }

        if ($this->config['width'] == 0) {
            $this->config['width'] = $this->config['font_size'] * ($this->config['length'] + 2);
        }

        if ($this->config['height'] == 0) {
            $this->config['height'] = $this->config['font_size'] * 2;
        }
    }

    //生成验证码
    private function createCode() {
        $_len = strlen($this->chars) - 1;
        $_arr_secCode = array();
        $_str_secCode = '';
        for ($_iii = 0; $_iii < $this->config['length']; ++$_iii) {
            $_code          = $this->chars[mt_rand(0, $_len)];
            $_arr_secCode[] = $_code;
            $_str_secCode  .= $_code;
        }

        //print_r($_arr_secCode);
        //file_put_contents('code.txt', $_str_secCode);

        $this->secCodeStr   = $_str_secCode;
        $this->secCode      = $_arr_secCode;
    }

    //生成图片及背景
    private function createBg() {
        $this->image    = imagecreate($this->config['width'], $this->config['height']);
        $_colorBg       = imagecolorallocate($this->image, mt_rand(250, 255), mt_rand(250, 255), mt_rand(250, 255));
        imagefilledrectangle($this->image, 0, $this->config['height'], $this->config['width'], 0, $_colorBg);
    }

    //生成文字
    private function createFont() {
        $_tmp = $this->config['width'] / $this->config['length'];
        foreach ($this->secCode as $_key=>$_value) {
            $_colorFont     = imagecolorallocate($this->image, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));
            $_colorShadow   = imagecolorallocate($this->image, 255, 255, 255);
            $_angle         = mt_rand(-15, 15);
            $_x             = $_tmp * $_key + mt_rand(1, 5);
            $_y             = $this->config['height'] / 1.4;
            imagettftext($this->image, $this->config['font_size'], $_angle, $_x + $this->offset[0], $_y + $this->offset[1], $_colorShadow, $this->config['font_file'], $_value); //投影
            imagettftext($this->image, $this->config['font_size'], $_angle, $_x, $_y, $_colorFont, $this->config['font_file'], $_value);
        }
    }

    //加入干扰
    private function createNoise() {
        //干扰线条
        foreach ($this->secCode as $_key=>$_value) {
            $_colorLine = imagecolorallocate($this->image, mt_rand(0, 150), mt_rand(0, 150), mt_rand(0, 150));
            imageline($this->image, mt_rand(0, $this->config['width']), mt_rand(0, $this->config['height']), mt_rand(0, $this->config['width']), mt_rand(0, $this->config['height']), $_colorLine);
        }

        //干扰象素
        for($_iii = 0; $_iii < $this->config['width']; ++$_iii) {
            $_colorPix = imagecolorallocate($this->image, rand(0, 150), rand(0, 150), rand(0, 150));
            imagesetpixel($this->image, rand(0, $this->config['width']), rand(0, $this->config['height']), $_colorPix);
        }
    }

    //输出
    private function output() {
        ob_start();
        ob_implicit_flush(0);
        imagepng($this->image);
        $_content = ob_get_clean();
        imagedestroy($this->image);

        $_content = Response::create($_content);
        $_content->contentType('image/png');

        return $_content;
    }

    //验证
    public function check($code, $id = '', $del = true) {
        $_key = $this->authcode($this->secKey) . $id;
        // 验证码不能为空
        $_arr_secode = Session::get($_key, '');
        if (Func::isEmpty($code) || Func::isEmpty($_arr_secode)) {
            return false;
        }
        // session 过期
        if (GK_NOW - $_arr_secode['verify_time'] > $this->config['expire']) {
            Session::delete($_key);
            return false;
        }

        if ($this->authcode(strtolower($code)) == $_arr_secode['verify_code']) {
            if ($this->config['reset'] && $del) {
                Session::delete($_key);
            }
            return true;
        }

        return false;
    }

    //对外生成
    public function create($id = '') {
        $this->createCode();
        $this->createBg();

        if ($this->config['noise']) {
            $this->createNoise();
        }

        $this->createFont();

        $_key   = $this->authcode($this->secKey);
        $_code  = $this->authcode(strtolower($this->secCodeStr));
        $_arr_secode['verify_code'] = $_code; // 把校验码保存到session
        $_arr_secode['verify_time'] = GK_NOW; // 验证码创建时间

        Session::set($_key . $id, $_arr_secode);

        return $this->output();
    }

    /* 加密验证码 */
    private function authcode($str) {
        $_key = substr(md5($this->secKey), 5, 8);
        $str  = substr(md5($str), 8, 10);
        return md5($_key . $str);
    }

    private function fontProcess() {
        $_str_fontPath = GK_PATH_CORE . 'captcha' . DS . 'font' . DS;
        $_arr_font     = File::instance()->dirList($_str_fontPath, 'ttf');

        $_fonts = array();

        foreach ($_arr_font as $_key=>$_value) {
            if ($_value['type'] == 'file') {
                $_fonts[] = $_value['path'];
            }
        }

        return $_fonts[array_rand($_fonts)];
    }

    function __destruct() { //构造函数
        unset($this);
    }
}
