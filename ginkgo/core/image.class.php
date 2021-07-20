<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

// 图片处理类
class Image {

    public $error; // 错误
    public $rule = 'md5'; // 生成文件名规则 (函数名)
    public $quality = 90; // 图片质量
    public $imageMimes = array(); // 设定哪些 mime 属于图片
    public $thumbs  = array(); // 缩略图
    public $infoDst = array(); // 目标图片信息

    public $infoSrc = array(
        'width'    => 0,
        'height'   => 0,
        'name'     => '',
        'ext'      => '',
        'mime'     => '',
        'path'     => '',
    );

    protected static $instance; // 当前实例

    // 设定哪些 mime 属于图片
    private $imageMimesThis = array(
        'gif' => array(
            'image/gif',
        ),
        'jpg' => array(
            'image/jpeg',
            'image/pjpeg'
        ),
        'jpeg' => array(
            'image/jpeg',
            'image/pjpeg'
        ),
        'jpe' => array(
            'image/jpeg',
            'image/pjpeg'
        ),
        'png' => array(
            'image/png',
            'image/x-png'
        ),
        'bmp' => array(
            'image/bmp',
            'image/ms-bmp',
            'image/x-bmp',
            'image/x-bitmap',
            'image/x-xbitmap',
            'image/x-win-bitmap',
            'image/x-windows-bmp',
            'image/x-ms-bmp',
            'application/bmp',
            'application/x-bmp',
            'application/x-win-bitmap'
        ),
    );

    private $res_imgSrc; // 源图片资源
    private $res_imgDst; // 目的图片资源
    private $obj_file; // 文件对象
    private $imageExts; // 设定哪些扩展名属于图片

    /** 构造函数
     * __construct function.
     *
     * @access protected
     * @return void
     */
    protected function __construct($imageMimes = array()) {
        $this->obj_file = File::instance();
        $this->config($imageMimes);
    }

    protected function __clone() { }

