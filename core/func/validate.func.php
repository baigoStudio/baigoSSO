<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*------验证表单------
@str 验证字符串
@min 最小
@max 最大
@type 类型
@format 格式

返回多维数组
    str 表单最终值
    status 状态
*/
function fn_validate($str, $min = 0, $max = 0, $type = 'str', $format = 'text', $safe_chk = true) {
    switch ($type) {
        case 'str':
            $_status = CLASS_VALIDATE::is_text($str, $min, $max, $format); //验证字符串
        break;
        case 'digit':
            $_status = CLASS_VALIDATE::is_digit($str, $min, $max); //验证字符串
        break;
        case 'num':
            $_status = CLASS_VALIDATE::is_num($str, $min, $max); //验证个数
        break;
    }

    if ($safe_chk) {
        $str = fn_safe($str);
    }

    return array(
        'str'     => $str,
        'status'  => $_status
    );
}

class CLASS_VALIDATE {

    /*------验证长度------
    @str 需验证字符串
    @length(min, max) 数组，(最小长度, 最大长度) 0 为不限制

    返回字符
    too_short 太短
    too_long 太长
    ok 正常
    */
    static function v_leng($str, $min, $max) {
        if ($min > 0 && strlen($str) < $min) {
            $_status = 'too_short'; //如果定义最小长度，且短于，则返回太短
        } else if ($max > 0 && strlen($str) > $max) {
            $_status = 'too_long'; //如果定义最大长度，且长于，则返回太长
        } else {
            $_status = 'ok'; //返回正确
        }
        return $_status;
    }

    /*------验证格式------
    @str 需验证字符串
    @format 格式，text 为任意
    */
    static function v_reg($str, $format) {
        switch ($format) {
            case 'date':
                $_reg = '/^[0-9]{4}-(((0?[13578]|(10|12))-(0?[1-9]|[1-2][0-9]|3[0-1]))|(0?2-(0[1-9]|[1-2][0-9]))|((0?[469]|11)-(0[1-9]|[1-2][0-9]|30)))$/'; //日期
            break;
            case 'time':
                $_reg = '/^(([1-9]{1})|([0-1][0-9])|([1-2][0-3])):([0-5][0-9])(:([0-5][0-9]))?$/';
            break;
            case 'datetime': //日期时间
                $_reg = '/^[0-9]{4}-(((0?[13578]|(10|12))-(0?[1-9]|[1-2][0-9]|3[0-1]))|(0?2-(0[1-9]|[1-2][0-9]))|((0?[469]|11)-(0[1-9]|[1-2][0-9]|30)))\s(([1-9]{1})|([0-1][0-9])|([1-2][0-3])):([0-5][0-9])(:([0-5][0-9]))?$/';
            break;
            case 'int':
                $_reg = '/^(\+|-)?\d*$/'; //整数
            break;
            case 'digit':
                $_reg = '/^(\+|-)?\d*(\.\d+)*$/'; //数值，可以包含小数点
            break;
            case 'email':
                $_reg = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/'; //Email
            break;
            case 'url':
                $_reg = '/^((http|ftp|https):\/\/)(([a-zA-Z0-9\._-]+\.[a-zA-Z]{2,6})|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,4})*(\/[a-zA-Z0-9\&%_\.\/-~-]*)?$/'; //URL地址
            break;
            case 'alphabetDigit':
                $_reg = '/^[a-zA-Z\d]*$/'; //数字英文字母
            break;
            case 'strDigit':
                $_reg = '/^[\x{4e00}-\x{9fa5}a-zA-Z\d-_]*$/u'; // '/^[\\\u4e00-\\\u9fa5|\\\uf900-\\\ufa2d|\w]*$/' 中文字母数字下划线连字符
            break;
            case 'alias':
                $_reg = '/^[a-zA-Z\d-_]*$/'; // '/^[\\\u4e00-\\\u9fa5|\\\uf900-\\\ufa2d|\w]*$/' 字母数字下划线连字符
            break;
            default:
                $_reg = ''; //默认
            break;
        }

        if (!fn_isEmpty($str) && $format != 'text') { //如果值不为空，且格式不为text则验证
            if (preg_match($_reg, $str)) {
                return true; //验证通过，返回正确
            } else {
                return false; //验证失败，返回错误
            }
        } else {
            return true; //如果为text，直接返回正确
        }
    }

    /*------验证是否为字符串------
    @str 需验证的字符串
    @length(min, max) 数组，(最小长度, 最大长度) 0 为不限制
    @format 格式
    */
    static function is_text($str, $min, $max, $format) {
        $_status_leng = self::v_leng($str, $min, $max);
        if ($_status_leng != 'ok') {
            $_status = $_status_leng; //如验证长度出错，直接返回错误
        } else {
            if (self::v_reg($str, $format)) {
                $_status = 'ok'; //格式验证成功，返回正确
            } else {
                $_status = 'format_err'; //格式验证失败，返回错误
            }
        }
        return $_status;
    }

    /*------验证数字------
    @num 需验证的数字
    @length(min, max) 数组，(最小个数, 最大个数) 0 为不限制
    */
    static function is_digit($num, $min, $max) {
        if (is_numeric($num)) {
            if ($min > 0 && $num < $min){
                $_status = 'too_small'; //如果定义最小数，且小于，则返回太小
            } else if ($max > 0 && $num > $max){
                $_status = 'too_big'; //如果定义最大数，且大于，则返回太大
            } else {
                $_status = 'ok'; //返回正确
            }
        } else {
            $_status = 'format_err'; //格式验证失败，返回错误
        }
        return $_status;
    }

    /*------验证个数------
    @num 需验证的个数
    @length(min, max) 数组，(最小个数, 最大个数) 0 为不限制
    */
    static function is_num($num, $min, $max) {
        if ($min > 0 && $num < $min){
            $_status = 'too_few'; //如果定义最小个数，且少于，则返回太少
        } else if ($max > 0 && $num > $max){
            $_status = 'too_many'; //如果定义最大个数，且多于，则返回太多
        } else {
            $_status = 'ok'; //返回正确
        }
        return $_status;
    }
}