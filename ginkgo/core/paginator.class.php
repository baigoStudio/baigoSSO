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

// 分页处理类
class Paginator {

  public $config = array(); // 配置
  public $current; // 当前页
  public $count; // 总记录数
  public $totalRow; // 总页数

  protected static $instance; // 当前实例

  private $configThis = array(
    'perpage'      => 10,
    'pergroup'     => 10,
    'pageparam'    => 'page',
  );

  protected function __construct($config = array()) {
    $this->config($config);

    if ($this->config['perpage'] < 1) { // 如果每页数依然小于 1, 则直接为 10
      $this->config['perpage'] = 10;
    }

    if ($this->config['pergroup'] < 1) { // 如果每个分组的页数依然小于 1, 则默认为 10
      $this->config['pergroup'] = 10;
    }
  }

  protected function __clone() { }

  /** 实例化
   * instance function.
   *
   * @access public
   * @static
   * @return 当前类的实例
   */
  public static function instance($config = array()) {
    if (Func::isEmpty(self::$instance)) {
      self::$instance = new static($config);
    }

    return self::$instance;
  }


  public function config($config = array()) {
    $_arr_config   = Config::get('var_default'); // 获取数据库配置

    $_arr_configDo = $this->configThis;

    if (is_array($_arr_config) && Func::notEmpty($_arr_config)) {
      $_arr_configDo = array_replace_recursive($_arr_configDo, $_arr_config); // 合并配置
    }

    if (is_array($this->config) && Func::notEmpty($this->config)) {
      $_arr_configDo = array_replace_recursive($_arr_configDo, $this->config); // 合并配置
    }

    if (is_array($config) && Func::notEmpty($config)) {
      $_arr_configDo = array_replace_recursive($_arr_configDo, $config); // 合并配置
    }

    $this->config  = $_arr_configDo;
  }


  /** 取得分页参数
   * make function.
   *
   * @access public
   * @param string $current (default: 'get') 方法
   * @return 分页参数
   */
  public function make($current = 'get') {
    $this->current($current);
    $_arr_totalRow  = $this->totalProcess();
    $_num_offset    = $this->offsetProcess();
    $_arr_groupRow  = $this->groupProcess();
    $_arr_stepRow   = $this->stepProcess();

    $_arr_return    = array(
      'page'      => $this->current, // 当前页码
      'count'     => $this->count, // 总记录数
      'offset'    => $_num_offset, // 应排除的记录数
      'except'    => $_num_offset, // 应排除的记录数, 兼容老版本
    );

    return array_replace_recursive($_arr_return, $_arr_totalRow, $_arr_groupRow, $_arr_stepRow);
  }


  /** 设置、获取当前页
   * setCount function.
   *
   * @access public
   * @param mixed $current 当前页
   * @return void
   */

  public function current($current = 'get') {
    $_obj_request   = Request::instance(); // 实例化请求对象
    $_arr_param     = $_obj_request->param; // 参数
    $_str_pageparam = $this->config['pageparam'];

    if (is_numeric($current)) { // 如果参数为数字, 则直接认为是当前页码
      $_num_current = $current;
    } else if (is_string($current)) {
      $current = strtolower($current); // 将参数转为小写

      switch ($current) {
        case 'post':
          $_num_current = $_obj_request->post($_str_pageparam, false, 'int', 1); // 从 post 中取得当前页码
        break;

        default:
          //print_r($_arr_param);
          if (isset($_arr_param[$_str_pageparam])) { // 如果参数中有当前页码, 则直接使用
            $_num_current = $_obj_request->input($_arr_param[$_str_pageparam], 'int', 1);
          } else { // 否则从 get 中取得
            $_num_current = $_obj_request->get($_str_pageparam, false, 'int', 1);
          }
        break;
      }
    }

    if ($_num_current < 1) { // 如果当前页小与 1, 则直接认为是首页
      $_num_current = 1;
    }

    $this->current = $_num_current;

    return $_num_current;
  }


  /** 设置、获取总记录数
   * setCount function.
   *
   * @access public
   * @param mixed $count 记录数
   * @return 记录数
   */
  public function count($count = 0) {
    if ($count > 0) {
      $this->count = $count;
    } else {
      return $this->count;
    }
  }


  /** 设置、获取每页记录数
   * setCount function.
   *
   * @access public
   * @param mixed $perpage 每页记录数
   * @return 每页记录数
   */
  public function perpage($perpage = 0) {
    if ($perpage > 0) {
      $this->config['perpage'] = $perpage;
    } else {
      return $this->config['perpage'];
    }
  }


  /** 设置、获取每组页数
   * setCount function.
   *
   * @access public
   * @param mixed $pergroup 每组页数
   * @return 每组页数
   */
  public function pergroup($pergroup = 0) {
    if ($pergroup > 0) {
      $this->config['pergroup'] = $pergroup;
    } else {
      return $this->config['pergroup'];
    }
  }