    /** 初始化实例
     * instance function.
     *
     * @access public
     * @static
     * @return 当前类的实例
     */
    public static function instance() {
        if (Func::isEmpty(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }


    // 配置 since 0.2.0
    public function config($imageMimes = array()) {
        $_arr_imageMimes    = Config::get('image'); // 取得图片配置
        $_arr_imageMimesDo  = array_replace_recursive($this->imageMimesThis, $this->imageMimes, $imageMimes); // 合并配置
        $this->imageMimes   = $_arr_imageMimesDo;
        $this->imageExts    = array_keys($_arr_imageMimesDo);
    }


    /** 打开一个图片文件
     * open function.
     *
     * @access public
     * @param string $path 路径
     * @return void
     */
    public function open($path) {
        $_mix_imgSrc = $this->openProcess($path);

        if ($_mix_imgSrc === false) { // 如果打开失败
            return false;
        }

        $this->infoSrc['path'] = $path;
        $this->res_imgSrc      = $_mix_imgSrc['resource'];

        unset($_mix_imgSrc['resource']);

        $_arr_fileInfoDo = array_replace_recursive($this->infoSrc, $_mix_imgSrc);
        $this->infoSrc   = $_arr_fileInfoDo;

        return $_arr_fileInfoDo;
    }


    // 水印 since 0.2.0
    public function stamp($stamp, $font = false, $size = false, $posi = false, $angle = 0, $pct = 100) {
        if (is_resource($this->res_imgDst)) { // 如果目的图片资源存在, 则处理
            $_res_imgDst = $this->res_imgDst;
        } else if (is_resource($this->res_imgSrc)) {
            $_res_imgDst = $this->res_imgSrc;
        } else {
            $this->error = 'Missing destination image resource';

            return false;
        }

        $type = 'text';

        if (Func::isEmpty($font) && File::fileHas($stamp)) {
            $type = 'image';
        }

        $_arr_stamp = array();

        switch ($type) {
            case 'text':
                $_arr_stamp = $this->txtStampInit($font, $stamp);
            break;

            default:
                $_mix_infoStampSrc = $this->imgSizeProcess($stamp);

                if ($_mix_infoStampSrc === false) { // 如果检测失败
                    return $this;
                }

                if (isset($_mix_infoStampSrc['width']) && !Func::isEmpty($_mix_infoStampSrc['width'])) {
                    $_arr_stamp['width'] = $_mix_infoStampSrc['width'];
                }

                if (isset($_mix_infoStampSrc['height']) && !Func::isEmpty($_mix_infoStampSrc['height'])) {
                    $_arr_stamp['height'] = $_mix_infoStampSrc['height'];
                }
            break;
        }

        $_arr_sizeStamp = $this->stampSizeProcess($size, $_arr_stamp);
        $_arr_posiStamp = $this->stampPosiProcess($posi, $_res_imgDst);
        $_res_imgStamp  = $this->createImgBg($_arr_sizeStamp['width'], $_arr_sizeStamp['height'], 'image/png', true, false); // 创建空白水印图片

        if (!$_res_imgStamp) {
            return $this;
        }

        switch ($type) {
            case 'text':
                $_color_text = imagecolorallocate($_res_imgStamp, $_arr_stamp['color']['r'], $_arr_stamp['color']['g'], $_arr_stamp['color']['b']); // 设置字体颜色

                //print_r($_arr_sizeStamp);

                imagettftext($_res_imgStamp, $_arr_stamp['size'], 0, $_arr_sizeStamp['x'], $_arr_sizeStamp['y'], $_color_text, $_arr_stamp['file'], $stamp); // 在空白水印图片上水平画一行字符串
            break;

            default:
                $_mix_imgStampSrc = $this->openProcess($stamp);

                if ($_mix_imgStampSrc === false) { // 如果打开失败
                    return $this;
                }

                $_res_imgStampSrc = $_mix_imgStampSrc['resource'];

                unset($_mix_imgStampSrc['resource']);

                imagecopy($_res_imgStamp, $_res_imgStampSrc, 0, 0, 0, 0, $_mix_infoStampSrc['width'], $_mix_infoStampSrc['height']); // 将源水印复制到空白水印图片并缩小

                empty($_res_imgStampSrc) || imagedestroy($_res_imgStampSrc);
            break;
        }

        if ($angle > 0) {
            $_color_alpha  = imagecolorallocatealpha($_res_imgStamp, 255, 255, 255, 127); // 创建透明色
            $_res_imgStamp = imagerotate($_res_imgStamp, $angle, $_color_alpha, 1); // 旋转

            $_arr_sizeStamp['width']  = imagesx($_res_imgStamp);
            $_arr_sizeStamp['height'] = imagesy($_res_imgStamp);
        }

        imagecopymerge($_res_imgDst, $_res_imgStamp, $_arr_posiStamp['x'], $_arr_posiStamp['y'], 0, 0, $_arr_sizeStamp['width'], $_arr_sizeStamp['height'], $pct); // 将合并后的水印复制到源图片

        empty($_res_imgStamp) || imagedestroy($_res_imgStamp);

        $this->res_imgDst = $_res_imgDst;

        return $this;
    }


    /** 裁切图片
     * crop function.
     *
     * @access public
     * @param int $width 宽度
     * @param int $height 高度
     * @param int $x_src (default: 0) 源裁切 x 坐标
     * @param int $y_src (default: 0) 源裁切 y 坐标
     * @param mixed $width_src (default: false) 源宽度 (如为 false, 则采用源宽度)
     * @param mixed $height_src (default: false) 源高度 (如为 false, 则采用源高度)
     * @return 当前实例
     */
    public function crop($width, $height, $x_src = 0, $y_src = 0, $width_src = false, $height_src = false) {
        if (!is_resource($this->res_imgSrc)) {
            $this->error = 'Missing source image resource';
            return $this;
        }

        if (!$width_src) {
            $width_src = $this->infoSrc['width']; // 源文件宽度 (如为 false, 则采用源宽度)
        }

        if (!$height_src) {
            $height_src = $this->infoSrc['height']; // 源文件高度 (如为 false, 则采用源高度)
        }

        $_res_imgBg = $this->createImgBg($width, $height); // 创建背景

        if (!$_res_imgBg) {
            return $this;
        }

        /*print_r($width_src);
        print_r(' || ');
        print_r($height_src);*/

        if (!imagecopyresampled($_res_imgBg, $this->res_imgSrc, 0, 0, $x_src, $y_src, $width, $height, $width_src, $height_src)) { // 将源图片合并到背景并缩小
            $this->error = 'Failed to resize';
            return $this;
        }

        $this->res_imgDst = $_res_imgBg;

        if (Func::isEmpty($this->infoDst)) {
            $this->infoDst = $this->dstProcess($this->infoSrc['path'], $width, $height, 'crop'); // 路径处理
        }

        return $this;
    }


    /** 生成缩略图
     * thumb function.
     *
     * @access public
     * @param int $width (default: 100) 宽度
     * @param int $height (default: 100) 高度
     * @param string $type (default: 'ratio') 缩略图类型
     * @return void
     */
    public function thumb($width = 100, $height = 100, $type = 'ratio') {
        $_arr_thumbSize   = $this->thumbSizeCalc($width, $height, $type); // 计算缩略图尺寸

        //print_r($_arr_thumbSize);

        if (Func::isEmpty($this->infoDst)) {
            $this->infoDst = $this->dstProcess($this->infoSrc['path'], $width, $height, $type); // 路径处理
        }

        /*switch ($type) {
            case 'ratio':
                $width  = false;
                $height = false;
            break;
        }*/

        $this->crop($_arr_thumbSize['width'], $_arr_thumbSize['height'], $_arr_thumbSize['x_src'], $_arr_thumbSize['y_src'], $_arr_thumbSize['width_src'], $_arr_thumbSize['height_src']); // 裁切

        return $this;
    }


    // 输出图片
    public function output($path = null, $mime = false, $quality = false, $interlace = 1) {
        $_return = false;

        if (is_resource($this->res_imgDst)) { // 如果目的图片资源存在, 则处理
            $_res_imgDst = $this->res_imgDst;
        } else if (is_resource($this->res_imgSrc)) {
            $_res_imgDst = $this->res_imgSrc;
        } else {
            $this->error = 'Missing destination image resource';

            return false;
        }

        if ($mime === false && isset($this->infoSrc['mime']) && !Func::isEmpty($this->infoSrc['mime'])) {
            $mime = $this->infoSrc['mime'];
        }

        if (Func::isEmpty($mime)) {
            $this->error = 'Missing destination extension';

            return false;
        }

        if ($quality === false) {
            $quality = $this->quality;
        }

        $quality = (int)$quality;

        if ($quality < 1) {
            $quality = 90;
        }

        if ($path === null) {
            ob_start(); // 打开输出控制缓冲
            ob_implicit_flush(0); // 关闭绝对刷送
        }

        switch ($mime) { //生成最终图片
            case 'image/jpeg':
            case 'image/pjpeg':
                imageinterlace($_res_imgDst, $interlace); // jpg 隔行扫描
                $_return = imagejpeg($_res_imgDst, $path, $quality);
            break;

            case 'image/gif':
                $_return = imagegif($_res_imgDst, $path);
            break;

            case 'image/png':
            case 'image/x-png':
                $quality = intval($quality / 10); // 计算 png 图片质量

                if ($quality < 1) {
                    $quality = 9;
                }

                $_return = imagepng($_res_imgDst, $path, $quality);
            break;

            case 'image/bmp':
            case 'image/ms-bmp':
            case 'image/x-bmp':
            case 'image/x-bitmap':
            case 'image/x-xbitmap':
            case 'image/x-win-bitmap':
            case 'image/x-windows-bmp':
            case 'image/x-ms-bmp':
            case 'application/bmp':
            case 'application/x-bmp':
            case 'application/x-win-bitmap':
                $_return = imagewbmp($_res_imgDst, $path);
            break;

            default: // 不支持的图片类型
                $this->error = 'Unsupported image type';

                return false;
            break;
        }

        if ($path === null) {
            $_content = ob_get_clean(); // 取得输出缓冲内容并清理关闭
        }

        imagedestroy($_res_imgDst); // 销毁目的图片资源

        if (!$_return) {
            $this->error = 'Failed to output image';

            return false;
        }

        if ($path === null) {
            $_content = Response::create($_content); // 用缓冲内容实例响应类
            $_content->contentType($mime); // 设置输出 mime (头)

            return $_content; // 返回响应实例
        }

        return $_return;
    }


    /** 保存图片
     * save function.
     *
     * @access public
     * @param mixed $dir (default: false) 保存目录 (默认与当前图片相同)
     * @param mixed $name (default: false) 保存文件名 (默认与当前图片相同)
     * @param bool $quality (default: false) 图片质量
     * @param int $interlace (default: 1) 是否打开 jpg 隔行扫描
     * @return void
     */
    public function save($dir = false, $name = false, $quality = false, $interlace = 1) {
        $_str_ext = false;

        if ($dir === false || Func::isEmpty($dir)) {
            if (isset($this->infoDst['path_dir'])) {
                $dir = $this->infoDst['path_dir']; // 采用当前图片目录
            } else {
                $this->error = 'Missing destination path';

                return false;
            }
        }

        if (Func::isEmpty($dir)) {
            $this->error = 'Missing destination path';

            return false;
        }

        if (!$this->obj_file->dirMk($dir)) { // 创建目的目录
            $this->error = 'Failed to create directory';

            return false;
        }

        if ($name === false || Func::isEmpty($name)) {
            if (isset($this->infoDst['name'])) {
                $name = $this->infoDst['name']; // 采用当前图片名称
            } else {
                $name = $this->genFilename($name);
            }
        }

        if (Func::isEmpty($name)) { // 如果没有文件名则报错
            $this->error = 'Missing destination filename';

            return false;
        }

        $_str_path  = Func::fixDs($dir) . $name; // 拼合最终路径
        $_str_mime  = $this->getMime($_str_path);

        if (Func::isEmpty($_str_mime)) {
            $this->error = 'Missing destination mime';

            return false;
        }

        if (!$this->output($_str_path, $_str_mime, $quality, $interlace)) {
            return false;
        }

        $this->infoDst = array();

        return $_str_path;
    }


    /** 批量生成缩略图
     * batThumb function.
     *
     * @access public
     * @param array $thumbRows (default: array()) 缩略图数组
     * @return void
     */
    public function batThumb($thumbRows) {
        $_return = true;

        if (is_array($thumbRows) && !Func::isEmpty($thumbRows)) {
            foreach ($thumbRows as $_key=>$_value) { // 遍历缩略图
                if (!isset($_value['thumb_width'])) {
                    $_value['thumb_width'] = 100; // 默认宽度
                }

                if (!isset($_value['thumb_height'])) {
                    $_value['thumb_height'] = 100; // 默认高度
                }

                if (isset($_value['thumb_type_value'])) {
                    $_value['thumb_type'] = $_value['thumb_type_value'];
                } else if (isset($_value['thumb_type'])) {
                    $_value['thumb_type'] = $_value['thumb_type'];
                } else {
                    $_value['thumb_type'] = 'ratio'; // 默认类型
                }

                if (!isset($_value['thumb_quality'])) {
                    $_value['thumb_quality'] = $this->quality; // 默认质量
                }

                $_return = $this->thumb($_value['thumb_width'], $_value['thumb_height'], $_value['thumb_type'])->save(false, false, $_value['thumb_quality']);

                if (!$_return) {
                    break;
                }

                $this->thumbs[] = $_return;
            }

            if (!$_return) {
                foreach ($thumbRows as $_key=>$_value) {
                    $_path_dst = $this->dstProcess($this->infoSrc['path'], $_value['thumb_width'], $_value['thumb_height'], $_value['thumb_type']);

                    $this->obj_file->fileDelete($_path_dst['path']);
                }
            }
        }

        return $_return;
    }


    /** 获取所有缩略图路径
     * getThumbs function.
     *
     * @access public
     * @return 所有缩略图路径数组
     */
    public function getThumbs() {
        return $this->thumbs;
    }


    /** 获取 mime
     * getMime function.
     *
     * @access public
     * @param string $path (default: '') 路径
     * @param string $mime (default: false) 严格获取 mime
     * @return mime
     */
    public function getMime($path, $strict = false) {
        $_return = false;

        if ($strict === true || $strict === 'true') {
            $_mix_info = $this->imgSizeProcess($path);

            if (isset($_mix_info['mime']) && !Func::isEmpty($_mix_info['mime'])) {
                $_return = $_mix_info['mime'];
            }
        } else {
            $_str_ext = $this->getExt($path);

            if (isset($this->imageMimes[$_str_ext])) {
                $_str_mime = $this->imageMimes[$_str_ext][0];

                $_return = $_str_mime;
            }
        }

        return $_return;
    }


    /** 获取扩展名
     * getExt function.
     *
     * @access public
     * @param string $path 路径
     * @param mixed $mime (default: false) mime 类型
     * @return 扩展名
     */
    public function getExt($path, $mime = false) {
        $_str_ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)); //取得扩展名

        if ($mime) {
            // 扩展名与 mime 不符的情况下, 反向查找, 如果存在, 则更改扩展名
            if (!isset($this->imageMimes[$_str_ext]) || !in_array($mime, $this->imageMimes[$_str_ext])) {
                foreach ($this->imageMimes as $_key_allow=>$_value_allow) {
                    if (in_array($mime, $_value_allow)) {
                        return $_key_allow;
                    }
                }
            }
        }

        return $_str_ext;
    }


