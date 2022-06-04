<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

use ginkgo\except\Vld_Except;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

// 验证
class Validate {

  public $config    = array(); // 配置
  public $delimiter = ' - '; // 范围符号

  protected static $instance; // 当前实例
  protected $obj_lang; // 语言实例
  protected $obj_rule; // 规则实例
  protected $rule         = array(); // 验证规则
  protected $data         = array(); // 待验证数据
  protected $attrName     = array(); // 字段名称
  protected $scene        = array(); // 场景
  protected $only         = array(); // 仅验证指定字段
  protected $remove       = array(); // 移除规则
  protected $append       = array(); // 追加规则
  protected $currentScene = null; // 当前场景

  // 默认提示信息 (类型验证)
  protected $typeMsg = array(
    'require'           => '{:attr} require',
    'confirm'           => '{:attr} out of accord with {:confirm}',
    'different'         => '{:attr} cannot be same with {:different}',
    'accepted'          => '{:attr} must be yes, on or 1',
    'in'                => '{:attr} must be in {:rule}',
    'not_in'            => '{:attr} be notin {:rule}',
    'between'           => '{:attr} must between {:rule}',
    'not_between'       => '{:attr} cannot between {:rule}',
    'length'            => 'Size of {:attr} must be {:rule}',
    'min'               => 'Min size of {:attr} must be {:rule}',
    'max'               => 'Max size of {:attr} must be {:rule}',
    'after'             => '{:attr} cannot be less than {:rule}',
    'before'            => '{:attr} cannot exceed {:rule}',
    'expire'            => '{:attr} not within {:rule}',
    'egt'               => '{:attr} must greater than or equal {:rule}',
    'gt'                => '{:attr} must greater than {:rule}',
    'elt'               => '{:attr} must less than or equal {:rule}',
    'lt'                => '{:attr} must less than {:rule}',
    'eq'                => '{:attr} must equal {:rule}',
    'neq'               => '{:attr} cannot be same with {:rule}',
    'filter'            => '{:attr} not conform to the rules',
    'regex'             => '{:attr} not conform to the rules',
    'format'            => '{:attr} not conform format of {:rule}',
    'date_format'       => '{:attr} must be date format of {:rule}',
    'time_format'       => '{:attr} must be time format of {:rule}',
    'date_time_format'  => '{:attr} must be datetime format of {:rule}',
    'token'             => 'Form token is incorrect',
    'captcha'           => 'Captcha is incorrect',
  );


  // 默认提示信息 (格式验证)
  protected $formatMsg = array(
    'number'            => '{:attr} must be numeric',
    'int'               => '{:attr} must be integer',
    'float'             => '{:attr} must be float',
    'bool'              => '{:attr} must be bool',
    'email'             => '{:attr} not a valid email address',
    'array'             => '{:attr} must be a array',
    'date'              => '{:attr} not a valid date',
    'time'              => '{:attr} not a valid time',
    'date_time'         => '{:attr} not a valid datetime',
    'alpha'             => '{:attr} must be alpha',
    'alpha_number'      => '{:attr} must be alpha-numeric',
    'alpha_dash'        => '{:attr} must be alpha-numeric, dash, underscore',
    'chs'               => '{:attr} must be chinese',
    'chs_alpha'         => '{:attr} must be chinese or alpha',
    'chs_alpha_number'  => '{:attr} must be chinese, alpha-numeric',
    'chs_dash'          => '{:attr} must be chinese, alpha-numeric, underscore, dash',
    'url'               => '{:attr} not a valid url',
    'ip'                => '{:attr} not a valid ip',
  );


  private $configThis = array(
    'rule_class' => 'Gk',
  ); // 默认配置

  private $message = array(); // 验证消息

  // 规则别名
  private $alias = array(
    '>'         => 'gt',
    '>='        => 'egt',
    '<'         => 'lt',
    '<='        => 'elt',
    '='         => 'eq',
    'same'      => 'eq',
    '!='        => 'neq',
    '<>'        => 'neq',
  );