  /** 设置分页参数
   * setCount function.
   *
   * @access public
   * @param mixed $pageparam 分页参数
   * @return 分页参数
   */
  public function pageparam($pageparam = '') {
    if (Func::isEmpty($pageparam)) {
      return $this->config['pageparam'];
    } else {
      $this->config['pageparam'] = $pageparam;
    }
  }

  /** 总页数计算
   * totalProcess function.
   *
   * @access private
   * @return 总页数数组
   */
  private function totalProcess() {
    $_num_first     = false; // 首页 (false 为隐藏, 以下同)
    $_num_final     = false; // 尾页

    $_num_current   = $this->current;
    $_num_perpage   = $this->config['perpage'];

    $_num_total = $this->count / $_num_perpage; // 记录数除以每页数

    if (intval($_num_total) < $_num_total) { // 有余数, 则总页数加 1
      $_num_total = intval($_num_total) + 1;
    } else if ($_num_total < 1) { // 总页数小于 1, 则认为只有一页
      $_num_total = 1;
    } else {
      $_num_total = intval($_num_total); // 将总页数转换为整数
    }

    if ($_num_current > 1 && $_num_total > 1) { // 当前页码大于 1 时
      $_num_first = 1; // 显示首页按钮
    }

    if ($_num_current < $_num_total && $_num_total > 1) { // 当前页小于总页数时
      $_num_final = $_num_total; // 显示尾页按钮
    }

    if ($_num_current > $_num_total) { // 如果当前页码大于总页数, 则总页数为当前页码
      $_num_current = $_num_total;
    }

    $_arr_totalRow = array(
      'first' => $_num_first,
      'final' => $_num_final,
      'total' => $_num_total,
    );

    $this->config['perpage'] = $_num_perpage;
    $this->current           = $_num_current;
    $this->totalRow          = $_arr_totalRow;

    return $_arr_totalRow;
  }


  /** 分组计算
   * groupProcess function.
   *
   * @access private
   * @return 分组参数
   */
  private function groupProcess() {
    $_num_groupPrev     = false; // 上一组
    $_num_groupNext     = false; // 下一组

    $_num_pergroup      = $this->config['pergroup'];
    $_arr_totalRow      = $this->totalRow;
    $_num_total         = $_arr_totalRow['total'];
    $_num_final         = $_arr_totalRow['final'];

    $_num_groupP        = intval(($this->current - 1) / $_num_pergroup); // 是否存在上十页、下十页参数
    $_num_groupBegin    = $_num_groupP * $_num_pergroup + 1; // 当前组起始页
    $_num_groupEnd      = $_num_groupP * $_num_pergroup + $_num_pergroup; // 当前组结束页

    if ($_num_groupEnd >= $_num_total) { // 如果当前组的结束页大于总页数, 则总页数为当前组的结束页
      $_num_groupEnd = $_num_total;
    }

    if ($_num_groupP * $_num_pergroup > 0) {
      $_num_groupPrev = $_num_groupP * $_num_pergroup;
    }

    if ($_num_groupEnd < $_num_final) { // 当前组的结束页小于尾页
      $_num_groupNext = $_num_groupEnd + 1; // 下一组为当前组的结束页加 1
    }

    $_arr_groupRow = array(
      'group_begin'   => $_num_groupBegin, // 当前组开始
      'group_end'     => $_num_groupEnd, // 当前组结束
      'group_prev'    => $_num_groupPrev, // 上一组
      'group_next'    => $_num_groupNext, // 下一组
    );

    $this->config['pergroup'] = $_num_pergroup;
    $this->groupRow           = $_arr_groupRow;

    return $_arr_groupRow;
  }


  /** 偏移计算
   * offsetProcess function.
   *
   * @access private
   * @return 排除记录数
   */
  private function offsetProcess() {
    $_num_current   = $this->current;
    $_num_offset    = 0;

    if ($_num_current > 1) { // 如果当前页码小于 1, 则需排除
      $_num_offset = ($_num_current - 1) * $this->config['perpage'];
    }

    return $_num_offset;
  }


  /** 上下页处理
   * stepProcess function.
   *
   * @access private
   * @return 上下页参数
   */
  private function stepProcess() {
    // 链接以及数值
    $_num_prev      = false; // 上一页
    $_num_next      = false; // 下一页
    $_arr_totalRow  = $this->totalRow;
    $_num_total     = $_arr_totalRow['total'];
    $_num_current   = $this->current;

    if ($_num_current > 1) { // 当前页码大于 1 时
      $_num_prev  = $_num_current - 1; // 上一页设置为当前页减 1
    }

    if ($_num_current < $_num_total) { // 当前页小于总页数时
      $_num_next  = $_num_current + 1; // 下一页设置为当前页加 1
    }

    return array(
      'prev'  => $_num_prev, // 上一页
      'next'  => $_num_next, // 下一页
    );
  }
}
