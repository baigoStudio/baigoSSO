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

// 调试
class Debug {

  public static $error; // 错误

  private static $init; // 是否初始化标志
  private static $obj_request; // 请求实例

  // 初始化
  private static function init() {
    self::$obj_request  = Request::instance();
    self::$init         = true; // 标识为已初始化
  }


  /** 取得错误
   * get function.
   *
   * @access public
   * @static
   * @param string $name (default: '') 错误名
   * @return 错误信息
   */
  public static function get($name = '') {
    $_value = '';

    if (Func::isEmpty($name)) {
      $_value = self::$error;
    } else if (isset(self::$error[$name])) {
      $_value = self::$error[$name];
    }

    //print_r($_value);

    return $_value;
  }


  /** 记录错误
   * record function.
   *
   * @access public
   * @static
   * @param string $name (default: '') 错误名
   * @param string $value (default: '') 错误内容
   * @return void
   */
  public static function record($name, $value) {
    self::$error[$name] = $value;
  }


  /** 注入调试信息
   * inject function.
   *
   * @access public
   * @static
   * @param string $content (default: '') 待注入内容
   * @param string $type (default: 'html') 待注入内容的类型
   * @return 注入调试信息后的数据
   */
  public static function inject($content, $type = 'html') {
    if (Func::isEmpty(self::$init)) {
      self::init();
    }

    $_mix_return   = $content;
    $_optDebugDump = '';

    $_mix_configDebug = Config::get('debug'); // 取得调试配置

    if (is_array($_mix_configDebug)) {
      if ($_mix_configDebug['dump'] === 'trace') { // 假如配置为输出
        $_optDebugDump = 'trace';
      }
    } else if (is_scalar($_mix_configDebug)) {
      if ($_mix_configDebug === 'trace') { // 假如配置为输出
        $_optDebugDump = 'trace';
      }
    }

    switch ($_optDebugDump) {
      case 'trace': // 追踪模式
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

        if ($_str_type != 'html' && $_str_type != 'xml') { // 如果不是 html 和 xml, 则不做处理
          return $_mix_return;
        }

        $_arr_files = get_included_files(); // 取得已载入的文件列表

        $_arr_fileRows = array();

        foreach ($_arr_files as $_key=>$_value) {
          $_arr_fileRows[$_key] = array( // 拼接已载入文件
            'path' => $_value, // 路径
            'size' => Strings::sizeFormat(filesize($_value)), // 计算大小
          );
        }

        $_runtime   = Strings::numFormat(microtime(true) - GK_START_TIME, 6) . ' sec'; // 计算运行时间
        $_memory    = Strings::sizeFormat((memory_get_usage() - GK_START_MEM)); // 计算占用内存大小

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

        $_str_html  = Error::fetch('trace', $_arr_data); // 渲染追踪信息

        $_num_pos   = strripos($_mix_return, '</body>'); // 取得 body 标签结束位置

        // 注入调试信息
        if ($_num_pos !== false) {
          $_mix_return = substr($_mix_return, 0, $_num_pos) . $_str_html . substr($_mix_return, $_num_pos);
        } else {
          $_mix_return .= $_str_html;
        }
      break;

      default: // 简洁调试模式 (只显示错误)
        if (Func::notEmpty(self::$error)) {
          switch ($type) {
            case 'arr': // 如果是数组, 则直接追加 error 元素
              $_mix_return['error'] = self::$error;
            break;

            default: // 注入 html
              $_str_err   = Error::dump(self::$error); // 输出

              $_num_pos   = strripos($_mix_return, '</body>'); // 取得 body 标签结束位置

              // 注入调试信息
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
}
