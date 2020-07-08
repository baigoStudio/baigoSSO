<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 验证码
class Captcha {
    protected static $instance; // 当前实例
    public $chars     = 'abdefhijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'; // 字符池
    public $secKey    = 'ginkgo'; // 安全码
    public $offset    = array(1, 2); // 阴影偏移
    private $captcha; // 验证码

    private $config = array( // 默认配置
        'length'    => 4, // 长度
        'expire'    => 1800, // 过期时间
        'font_file' => '', // 字体
        'font_size' => 20, // 字号
        'width'     => 0, // 图片宽度
        'height'    => 0, // 图片高度
        'reset'     => true, // 验证成功后是否重置
        'noise'     => true, // 是否加入干扰
    );

    private $image; // 图形资源

    protected function __construct($config = array()) {
        if (!Func::isEmpty($config)) {
            $this->config = array_replace_recursive($this->config, $config); // 合并配置
        }
    }

    protected function __clone() {

    }

    /** 实例化
     * instance function.
     *
     * @access public
     * @static
     * @param array $config (default: array()) 配置
     * @return 当前类的实例
     */
    public static function instance($config = array()) {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static($config);
        }
        return static::$instance;
    }

    /** 设置
     * set function.
     *
     * @access public
     * @param int $font_size (default: 20) 字号
     * @param int $length (default: 4) 长度
     * @return void
     */
    public function set($font_size = 20, $length = 4) {
        $this->config['length']     = $length;
        $this->config['font_size']  = $font_size;

        if (Func::isEmpty($this->config['font_file'])) { // 如果没有指定字体, 则从系统字体库随机取
            $this->config['font_file']  = $this->fontProcess();
        }

        if ($this->config['width'] == 0) { // 如果没有指定图片宽度, 则根据字号计算
            $this->config['width'] = $this->config['font_size'] * ($this->config['length'] + 2);
        }

        if ($this->config['height'] == 0) { // 如果没有指定图片高度, 则根据字号计算
            $this->config['height'] = $this->config['font_size'] * 2;
        }
    }

    /** 对外生成
     * create function.
     *
     * @access public
     * @param string $id (default: '') 验证码 id (多个验证码时适用)
     * @return 图片
     */
    public function create($id = '') {
        $this->createCode(); // 生成验证码
        $this->createBg(); // 生成背景

        if ($this->config['noise']) { // 假如干扰
            $this->createNoise();
        }

        $this->createFont(); // 生成文字

        $_key   = $this->authcode($this->secKey); // 加密安全码
        $_code  = $this->authcode(strtolower($this->captchaStr)); // 加密验证码
        $_arr_secode['verify_code'] = $_code; // 把校验码保存到 session
        $_arr_secode['verify_time'] = GK_NOW; // 验证码创建时间

        Session::set($_key . $id, $_arr_secode);

        return $this->output();
    }


    /** 验证
     * check function.
     *
     * @access public
     * @param mixed $code 用户输入的码
     * @param string $id (default: '') 验证码 id (多个验证码时适用)
     * @param bool $del (default: true) 验证后是否删除 (默认删除)
     * @return 验证结果 (bool)
     */
    public function check($code, $id = '', $del = true) {
        $_key = $this->authcode($this->secKey) . $id; // 拼合安全码和 id 然后加密
        // 输入验证码为空或者会话中验证码为空
        $_arr_secode = Session::get($_key, '');
        if (Func::isEmpty($code) || Func::isEmpty($_arr_secode)) {
            return false;
        }
        // session 过期
        if (GK_NOW - $_arr_secode['verify_time'] > $this->config['expire']) {
            Session::delete($_key);
            return false;
        }

        // 对比验证码
        if ($this->authcode(strtolower($code)) == $_arr_secode['verify_code']) {
            if ($this->config['reset'] && $del) { // 根据参数删除会话中的验证码
                Session::delete($_key);
            }
            return true;
        }

        return false;
    }


    // 生成验证码
    private function createCode() {
        $_len = strlen($this->chars) - 1; // 取得字符池长度
        $_arr_captcha = array(); // 验证码数组
        $_str_captcha = ''; // 验证码字符串
        for ($_iii = 0; $_iii < $this->config['length']; ++$_iii) { // 根据长度循环
            $_code          = $this->chars[mt_rand(0, $_len)]; // 随机取字符
            $_arr_captcha[] = $_code; // 拼接验证码数组
            $_str_captcha  .= $_code; // 拼接验证码字符串
        }

        //print_r($_arr_captcha);
        //file_put_contents('code.txt', $_str_captcha);

        $this->captchaStr   = $_str_captcha;
        $this->captcha      = $_arr_captcha;
    }

    // 生成图片及背景
    private function createBg() {
        $this->image    = imagecreate($this->config['width'], $this->config['height']); // 生成图片资源
        $_colorBg       = imagecolorallocate($this->image, mt_rand(250, 255), mt_rand(250, 255), mt_rand(250, 255)); // 为图片资源分配背景颜色
        imagefilledrectangle($this->image, 0, $this->config['height'], $this->config['width'], 0, $_colorBg); // 向图片资源画一矩形并用上一行获得的颜色填充
    }

    // 生成文字
    private function createFont() {
        $_tmp = $this->config['width'] / $this->config['length']; // 计算长宽比
        foreach ($this->captcha as $_key=>$_value) { // 遍历验证码数组
            // 以下操作针对验证码中的单个字符
            $_colorFont     = imagecolorallocate($this->image, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100)); // 为图片资源分配字体颜色
            $_colorShadow   = imagecolorallocate($this->image, 255, 255, 255); // 为图片资源分配阴影颜色
            $_angle         = mt_rand(-15, 15); // 随机产生角度值
            $_x             = $_tmp * $_key + mt_rand(1, 5); // 随机产生 x 轴位置
            $_y             = $this->config['height'] / 1.4; // 根据图片高度计算 y 轴位置
            imagettftext($this->image, $this->config['font_size'], $_angle, $_x + $this->offset[0], $_y + $this->offset[1], $_colorShadow, $this->config['font_file'], $_value); // 向图片资源写入文本(投影)
            imagettftext($this->image, $this->config['font_size'], $_angle, $_x, $_y, $_colorFont, $this->config['font_file'], $_value); // 向图片资源写入文本
        }
    }

    // 加入干扰
    private function createNoise() {
        // 干扰线条
        foreach ($this->captcha as $_key=>$_value) { // 遍历验证码数组
            $_colorLine = imagecolorallocate($this->image, mt_rand(0, 150), mt_rand(0, 150), mt_rand(0, 150)); // 为图片资源分配干扰线条颜色
            imageline($this->image, mt_rand(0, $this->config['width']), mt_rand(0, $this->config['height']), mt_rand(0, $this->config['width']), mt_rand(0, $this->config['height']), $_colorLine); // 向图片资源画干扰线条
        }

        // 干扰象素
        for ($_iii = 0; $_iii < $this->config['width']; ++$_iii) {
            $_colorPix = imagecolorallocate($this->image, rand(0, 150), rand(0, 150), rand(0, 150)); // 为图片资源分配干扰象素颜色
            imagesetpixel($this->image, rand(0, $this->config['width']), rand(0, $this->config['height']), $_colorPix); // 向图片资源画干扰象素
        }
    }

    /** 输出
     * output function.
     *
     * @access private
     * @return 图片
     */
    private function output() {
        ob_start(); // 打开输出控制缓冲
        ob_implicit_flush(0); // 关闭绝对刷送
        imagepng($this->image); // 以 PNG 格式将图片资源输出到浏览器
        $_content = ob_get_clean(); // 取得输出缓冲内容并清理关闭
        imagedestroy($this->image); // 销毁图片资源

        $_content = Response::create($_content); // 用缓冲内容实例响应类
        $_content->contentType('image/png'); // 设置输出 mime (头)

        return $_content; // 返回响应实例
    }


    /** 加密
     * authcode function.
     *
     * @access private
     * @param mixed $str
     * @return 加密后字符串
     */
    private function authcode($str) {
        $_key = substr(md5($this->secKey), 5, 8);
        $str  = substr(md5($str), 8, 10);
        return md5($_key . $str);
    }

    /** 字体处理
     * fontProcess function.
     *
     * @access private
     * @return 字体路径
     */
    private function fontProcess() {
        $_str_fontPath = GK_PATH_CORE . 'captcha' . DS . 'font' . DS; // 设置系统字体目录
        $_arr_font     = File::instance()->dirList($_str_fontPath, 'ttf'); // 列出字体种类

        $_fonts = array();

        foreach ($_arr_font as $_key=>$_value) {
            if ($_value['type'] == 'file') {
                $_fonts[] = $_value['path']; // 拼合字体池
            }
        }

        return $_fonts[array_rand($_fonts)]; // 随机返回字体路径
    }

    // 析构函数
    function __destruct() {
        unset($this);
    }
}
