<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Debug {

    private static $error;
    private static $data;
    private static $init;
    private static $suffix = GK_EXT_TPL; // 默认模板文件后缀
    private static $obj_request;

    protected function __construct() {
    }

    protected function __clone() {

    }

    private static function init() {
        self::$init         = true;
        self::$obj_request  = Request::instance();
    }

    static function get($name = '') {
        $_value = '';

        if (Func::isEmpty($name)) {
            $_value = self::$error;
        } else if (isset(self::$error[$name])) {
            $_value = self::$error[$name];
        }

        //print_r($_value);

        return $_value;
    }

    static function record($name = '', $value = '') {
        self::$error[$name] = $value;
    }


    /**
     * 调试信息注入到响应中
     * @access public
     * @param  Response $response 响应实例
     * @param  string   $content  返回的字符串
     * @return void
     */
    static function inject($content = '', $type = 'html') {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        $_mix_return = $content;

        $_dump = Config::get('dump', 'debug');

        if ($_dump === 'trace') {
            $_dump = 2;
        } else {
            $_dump = 1;
        }

        switch ($_dump) {
            case 2:
                $_arr_configDefault = Config::get('var_default');

                if (self::$obj_request->isAjax()) {
                    if (Func::isEmpty($_arr_configDefault['return_type_ajax'])) {
                        $_str_type = $_arr_configDefault['return_type'];
                    } else {
                        $_str_type = $_arr_configDefault['return_type_ajax'];
                    }
                } else {
                    $_str_type = self::$obj_request->type();
                }

                if ($_str_type != 'html' && $_str_type != 'xml') {
                    return $_mix_return;
                }

                $_arr_files = get_included_files();

                $_arr_fileRows = array();

                foreach ($_arr_files as $_key=>$_value) {
                    $_arr_fileRows[$_key] = array(
                        'path' => $_value,
                        'size' => number_format(filesize($_value) / 1024, 2) . ' KB',
                    );
                }

                $_runtime   = Func::numFormat(microtime(true) - GK_START_TIME, 6) . ' sec';
                $_memory    = Func::sizeFormat((memory_get_usage() - GK_START_MEM));

                $_arr_data['trace'] = array(
                    'base'  => array(
                        'runtime'   => $_runtime,
                        'memory'    => $_memory,
                        'included'  => count($_arr_files),
                        'config'    => Config::count(),
                    ),
                    'backtrace' => debug_backtrace(false),
                    'files'     => $_arr_fileRows,
                    'error'     => self::$error,
                    'sql'       => Log::get('sql'),
                );

                //print_r($_arr_data);

                $_str_html  = Error::fetch('trace', $_arr_data);

                $_num_pos   = strripos($_mix_return, '</body>');
                if ($_num_pos !== false) {
                    $_mix_return = substr($_mix_return, 0, $_num_pos) . $_str_html . substr($_mix_return, $_num_pos);
                } else {
                    $_mix_return .= $_str_html;
                }
            break;

            default:
                if (!Func::isEmpty(self::$error)) {
                    switch ($type) {
                        case 'arr':
                            $_mix_return['error'] = self::$error;
                        break;

                        default:
                            $_str_tag   = '';
                            $_str_class = '';

                            $_arr_configDebug = Config::get('debug');

                            if (!Func::isEmpty($_arr_configDebug['tag'])) {
                                $_str_tag = $_arr_configDebug['tag'];
                            }

                            if (!Func::isEmpty($_arr_configDebug['class'])) {
                                $_str_class = $_arr_configDebug['class'];
                            }

                            $_str_err   = self::dump($_str_tag, $_str_class);
                            $_num_pos   = strripos($_mix_return, '</body>');
                            if ($_num_pos !== false) {
                                $_mix_return = substr($_mix_return, 0, $_num_pos) . $_str_err . substr($_mix_return, $_num_pos);
                            } else {
                                $_mix_return .= $_str_err;
                            }
                        break;
                    }
                }
            break;
        }

        return $_mix_return;
    }


    /**
     * 浏览器友好的变量输出
     * @access public
     * @param  mixed       $arr_value   变量
     * @param  boolean     $echo  是否输出(默认为 true，为 false 则返回输出字符串)
     * @param  string|null $tag 标签(默认为空)
     * @param  integer     $flags htmlspecialchars 的标志
     * @return null|string
     */
    private static function dump($tag = '', $class = '') {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        $_str_html = '';

        if (!Func::isEmpty(self::$error)) {
            $_arr_tag  = array(
                'begin' => '',
                'end'   => '',
            );

            if (!Func::isEmpty($tag)) {
                $_str_tagStart = '<' . $tag;

                if (!Func::isEmpty($class)) {
                    $_str_tagStart .= ' class="' . $class . '"';
                }

                $_arr_tag['begin']  = $_str_tagStart . '><h5>Debug information</h5>';
                $_str_tag['end']    = '</' . $tag . '>';
            }

            $_str_html = $_arr_tag['begin'];

            foreach (self::$error as $_key=>$_value) {
                $_str_html .= '<hr><dl>';
                    if (isset($_value['err_message'])) {
                        $_str_html .= '<dt>message</dt>';
                        $_str_html .= '<dd>' . $_value['err_message'] . '</dd>';
                    }

                    if (isset($_value['err_file'])) {
                        $_str_html .= '<dt>file</dt>';
                        $_str_html .= '<dd>' . $_value['err_file'] . '</dd>';
                    }

                    if (isset($_value['err_line'])) {
                        $_str_html .= '<dt>line</dt>';
                        $_str_html .= '<dd>' . $_value['err_line'] . '</dd>';
                    }

                    if (isset($_value['err_type'])) {
                        $_str_html .= '<dt>type</dt>';
                        $_str_html .= '<dd>' . $_value['err_type'] . '</dd>';
                    }
                $_str_html .= '</dl>';
            }

            $_str_html .= $_arr_tag['end'];
        }


        return $_str_html;
    }
}