    // 获取信息
    public function getInfo($name = '') {
        $_mix_retrun = '';

        if (Func::isEmpty($name)) {
            $_mix_retrun = $this->infoSrc;
        } else if (isset($this->infoSrc[$name])) {
            $_mix_retrun = $this->infoSrc[$name];
        }

        return $_mix_retrun;
    }

    // 获取错误
    public function getError() {
        return $this->error;
    }


    /** 设置生成文件名规则 (函数名)
     * rule function.
     *
     * @access public
     * @param mixed $rule
     * @return 当前实例
     */
    public function rule($rule) {
        $this->rule = $rule;

        return $this;
    }


    // 打开图片处理 since 0.2.0
    private function openProcess($path) {
        if (!File::fileHas($path)) { // 如果不是文件
            $this->error = 'Source image not found';

            return false;
        }

        $_str_ext = $this->getExt($path); // 获取扩展名

        if (!$this->verifyFile($_str_ext)) { // 验证是否为图片
            return false;
        }

        $_str_mime  = $this->getMime($path, true); // 获取 mime
        $_str_ext   = $this->getExt($path, $_str_mime); // 获取扩展名

        if (!$this->verifyFile($_str_ext, $_str_mime)) { // 严格验证是否为图片
            return false;
        }

        $_str_name    = basename($path);
        $_img_content = file_get_contents($path);
        $_res_image   = imagecreatefromstring($_img_content);

        if (!is_resource($_res_image)) {
            $this->error = 'Faild to open source image'; // 打开失败

            return false;
        }

        $_num_width  = imagesx($_res_image);
        $_num_height = imagesy($_res_image);

        return array(
            'resource' => $_res_image,
            'ext'      => $_str_ext,
            'mime'     => $_str_mime,
            'name'     => $_str_name,
            'width'    => $_num_width,
            'height'   => $_num_height,
        );
    }


