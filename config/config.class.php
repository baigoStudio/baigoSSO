<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

class CLASS_CONFIG {
    /** 生成配置
     * config_gen function.
     *
     * @access public
     * @return void
     */
    static function config_gen() {
        self::file_gen('config'); //全局配置
        self::file_gen('opt_dbconfig'); //数据库配置
        self::file_gen('opt_base'); //基本配置
        self::file_gen('opt_reg'); //注册配置
        self::file_gen('opt_smtp'); //注册配置
    }


    /** 生成文件
     * file_gen function.
     *
     * @access private
     * @param mixed $arr_configSrc
     * @param mixed $str_file
     * @return void
     */
    private static function file_gen($str_file) {
        $_str_config        = '';
        if (file_exists(BG_PATH_CONFIG . $str_file . '.inc.php')) { //如果文件存在并且是安装状态
            if (BG_APP == 'install') {
                $_str_config = file_get_contents(BG_PATH_CONFIG . $str_file . '.inc.php'); //将配置文件转换为变量
                $_str_config = preg_replace('/(require_once|include_once|require|include)\(.*\);/i', '', $_str_config);
                $_str_config = str_ireplace('?>', '', $_str_config); //去除旧版本配置文件的 php 结尾

                file_put_contents(BG_PATH_CONFIG . $str_file . '.inc.php', $_str_config);
            }
        } else { //如果文件不存在则生成默认
            $_str_config = '<?php' . PHP_EOL;

            file_put_contents(BG_PATH_CONFIG . $str_file . '.inc.php', $_str_config);
        }
    }
}

CLASS_CONFIG::config_gen();