  /** 构造函数
   * __construct function.
   *
   * @access public
   * @return void
   */
  public function __construct($config = array()) {
    $this->obj_lang = Lang::instance();

    $this->config($config);

    if (Func::isEmpty($this->config['rule_class'])) { // 假如未指定类型, 则默认为 ginkgo
      $this->config['rule_class'] = $this->configThis['rule_class'];
    }

    if (strpos($this->config['rule_class'], '\\')) {
      $_class = $this->config['rule_class'];
    } else {
      $_class = 'ginkgo\\validate\\rule\\' . Strings::ucwords($this->config['rule_class'], '_');
    }

    if (class_exists($_class)) {
      $this->obj_rule = $_class::instance(); // 实例化规则
    } else {
      $_obj_excpt = new Vld_Except('Unsupported rule type', 500);

      $_obj_excpt->setData('err_detail', $_class);

      throw $_obj_excpt;
    }

    $this->v_init(); // 验证类初始化
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

  // 验证器初始化
  protected function v_init() { }

  /** 配置
   * prefix function.
   * since 0.2.0
   * @access public
   * @param string $config (default: array()) 配置
   * @return
   */
  public function config($config = array()) {
    $_arr_config   = Config::get('validate'); // 取得配置

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

  /** 设置规则
   * rule function.
   *
   * @access public
   * @param mixed $rule 规则
   * @param string $value (default: '') 值
   * @return void
   */
  public function rule($rule, $value = '') {
    if (is_array($rule)) {
      $this->rule = array_replace_recursive($this->rule, $rule);
    } else {
      $this->rule[$rule] = $value;
    }
  }

  /** 设置场景
   * setScene function.
   *
   * @access public
   * @param mixed $scene 场景
   * @param array $value (default: array()) 值
   * @return void
   */
  public function setScene($scene, $value = array()) {
    if (is_array($scene)) {
      $this->scene = array_replace_recursive($this->scene, $scene);
    } else {
      $this->scene[$scene] = $value;
    }
  }

  /** 设置类型消息
   * setTypeMsg function.
   *
   * @access public
   * @param mixed $msg 消息
   * @param string $value (default: '') 值
   * @return void
   */
  public function setTypeMsg($msg, $value = '') {
    if (is_array($msg)) {
      $this->typeMsg = array_replace_recursive($this->typeMsg, $msg);
    } else {
      $this->typeMsg[$msg] = $value;
    }
  }

  /** 设置格式消息
   * setFormatMsg function.
   *
   * @access public
   * @param mixed $msg 消息
   * @param string $value (default: '') 值
   * @return void
   */
  public function setFormatMsg($msg, $value = '') {
    if (is_array($msg)) {
      $this->formatMsg = array_replace_recursive($this->formatMsg, $msg);
    } else {
      $this->formatMsg[$msg] = $value;
    }
  }

  /** 设置字段名
   * setAttrName function.
   *
   * @access public
   * @param mixed $attr 字段
   * @param string $value (default: '') 值
   * @return void
   */
  public function setAttrName($attr, $value = '') {
    if (is_array($attr)) {
      $this->attrName = array_replace_recursive($this->attrName, $attr);
    } else {
      $this->attrName[$attr] = $value;
    }
  }

  /** 验证
   * verify function.
   *
   * @access public
   * @param array $data (default: array()) 待验证数据
   * @return bool
   */
  public function verify($data = array()) {
    $_bool_return   = true;
    $_num_err       = 0;

    $this->data     = $data;

    $_arr_rule      = $this->getRule(); // 获取规则

    if (Func::notEmpty($_arr_rule)) {
      foreach ($_arr_rule as $_key=>$_value) { // 遍历规则
        if (!isset($data[$_key])) { // 数据中没有规则中定义的数据
          $data[$_key]       = ''; // 补全
          $this->data[$_key] = '';
        }

        if (is_array($data[$_key])) { // 如果数据为数组
          //$data[$_key]      = implode(',', $data[$_key]);
          $data[$_key]        = Arrays::toJson($data[$_key]);
          $this->data[$_key]  = $data[$_key];
        }

        /*print_r($_key);
        print_r(' | ');
        print_r($data[$_key]);
        print_r(PHP_EOL);*/

        $_num_errCheck  = $this->check($data[$_key], $_value, $_key); // 验证
        if ($_num_errCheck > 0) { // 错误计数
          $_num_err = $_num_err + $_num_errCheck;
        }
      }
    }

    if ($_num_err > 0) {
      $_bool_return = false; // 计算大于 0, 验证失败
    }

    return $_bool_return;
  }


  /** 设置当前场景
   * scene function.
   *
   * @access public
   * @param mixed $scene 场景名
   * @return 当前实例
   */
  public function scene($scene) {
    // 设置当前场景
    $this->currentScene = $scene;

    return $this;
  }

  /** 设置仅验证字段
   * only function.
   *
   * @access public
   * @param mixed $field 字段名
   * @return 当前实例
   */
  public function only($field) {
    if (is_array($field)) {
      $this->only = array_merge($this->only, $field);
    } else if (is_string($field)) {
      array_push($this->only, $field);
    }

    return $this;
  }

  /** 移除规则
   * remove function.
   *
   * @access public
   * @param mixed $field 字段
   * @return void
   */
  public function remove($field) {
    if (is_array($field)) {
      $this->remove = array_replace_recursive($this->remove, $field);
    } else if (is_string($field)) {
      array_push($this->remove, $field);
    }

    return $this;
  }

  /** 追加规则
   * append function.
   *
   * @access public
   * @param mixed $field 字段
   * @param string $rule (default: '') 规则
   * @return void
   */
  public function append($field, $rule = '') {
    if (is_array($field)) {
      $this->append = array_replace_recursive($this->append, $field);
    } else if (is_scalar($field)) {
      $this->append[$field] = $rule;
    }

    return $this;
  }


  /** 直接验证
   * is function.
   *
   * @access public
   * @param mixed $value 值
   * @param mixed $rule 规则
   * @return bool
   */
  public function is($value, $rule) {
    $_bool_return = false;

    if (isset($this->formatMsg[$rule])) { // 有效类型
      $_arr_rule = array(
        'type' => $rule,
      );
      $_bool_return = $this->checkItem($value, $_arr_rule); // 验证单个项目
    }

    return $_bool_return;
  }


  /** 获取消息
   * getMessage function.
   *
   * @access public
   * @return void
   */
  public function getMessage() {
    return $this->message;
  }


  /** 魔术静态调用
   * __callStatic function.
   *
   * @access public
   * @static
   * @param mixed $method 方法名
   * @param mixed $params 参数
   * @return void
   */
  public static function __callStatic($method, $params) {
    if (method_exists($this->obj_rule, $method)) {
      return call_user_func_array(array($this->obj_rule, Strings::toHump($method, '_', true)), $params);
    } else {
      $_obj_excpt = new Vld_Except('Method not found', 500);
      $_obj_excpt->setData('err_detail', __CLASS__ . '->' . $method);

      throw $_obj_excpt;
    }
  }


  /** 验证表单令牌
   * token function.
   *
   * @access public
   * @param mixed $value
   * @param string $rule (default: '__token__')
   * @return void
   */
  private function token($value, $rule = '__token__') {
    return Session::get($rule) === $value;
  }

  /** 验证码
   * captcha function.
   *
   * @access public
   * @param mixed $value
   * @return void
   */
  private function captcha($value, $id = '') {
    $_obj_captcha = Captcha::instance();

    /*print_r('value: ');
    print_r($value);
    print_r('<br>id: ');
    print_r($id);*/

    return $_obj_captcha->check($value, $id);
  }


  /** 验证
   * check function.
   *
   * @access private
   * @param mixed $value 值
   * @param mixed $rule 规则
   * @param string $key (default: '') 名称
   * @return 错误数
   */
  private function check($value, $rule, $key = '') {
    $_num_err   = 0;
    $_arr_rule  = $this->parseRule($rule); // 解析规则

    //print_r($_arr_rule);

    if (Func::notEmpty($_arr_rule)) {
      foreach ($_arr_rule as $_key_item=>$_value_item) { // 遍历单个规则
        /*print_r('key: ' . $key);
        print_r(' --- ');
        print_r('value: ' . $value);
        print_r(' --- ');
        print_r('type: ' . $_value_item['type']);
        print_r(' --- ');
        print_r('rule: ' . $_value_item['rule']);
        print_r(PHP_EOL);*/

        if (isset($_value_item['type']) && isset($_value_item['rule'])) {
          if (!$this->checkItem($value, $_value_item, $key)) { // 验证单个项目
            ++$_num_err;
          }
        }
      }
    }

    return $_num_err;
  }


  /** 验证单个项目
   * checkItem function.
   *
   * @access private
   * @param mixed $value 待验证值
   * @param mixed $rule 规则
   * @param string $key (default: '') 名称
   * @return void
   */
  private function checkItem($value, $rule, $key = '') {
    $_bool_return = false;

    /*print_r($rule);
    print_r(PHP_EOL);*/

    switch ($rule['type']) {
      case 'require':
        $_bool_return = $this->obj_rule->min($value, 1);
      break;

      case 'length':
        $_bool_return = $this->obj_rule->leng($value, $rule['rule']);
      break;

      case 'between':
      case 'not_between':
      case 'expire':
      case 'in':
      case 'not_in':
      case 'egt':
      case 'gt':
      case 'elt':
      case 'lt':
      case 'eq':
      case 'neq':
      case 'before':
      case 'after':
      case 'filter':
      case 'regex':
      case 'min':
      case 'max':
        /*print_r($value);
        print_r(' -- ');
        print_r($rule);
        print_r(PHP_EOL);*/

        $_bool_return = call_user_func_array(array($this->obj_rule, Strings::toHump($rule['type'], '_', true)), array($value, $rule['rule']));
      break;

      case 'token':
        $_bool_return = $this->token($value, $rule['rule']); // 表单令牌
      break;

      case 'captcha':
        $_bool_return = $this->captcha($value, $rule['rule']); // 验证码
      break;

      case 'accepted':
        if (in_array($value, array('1', 'on', 'yes'))) {
          $_bool_return = true;
        }
      break;

      case 'date_format':
      case 'time_format':
      case 'date_time_format':
        $_bool_return = $this->obj_rule->dateFormat($value, $rule['rule']);
      break;

      case 'confirm':
        if (!is_string($rule['rule'])) {
          $rule['rule'] = str_ireplace('_confirm', '', $key);
        }

        /*print_r('key: ' . $key . ' -- ');
        print_r('value: ' . $value . ' -- ');
        print_r('rule: ' . $rule['rule'] . ' -- ');*/

        $_data = $this->data[$rule['rule']];

        $_bool_return = $this->obj_rule->confirm($value, $_data); // 确认输入
      break;

      case 'different':
        if (!is_string($rule['rule'])) {
          $rule['rule'] = str_ireplace('_different', '', $key);
        }

        /*print_r('key: ' . $key . ' -- ');
        print_r('value: ' . $value . ' -- ');
        print_r('rule: ' . $rule['rule'] . ' -- ');*/

        $_data = $this->data[$rule['rule']];

        $_bool_return = $this->obj_rule->different($value, $_data);
      break;

      case 'format':
        if (Func::isEmpty($value)) {
          $_bool_return = true;
        } else {
          switch ($rule['rule']) {
            case 'alpha':
              $_bool_return = ctype_alpha($value);
            break;

            case 'alpha_number':
              $_bool_return = ctype_alnum($value);
            break;

            case 'number':
              $_bool_return = ctype_digit((string)$value);
            break;

            case 'bool':
              $_bool_return = in_array($value, array(true, false, 0, 1, '0', '1'), true);
            break;

            case 'array':
              $_bool_return = is_array($value);
            break;

            case 'date':
            case 'time':
            case 'date_time':
              $_bool_return = strtotime($value) !== false;
            break;

            case 'alpha_dash':
              // 只允许字母、数字和下划线 破折号
              $_bool_return = $this->obj_rule->regex($value, '/^[A-Za-z0-9\-\_]+$/');
            break;

            case 'chs':
              // 只允许汉字
              $_bool_return = $this->obj_rule->regex($value, '/^[\x{4e00}-\x{9fa5}]+$/u');
            break;

            case 'chs_alpha':
              // 只允许汉字、字母
              $_bool_return = $this->obj_rule->regex($value, '/^[\x{4e00}-\x{9fa5}a-zA-Z]+$/u');
            break;

            case 'chs_alpha_number':
              // 只允许汉字、字母和数字
              $_bool_return = $this->obj_rule->regex($value, '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]+$/u');
            break;

            case 'chs_dash':
              // 只允许汉字、字母、数字和下划线_及破折号-
              $_bool_return = $this->obj_rule->regex($value, '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9\_\-]+$/u');
            break;

            case 'url':
              // 是否为一个URL地址
              $_bool_return = $this->obj_rule->regex($value, '/^((https?|ftp|file):)?\/\/[\-A-Za-z0-9+&@#\/%?\=~\_|!:,\.;]+[\-A-Za-z0-9+&@#\/%\=~\_|]$/u');
              //$_bool_return = $this->obj_rule->filter($value, FILTER_VALIDATE_URL);
            break;

            case 'int':
              $_bool_return = $this->obj_rule->filter($value, FILTER_VALIDATE_INT);
            break;

            case 'float':
              $_bool_return = $this->obj_rule->filter($value, FILTER_VALIDATE_FLOAT);
            break;

            case 'email':
              $_bool_return = $this->obj_rule->filter($value, FILTER_VALIDATE_EMAIL);
            break;

            case 'ip':
              // 是否为IP地址
              $_bool_return = $this->obj_rule->filter($value, FILTER_VALIDATE_IP, array(FILTER_FLAG_IPV4, FILTER_FLAG_IPV6));
            break;
          }
        }
      break;
    }

    if (!$_bool_return) {
      $_str_msg       = 'unknown'; // 错误消息
      $_str_field     = $key; // 字段名
      $_str_confirm   = $key; // 确认字段名
      $_str_different = $key; // 不同字段名

      if ($rule['type'] == 'format') {
        if (isset($this->formatMsg[$rule['rule']])) {
          $_str_msg = $this->formatMsg[$rule['rule']]; // 设置格式验证消息
        }
      } else {
        if (isset($this->typeMsg[$rule['type']])) {
          $_str_msg = $this->typeMsg[$rule['type']]; // 设置类型验证消息
        }
      }

      if (isset($this->attrName[$key])) {
        $_str_field = $this->attrName[$key]; // 设置字段名
      }

      if (isset($this->attrName[$rule['rule']])) {
        $_str_confirm   = $this->attrName[$rule['rule']]; // 设置确认字段名
        $_str_different = $this->attrName[$rule['rule']]; // 设置不同字段名
      }

      switch ($rule['type']) {
        case 'length':
        case 'between':
        case 'not_between':
          $rule['rule'] = str_replace(',', $this->delimiter, $rule['rule']); // 替换范围符号
        break;
      }

      $_str_msg = str_ireplace('{:attr}', $_str_field, $_str_msg); // 替换字段名
      $_str_msg = str_ireplace('{:rule}', $rule['rule'], $_str_msg); // 替换规则
      $_str_msg = str_ireplace('{:confirm}', $_str_confirm, $_str_msg); // 替换确认字段名
      $_str_msg = str_ireplace('{:different}', $_str_different, $_str_msg); // 替换不同字段名

      $this->message[$key] = $_str_msg; // 设置消息
    }

    /*print_r($rule['type']);
    print_r(' -- ');
    print_r($value);
    print_r(' -- ');
    print_r($_bool_return);
    print_r('<br>');*/

    return $_bool_return;
  }


  /** 解析规则
   * parseRule function.
   *
   * @access private
   * @param mixed $rule 规则
   * @return 解析后的规则
   */
  private function parseRule($rule) {
    $_arr_ruleReturn    = array();

    foreach ($rule as $_key=>$_value) {
      if (isset($this->alias[$_key])) { // 到规则别名数组中过滤一遍
        $_key = $this->alias[$_key];
      }

      if (isset($this->typeMsg[$_key])) { // 有效类型
        switch ($_key) {
          case 'token': // 表单令牌
            $_arr_ruleReturn[$_key]['type']    = $_key;
            if (is_string($_value)) {
              $_arr_ruleReturn[$_key]['rule']    = $_value;
            } else {
              $_arr_ruleReturn[$_key]['rule']    = '__token__';
            }
          break;

          case 'captcha': // 验证码
            $_arr_ruleReturn[$_key]['type']    = $_key;
            if (is_string($_value)) {
              $_arr_ruleReturn[$_key]['rule']    = $_value;
            } else {
              $_arr_ruleReturn[$_key]['rule']    = '';
            }
          break;

          case 'require':
            if ($_value === true || $_value === 'true') {
              $_arr_ruleReturn[$_key] = array(
                'type' => $_key,
                'rule' => 1,
              );
            }
          break;

          case 'length':
          case 'between':
          case 'not_between':
          case 'expire':
            if (Func::notEmpty($_value) && strpos($_value, ',')) {
              $_arr_ruleReturn[$_key] = array(
                'type' => $_key,
                'rule' => $_value,
              );
            }
          break;

          case 'min':
          case 'max':
          case 'in':
          case 'not_in':
          case 'egt':
          case 'gt':
          case 'elt':
          case 'lt':
          case 'eq':
          case 'neq':
          case 'before':
          case 'after':
          case 'filter':
          case 'regex':
            if (Func::notEmpty($_value)) {
              $_arr_ruleReturn[$_key] = array(
                'type' => $_key,
                'rule' => $_value,
              );
            }
          break;

          case 'date_format':
          case 'time_format':
          case 'date_time_format':
            $_str_format = $this->getRuleDate($_key); // 取得日期规则

            if (Func::notEmpty($_value)) {
              $_str_format = $_value;
            }

            $_arr_ruleReturn[$_key] = array(
              'type' => $_key,
              'rule' => $_str_format,
            );
          break;

          case 'format':
            if (isset($this->formatMsg[$_value])) {
              $_arr_ruleReturn[$_key] = array(
                'type' => $_key,
                'rule' => $_value,
              );
            }
          break;

          default:
            $_arr_ruleReturn[$_key]['type']    = $_key;
            if (is_string($_value)) {
              $_arr_ruleReturn[$_key]['rule']    = $_value;
            } else {
              $_arr_ruleReturn[$_key]['rule']    = 1;
            }
          break;
        }
      }
    }

    //print_r($_arr_ruleReturn);

    return $_arr_ruleReturn;
  }


  /** 取得规则
   * getRule function.
   *
   * @access private
   * @return void
   */
  private function getRule() {
    $_arr_rule = array();

    if (Func::isEmpty($this->currentScene) || !isset($this->scene[$this->currentScene]) || Func::isEmpty($this->scene[$this->currentScene])) { // 如果没有规定场景, 或规定的场景不存在, 则返回全部规则
      $_arr_rule = $this->rule;
    } else {
      foreach ($this->scene[$this->currentScene] as $_key=>$_value) { // 遍历当前场景下的规则
        if (is_numeric($_key)) {
          if (isset($this->rule[$_value])) {
            $_arr_rule[$_value] = $this->rule[$_value];
          }
        } else {
          if (isset($this->rule[$_key])) {
            $_arr_rule[$_key] = $this->rule[$_key];
          } else if (is_array($_value)) {
            $_arr_rule[$_key] = $_value;
          }
        }
      }
    }

    if (Func::notEmpty($this->only)) { // 如果规定了仅验证规则, 则取出匹配的规则
      $_arr_only = array();

      foreach ($this->only as $_key=>$_value) {
        if (isset($_arr_rule[$_value])) {
          $_arr_only[$_value] = $_arr_rule[$_value];
        }
      }

      if (Func::notEmpty($_arr_only)) {
        $_arr_rule = $_arr_only;
      }
    } else if (Func::notEmpty($this->remove)) { // 移除规则
      foreach ($this->remove as $_key=>$_value) {
        if (isset($_arr_rule[$_value])) {
          unset($_arr_rule[$_value]);
        }
      }
    } else if (Func::notEmpty($this->append)) { // 追加规则
      foreach ($this->append as $_key=>$_value) {
        if (!isset($_arr_rule[$_key])) {
          $_arr_rule[$_key] = $_value;
        }
      }
    }

    return $_arr_rule;
  }


  /** 取得日期规则
   * getRuleDate function.
   *
   * @access private
   * @param mixed $rule 规则类型
   * @return 规则
   */
  private function getRuleDate($rule) {
    $_str_format = '';

    switch ($rule) {
      case 'date_format':
        $_str_format = 'Y-m-d';
      break;

      case 'time_format':
        $_str_format = 'H:i:s';
      break;

      case 'date_time_format':
        $_str_format = 'Y-m-d H:i:s';
      break;
    }

    return $_str_format;
  }
}