    /** 创建背景
     * createImgBg function.
     *
     * @access private
     * @return 图片资源
     */
    private function createImgBg($width, $height, $mime = false, $transparent = true, $savealpha = true) {
        $_res_imgBg = imagecreatetruecolor($width, $height); // 创建目标图片资源

        if (!$_res_imgBg) {
            $this->error = 'Failed to create a new true color image';
            return false;
        }

        if ($mime === false && isset($this->infoSrc['mime']) && !Func::isEmpty($this->infoSrc['mime'])) {
            $mime = $this->infoSrc['mime'];
        }

        switch ($mime) { // 创建图片对象
            case 'image/gif':
                $_color_bg = imagecolorallocate($_res_imgBg, 255, 255, 255); // 为图片资源分配背景色
                if ($_color_bg === -1) {
                    $this->error = 'Failed to allocate a color';
                    return false;
                }
                if (!imagefill($_res_imgBg, 0, 0, $_color_bg)) { // 用背景色填充
                    $this->error = 'Failed to flood fill';
                    return false;
                }
                if ($transparent) {
                    if (imagecolortransparent($_res_imgBg, $_color_bg) === -1) { // 将背景色设为透明
                        $this->error = 'No transparent color';
                        return false;
                    }
                }
            break;

            case 'image/png':
            case 'image/x-png':
                $_color_alpha = imagecolorallocatealpha($_res_imgBg, 255, 255, 255, 127); // 为图片资源分配背景色 (包含 alpha 通道), 并设为完全透明
                if (!$_color_alpha) {
                    $this->error = 'Failed to allocate a color + alpha';
                    return false;
                }
                if (!imagefill($_res_imgBg, 0, 0, $_color_alpha)) { // 用背景色填充
                    $this->error = 'Failed to flood fill';
                    return false;
                }
                if ($transparent) {
                    if (imagecolortransparent($_res_imgBg, $_color_alpha) === -1) { // 将背景色设为透明
                        $this->error = 'No transparent color';
                        return false;
                    }
                }
                if ($savealpha) {
                    if (!imagealphablending($_res_imgBg, false)) { // 关闭混色模式
                        $this->error = 'Failed to set the blending mode';
                        return false;
                    }
                    if (!imagesavealpha($_res_imgBg, true)) { // 保存 PNG 图像时保存完整的 alpha 通道信息
                        $this->error = 'Failed to save full alpha';
                        return false;
                    }
                }
            break;

            default:
                $_color_bg = imagecolorallocate($_res_imgBg, 255, 255, 255); // 为图片资源分配背景色
                if ($_color_bg === -1) {
                    $this->error = 'Failed to allocate a color';
                    return false;
                }
                if (!imagefill($_res_imgBg, 0, 0, $_color_bg)) { // 用背景色填充
                    $this->error = 'Failed to flood fill';
                    return false;
                }
            break;
        }

        return $_res_imgBg;
    }


