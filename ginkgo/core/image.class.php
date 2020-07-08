<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access Denied');

// 图片处理类
class Image extends File {

    protected static $instance; // 当前实例

    public $quality = 90; // 图片质量
	private $res_imgSrc; // 原始图片资源
	private $pathSrc; // 原始图片路径
	private $pathDst = array(); // 目标图片路径
    private $thumbs  = array(); // 缩略图

    // 图片信息
    protected $info = array(
        'width'  => 100,
        'height' => 100,
        'mime'   => '',
        'ext'    => '',
    );

    /** 构造函数
     * __construct function.
     *
     * @access protected
     * @return void
     */
    protected function __construct() {
        $_arr_config        = Config::get('image'); // 取得图片配置
        $this->mimeRows     = $_arr_config; // 设定哪些 mime 属于图片
        $this->imageExts    = array_keys($_arr_config); // 设定哪些扩展名属于图片
    }

    protected function __clone() {

    }

    /** 初始化实例
     * instance function.
     *
     * @access public
     * @static
     * @return 当前类的实例
     */
    public static function instance() {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }


    /** 打开一个图片文件
     * open function.
     *
     * @access public
     * @param string $path_src 路径
     * @return void
     */
    public function open($path_src) {
        if (!Func::isFile($path_src)) { // 如果不是文件
            $this->error = 'Image not found';

            return false;
        }

        $_str_ext = $this->getExt($path_src); // 获取扩展名

        if (!$this->checkFile($_str_ext)) { // 验证是否为图片
            return false;
        }

        $_str_mime  = $this->getMime($path_src); // 获取 mime
        $_str_ext   = $this->getExt($path_src, $_str_mime); // 获取扩展名

        if (!$this->checkFile($_str_ext, $_str_mime)) { // 严格验证是否为图片
            return false;
        }

        switch ($_str_mime) { // 创建图片对象
            case 'image/jpeg':
            case 'image/pjpeg':
                $_res_imgSrc = imagecreatefromjpeg($path_src);
            break;

            case 'image/gif':
                $_res_imgSrc = imagecreatefromgif($path_src);
            break;

            case 'image/png':
            case 'image/x-png':
                $_res_imgSrc = imagecreatefrompng($path_src);
            break;

            case 'image/bmp':
            case 'image/x-ms-bmp':
            case 'image/x-windows-bmp':
                $_res_imgSrc = imagecreatefromwbmp($path_src);
            break;

            default:
                $this->error = 'Unsupported MIME'; // 不支持的类型

                return false;
            break;
        }

        $this->pathSrc      = $path_src;
        $this->res_imgSrc   = $_res_imgSrc;

        $this->info['mime'] = $_str_mime;
        $this->info['ext']  = $_str_ext;

        return $this->info;
    }


    /** 取得图片宽度
     * width function.
     *
     * @access public
     * @return 图片宽度
     */
    public function width() {
        return $this->info['width'];
    }

    /** 取得图片高度
     * width function.
     *
     * @access public
     * @return 图片高度
     */
    public function height() {
        return $this->info['height'];
    }

    /** 获取扩展名
     * ext function.
     *
     * @access public
     * @return 扩展名
     */
    public function ext() {
        return $this->info['ext'];
    }

    /** 获取 mime
     * mime function.
     *
     * @access public
     * @return mime
     */
    public function mime() {
        return $this->info['mime'];
    }


    /** 获取图像尺寸数组
     * size function.
     *
     * @access public
     * @return 0 - 图像宽度, 1 - 图像高度
     */
    public function size() {
        return array(
            $this->info['width'],
            $this->info['height'],
        );
    }


    /** 获取错误信息
     * getError function.
     *
     * @access public
     * @return 错误信息
     */
    function getError() {
        return $this->error;
    }

    /** 获取所有缩略图路径
     * getThumbs function.
     *
     * @access public
     * @return 所有缩略图路径数组
     */
    function getThumbs() {
        return $this->thumbs;
    }

