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

// html 处理类
class Html {

  public $html          = ''; // 源字符串
  public $tagAllow      = array(); // 允许保留的 tag 例如: array('p', 'div')
  public $attrAllow     = array(); // 允许保留的属性 例如: array('id', 'class', 'title')
  public $attrExcept    = array(); // 特例 例如: array('a' => array('href', 'class'), 'span' => array('class'))
  public $tagIgnore     = array(); // 忽略过滤的标记 例如: array('span','img')

  protected static $instance; //用静态属性保存实例

  protected function __construct() { }
  protected function __clone() { }


  /**
   * instance function.
   * 实例化
   * @access public
   * @static
   * @return 当前类的实例
   */
  public static function instance() {
    if (Func::isEmpty(self::$instance)) {
      self::$instance = new static();
    }
    return self::$instance;
  }


  /** html 编码
   * encode function.
   *
   * @access public
   * @static
   * @param string $string 待编码的的 html
   * @return 编码后的 html
   */
  public static function encode($string) {
    if (Func::notEmpty($string)) {
      $string = (string)$string;
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
  public static function decode($string, $spec = '') {
    //print_r($string);

    if (Func::notEmpty($string)) {
      $string         = (string)$string;
      $string         = html_entity_decode($string, ENT_COMPAT, 'UTF-8');
      $_arr_src       = array('(', ')', '`');
      $_arr_dst       = array('&#40;', '&#41;', '&#96;');
      $_arr_srcSub    = array();
      $_arr_dstSub    = array();

      switch ($spec) {
        case 'json': // 转换 json 特殊字符
        case 'json_safe':
          $_arr_srcSub = array('&#58;', '&#91;', '&#93;', '&#123;', '&#125;');
          $_arr_dstSub = array(':', '[', ']', '{', '}');
        break;
        case 'url': // 转换 url 特殊字符
          $_arr_srcSub = array('&#58;', '&#45;', '&#61;', '&#63;');
          $_arr_dstSub = array(':', '-', '=', '?');
        break;
        case 'selector': // 转换 选择器 特殊字符
          $_arr_srcSub = array('&#58;', '&#45;', '&#61;', '&#33;');
          $_arr_dstSub = array(':', '-', '=', '!');
        break;
        case 'date_time': // 转换 日期时间 特殊字符
        case 'datetime':
          $_arr_srcSub = array('&#45;', '&#58;');
          $_arr_dstSub = array('-', ':');
        break;
        case 'rgb': // 转换 rgb 值 特殊字符
          $_arr_src   = array('`');
          $_arr_dst   = array('&#96;');
        break;
      }

      if (Func::notEmpty($_arr_srcSub)) {
        $_arr_src = array_merge($_arr_src, $_arr_srcSub);
      }

      if (Func::notEmpty($_arr_dstSub)) {
        $_arr_dst = array_merge($_arr_dst, $_arr_dstSub);
      }

      $string = str_replace($_arr_src, $_arr_dst, $string);

      $string = trim($string);
    }

    //$string = str_replace('{:br}', PHP_EOL, $string);

    return $string;
  }


  /** 补全 html 标签的图片地址部分
   * fillImg function.
   *
   * @access public
   * @static
   * @param string $content html 全文
   * @param string $baseUrl 基本 url
   * @return 补全后的 html
   */
  public static function fillImg($content, $baseUrl) {
    $_pattern           = '/<img[^>]*src[=\"\'\s]+([^\.]*\/[^\.]+\.[^\"\']+)[\"\']?[^>]*>/i'; // 图片标签的正则
    //$_pattern_2         = '/\ssrc=["|\']?.*?["|\']?\s/i'; // 匹配图片src
    $_str_contentTemp   = self::decode($content); // html 解码
    $_str_contentTemp   = str_replace('\\', '', $_str_contentTemp); // 替换反斜杠

    preg_match_all($_pattern, $_str_contentTemp, $_arr_match); // 匹配图片

    //print_r($_arr_match);

    if (isset($_arr_match[1])) { //匹配成功
      $_arr_urlSrc    = array();
      $_arr_urlDst    = array();
      foreach ($_arr_match[1] as $_key=>$_value) { // 遍历匹配结果
        $_str_urlSrc    = trim($_value);
        $_str_urlSrc    = str_ireplace('src=', '', $_str_urlSrc); // 剔除属性
        $_str_urlSrc    = str_replace('"', '', $_str_urlSrc);
        $_str_urlSrc    = str_replace('\'', '', $_str_urlSrc);

        $_arr_urlSrc[]  = trim($_str_urlSrc); // 源路径
        $_arr_urlDst[]  = Func::fillUrl($_str_urlSrc, $baseUrl); // 补全后的替换路径
      }

      /*print_r($_arr_urlSrc);
      print_r('<br>');
      print_r($_arr_urlDst);
      print_r('<br>');*/

      $_arr_urlSrc = Arrays::unique($_arr_urlSrc); // 剔除重复项目
      $_arr_urlDst = Arrays::unique($_arr_urlDst);

      $content = str_replace($_arr_urlSrc, $_arr_urlDst, $content); // 替换
    }

    return $content;
  }


  /** 剔除 tag
   * stripTag function.
   *
   * @access public
   * @param string $html 待处理 html
   * @return 处理后的 html
   */
  public function stripTag($html) {
    $this->html = $html;

    if (is_string($this->html) && Func::notEmpty($this->html)) { // 判断字符串
      $_str_tagAllow  = $this->tagAllowProcess();
      $this->html     = strip_tags($this->html, $_str_tagAllow);
    }

    return $this->html;
  }


  /** 剔除属性
   * stripAttr function.
   *
   * @access public
   * @param string $html 待处理 html
   * @return 处理后的 html
   */
  public function stripAttr($html) {
    $this->html = $html;

    if (is_string($this->html) && Func::notEmpty($this->html)) { // 判断字符串
      $_mix_eleRows = $this->findEle();
      if (is_string($_mix_eleRows)) {
        return $_mix_eleRows;
      }
      $_arr_nodeRows = $this->findAttr($_mix_eleRows);
      $this->removeAttr($_arr_nodeRows);
    }

    return $this->html;
  }


  /** 设置允许的 tag
   * setTagAllow function.
   *
   * @access public
   * @param array $param (default: array())
   * @return void
   */
  public function setTagAllow($tag) {
    if (is_array($tag)) {
      $this->tagAllow = array_merge($this->tagAllow, $tag);
    } else if (is_string($tag)) {
      $this->tagAllow[] = $tag;
    }
  }


  /** 设置忽略的 tag
   * setTagIgnore function.
   *
   * @access public
   * @param array $param (default: array()) 忽略
   * @return void
   */
  public function setTagIgnore($tag) {
    if (is_array($tag)) {
      $this->tagIgnore = array_merge($this->tagIgnore, $tag);
    } else if (is_string($tag)) {
      $this->tagIgnore[] = $tag;
    }
  }


  /** 设置允许的属性
   * setAttrAllow function.
   *
   * @access public
   * @param array $param (default: array()) 属性
   * @return void
   */
  public function setAttrAllow($attr) {
    if (is_array($attr)) {
      $this->attrAllow = array_merge($this->attrAllow, $attr);
    } else if (is_string($attr)) {
      $this->attrAllow[] = $attr;
    }
  }


  /** 设置特例
   * setAttrExcept function.
   *
   * @access public
   * @param array $param (default: array()) 特例
   * @return void
   */
  public function setAttrExcept($tag, $attr = '') {
    if (is_array($tag)) {
      $this->attrExcept = array_replace_recursive($this->attrExcept, $tag);
    } else if (is_string($tag) && Func::notEmpty($attr)) {
      if (is_array($attr)) {
        $this->attrExcept[$tag] = $attr;
      } else if (is_string($attr)) {
        $this->attrExcept[$tag][] = $attr;
      }
    }
  }


  /** 处理保留标签
   * tagAllowProcess function.
   *
   * @access private
   * @return 保留标签参数值
   */
  private function tagAllowProcess() {
    $_str_tagAllow = '';
    if (Func::notEmpty($this->tagAllow)) {
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
    preg_match_all('/<([^ !\/\>\n]+)([^>]*)>/i', $this->html, $_arr_eleRows);

    if (isset($_arr_eleRows)) {
      foreach ($_arr_eleRows[1] as $_key=>$_value) {
        if (isset($_arr_eleRows[2][$_key])) {
          $_str_literal   = $_arr_eleRows[0][$_key];
          $_str_eleName   = $_arr_eleRows[1][$_key];
          $_arr_attrRows  = $_arr_eleRows[2][$_key];

          if (is_array($this->tagIgnore) && !in_array($_str_eleName, $this->tagIgnore)) {
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
      return $this->html;
    }
  }


  /** 搜索属性
   * findAttr function.
   *
   * @access private
   * @param array $nodeRows 待处理节点
   * @return 处理完的数据
   */
  private function findAttr($nodeRows) {
    foreach($nodeRows as $_key=>&$_value) {
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
    return $nodeRows;
  }

  /** 移除属性
   * removeAttr function.
   *
   * @access private
   * @param array $nodeRows 待处理节点
   * @return void
   */
  private function removeAttr($nodeRows) {
    foreach ($nodeRows as $_key=>$_value) {
      $_str_nodeName = $_value['name'];
      $_str_newAttrs = '';
      if (is_array($_value['attrs'])) {
        foreach ($_value['attrs'] as $_key_attrs=>$_value_attrs) {
          if ((is_array($this->attrAllow) && in_array($_value_attrs['name'], $this->attrAllow)) || $this->isAttrExcept($_str_nodeName, $_value_attrs['name'])) {
            $_str_newAttrs = $this->createAttr($_str_newAttrs, $_value_attrs['name'], $_value_attrs['value']);
          }
        }
      }
      $_str_replace = ($_str_newAttrs) ? '<' . $_str_nodeName . ' ' . $_str_newAttrs . '>' : '<' . $_str_nodeName . '>';
      $this->html   = preg_replace('/' . $this->protect($_value['literal']) . '/i', $_str_replace, $this->html);
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
  * @param String $tag
  * @param String $value
  * @return String
  */
  private function createAttr($new_attrs, $attr, $value) {
    if (Func::notEmpty($new_attrs)) {
      $new_attrs .= ' ';
    }
    $new_attrs .= $attr . '="' . $value . '"';
    return $new_attrs;
  }


  /** 特殊字符转义
   * protect function.
   *
   * @access private
  * @param string $html 源字符串
   * @return 处理完的字符串
   */
  private function protect($html) {
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
    return strtr($html, $conversions);
  }
}