    /** 验证文件是否允许
     * verifyFile function.
     *
     * @access protected
     * @param string $ext 扩展名
     * @param string $mime (default: '') mime
     * @return bool
     */
    private function verifyFile($ext, $mime = '') {
        if (Func::isEmpty($mime)) { // 如指定 mime 参数, 则直接验证扩展名
            if (!in_array($ext, $this->imageExts)) {
                $this->error = 'Not an image';

                return false;
            }
        } else { // 严格检验
            if (!Func::isEmpty($this->imageMimes)) {
                if (!isset($this->imageMimes[$ext])) { // 该扩展名的 mime 数组是否存在
                    $this->error = 'MIME check failed';

                    return false;
                }

                if (!in_array($mime, $this->imageMimes[$ext])) { // 是否允许
                    $this->error = 'Not an image';

                    return false;
                }
            }
        }

        return true;
    }


    // 图片类型检测处理 since 0.2.0
    private function imgSizeProcess($path) {
        $_arr_info = array(
            'width'    => '',
            'height'   => '',
            'type'     => '',
            'mime'     => '',
        );

        $_arr_size = getimagesize($path); // 取得图片详细信息

        //设置图像信息
        if (isset($_arr_size[0])) {
            $_arr_info['width']  = $_arr_size[0];
        }

        if (isset($_arr_size[1])) {
            $_arr_info['height'] = $_arr_size[1];
        }

        if (isset($_arr_size[2])) {
            $_arr_info['type']   = image_type_to_extension($_arr_size[2], false); // 返回图片类型
        }

        if (isset($_arr_size['mime'])) {
            $_arr_info['mime']   = $_arr_size['mime'];
        }

        return $_arr_info;
    }


    // 文字水印初始化 since 0.2.0
    private function txtStampInit($font, $string) {
        $_arr_stamp = array();

        if (is_array($font)) {
            $_arr_stamp = $font;
        } else if (is_numeric($font)) {
            $_arr_stamp['size'] = $font;
        }

        if (!isset($_arr_stamp['color'])) {
            $_arr_stamp['color'] = array(0, 0, 0);
        }

        if (!isset($_arr_stamp['size'])) {
            $_arr_stamp['size'] = 9;
        }

        if (!isset($_arr_stamp['file'])) {
            $_arr_stamp['file'] = $this->fontProcess();
        }

        $_num_fontSize        = $this->ptToPx($_arr_stamp['size']);
        $_num_textLen         = mb_strlen($string);
        $_arr_stamp['color']  = $this->colorProcess($_arr_stamp['color']);
        $_arr_stamp['width']  = $_num_textLen * $_num_fontSize + $_num_fontSize;
        $_arr_stamp['height'] = $_num_fontSize + $_num_fontSize;

        return $_arr_stamp;
    }


