<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\validate;

use ginkgo\Func;
use ginkgo\Strings;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

// 验证规则
abstract class Rule {

    protected static $instance; // 当前实例

    protected function __construct() { }
    protected function __clone() { }

    public static function instance($config = array()) {
        if (Func::isEmpty(self::$instance)) {
            self::$instance = new static($config);
        }
        return self::$instance;
    }


    /** 正则验证
     * regex function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    public function regex($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        if (strpos($rule, '/') !== 0 && !preg_match('/\/[imsU]{0,4}$/', $rule)) {
            // 不是正则表达式则两端补上/
            $rule = '/^' . $rule . '$/';
        }

        return is_scalar($value) && preg_match($rule, (string)$value) === 1;
    }



    /** 使用 filter_var 函数验证
     * filter function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @param mixed $param (default: null) 参数
     * @return void
     */
    public function filter($value, $rule, $param = null) {
        if (Func::isEmpty($value)) {
            return true;
        }

        if (!is_int($rule)) {
            $rule = filter_id($rule);
        }

        return filter_var($value, $rule, $param) !== false;
    }


    /** 验证长度
     * leng function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    public function leng($value, $rule) {
        $_status = true;

        $_arr_rule = explode(',', $rule);

        $_min = $_arr_rule[0];
        $_max = $_arr_rule[1];

        if ($_min > 0 && strlen($value) < $_min) {
            $_status = false;
        } else if ($_max > 0 && strlen($value) > $_max) {
            $_status = false;
        }

        return $_status;
    }


    /** 验证最小长度
     * min function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    public function min($value, $rule = 0) {
        $_status = true;

        //print_r($rule);

        if ($rule > 0 && strlen($value) < $rule) {
            $_status = false;
        }

        return $_status;
    }

    /** 验证最大长度
     * max function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    public function max($value, $rule = 0) {
        $_status = true;

        //print_r($value);

        if ($rule > 0 && strlen($value) > $rule) {
            $_status = false;
        }

        return $_status;
    }

    /** 日期格式是否正确
     * dateFormat function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    public function dateFormat($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        $info = date_parse_from_format($rule, $value);

        return $info['warning_count'] == 0 && $info['error_count'] == 0;
    }


    /** 是否在有效期内
     * expire function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    public function expire($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        $_arr_rule = explode(',', $rule);

        $_start = $_arr_rule[0];
        $_end   = $_arr_rule[1];

        if (!is_numeric($_start)) {
            $_start = Strings::toTime($_start);
        }

        if (!is_numeric($_end)) {
            $_end = Strings::toTime($_end);
        }

        if (!is_numeric($value)) {
            $value = Strings::toTime($value);
        }

        return $value >= $_start && $value <= $_end;
    }


    /** 是否晚于规定时间
     * after function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    public function after($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        if (!is_numeric($value)) {
            $value = Strings::toTime($value);
        }

        if (!is_numeric($rule)) {
            $rule = Strings::toTime($rule);
        }

        return $value >= $rule;
    }

    /** 是否早于规定时间
     * before function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    public function before($value, $rule) {
        return !$this->after($value, $rule);
    }

    /** 值是否在规定的选项内
     * in function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    public function in($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        $_arr_rule = explode(',', $rule);

        return in_array($value, $_arr_rule);
    }


    /** 值是否不在规定的选项内
     * notIn function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    public function notIn($value, $rule) {
        return !$this->in($value, $_arr_rule);
    }


    /** 值介于规定之间
     * between function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    public function between($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        $_arr_rule = explode(',', $rule);

        $_min = $_arr_rule[0];
        $_max = $_arr_rule[1];

        return $value >= $_min && $value <= $_max;
    }


    /** 值在规定之外
     * notBetween function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    public function notBetween($value, $rule) {
        return !$this->between($value, $rule);
    }


    /** 是否大于某个值
     * gt function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    public function gt($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        return $value > $rule;
    }

    /** 是否小于某个值
     * lt function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    public function lt($value, $rule) {
        return !$this->gt($value, $rule);
    }


    /** 是否大于等于某个值
     * egt function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    public function egt($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        return $value >= $rule;
    }

    /** 是否小于等于某个值
     * elt function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    public function elt($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        return $value <= $rule;
    }


    /** 是否等于某个值
     * eq function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    public function eq($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        return $value === $rule;
    }

    /** 是否不等于某个值
     * neq function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    public function neq($value, $rule) {
        return !$this->eq($value, $rule);
    }


    /** 比较值是否相同
     * confirm function.
     *
     * @access protected
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @param bool $is_different (default: false) 是否不同
     * @return void
     */
    public function confirm($value, $rule) {
        return $this->eq($value, $rule);
    }

    /** 比较值是否不同
     * confirm function.
     *
     * @access protected
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @param bool $is_different (default: false) 是否不同
     * @return void
     */
     public function different($value, $rule) {
        return !$this->eq($value, $rule);
    }
}