    /** 获取 mime
     * getMime function.
     *
     * @access public
     * @param string $path (default: '') 路径
     * @param string $mime (default: '') 兼容用, 本类中无意义
     * @return mime
     */
    function getMime($path = '', $mime = '') {
        try {
            $_info = getimagesize($path); // 取得图片详细信息

            //设置图像信息
            $this->info = array(
                'width'  => $_info[0],
                'height' => $_info[1],
                'type'   => image_type_to_extension($_info[2], false), // 返回图片类型
                'mime'   => $_info['mime'],
            );
        } catch (\Exception $excpt) {
            $this->error = $excpt->getMessage();

            return false;
        }

        return $_info['mime'];
    }


    /** 裁切图片
     * crop function.
     *
     * @access public
     * @param int $width 宽度
     * @param int $height 高度
     * @param int $x_crop (default: 0) 裁切 x 坐标
     * @param int $y_crop (default: 0) 裁切 y 坐标
     * @param mixed $width_save (default: false) 要保存的宽度 (如为 false, 则采用 $width 参数)
     * @param mixed $height_save (default: false) 要保存的高度 (如为 false, 则采用 $height 参数)
     * @param mixed $width_src (default: false) 原始文件宽度 (如为 false, 则采用原始宽度)
     * @param mixed $height_src (default: false) 原始文件高度 (如为 false, 则采用原始高度)
     * @return 当前实例
     */
    function crop($width, $height, $x_crop = 0, $y_crop = 0, $width_save = false, $height_save = false, $width_src = false, $height_src = false) {
        if (!$width_save) {
            $width_save = $width; // 要保存的宽度 (如为 false, 则采用 $width 参数)
        }

        if (!$height_save) {
            $height_save = $height; // 要保存的高度 (如为 false, 则采用 $height 参数)
        }

        if (!$width_src) {
            $width_src = $this->info['width']; // 原始文件宽度 (如为 false, 则采用原始宽度)
        }

        if (!$height_src) {
            $height_src = $this->info['height']; // 原始文件高度 (如为 false, 则采用原始高度)
        }

        $_res_imgDst = imagecreatetruecolor($width_save, $height_save); // 创建目标图片资源

        if (!$_res_imgDst) {
            $this->error = 'Failed to Create a new true color image';
            return false;
        }

        $_res_imgDst = $this->transparentProcess($_res_imgDst); // 透明处理

        if (!$_res_imgDst) {
            return false;
        }

        if (!imagecopyresampled($_res_imgDst, $this->res_imgSrc, 0, 0, $x_crop, $y_crop, $width, $height, $width_src, $height_src)) { // 直接缩小
            $this->error = 'Failed to resize';
            return false;
        }

        $this->res_imgDst = $_res_imgDst;

        if (Func::isEmpty($this->pathDst)) {
            $this->pathDst = $this->nameProcess($this->pathSrc, $width, $height, 'crop'); // 路径处理
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
    function thumb($width = 100, $height = 100, $type = 'ratio') {
        $_arr_thumbSize   = $this->sizeProcess($width, $height, $type); // 计算缩略图尺寸

        if (Func::isEmpty($this->pathDst)) {
            $this->pathDst = $this->nameProcess($this->pathSrc, $width, $height, $type); // 路径处理
        }

        switch ($type) {
            case 'ratio':
                $width  = false;
                $height = false;
            break;
        }

        if ($width >= $this->info['width'] && $height >= $this->info['height']) { //如果源图片小于目标缩略图, 则只是拷贝
            return $this;
        } else {
            $this->crop($_arr_thumbSize['width'], $_arr_thumbSize['height'], $_arr_thumbSize['x_crop'], $_arr_thumbSize['y_crop'], $width, $height); // 裁切
        }

        return $this;
    }


    /** 批量生成缩略图
     * batThumb function.
     *
     * @access public
     * @param array $thumbRows (default: array()) 缩略图数组
     * @return void
     */
    function batThumb($thumbRows = array()) {
        $_return = true;

        if (!Func::isEmpty($thumbRows)) {
            foreach ($thumbRows as $_key=>$_value) { // 遍历缩略图
                if (!isset($_value['thumb_width'])) {
                    $_value['thumb_width'] = 100; // 默认宽度
                }

                if (!isset($_value['thumb_height'])) {
                    $_value['thumb_height'] = 100; // 默认高度
                }

                if (!isset($_value['thumb_type'])) {
                    $_value['thumb_type'] = 'ratio'; // 默认类型
                }

                if (!isset($_value['thumb_quality'])) {
                    $_value['thumb_quality'] = $this->quality; // 默认质量
                }

                $_return = $this->thumb($_value['thumb_width'], $_value['thumb_height'], $_value['thumb_type'])->save(false, false, false, $_value['thumb_quality']);

                if (!$_return) {
                    break;
                }

                $this->thumbs[] = $_return;
            }

            if (!$_return) {
                foreach ($thumbRows as $_key=>$_value) {
                    $_path_dst = $this->nameProcess($this->pathSrc, $_value['thumb_width'], $_value['thumb_height'], $_value['thumb_type']);

                    $this->fileDelete($_path_dst);
                }
            }
        }

        return $_return;
    }


    /** 保存图片
     * save function.
     *
     * @access public
     * @param mixed $path_dir (default: false) 保存目录 (默认与当前图片相同)
     * @param mixed $name (default: false) 保存文件名 (默认与当前图片相同)
     * @param mixed $type (default: false) 保存文件类型 (默认与当前图片相同)
     * @param bool $quality (default: false) 图片质量
     * @param int $interlace (default: 1) 是否打开 jpg 隔行扫描
     * @return void
     */
    function save($path_dir = false, $name = false, $type = false, $quality = false, $interlace = 1) {
        $_return        = false;
        $_str_ext       = false;

        if (!$path_dir || Func::isEmpty($path_dir)) {
            if (isset($this->pathDst['path_dir'])) {
                $path_dir = $this->pathDst['path_dir']; // 采用当前图片目录
            } else {
                $this->error = 'Missing path';

                return false;
            }
        }

        if (!$name || Func::isEmpty($name)) {
            if (isset($this->pathDst['name'])) {
                $name = $this->pathDst['name']; // 采用当前图片名称
            } else {
                $this->error = 'Missing filename';

                return false;
            }
        }

        if (Func::isEmpty($path_dir)) {
            $this->error = 'Missing path';

            return false;
        }

        if (!$this->dirMk($path_dir)) { // 创建目的地目录
            $this->error = 'Failed to create directory';

            return false;
        }

        if (Func::isEmpty($name)) { // 如果没有文件名则报错
            $this->error = 'Missing filename';

            return false;
        }

        $_str_path = Func::fixDs($path_dir) . $name; // 拼合最终路径

        $_str_ext = $this->getExt($_str_path); // 取得扩展名

        if (Func::isEmpty($_str_ext)) {
            $this->error = 'Missing extension';

            return false;
        }

        if (is_resource($this->res_imgDst)) { // 如果目的图片资源存在, 则处理
            if ($_str_ext !== false && !Func::isEmpty($_str_ext)) { // 如果指定了扩展名, 则将扩展名设定为保存类型
                $type = $_str_ext;
            } else if ($type !== false || Func::isEmpty($type)) { // 如果指定了保存类型, 则直接采用
                $type = $this->info['type'];
            }

            if ($quality === false) {
                $quality = $this->quality;
            }

            $quality = (int)$quality;

            if ($quality < 1) {
                $quality = 90;
            }

            switch ($type) { //生成最终图片
                case 'jpe':
                case 'jpg':
                case 'jpeg':
                case 'pjpeg':
                    imageinterlace($this->res_imgDst, $interlace); // jpg 隔行扫描
                    $_return = imagejpeg($this->res_imgDst, $_str_path, $quality);
                break;

                case 'gif':
                    $_return = imagegif($this->res_imgDst, $_str_path);
                break;

                case 'png':
                case 'x-png':
                    $quality = intval($quality / 10); // 计算 png 图片质量

                    if ($quality < 1) {
                        $quality = 9;
                    }

                    $_return = imagepng($this->res_imgDst, $_str_path, $quality);
                break;

                case 'bmp':
                case 'x-ms-bmp':
                case 'x-windows-bmp':
                    $_return = imagewbmp($this->res_imgDst, $_str_path);
                break;

                default: // 不支持的图片类型
                    $this->error = 'Unsupported image type';

                    return false;
                break;
            }

            imagedestroy($this->res_imgDst); // 销毁目的地图片资源
        } else { // 否则直接拷贝
            $_return = copy($this->pathSrc, $_str_path);
        }

        if (!$_return) {
            $this->error = 'Failed to save image';

            return false;
        }

        $this->pathDst = array();

        return $_str_path;
    }


    /** 名称处理
     * nameProcess function.
     *
     * @access private
     * @param string $src 源路径
     * @param string $width (default: '') 宽度
     * @param string $height (default: '') 高度
     * @param string $type (default: '') 裁切类型
     * @return 名称数组
     */
    private function nameProcess($src, $width = '', $height = '', $type = '') {
        $_arr_pathinfo  = pathinfo($src);

        $_str_pathDir   = Func::fixDs($_arr_pathinfo['dirname']);
        $_str_filename  = $_arr_pathinfo['filename'];

        $_str_name      = $_str_filename;

        if (!Func::isEmpty($width)) {
            $_str_name .= '_' . $width;
        }

        if (!Func::isEmpty($height)) {
            $_str_name .= '_' . $height;
        }

        if (!Func::isEmpty($type)) {
            $_str_name .= '_' . $type;
        }

        $_str_name .= '.' . $this->info['ext'];

        return array(
            'path_dir'  => $_str_pathDir,
            'name'      => $_str_name,
            'path'      => $_str_pathDir . $_str_name,
        );
    }


    /** 计算缩略图尺寸
     * sizeProcess function.
     *
     * @access public
     * @param int $width 宽度
     * @param int $height 高度
     * @param string $type (default: 'ratio') 类型（默认等比例）
     * @return 图片尺寸数组
     */
    private function sizeProcess($width_dst, $height_dst, $type = 'ratio') {
        $_width_src     = $this->info['width']; // 源图宽度
        $_height_src    = $this->info['height']; // 源图高度

        switch ($type) {
            case 'ratio': // 按比例缩小
                if ($_width_src > $_height_src) { // 横向
                    $_width    = intval($width_dst); // 缩小宽度
                    $_height   = intval($_height_src / $_width_src * $_width); // 按比例计算高度
                    if ($_height > $height_dst) { // 如缩小后, 高度大于设定高度, 则按照高度重新计算
                        $_height  = intval($height_dst);
                        $_width   = intval($_height / ($_height_src / $_width_src));
                    }
                } else { // 纵向
                    $_height   = intval($height_dst); // 缩小高度
                    $_width    = intval($_width_src / $_height_src * $_height); // 按比例计算宽度
                    if ($_width > $width_dst) { // 如缩小后, 宽度大于设定宽度, 则按照宽度重新计算
                        $_width   = intval($width_dst);
                        $_height  = intval($_width / ($_width_src / $_height_src));
                    }
                }
                $_x_crop    = 0;
                $_y_crop    = 0;
            break;

            default:
                if ($_width_src > $_height_src) { // 横向
                    $_height    = intval($height_dst); // 缩小高度
                    $_width     = intval($_width_src / $_height_src * $_height); // 按比例计算宽度
                    $_y_crop    = 0;
                    $_x_crop    = intval(($_width - $width_dst) / 2); // 需裁切的部分
                    if ($_width < $width_dst) { // 如缩小后, 宽度小于设定的宽度, 则按照宽度重新计算
                        $_width     = intval($width_dst);
                        $_height    = intval($_width / ($_width_src / $_height_src));
                        $_x_crop    = 0;
                        $_y_crop    = intval(($_height - $height_dst) / 2);
                    }
                } else { // 纵向
                    $_width     = intval($width_dst); // 缩小宽度
                    $_height    = intval($_height_src / $_width_src * $_width); // 按比例计算高度
                    $_x_crop    = 0;
                    $_y_crop    = intval(($_height - $height_dst) / 2); // 需裁切的部分
                    if ($_height < $height_dst) { // 如缩小后, 高度小于设定的高度, 则按照高度重新计算
                        $_height    = intval($height_dst);
                        $_width     = intval($_height / ($_height_src / $_width_src));
                        $_y_crop    = 0;
                        $_x_crop    = intval(($_width - $width_dst) / 2);
                    }
                }
            break;
        }

        return array(
            'width'     => $_width, // 宽度
            'height'    => $_height, // 高度
            'x_crop'    => $_x_crop, // 裁切 x 坐标
            'y_crop'    => $_y_crop, // 裁切 y 坐标
        );
    }


    /** 验证文件是否允许
     * checkFile function.
     *
     * @access protected
     * @param string $ext 扩展名
     * @param string $mime (default: '') mime
     * @return bool
     */
    protected function checkFile($ext, $mime = '') {
        if (Func::isEmpty($mime)) { // 如指定 mime 参数, 则直接验证扩展名
            if (!in_array($ext, $this->imageExts)) {
                $this->error = 'Not an image';

                return false;
            }
        } else { // 严格检验
            if (!Func::isEmpty($this->mimeRows)) {
                if (!isset($this->mimeRows[$ext])) { // 该扩展名的 mime 数组是否存在
                    $this->error = 'MIME check failed';

                    return false;
                }

                if (!in_array($mime, $this->mimeRows[$ext])) { // 是否允许
                    $this->error = 'Not an image';

                    return false;
                }
            }
        }

        return true;
    }


    /** 透明处理
     * transparentProcess function.
     *
     * @access private
     * @param resource $res_image 图片资源
     * @return 图片资源
     */
    private function transparentProcess($res_image) {
        switch ($this->info['mime']) { // 创建图片对象
            case 'image/gif':
                $_color_bg = imagecolorallocate($res_image, 255, 255, 255); // 为图片资源分配背景色
                if ($_color_bg === -1) {
                    $this->error = 'Failed to allocate a color';
                    return false;
                }
                if (!imagefill($res_image, 0, 0, $_color_bg)) { // 用背景色填充
                    $this->error = 'Failed to flood fill';
                    return false;
                }
                if (imagecolortransparent($res_image, $_color_bg) === -1) { // 将背景色设为透明
                    $this->error = 'No transparent color';
                    return false;
                }
            break;

            case 'image/png':
            case 'image/x-png':
                if (!imagealphablending($res_image, false)) { // 关闭混色模式
                    $this->error = 'Failed to set the blending mode';
                    return false;
                }
                $_color_transparent = imagecolorallocatealpha($res_image, 255, 255, 255, 127); // 为图片资源分配背景色 (包含 alpha 通道), 并设为完全透明
                if (!$_color_transparent) {
                    $this->error = 'Failed to allocate a color + alpha';
                    return false;
                }
                if (!imagefill($res_image, 0, 0, $_color_transparent)) { // 用背景色填充
                    $this->error = 'Failed to flood fill';
                    return false;
                }
                if (!imagesavealpha($res_image, true)) { // 保存 PNG 图像时保存完整的 alpha 通道信息
                    $this->error = 'Failed to save full alpha';
                    return false;
                }
            break;
        }

        return $res_image;
    }


    public function __destruct() {
        empty($this->res_imgSrc) || imagedestroy($this->res_imgSrc);
    }
}