    // 水印尺寸处理 since 0.2.0
    private function stampSizeProcess($size, $stamp) {
        $_arr_size = array();

        if (is_array($size)) {
            if (isset($size[0])) {
                $_arr_size['width']  = $size[0];
            } else if (isset($size['width'])) {
                $_arr_size['width']  = $size['width'];
            }

            if (isset($size[1])) {
                $_arr_size['height'] = $size[1];
            } else if (isset($size['height'])) {
                $_arr_size['height'] = $size['height'];
            }
        } else if (is_numeric($size)) {
            $_arr_size['width']  = $size;
            $_arr_size['height'] = $size;
        }

        if (Func::isEmpty($_arr_size)) {
            $_arr_size['width']  = $stamp['width'];
            $_arr_size['height'] = $stamp['height'];
        }

        if (!isset($_arr_size['width']) && isset($stamp['width'])) {
            $_arr_size['width'] = $stamp['width'];
        }

        if (!isset($_arr_size['height']) && isset($stamp['height'])) {
            $_arr_size['height'] = $stamp['height'];
        }

        $_arr_size['x']      = $_arr_size['width'] / $_arr_size['height']; // 计算长宽比
        $_arr_size['y']      = $_arr_size['height'] / 1.4; // 根据图片高度计算 y 轴位置

        $_arr_size['width']  = (int)($_arr_size['width']);
        $_arr_size['height'] = (int)($_arr_size['height']);
        $_arr_size['x']      = (int)($_arr_size['x']);
        $_arr_size['y']      = (int)($_arr_size['y']);

        return $_arr_size;
    }


    // 水印位置处理 since 0.2.0
    private function stampPosiProcess($posi, $imgDst) {
        $_arr_posi = array();

        if (is_array($posi)) {
            if (isset($posi[0])) {
                $_arr_posi['x'] = $posi[0];
            } else if (isset($posi['x'])) {
                $_arr_posi['x'] = $posi['x'];
            }

            if (isset($posi[1])) {
                $_arr_posi['y'] = $posi[1];
            } else if (isset($posi['y'])) {
                $_arr_posi['y'] = $posi['y'];
            }
        } else if (is_string($posi)) {
            switch ($posi) {
                case 'rt':
                    $_arr_posi['x'] = -10;
                    $_arr_posi['y'] = 10;
                break;

                case 'lb':
                    $_arr_posi['x'] = 10;
                    $_arr_posi['y'] = -10;
                break;

                case 'rb':
                    $_arr_posi['x'] = -10;
                    $_arr_posi['y'] = -10;
                break;

                default:
                    $_arr_posi['x'] = 10;
                    $_arr_posi['y'] = 10;
                break;
            }
        } else if (is_numeric($posi)) {
            $_arr_posi['x'] = $posi;
            $_arr_posi['y'] = $posi;
        }

        if (!isset($_arr_posi['x'])) {
            $_arr_posi['x'] = 10;
        }

        if (!isset($_arr_posi['y'])) {
            $_arr_posi['y'] = 10;
        }

        if ($_arr_posi['x'] < 0) {
            $_dst_width     = imagesx($imgDst);
            $_arr_posi['x'] = $_dst_width - abs($_arr_posi['x']);
        }

        if ($_arr_posi['y'] < 0) {
            $_dst_height    = imagesy($imgDst);
            $_arr_posi['y'] = $_dst_height - abs($_arr_posi['x']);
        }

        $_arr_posi['x'] = (int)($_arr_posi['x']);
        $_arr_posi['y'] = (int)($_arr_posi['y']);

        return $_arr_posi;
    }


    // 颜色处理
    private function colorProcess($color) {
        $_arr_color = array();

        if (is_array($color)) {
            $_arr_color = $color;
        } else if (is_string($color)) {
            if (stristr('rgb(', $color) && strpos(',', $color)) {
                $_str_color = trim($color, 'rgb(..)');
                $_arr_color = explode(',', $_str_color);
            } else if (stristr('(', $color) && strpos(',', $color)) {
                $_str_color = trim($color, '(..)');
                $_arr_color = explode(',', $_str_color);
            } else if (strpos(',', $color)) {
                $_arr_color = explode(',', $_str_color);
            } else {
                $_str_color = str_replace('#', '', $color);

                if (strlen($_str_color) == 3) {
                    $_arr_color['r'] = hexdec(substr($_str_color, 0, 1) . substr($_str_color, 0, 1));
                    $_arr_color['g'] = hexdec(substr($_str_color, 1, 1) . substr($_str_color, 1, 1));
                    $_arr_color['b'] = hexdec(substr($_str_color, 2, 1) . substr($_str_color, 2, 1));
                } else if (strlen($_str_color) == 6) {
                    $_arr_color['r'] = hexdec(substr($_str_color, 0, 2));
                    $_arr_color['g'] = hexdec(substr($_str_color, 2, 2));
                    $_arr_color['b'] = hexdec(substr($_str_color, 4, 2));
                }
            }
        }

        $_arr_color = $this->rgbProcess($_arr_color);

        return $_arr_color;
    }

