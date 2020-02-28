<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

use ginkgo\validate\Rule;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Validate {

    protected static $instance;
    private $message        = array();

    protected $rule         = array();
    protected $data         = array();
    protected $attrName     = array();
    protected $scene        = array();
    protected $only         = array();
    protected $remove       = array();
    protected $append       = array();
    protected $delimiter    = ' - ';

    protected $currentScene = null;

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

    //验证规则默认提示信息
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

    public function __construct() {
        $this->obj_lang = Lang::instance();

        $this->v_init();
    }

    protected function __clone() {

    }

    public static function instance() {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    protected function v_init() {

    }

    function rule($rule, $value = '') {
        if (is_array($rule)) {
            $this->rule = array_replace_recursive($this->rule, $rule);
        } else {
            $this->rule[$rule] = $value;
        }
    }

    function setScene($scene, $value = array()) {
        if (is_array($scene)) {
            $this->scene = array_replace_recursive($this->scene, $scene);
        } else {
            $this->scene[$scene] = $value;
        }
    }

    function setTypeMsg($msg, $value = '') {
        if (is_array($msg)) {
            $this->typeMsg = array_replace_recursive($this->typeMsg, $msg);
        } else {
            $this->typeMsg[$msg] = $value;
        }
    }

    function setFormatMsg($msg, $value = '') {
        if (is_array($msg)) {
            $this->formatMsg = array_replace_recursive($this->formatMsg, $msg);
        } else {
            $this->formatMsg[$msg] = $value;
        }
    }

    function setAttrName($attr, $value = '') {
        if (is_array($attr)) {
            $this->attrName = array_replace_recursive($this->attrName, $attr);
        } else {
            $this->attrName[$attr] = $value;
        }
    }

    function verify($data = array()) {
        $_bool_return   = true;
        $_num_err       = 0;

        $this->data     = $data;

        $_arr_rule = $this->getRule();

        if (!Func::isEmpty($_arr_rule)) {
            foreach ($_arr_rule as $_key=>$_value) {
                if (!isset($data[$_key])) {
                    $data[$_key]       = '';
                    $this->data[$_key] = '';
                }

                if (is_array($data[$_key])) {
                    //$data[$_key]       = implode(',', $data[$_key]);
                    $data[$_key] = Json::encode($data[$_key]);
                    $this->data[$_key] = $data[$_key];
                }

                /*print_r($_key);
                print_r(' | ');
                print_r($data[$_key]);
                print_r(PHP_EOL);*/

                $_num_errCheck  = $this->check($data[$_key], $_value, $_key);
                if ($_num_errCheck > 0) {
                    $_num_err = $_num_err + $_num_errCheck;
                }
            }
        }

        if ($_num_err > 0) {
            $_bool_return   = false;
        }

        return $_bool_return;
    }


    public function scene($scene) {
        // 设置当前场景
        $this->currentScene = $scene;

        return $this;
    }

    public function only($fields) {
        if (is_array($field)) {
            $this->only = $fields;
        }

        return $this;
    }

    /**
     * 移除某个字段的验证规则
     * @access public
     * @param  string|array  $field  字段名
     * @param  mixed         $rule   验证规则 null 移除所有规则
     * @return $this
     */
    public function remove($field) {
        if (is_array($field)) {
            $this->remove = array_replace_recursive($this->remove, $field);
        } else if (is_scalar($field)) {
            $this->remove[] = $field;
        }

        return $this;
    }

    /**
     * 追加某个字段的验证规则
     * @access public
     * @param  string|array  $field  字段名
     * @param  mixed         $rule   验证规则
     * @return $this
     */
    public function append($field, $rule = '') {
        if (is_array($field)) {
            $this->append = array_replace_recursive($this->append, $field);
        } else if (is_scalar($field)) {
            $this->append[$field] = $rule;
        }

        return $this;
    }


    private function check($value, $rule, $key = '') {
        $_num_err   = 0;
        $_arr_rule  = $this->parseRule($rule);

        if (!Func::isEmpty($_arr_rule)) {
            foreach ($_arr_rule as $_key_item=>$_value_item) {
                /*print_r('key: ' . $key);
                print_r(' --- ');
                print_r('value: ' . $value);
                print_r(' --- ');
                print_r('type: ' . $_value_item['type']);
                print_r(' --- ');
                print_r('rule: ' . $_value_item['rule']);
                print_r(PHP_EOL);*/

                if (isset($_value_item['type']) && isset($_value_item['rule'])) {
                    if (!$this->checkItem($value, $_value_item, $key)) {
                        ++$_num_err;
                    }
                }
            }
        }

        return $_num_err;
    }


    private function parseRule($rule) { //验证规则是否有效
        $_arr_ruleReturn    = array();

        foreach ($rule as $_key=>$_value) {
            if (isset($this->alias[$_key])) {
                $_key = $this->alias[$_key];
            }

            if (isset($this->typeMsg[$_key])) {
                switch ($_key) {
                    case 'token':
                        $_arr_ruleReturn[$_key]['type']    = $_key;
                        if (is_string($_value)) {
                            $_arr_ruleReturn[$_key]['rule']    = $_value;
                        } else {
                            $_arr_ruleReturn[$_key]['rule']    = '__token__';
                        }

                    break;

                    case 'require':
                        if ($_value === true || $_value == 'true') {
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
                        if (!Func::isEmpty($_value) && strpos($_value, ',')) {
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
                    case 'captcha':
                        if (!Func::isEmpty($_value)) {
                            $_arr_ruleReturn[$_key] = array(
                                'type' => $_key,
                                'rule' => $_value,
                            );
                        }
                    break;

                    case 'date_format':
                    case 'time_format':
                    case 'date_time_format':
                        $_str_format = $this->getRuleDate($_key);

                        if (!Func::isEmpty($_value)) {
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


    private function getRule() {
        $_arr_rule = array();

        if (Func::isEmpty($this->currentScene)) {
            $_arr_rule = $this->rule;
        } else {
            if (isset($this->scene[$this->currentScene]) && !Func::isEmpty($this->scene[$this->currentScene])) {
                foreach ($this->scene[$this->currentScene] as $_key=>$_value) {
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
            } else {
                $_arr_rule = $this->rule;
            }
        }

        if (!Func::isEmpty($this->only)) {
            $_arr_only = array();

            foreach ($this->only as $_key=>$_value) {
                if (isset($_arr_rule[$_value])) {
                    $_arr_only[$_value] = $_arr_rule[$_value];
                }
            }

            if (!Func::isEmpty($_arr_only)) {
                $_arr_rule = $_arr_only;
            }
        } else if (!Func::isEmpty($this->remove)) {
            foreach ($this->remove as $_key=>$_value) {
                if (isset($_arr_rule[$_value])) {
                    unset($_arr_rule[$_value]);
                }
            }
        } else if (!Func::isEmpty($this->append)) {
            foreach ($this->append as $_key=>$_value) {
                if (!isset($_arr_rule[$_key])) {
                    $_arr_rule[$_key] = $_value;
                }
            }
        }

        return $_arr_rule;
    }


    private function getRuleDate($rule) { //验证规则是否有效
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

    public function is($value, $rule) {
        $_bool_return = false;

        if (isset($this->formatMsg[$rule])) {
            $_arr_rule = array(
                'type' => $rule,
            );
            $_bool_return = $this->checkItem($value, $_arr_rule);
        }

        return $_bool_return;
    }

    private function checkItem($value, $rule, $key = '') {
        $_bool_return = false;

        /*print_r($rule);
        print_r(PHP_EOL);*/

        switch ($rule['type']) {
            case 'require':
                $_bool_return = Rule::min($value, 1);
            break;

            case 'length':
                $_bool_return = Rule::leng($value, $rule['rule']);
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

                $_bool_return = call_user_func_array('ginkgo\validate\Rule::' . Func::toHump($rule['type'], '_', true), array($value, $rule['rule']));
            break;

            case 'token':
                $_bool_return = $this->token($value, $rule['rule']);
            break;

            case 'captcha':
                $_bool_return = $this->captcha($value);
            break;

            case 'accepted':
                if (in_array($value, array('1', 'on', 'yes'))) {
                    $_bool_return = true;
                }
            break;

            case 'date_format':
            case 'time_format':
            case 'date_time_format':
                $_bool_return = Rule::dateFormat($value, $rule['rule']);
            break;

            case 'confirm':
                if (is_string($rule['rule'])) {
                    $_str_rule = $rule['rule'];
                } else {
                    $_str_rule = str_ireplace('_confirm', '', $key);
                }

                /*print_r('key: ' . $key . ' -- ');
                print_r('value: ' . $value . ' -- ');
                print_r('rule: ' . $_str_rule . ' -- ');*/

                $_bool_return = $this->confirm($value, $_str_rule);
            break;

            case 'different':
                if (is_string($rule['rule'])) {
                    $_str_rule = $rule['rule'];
                } else {
                    $_str_rule = str_ireplace('_different', '', $key);
                }

                /*print_r('key: ' . $key . ' -- ');
                print_r('value: ' . $value . ' -- ');
                print_r('rule: ' . $_str_rule . ' -- ');*/

                $_bool_return = $this->confirm($value, $_str_rule, true);
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
                            $_bool_return = Rule::regex($value, '/^[A-Za-z0-9\-\_]+$/');
                        break;

                        case 'chs':
                            // 只允许汉字
                            $_bool_return = Rule::regex($value, '/^[\x{4e00}-\x{9fa5}]+$/u');
                        break;

                        case 'chs_alpha':
                            // 只允许汉字、字母
                            $_bool_return = Rule::regex($value, '/^[\x{4e00}-\x{9fa5}a-zA-Z]+$/u');
                        break;

                        case 'chs_alpha_number':
                            // 只允许汉字、字母和数字
                            $_bool_return = Rule::regex($value, '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]+$/u');
                        break;

                        case 'chs_dash':
                            // 只允许汉字、字母、数字和下划线_及破折号-
                            $_bool_return = Rule::regex($value, '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9\_\-]+$/u');
                        break;

                        case 'url':
                            // 是否为一个URL地址
                            $_bool_return = Rule::regex($value, '/^((https?|ftp|file):)?\/\/[\-A-Za-z0-9+&@#\/%?\=~\_|!:,\.;]+[\-A-Za-z0-9+&@#\/%\=~\_|]$/u');
                            //$_bool_return = Rule::filter($value, FILTER_VALIDATE_URL);
                        break;

                        case 'int':
                            $_bool_return = Rule::filter($value, FILTER_VALIDATE_INT);
                        break;

                        case 'float':
                            $_bool_return = Rule::filter($value, FILTER_VALIDATE_FLOAT);
                        break;

                        case 'email':
                            $_bool_return = Rule::filter($value, FILTER_VALIDATE_EMAIL);
                        break;

                        case 'ip':
                            // 是否为IP地址
                            $_bool_return = Rule::filter($value, FILTER_VALIDATE_IP, array(FILTER_FLAG_IPV4, FILTER_FLAG_IPV6));
                        break;
                    }
                }
            break;
        }

        if (!$_bool_return) {
            $_str_msg       = 'unknown';
            $_str_field     = $key;
            $_str_confirm   = $key;
            $_str_different = $key;

            if ($rule['type'] == 'format') {
                if (isset($this->formatMsg[$rule['rule']])) {
                    $_str_msg = $this->formatMsg[$rule['rule']];
                }
            } else {
                if (isset($this->typeMsg[$rule['type']])) {
                    $_str_msg = $this->typeMsg[$rule['type']];
                }
            }

            if (isset($this->attrName[$key])) {
                $_str_field = $this->attrName[$key];
            }

            if (isset($this->attrName[$rule['rule']])) {
                $_str_confirm   = $this->attrName[$rule['rule']];
                $_str_different = $this->attrName[$rule['rule']];
            }

            switch ($rule['type']) {
                case 'length':
                case 'between':
                case 'not_between':
                    $rule['rule'] = str_ireplace(',', $this->delimiter, $rule['rule']);
                break;
            }

            $_str_msg = str_ireplace('{:attr}', $_str_field, $_str_msg);
            $_str_msg = str_ireplace('{:rule}', $rule['rule'], $_str_msg);
            $_str_msg = str_ireplace('{:confirm}', $_str_confirm, $_str_msg);
            $_str_msg = str_ireplace('{:different}', $_str_different, $_str_msg);

            $this->message[$key] = $_str_msg;
        }

        /*print_r($rule['type']);
        print_r(' -- ');
        print_r($value);
        print_r(' -- ');
        print_r($_bool_return);
        print_r('<br>');*/

        return $_bool_return;
    }

    protected function confirm($value, $rule, $is_different = false) {
        /*print_r('data[$rule]: ');
        print_r($this->data);
        print_r(' -- ');
        print_r('$value: ' . $value . ' -- ');*/

        if ($is_different) {
            return $this->data[$rule] != $value;
        } else {
            return $this->data[$rule] == $value;
        }
    }

    function token($value, $rule = '__token__') {
        return Session::get($rule) === $value;
    }

    function captcha($value) {
        $_obj_captcha = Captcha::instance();

        return $_obj_captcha->check($value);
    }

    public function getMessage() {
        return $this->message;
    }


    public static function __callStatic($method, $params) {
        $_class = self::instance();
        if (method_exists('Rule', $method)) {
            return call_user_func_array('ginkgo\validate\Rule::' . Func::toHump($method, '_', true), $params);
        } else {
            $_obj_excpt = new Exception('Method not found', 500);
            $_obj_excpt->setData('err_detail', __CLASS__ . '->' . $method);

            throw $_obj_excpt;
        }
    }
}