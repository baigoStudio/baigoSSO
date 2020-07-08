<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access Denied');

// html 处理类
class Html {

    private $str           = ''; // 源字符串
    private $tagAllow      = array(); // 允许保留的tag 例如: array('p', 'div')
    private $attrAllow     = array(); // 允许保留的属性 例如 :array('id', 'class', 'title')
    private $attrExcept    = array(); // 特例 例如: array('a' => array('href', 'class'), 'span' => array('class'))
    private $attrIgnore    = array(); // 忽略过滤的标记 例如: array('span','img')

    protected static $instance; //用静态属性保存实例

    protected function __construct() {

    }

    protected function __clone() {

    }


    /**
     * instance function.
     * 实例化
     * @access public
     * @static
     * @return 当前类的实例
     */
    public static function instance() {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }


    /** html 编码
     * encode function.
     *
     * @access public
     * @static
     * @param string $string 待编码的的 html
     * @return 编码后的 html
     */
    static function encode($string) {
        if (!Func::isEmpty($string)) {
            $string = trim(htmlentities($string, ENT_QUOTES, 'UTF-8'));
        }

        return $string;
    }


    /** html 解码
     * decode function.
     *
     * @access public
     * @static
     * @param string $string 待解码的的 html
     * @param string $spec (default: '') 特殊处理
     * @return 解码后的 html
     */
    static function decode($string, $spec = '') {
        //print_r($string);

        if (!Func::isEmpty($string)) {
            $string         = html_entity_decode($string, ENT_COMPAT, 'UTF-8');
            $_arr_src       = array('(', ')', '`');
            $_arr_dst       = array('&#40;', '&#41;', '&#96;');
            $_arr_srcSub    = array();
            $_arr_dstSub    = array();

            switch ($spec) {
                case 'json': //转换 json 特殊字符
                    $_arr_srcSub = array('&#58;', '&#91;', '&#93;', '&#123;', '&#125;');
                    $_arr_dstSub = array(':', '[', ']', '{', '}');
                break;
                case 'json_safe': //转换 json 特殊字符
                    $_arr_srcSub = array('&#58;', '&#91;', '&#93;', '&#123;', '&#125;');
                    $_arr_dstSub = array(':', '[', ']', '{', '}');
                break;
                case 'url': //转换 加密 特殊字符
                    $_arr_srcSub = array('&#58;', '&#45;', '&#61;', '&#63;');
                    $_arr_dstSub = array(':', '-', '=', '?');
                break;
                case 'selector': //转换 选择器 特殊字符
                    $_arr_srcSub = array('&#58;', '&#45;', '&#61;', '&#33;');
                    $_arr_dstSub = array(':', '-', '=', '!');
                break;
                case 'date_time':
                    $_arr_srcSub = array('&#45;', '&#58;');
                    $_arr_dstSub = array('-', ':');
                break;
                case 'rgb':
                    $_arr_src   = array('`');
                    $_arr_dst   = array('&#96;');
                break;
            }

            if (!Func::isEmpty($_arr_srcSub)) {
                $_arr_src = array_merge($_arr_src, $_arr_srcSub);
            }

            if (!Func::isEmpty($_arr_dstSub)) {
                $_arr_dst = array_merge($_arr_dst, $_arr_dstSub);
            }

            $string = str_replace($_arr_src, $_arr_dst, $string);

            $string = trim($string);
        }

        //$string = str_replace('{:br}', PHP_EOL, $string);

        return $string;
    }


    /** 剔除 tag
     * stripTag function.
     *
     * @access public
     * @param string $str 待处理 html
     * @return 处理后的 html
     */
    public function stripTag($str) {
        $this->str = $str;

        if (is_string($this->str) && !Func::isEmpty($this->str)) { // 判断字符串
            $_str_tagAllow  = $this->tagAllowProcess();
            $this->str      = strip_tags($this->str, $_str_tagAllow);
        }

        return $this->str;
    }


    /** 设置允许的 tag
     * setTagAllow function.
     *
     * @access public
     * @param array $param (default: array())
     * @return void
     */
    public function setTagAllow($param = array()) {
        $this->tagAllow = $param;
    }


    /** 剔除属性
     * stripAttr function.
     *
     * @access public
     * @param string $str 待处理 html
     * @return 处理后的 html
     */
    public function stripAttr($str) {
        $this->str = $str;

        if (is_string($this->str) && !Func::isEmpty($this->str)) { // 判断字符串
            //$this->str = strtolower($this->str); // 转成小写

            $_mix_eleRows = $this->findEle();
            if (is_string($_mix_eleRows)) {
                return $_mix_eleRows;
            }
            $_arr_nodeRows = $this->findAttr($_mix_eleRows);
            $this->removeAttr($_arr_nodeRows);
        }

        return $this->str;
    }


    /** 设置允许的属性
     * setAttrAllow function.
     *
     * @access public
     * @param array $param (default: array()) 属性
     * @return void
     */
    public function setAttrAllow($param = array()) {
        $this->attrAllow = $param;
    }


    /** 设置特例
     * setAttrExcept function.
     *
     * @access public
     * @param array $param (default: array()) 特例
     * @return void
     */
    public function setAttrExcept($param = array()) {
        $this->attrExcept = $param;
    }


    /** 设置忽略的标记
     * setAttrIgnore function.
     *
     * @access public
     * @param array $param (default: array()) 忽略
     * @return void
     */
    public function setAttrIgnore($param = array()) {
        $this->attrIgnore = $param;
    }