    // rgb 处理
    private function rgbProcess($color) {
        $_arr_color = array();

        if (isset($color[0])) {
            $_arr_color['r'] = $color[0];
        } else if (isset($color['r'])) {
            $_arr_color['r'] = $color['r'];
        }

        if (isset($color[1])) {
            $_arr_color['g'] = $color[1];
        } else if (isset($color['g'])) {
            $_arr_color['g'] = $color['g'];
        }

        if (isset($color[2])) {
            $_arr_color['b'] = $color[2];
        } else if (isset($color['b'])) {
            $_arr_color['b'] = $color['b'];
        }

        if (!isset($_arr_color['r'])) {
            $_arr_color['r'] = 0;
        }

        if (!isset($_arr_color['g'])) {
            $_arr_color['g'] = 0;
        }

        if (!isset($_arr_color['b'])) {
            $_arr_color['b'] = 0;
        }

        if (!ctype_digit($_arr_color['r'])) {
            $_arr_color['r'] = hexdec($_arr_color['r']);
        }

        if (!ctype_digit($_arr_color['g'])) {
            $_arr_color['g'] = hexdec($_arr_color['g']);
        }

        if (!ctype_digit($_arr_color['b'])) {
            $_arr_color['b'] = hexdec($_arr_color['b']);
        }

        $_arr_color['r'] = (int)($_arr_color['r']);
        $_arr_color['g'] = (int)($_arr_color['g']);
        $_arr_color['b'] = (int)($_arr_color['b']);

        return $_arr_color;
    }

    // 生成文件名
    private function genFilename($name = true) {
        if ($name === true) { // 参数为 true 时, 按规则生成文件名
            if (is_callable($this->rule)) {
                $_str_type = $this->rule;
            } else {
                $_str_type = 'md5';
            }

            if (isset($_SERVER['REQUEST_TIME_FLOAT'])) {
                $_tm_time = $_SERVER['REQUEST_TIME_FLOAT'];
            } else {
                $_tm_time = GK_NOW;
            }

            $name = call_user_func($_str_type, $_tm_time) . '.' . $this->infoSrc['ext'];
        } else if ($name === false) { // 参数为 false 时, 使用源文件名
            $name = $this->infoSrc['name'];
        }

        // 指定为字符串时, 直接使用

        return $name;
    }


    /** 目的处理
     * dstProcess function.
     *
     * @access private
     * @param string $path 源路径
     * @param string $width (default: '') 宽度
     * @param string $height (default: '') 高度
     * @param string $type (default: '') 裁切类型
     * @return 名称数组
     */
    private function dstProcess($path, $width = '', $height = '', $type = '') {
        $width  = (int)$width;
        $height = (int)$height;
        $type   = (string)$type;

        $_arr_pathinfo  = pathinfo($path);
        $_str_pathDir   = Func::fixDs($_arr_pathinfo['dirname']);
        $_str_name      = $_arr_pathinfo['filename'];

        if (!Func::isEmpty($width)) {
            $_str_name .= '_' . $width;
        }

        if (!Func::isEmpty($height)) {
            $_str_name .= '_' . $height;
        }

        if (!Func::isEmpty($type)) {
            $_str_name .= '_' . $type;
        }

        $_str_name .= '.' . $this->infoSrc['ext'];

        return array(
            'path_dir'  => $_str_pathDir,
            'name'      => $_str_name,
            'path'      => $_str_pathDir . $_str_name,
            'width'     => $width,
            'height'    => $height,
            'type'      => $type,
        );
    }


