<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\validate;

use ginkgo\Func;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Rule {

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



    /**
     * 使用filter_var方式验证
     * @access private
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
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



    /*------验证长度------
    @str 需验证字符串
    @length(min, max) 数组，(最小长度, 最大长度) 0 为不限制

    返回字符
    too_short 太短
    too_long 太长
    ok 正常
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


    static function min($value, $rule = 0) {
        $_status = true;

        //print_r($rule);

        if ($rule > 0 && strlen($value) < $rule) {
            $_status = false;
        }

        return $_status;
    }

    static function max($value, $rule = 0) {
        $_status = true;

        //print_r($value);

        if ($rule > 0 && strlen($value) > $rule) {
            $_status = false;
        }

        return $_status;
    }

    static function dateFormat($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        $info = date_parse_from_format($rule, $value);

        return $info['warning_count'] == 0 && $info['error_count'] == 0;
    }


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

    /**
     * 验证日期
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    static function before($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        if (!is_numeric($value)) {
            $value = Func::strtotime($value);
        }

        if (!is_numeric($rule)) {
            $rule = Func::strtotime($rule);
        }

        return $value <= $rule;
    }

    static function in($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        $_arr_rule = explode(',', $rule);

        return in_array($value, $_arr_rule);
    }

    /**
     * 验证是否不在某个范围
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    static function notIn($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        $_arr_rule = explode(',', $rule);

        return !in_array($value, $_arr_rule);
    }

    static function between($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        $_arr_rule = explode(',', $rule);

        list($min, $max) = $_arr_rule;

        return $value >= $min && $value <= $max;
    }


    static function notBetween($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        $_arr_rule = explode(',', $rule);

        list($min, $max) = $_arr_rule;

        return $value < $min || $value > $max;
    }


    /**
     * 验证是否大于等于某个值
     * @access private
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @param array     $data  数据
     * @return bool
     */
    static function egt($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        return $value >= $rule;
    }

    /**
     * 验证是否大于某个值
     * @access private
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @param array     $data  数据
     * @return bool
     */
    static function gt($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        return $value > $rule;
    }

    /**
     * 验证是否小于等于某个值
     * @access private
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @param array     $data  数据
     * @return bool
     */
    static function elt($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        return $value <= $rule;
    }

    /**
     * 验证是否小于某个值
     * @access private
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @param array     $data  数据
     * @return bool
     */
    static function lt($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        return $value < $rule;
    }

    /**
     * 验证是否等于某个值
     * @access private
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @return bool
     */
    static function eq($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        return $value == $rule;
    }

    static function neq($value, $rule) {
        if (Func::isEmpty($value)) {
            return true;
        }

        return $value != $rule;
    }
}