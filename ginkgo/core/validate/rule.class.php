<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\validate;

use ginkgo\Func;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 验证规则
class Rule {

    /** 正则验证
     * regex function.
     *
     * @access public
     * @static
     * @param mixed $value 值
     * @param mixed $rule 规则
     * @return void
     */
    static function regex($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        if (strpos($rule, '/') === false && !preg_match('/\/[imsU]{0,4}$/', $rule)) {
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
    static function filter($value, $rule, $param = null) {
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
    static function leng($value, $rule) {
        $_status = true;

        $_arr_rule = explode(',', $rule);

        list($min, $max) = $_arr_rule;

        if ($min > 0 && strlen($value) < $min) {
            $_status = false;
        } else if ($max > 0 && strlen($value) > $max) {
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
    static function min($value, $rule = 0) {
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
    static function max($value, $rule = 0) {
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
    static function dateFormat($value, $rule) {
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
    static function expire($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        $_arr_rule = explode(',', $rule);

        list($start, $end) = $_arr_rule;

        if (!is_numeric($start)) {
            $start = Func::strtotime($start);
        }

        if (!is_numeric($end)) {
            $end = Func::strtotime($end);
        }

        return GK_NOW >= $start && GK_NOW <= $end;
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
    static function after($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        if (!is_numeric($value)) {
            $value = Func::strtotime($value);
        }

        if (!is_numeric($rule)) {
            $rule = Func::strtotime($rule);
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
    static function before($value, $rule) {
        return !self::after($value, $rule);
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
    static function in($value, $rule) {
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
    static function notIn($value, $rule) {
        return !self::in($value, $_arr_rule);
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
    static function between($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        $_arr_rule = explode(',', $rule);

        list($min, $max) = $_arr_rule;

        return $value >= $min && $value <= $max;
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
    static function notBetween($value, $rule) {
        return !self::between($value, $rule);
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
    static function gt($value, $rule) {
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
    static function lt($value, $rule) {
        return !self::gt($value, $rule);
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
    static function egt($value, $rule) {
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
    static function elt($value, $rule) {
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
    static function eq($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        return $value == $rule;
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
    static function neq($value, $rule) {
        return !self::eq($value, $rule);
    }
}