    /** 计算缩略图尺寸
     * thumbSizeCalc function.
     *
     * @access public
     * @param int $width 宽度
     * @param int $height 高度
     * @param string $type (default: 'ratio') 类型（默认等比例）
     * @return 图片尺寸数组
     */
    private function thumbSizeCalc($width_dst = 0, $height_dst = 0, $type = 'ratio') {
        $width_dst   = (int)$width_dst; // 目的宽度
        $height_dst  = (int)$height_dst; // 目的高度
        $_width_src  = (int)$this->infoSrc['width']; // 源图宽度
        $_height_src = (int)$this->infoSrc['height']; // 源图高度

        switch ($type) {
            case 'ratio': // 按比例缩小
                if ($width_dst < 1) {
                    $width_dst = $_width_src * 1000;
                }

                if ($height_dst < 1) {
                    $height_dst = $_height_src * 1000;
                }

                if ($_width_src > $_height_src) { // 横向
                    $_scale          = $_height_src / $_width_src; // 计算比例
                    $_width_ratio    = $width_dst;
                    $_height_ratio   = $_width_ratio * $_scale; // 按比例计算高度

                    if ($_height_ratio > $height_dst) { // 如缩小后, 高度大于设定高度, 则按照高度重新计算
                        $_height_ratio  = $height_dst;
                        $_width_ratio   = $_height_ratio / $_scale;
                    }
                } else { // 纵向
                    $_scale          = $_width_src / $_height_src; // 计算比例
                    $_height_ratio   = $height_dst;
                    $_width_ratio    = $_height_ratio * $_scale; // 按比例计算宽度



                    if ($_width_ratio > $width_dst) { // 如缩小后, 宽度大于设定宽度, 则按照宽度重新计算
                        $_width_ratio   = $width_dst;
                        $_height_ratio  = $_width_ratio / $_scale;
                    }
                }

                $_x_src      = 0;
                $_y_src      = 0;
                $_width      = $_width_ratio;
                $_height     = $_height_ratio;
                $_width_src  = false;
                $_height_src = false;
            break;

            default:
                if ($width_dst > $height_dst) { // 目的横向
                    $_scale       = $width_dst / $height_dst; // 计算目的比例

                    if ($_height_src > $height_dst && $width_dst > $_width_src) { // 目的横向交叉
                        $_width_crop  = $_width_src;
                        $_height_crop = $_width_crop / $_scale;

                        $_x_src = 0;
                        $_y_src = ($_height_src - $_height_crop) / 2;
                    } else if ($height_dst > $_height_src && $_width_src > $width_dst) { // 目的纵向交叉
                        $_height_crop = $_height_src;
                        $_width_crop  = $_height_crop * $_scale; // 按比例计算宽度

                        $_x_src = ($_width_src - $width_dst) / 2;
                        $_y_src = 0;
                    } else { // 目的在内
                        $_height_crop = $_height_src;
                        $_width_crop  = $_height_crop * $_scale; // 按比例计算宽度

                        $_x_src = ($_width_src - $_width_crop) / 2;
                        $_y_src = 0;

                        if ($_width_crop > $_width_src) { // 如计算后, 设定宽度小于源宽度, 则按照宽度重新计算
                            $_width_crop  = $_width_src;
                            $_height_crop = $_width_crop / $_scale; // 按比例计算高度
                            $_x_src = 0;
                            $_y_src = ($_height_src - $_height_crop) / 2;
                        }
                    }
                } else {
                    $_scale = $height_dst / $width_dst; // 计算目的比例

                    if ($height_dst > $_height_src && $_width_src > $width_dst) { // 目的纵向交叉
                        $_height_crop = $_height_src;
                        $_width_crop  = $_height_crop / $_scale;

                        $_x_src = ($_width_src - $width_dst) / 2;
                        $_y_src = 0;
                    } else if ($_height_src > $height_dst && $width_dst > $_width_src) { // 目的横向向交叉
                        $_width_crop  = $_width_src;
                        $_height_crop = $_width_crop * $_scale;

                        $_x_src = 0;
                        $_y_src = ($_height_src - $height_dst) / 2;
                    } else { // 目的在内
                        $_width_crop  = $_width_src;
                        $_height_crop = $_width_crop * $_scale;
                        $_x_src = 0;
                        $_y_src = ($_height_src - $_height_crop) / 2;

                        if ($_height_crop > $_height_src) { // 如计算后, 设定高度小于源高度, 则按照源高度重新计算
                            $_height_crop = $_height_src;
                            $_width_crop  = $_height_crop / $_scale; // 按比例计算宽度
                            $_x_src = ($_width_src - $_width_crop) / 2;
                            $_y_src = 0;
                        }
                    }
                }

                $_width      = $width_dst;
                $_height     = $height_dst;
                $_width_src  = $_width_crop;
                $_height_src = $_height_crop;
            break;
        }

        return array(
            'width'      => $_width, // 宽度
            'height'     => $_height, // 高度
            'width_src'  => $_width_src, // 源宽度
            'height_src' => $_height_src, // 源高度
            'x_src'      => $_x_src, // 裁切 x 坐标
            'y_src'      => $_y_src, // 裁切 y 坐标
        );
    }


    private function ptToPx($pt) {
        return round($pt / 0.76, 0);
    }


    /** 字体处理
     * fontProcess function.
     *
     * @access private
     * @return 字体路径
     */
    private function fontProcess() {
        $_str_fontPath = GK_PATH_CORE . 'font' . DS; // 设置系统字体目录
        $_arr_font     = $this->obj_file->dirList($_str_fontPath, 'ttf'); // 列出字体种类

        $_fonts = array();

        foreach ($_arr_font as $_key=>$_value) {
            if ($_value['type'] == 'file') {
                $_fonts[] = $_value['path']; // 拼合字体池
            }
        }

        return $_fonts[array_rand($_fonts)]; // 随机返回字体路径
    }

    public function __destruct() {
        empty($this->res_imgSrc) || imagedestroy($this->res_imgSrc) || empty($this->res_imgDst) || imagedestroy($this->res_imgDst);
    }
}
