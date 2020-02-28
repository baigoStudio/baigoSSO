<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access Denied');

/*-------------上传类-------------*/
class Image extends File {

    protected static $instance;

    public $quality = 90;
	private $res_imgSrc;
	private $pathSrc;
	private $pathDst = array();
    private $thumbs  = array();

    protected $info = array(
        'width'  => 100,
        'height' => 100,
        'mime'   => '',
        'ext'    => '',
    );

    protected function __construct() {
        $_arr_config        = Config::get('image');
        $this->mimeRows     = $_arr_config;
        $this->imageExts    = array_keys($_arr_config);
    }

    protected function __clone() {

    }

    public static function instance() {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function open($path_src) {
        if (!Func::isFile($path_src)) {
            $this->error = 'Image not found';

            return false;
        }

        $_str_ext = $this->getExt($path_src);

        if (!$this->checkFile($_str_ext)) {
            return false;
        }

        $_str_mime  = $this->getMime($path_src);
        $_str_ext   = $this->getExt($path_src, $_str_mime);

        if (!$this->checkFile($_str_ext, $_str_mime)) {
            return false;
        }

        switch ($_str_mime) { //创建图片对象
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
                $this->error = 'Unsupported MIME';

                return false;
            break;
        }

        $this->pathSrc      = $path_src;
        $this->res_imgSrc   = $_res_imgSrc;

        $this->info['mime'] = $_str_mime;
        $this->info['ext']  = $_str_ext;

        return $this->info;
    }


    public function width() {
        return $this->info['width'];
    }

    /**
     * 返回图像高度
     * @return int 图像高度
     */
    public function height() {
        return $this->info['height'];
    }

    /**
     * 返回扩展名
     * @return string 扩展名
     */
    public function ext() {
        return $this->info['ext'];
    }

    /**
     * 返回图像MIME类型
     * @return string 图像MIME类型
     */
    public function mime() {
        return $this->info['mime'];
    }

    /**
     * 返回图像尺寸数组 0 - 图像宽度，1 - 图像高度
     * @return array 图像尺寸
     */
    public function size() {
        return array(
            $this->info['width'],
            $this->info['height'],
        );
    }


    function getError() {
        return $this->error;
    }

    function getThumbs() {
        return $this->thumbs;
    }

    function getMime($path = '', $type = '') {
        try {
            $_info = getimagesize($path);

            //设置图像信息
            $this->info = array(
                'width'  => $_info[0],
                'height' => $_info[1],
                'type'   => image_type_to_extension($_info[2], false),
                'mime'   => $_info['mime'],
            );
        } catch (\Exception $excpt) {
            $this->error = $excpt->getMessage();

            return false;
        }

        return $_info['mime'];
    }


    function crop($width, $height, $x_crop = 0, $y_crop = 0, $width_save = false, $height_save = false, $width_src = false, $height_src = false) {
        if (!$width_save) {
            $width_save = $width;
        }

        if (!$height_save) {
            $height_save = $height;
        }

        if (!$width_src) {
            $width_src = $this->info['width'];
        }

        if (!$height_src) {
            $height_src = $this->info['height'];
        }

        $_res_imgDst = imagecreatetruecolor($width_save, $height_save);

        if (!$_res_imgDst) {
            $this->error = 'Failed to Create a new true color image';
            return false;
        }

        $_res_imgDst = $this->transparentProcess($_res_imgDst);

        if (!$_res_imgDst) {
            return false;
        }

        if (!imagecopyresampled($_res_imgDst, $this->res_imgSrc, 0, 0, $x_crop, $y_crop, $width, $height, $width_src, $height_src)) { //直接缩小
            $this->error = 'Failed to resize';
            return false;
        }

        $this->res_imgDst = $_res_imgDst;

        if (Func::isEmpty($this->pathDst)) {
            $this->pathDst = $this->nameProcess($this->pathSrc, $width, $height, 'crop');
        }

        return $this;
    }


    /** 生成缩略图
     * dst_do function.
     *
     * @access public
     * @param mixed $width 宽度
     * @param mixed $height 高度
     * @param string $thumbType (default: 'ratio') 类型（默认等比例）
     * @return void
     */
    function thumb($width = 100, $height = 100, $type = 'ratio') {
        $_arr_thumbSize   = $this->sizeProcess($width, $height, $type); //计算缩略图尺寸

        if (Func::isEmpty($this->pathDst)) {
            $this->pathDst = $this->nameProcess($this->pathSrc, $width, $height, $type);
        }

        switch ($type) {
            case 'ratio':
                $width  = false;
                $height = false;
            break;
        }

        if ($width >= $this->info['width'] && $height >= $this->info['height']) { //如果源图片小于目标缩略图，则只是拷贝
            return $this;
        } else {
            $this->crop($_arr_thumbSize['width'], $_arr_thumbSize['height'], $_arr_thumbSize['x_crop'], $_arr_thumbSize['y_crop'], $width, $height);
        }

        return $this;
    }


    function batThumb($thumbRows = array()) {
        $_return = true;

        if (!Func::isEmpty($thumbRows)) {
            foreach ($thumbRows as $_key=>$_value) {
                if (!isset($_value['thumb_width'])) {
                    $_value['thumb_width'] = 100;
                }

                if (!isset($_value['thumb_height'])) {
                    $_value['thumb_height'] = 100;
                }

                if (!isset($_value['thumb_type'])) {
                    $_value['thumb_type'] = 'ratio';
                }

                if (!isset($_value['thumb_quality'])) {
                    $_value['thumb_quality'] = $this->quality;
                }

                $_return = $this->thumb($_value['thumb_width'], $_value['thumb_height'], $_value['thumb_type'], $_value['thumb_quality'])->save();

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


    function save($path_dir = false, $name = false, $type = false, $quality = false, $interlace = 1) {
        $_return        = false;
        $_str_ext       = false;

        if (!$path_dir || Func::isEmpty($path_dir)) {
            if (isset($this->pathDst['path_dir'])) {
                $path_dir = $this->pathDst['path_dir'];
            } else {
                $this->error = 'Missing path';

                return false;
            }
        }

        if (!$name || Func::isEmpty($name)) {
            if (isset($this->pathDst['name'])) {
                $name = $this->pathDst['name'];
            } else {
                $this->error = 'Missing filename';

                return false;
            }
        }

        if (Func::isEmpty($path_dir)) {
            $this->error = 'Missing path';

            return false;
        }

        if (!$this->dirMk($path_dir)) {
            $this->error = 'Failed to create directory';

            return false;
        }

        if (Func::isEmpty($name)) {
            $this->error = 'Missing filename';

            return false;
        }

        $_str_path = Func::fixDs($path_dir) . $name;

        $_str_ext = $this->getExt($_str_path);

        if (Func::isEmpty($_str_ext)) {
            $this->error = 'Missing extension';

            return false;
        }

        if (is_resource($this->res_imgDst)) {
            if ($_str_ext !== false && !Func::isEmpty($_str_ext)) {
                $type = $_str_ext;
            } else if (!$type || Func::isEmpty($type)) {
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
                    imageinterlace($this->res_imgDst, $interlace);
                    $_return = imagejpeg($this->res_imgDst, $_str_path, $quality);
                break;

                case 'gif':
                    $_return = imagegif($this->res_imgDst, $_str_path);
                break;

                case 'png':
                case 'x-png':
                    $quality = intval($quality / 10);

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

                default:
                    $this->error = 'Unsupported image type';

                    return false;
                break;
            }

            imagedestroy($this->res_imgDst);
        } else {
            $_return = copy($this->pathSrc, $_str_path);
        }

        if (!$_return) {
            $this->error = 'Failed to save image';

            return false;
        }

        $this->pathDst = array();

        return $_str_path;
    }


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
     * @param mixed $width 宽度
     * @param mixed $height 高度
     * @param string $type (default: 'ratio') 类型（默认等比例）
     * @return void
     */
    private function sizeProcess($width_dst, $height_dst, $type = 'ratio') {
        $_width_src     = $this->info['width'];
        $_height_src    = $this->info['height'];

        switch ($type) {
            case 'ratio': //按比例缩小
                if ($_width_src > $_height_src) { //横向
                    $_width    = intval($width_dst); //缩小宽度
                    $_height   = intval($_height_src / $_width_src * $_width); //按比例计算高度
                    if ($_height > $height_dst) { //如缩小后，高度大于设定高度，则按照高度重新计算
                        $_height  = intval($height_dst);
                        $_width   = intval($_height / ($_height_src / $_width_src));
                    }
                } else { //纵向
                    $_height   = intval($height_dst); //缩小高度
                    $_width    = intval($_width_src / $_height_src * $_height); //按比例计算宽度
                    if ($_width > $width_dst) { //如缩小后，宽度大于设定宽度，则按照宽度重新计算
                        $_width   = intval($width_dst);
                        $_height  = intval($_width / ($_width_src / $_height_src));
                    }
                }
                $_x_crop    = 0;
                $_y_crop    = 0;
            break;

            default:
                if ($_width_src > $_height_src) { //横向
                    $_height    = intval($height_dst); //缩小高度
                    $_width     = intval($_width_src / $_height_src * $_height); //按比例计算宽度
                    $_y_crop    = 0;
                    $_x_crop    = intval(($_width - $width_dst) / 2); //需裁切的部分
                    if ($_width < $width_dst) { //如缩小后，宽度小于设定的宽度，则按照宽度重新计算
                        $_width     = intval($width_dst);
                        $_height    = intval($_width / ($_width_src / $_height_src));
                        $_x_crop    = 0;
                        $_y_crop    = intval(($_height - $height_dst) / 2);
                    }
                } else { //纵向
                    $_width     = intval($width_dst); //缩小宽度
                    $_height    = intval($_height_src / $_width_src * $_width); //按比例计算高度
                    $_x_crop    = 0;
                    $_y_crop    = intval(($_height - $height_dst) / 2); //需裁切的部分
                    if ($_height < $height_dst) { //如缩小后，高度小于设定的高度，则按照高度重新计算
                        $_height    = intval($height_dst);
                        $_width     = intval($_height / ($_height_src / $_width_src));
                        $_y_crop    = 0;
                        $_x_crop    = intval(($_width - $width_dst) / 2);
                    }
                }
            break;
        }

        return array(
            'width'     => $_width,
            'height'    => $_height,
            'x_crop'    => $_x_crop,
            'y_crop'    => $_y_crop,
        );
    }


    protected function checkFile($ext, $mime = '') {
        if (Func::isEmpty($mime)) {
            if (!in_array($ext, $this->imageExts)) {
                $this->error = 'Not an image';

                return false;
            }
        } else { //严格检验
            if (!Func::isEmpty($this->mimeRows)) {
                if (!isset($this->mimeRows[$ext])) { //该扩展名的 mime 数组是否存在
                    $this->error = 'MIME check failed';

                    return false;
                }

                if (!in_array($mime, $this->mimeRows[$ext])) { //是否允许
                    $this->error = 'Not an image';

                    return false;
                }
            }
        }

        return true;
    }


    protected function checkSave($path, $ext = false, $ext_allow = '') {
        if ($ext === false) {
            $path .= '.' . $this->info['ext'];
        } else if (!Func::isEmpty($ext)) {
            if (is_array($ext_allow)) {
                if (in_array($ext, array('jpe', 'jpg', 'jpeg', 'pjpeg'))) {
                    $path .= '.' . $ext;
                } else {
                    $this->error = 'Extension does not match the save type';

                    return false;
                }
            } else if (is_string($ext_allow)) {
                if ($ext == $ext_allow) {
                    $path .= '.' . $ext;
                } else {
                    $this->error = 'Extension does not match the save type';

                    return false;
                }
            } else {
                $this->error = 'Save check failed';

                return false;
            }
        }

        return $path;
    }


    private function transparentProcess($res_image) {
        switch ($this->info['mime']) { //创建图片对象
            case 'image/gif':
                $_color_bg = imagecolorallocate($res_image, 255, 255, 255);
                if ($_color_bg === -1) {
                    $this->error = 'Failed to allocate a color';
                    return false;
                }
                if (!imagefill($res_image, 0, 0, $_color_bg)) {
                    $this->error = 'Failed to flood fill';
                    return false;
                }
                if (imagecolortransparent($res_image, $_color_bg) === -1) {
                    $this->error = 'No transparent color';
                    return false;
                }
            break;

            case 'image/png':
            case 'image/x-png':
                if (!imagealphablending($res_image, false)) {
                    $this->error = 'Failed to set the blending mode';
                    return false;
                }
                $_color_transparent = imagecolorallocatealpha($res_image, 255, 255, 255, 127);
                if (!$_color_transparent) {
                    $this->error = 'Failed to allocate a color + alpha';
                    return false;
                }
                if (!imagefill($res_image, 0, 0, $_color_transparent)) {
                    $this->error = 'Failed to flood fill';
                    return false;
                }
                if (!imagesavealpha($res_image, true)) {
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