    /** 处理保留标签
     * tagAllowProcess function.
     *
     * @access private
     * @return 保留标签参数值
     */
    private function tagAllowProcess() {
        $_str_tagAllow = '';
        if (!Func::isEmpty($this->tagAllow)) {
            $_str_tagAllow .= '<' . implode('><', $this->tagAllow) . '>'; //拼接保留标签
        }
        return $_str_tagAllow;
    }


    /** 搜索需要处理的元素
     * findEle function.
     *
     * @access private
     * @return void
     */
    private function findEle() {
        $_arr_nodeRows = array();
        preg_match_all('/<([^ !\/\>\n]+)([^>]*)>/i', $this->str, $_arr_eleRows);

        if (isset($_arr_eleRows)) {
            foreach ($_arr_eleRows[1] as $_key=>$_value) {
                if (isset($_arr_eleRows[2][$_key])) {
                    $_str_literal   = $_arr_eleRows[0][$_key];
                    $_str_eleName   = $_arr_eleRows[1][$_key];
                    $_arr_attrRows  = $_arr_eleRows[2][$_key];

                    if (is_array($this->attrIgnore) && !in_array($_str_eleName, $this->attrIgnore)) {
                        $_arr_nodeRows[] = array(
                            'literal'   => $_str_literal,
                            'name'      => $_str_eleName,
                            'attrs'     => $_arr_attrRows
                        );
                    }
                }
            }
        }

        if (isset($_arr_nodeRows[0])) {
            return $_arr_nodeRows;
        } else {
            return $this->str;
        }
    }


    /** 搜索属性
     * findAttr function.
     *
     * @access private
     * @param array $arr_nodeRows 待处理节点
     * @return 处理完的数据
     */
    private function findAttr($arr_nodeRows) {
        foreach($arr_nodeRows as $_key=>&$_value) {
            preg_match_all('/([^ =]+)\s*=\s*["|\']{0,1}([^"\']*)["|\']{0,1}/i', $_value['attrs'], $_arr_attrRows);
            $_arr_attrs = array();
            if (isset($_arr_attrRows[1])) {
                foreach ($_arr_attrRows[1] as $_key_attr=>$_value_attr) {
                    $_str_literal   = $_arr_attrRows[0][$_key_attr];
                    $_str_attrName  = $_arr_attrRows[1][$_key_attr];
                    $_str_value     = $_arr_attrRows[2][$_key_attr];
                    $_arr_attrs[] = array(
                        'literal'   => $_str_literal,
                        'name'      => $_str_attrName,
                        'value'     => $_str_value
                    );
                }
            } else {
                $_value['attrs'] = null;
            }
            $_value['attrs'] = $_arr_attrs;
            unset($_arr_attrs);
        }
        return $arr_nodeRows;
    }

    /** 移除属性
     * removeAttr function.
     *
     * @access private
     * @param array $arr_nodeRows 待处理节点
     * @return void
     */
    private function removeAttr($arr_nodeRows) {
        foreach ($arr_nodeRows as $_key=>$_value) {
            $_str_nodeName = $_value['name'];
            $_str_newAttrs = '';
            if (is_array($_value['attrs'])) {
                foreach ($_value['attrs'] as $_key_attrs=>$_value_attrs) {
                    if ((is_array($this->attrAllow) && in_array($_value_attrs['name'], $this->attrAllow)) || $this->isAttrExcept($_str_nodeName, $_value_attrs['name'])) {
                        $_str_newAttrs = $this->createAttr($_str_newAttrs, $_value_attrs['name'], $_value_attrs['value']);
                    }
                }
            }
            $_str_replace = ($_str_newAttrs) ? "<$_str_nodeName $_str_newAttrs>" : "<$_str_nodeName>";
            $this->str = preg_replace('/' . $this->protect($_value['literal']) . '/i', $_str_replace, $this->str);
        }
    }

    /** 判断是否特例
     * isAttrExcept function.
     *
     * @access private
     * @param String $ele_name 元素名
     * @param String $attr_name 属性名
     * @return void
     */
    private function isAttrExcept($ele_name, $attr_name) {
        if (isset($this->attrExcept[$ele_name])) {
            if (in_array($attr_name, $this->attrExcept[$ele_name])) {
                return true;
            }
        }
        return false;
    }

    /** 创建属性
    * @param String $new_attrs
    * @param String $name
    * @param String $value
    * @return String
    */
    private function createAttr($new_attrs, $name, $value) {
        if (!Func::isEmpty($new_attrs)) {
            $new_attrs .= ' ';
        }
        $new_attrs .= "$name=\"$value\"";
        return $new_attrs;
    }


    /** 特殊字符转义
     * protect function.
     *
     * @access private
    * @param string $str 源字符串
     * @return 处理完的字符串
     */
    private function protect($str) {
        $conversions = array(
            '^'     => '\^',
            '['     => '\[',
            '.'     => '\.',
            '$'     => '\$',
            '{'     => '\{',
            '*'     => '\*',
            '('     => '\(',
            '\\'    => '\\\\',
            '/'     => '\/',
            '+'     => '\+',
            ')'     => '\)',
            '|'     => '\|',
            '?'     => '\?',
            '<'     => '\<',
            '>'     => '\>'
        );
        return strtr($str, $conversions);
    }

}