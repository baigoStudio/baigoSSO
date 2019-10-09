<?php
namespace extend\plugin\test;

use ginkgo\Plugin;

/**
* 这是一个 Hello 简单插件的实现
*/
if (!class_exists('extend\plugin\test\Test')) { //防止类重复
    class Test {

        public $config;
        public $opts;

        function __construct() {
            //注册这个插件
            //第一个参数是 钩子 的名称
            //第二个参数是 对象名 一般为本类
            //第三个是插件所执行的 方法（函数）
            Plugin::add('filter_console_link_edit', $this, 'sayExample');
        }


        function sayExample($param) {

            $param = str_ireplace('a', '2', $param);

            return $param;
        }
    }
}