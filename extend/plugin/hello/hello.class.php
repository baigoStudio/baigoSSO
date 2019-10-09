<?php
namespace extend\plugin\hello;

use ginkgo\Plugin;

/**
* 这是一个 Hello 简单插件的实现
*/
if (!class_exists('extend\plugin\hello\Hello')) { //防止类重复
    class Hello {

        public $config;
        public $opts;

        function __construct() {
            //echo 'Hello';

            //注册这个插件
            //第一个参数是 钩子 的名称
            //第二个参数是 对象名 一般为本类
            //第三个是插件所执行的 方法（函数）
            Plugin::add('filter_fw_view', $this, 'sayHello');
            //Plugin::add('filter_fw_view', $this, 'doHello');
        }


        function sayHello($data) {
            //print_r($this->opts);
        }


        function doHello($data) {
            //echo '<div>test</div>';

            $data = '__doHello__';

            return $data;
        }
    }
}