<?php
define('IN_BAIGO', true);
define('DS', DIRECTORY_SEPARATOR);

class CLASS_CONFIG {
    function __construct() {
        $this->arr_config = array(
            'BG_DEBUG_SYS'           => array(
                'default'   => 0,
                'kind'      => 'num',
            ),
            'BG_DEBUG_DB'            => array(
                'default'   => 0,
                'kind'      => 'num',
            ),
            'BG_SWITCH_LANG'         => array(
                'default'   => 0,
                'kind'      => 'num',
            ),
            'BG_SWITCH_UI'           => array(
                'default'   => 0,
                'kind'      => 'num',
            ),
            'BG_SWITCH_TOKEN'        => array(
                'default'   => 1,
                'kind'      => 'num',
            ),
            'BG_SWITCH_SMARTY_DEBUG' => array(
                'default'   => 0,
                'kind'      => 'num',
            ),
            'BG_DEFAULT_SESSION'     => array(
                'default'   => 1200,
                'kind'      => 'num',
            ),
            'BG_DEFAULT_PERPAGE'     => array(
                'default'   => 30,
                'kind'      => 'num',
            ),
            'BG_DEFAULT_LANG'        => array(
                'default'   => 'zh_CN',
                'kind'      => 'str',
            ),
            'BG_DEFAULT_UI'          => array(
                'default'   => 'default',
                'kind'      => 'str',
            ),
            'BG_NAME_TPL'            => array(
                'default'   => 'tpl',
                'kind'      => 'str',
            ),
            'BG_NAME_CACHE'          => array(
                'default'   => 'cache',
                'kind'      => 'str',
            ),
            'BG_NAME_HELP'           => array(
                'default'   => 'help',
                'kind'      => 'str',
            ),
            'BG_NAME_CORE'           => array(
                'default'   => 'core',
                'kind'      => 'str',
            ),
            'BG_NAME_MODULE'         => array(
                'default'   => 'module',
                'kind'      => 'str',
            ),
            'BG_NAME_MODEL'          => array(
                'default'   => 'model',
                'kind'      => 'str',
            ),
            'BG_NAME_CONTROL'        => array(
                'default'   => 'control',
                'kind'      => 'str',
            ),
            'BG_NAME_INC'            => array(
                'default'   => 'inc',
                'kind'      => 'str',
            ),
            'BG_NAME_LANG'           => array(
                'default'   => 'lang',
                'kind'      => 'str',
            ),
            'BG_NAME_CLASS'          => array(
                'default'   => 'class',
                'kind'      => 'str',
            ),
            'BG_NAME_FUNC'           => array(
                'default'   => 'func',
                'kind'      => 'str',
            ),
            'BG_NAME_FONT'           => array(
                'default'   => 'font',
                'kind'      => 'str',
            ),
            'BG_NAME_LIB'            => array(
                'default'   => 'lib',
                'kind'      => 'str',
            ),
            'BG_NAME_CONSOLE'        => array(
                'default'   => 'console',
                'kind'      => 'str',
            ),
            'BG_NAME_PERSONAL'             => array(
                'default'   => 'personal',
                'kind'      => 'str',
            ),
            'BG_NAME_MISC'           => array(
                'default'   => 'misc',
                'kind'      => 'str',
            ),
            'BG_NAME_INSTALL'        => array(
                'default'   => 'install',
                'kind'      => 'str',
            ),
            'BG_NAME_API'            => array(
                'default'   => 'api',
                'kind'      => 'str',
            ),
            'BG_NAME_STATIC'         => array(
                'default'   => 'static',
                'kind'      => 'str',
            ),
            'BG_PATH_ROOT' => array(
                'default'   => 'realpath(__DIR__ . \'/../\') . DS',
                'kind'      => 'const',
            ),
            'BG_PATH_HELP'           => array(
                'default'   => 'BG_PATH_ROOT . BG_NAME_HELP . DS',
                'kind'      => 'const',
            ),
            'BG_PATH_TPL'            => array(
                'default'   => 'BG_PATH_ROOT . BG_NAME_TPL . DS',
                'kind'      => 'const',
            ),
            'BG_PATH_CACHE'          => array(
                'default'   => 'BG_PATH_ROOT . BG_NAME_CACHE . DS',
                'kind'      => 'const',
            ),
            'BG_PATH_STATIC'         => array(
                'default'   => 'BG_PATH_ROOT . BG_NAME_STATIC . DS',
                'kind'      => 'const',
            ),
            'BG_PATH_CORE'           => array(
                'default'   => 'BG_PATH_ROOT . BG_NAME_CORE . DS',
                'kind'      => 'const',
            ),
            'BG_PATH_MODULE'         => array(
                'default'   => 'BG_PATH_CORE . BG_NAME_MODULE . DS',
                'kind'      => 'const',
            ),
            'BG_PATH_CONTROL'        => array(
                'default'   => 'BG_PATH_CORE . BG_NAME_CONTROL . DS',
                'kind'      => 'const',
            ),
            'BG_PATH_MODEL'          => array(
                'default'   => 'BG_PATH_CORE . BG_NAME_MODEL . DS',
                'kind'      => 'const',
            ),
            'BG_PATH_FONT'           => array(
                'default'   => 'BG_PATH_CORE . BG_NAME_FONT . DS',
                'kind'      => 'const',
            ),
            'BG_PATH_INC'            => array(
                'default'   => 'BG_PATH_CORE . BG_NAME_INC . DS',
                'kind'      => 'const',
            ),
            'BG_PATH_LANG'           => array(
                'default'   => 'BG_PATH_CORE . BG_NAME_LANG . DS',
                'kind'      => 'const',
            ),
            'BG_PATH_CLASS'          => array(
                'default'   => 'BG_PATH_CORE . BG_NAME_CLASS . DS',
                'kind'      => 'const',
            ),
            'BG_PATH_FUNC'           => array(
                'default'   => 'BG_PATH_CORE . BG_NAME_FUNC . DS',
                'kind'      => 'const',
            ),
            'BG_PATH_LIB'            => array(
                'default'   => 'BG_PATH_CORE . BG_NAME_LIB . DS',
                'kind'      => 'const',
            ),
            'BG_PATH_TPLSYS'         => array(
                'default'   => 'BG_PATH_CORE . BG_NAME_TPL . DS',
                'kind'      => 'const',
            ),
            'BG_URL_ROOT'            => array(
                'default'   => 'str_ireplace(DS, \'/\', str_ireplace($_SERVER[\'DOCUMENT_ROOT\'], \'\', BG_PATH_ROOT))',
                'kind'      => 'const',
            ),
            'BG_URL_HELP'            => array(
                'default'   => 'BG_URL_ROOT . BG_NAME_HELP . \'/\'',
                'kind'      => 'const',
            ),
            'BG_URL_CONSOLE'         => array(
                'default'   => 'BG_URL_ROOT . BG_NAME_CONSOLE . \'/\'',
                'kind'      => 'const',
            ),
            'BG_URL_PERSONAL'              => array(
                'default'   => 'BG_URL_ROOT . BG_NAME_PERSONAL . \'/\'',
                'kind'      => 'const',
            ),
            'BG_URL_MISC'            => array(
                'default'   => 'BG_URL_ROOT . BG_NAME_MISC . \'/\'',
                'kind'      => 'const',
            ),
            'BG_URL_INSTALL'         => array(
                'default'   => 'BG_URL_ROOT . BG_NAME_INSTALL . \'/\'',
                'kind'      => 'const',
            ),
            'BG_URL_API'             => array(
                'default'   => 'BG_URL_ROOT . BG_NAME_API . \'/\'',
                'kind'      => 'const',
            ),
            'BG_URL_STATIC'          => array(
                'default'   => 'BG_URL_ROOT . BG_NAME_STATIC . \'/\'',
                'kind'      => 'const',
            ),
        );

        $this->arr_dbconfig = array(
            'BG_DB_HOST' => array(
                'default'   => 'localhost',
                'kind'      => 'str',
            ),
            'BG_DB_PORT' => array(
                'default'   => 3306,
                'kind'      => 'num',
            ),
            'BG_DB_NAME' => array(
                'default'   => 'baigo_cms',
                'kind'      => 'str',
            ),
            'BG_DB_USER' => array(
                'default'   => 'baigo_cms',
                'kind'      => 'str',
            ),
            'BG_DB_PASS' => array(
                'default'   => 'baigo_cms',
                'kind'      => 'str',
            ),
            'BG_DB_CHARSET' => array(
                'default'   => 'utf8',
                'kind'      => 'str',
            ),
            'BG_DB_TABLE' => array(
                'default'   => 'sso_',
                'kind'      => 'str',
            ),
        );

        $this->arr_opt = array(
            'base' => array(
                'title' => 'Base Settings',
                'list'  => array(
                    'BG_SITE_NAME' => array(
                        'type'       => 'str',
                        'format'     => 'text',
                        'min'        => 1,
                        'default'    => 'baigo SSO',
                        'kind'       => 'str',
                    ),
                    'BG_SITE_DOMAIN' => array(
                        'type'       => 'str',
                        'format'     => 'text',
                        'min'        => 1,
                        'default'    => '$_SERVER[\'SERVER_NAME\']',
                        'kind'       => 'const',
                    ),
                    'BG_SITE_URL' => array(
                        'type'       => 'str',
                        'format'     => 'url',
                        'min'        => 1,
                        'default'    => '\'http://\' . $_SERVER[\'SERVER_NAME\']',
                        'kind'       => 'const',
                    ),
                    'BG_SITE_PERPAGE' => array(
                        'type'       => 'str',
                        'format'     => 'int',
                        'min'        => 1,
                        'default'    => 30,
                        'kind'       => 'num',
                    ),
                    'BG_SITE_DATE' => array(
                        'type'   => 'select',
                        'min'    => 1,
                        'option' => array(
                            'Y-m-d'     => '2014-09-11',
                            'y-m-d'     => '14-09-11',
                            'M. d, Y'   => 'Sep. 11, 2014',
                        ),
                        'default' => 'Y-m-d',
                        'kind'       => 'str',
                    ),
                    'BG_SITE_DATESHORT' => array(
                        'type'   => 'select',
                        'min'    => 1,
                        'option' => array(
                            'm-d'   => '09-11',
                            'M. d'  => 'Sep. 11',
                        ),
                        'default' => 'Y-m-d',
                        'kind'       => 'str',
                    ),
                    'BG_SITE_TIME' => array(
                        'type'   => 'select',
                        'min'    => 1,
                        'option' => array(
                            'H:i:s'     => '14:08:25',
                            'h:i:s A'   => '02:08:25 PM',
                        ),
                        'default' => 'H:i:s',
                        'kind'       => 'str',
                    ),
                    'BG_SITE_TIMESHORT' => array(
                        'type'   => 'select',
                        'min'    => 1,
                        'option' => array(
                            'H:i'   => '14:08',
                            'h:i A' => '02:08 PM',
                        ),
                        'default' => 'H:i',
                        'kind'       => 'str',
                    ),
                    'BG_ACCESS_EXPIRE' => array(
                        'type'      => 'select',
                        'min'       => 1,
                        'option' => array(
                            10    => '10 minutes',
                            20    => '20 minutes',
                            30    => '30 minutes',
                            40    => '40 minutes',
                            50    => '50 minutes',
                            60    => '60 minutes',
                            70    => '70 minutes',
                            80    => '80 minutes',
                            90    => '90 minutes',
                        ),
                        'default'   => 60,
                        'kind'       => 'num',
                    ),
                    'BG_REFRESH_EXPIRE' => array(
                        'type'      => 'select',
                        'min'       => 1,
                        'option' => array(
                            10    => '10 days',
                            20    => '20 days',
                            30    => '30 days',
                            40    => '40 days',
                            50    => '50 days',
                            60    => '60 days',
                        ),
                        'default'   => 60,
                        'kind'       => 'num',
                    ),
                    'BG_VERIFY_EXPIRE' => array(
                        'type'      => 'select',
                        'min'       => 1,
                        'option' => array(
                            10    => '10 minutes',
                            20    => '20 minutes',
                            30    => '30 minutes',
                            40    => '40 minutes',
                            50    => '50 minutes',
                            60    => '60 minutes',
                            70    => '70 minutes',
                            80    => '80 minutes',
                            90    => '90 minutes',
                        ),
                        'default'   => 30,
                        'kind'       => 'num',
                    ),
                    'BG_SITE_SSIN' => array(
                        'type'      => 'str',
                        'format'    => 'text',
                        'min'       => 1,
                        'default'   => $this->rand(6),
                        'kind'      => 'str',
                    ),
                ),
            ),

            'reg' => array(
                'title' => 'Register Settings',
                'list'  => array(
                    'BG_REG_ACC' => array(
                        'type'   => 'radio',
                        'min'    => 1,
                        'option' => array(
                            'enable'    => array(
                                'value'    => 'Allow'
                            ),
                            'disable'   => array(
                                'value'    => 'Forbid'
                            ),
                        ),
                        'default' => 'enable',
                        'kind'      => 'str',
                    ),
                    'BG_REG_NEEDMAIL' => array(
                        'type'   => 'radio',
                        'min'    => 1,
                        'option' => array(
                            'on'    => array(
                                'value'    => 'ON'
                            ),
                            'off'   => array(
                                'value'    => 'OFF'
                            ),
                        ),
                        'default' => 'off',
                        'kind'      => 'str',
                    ),
                    'BG_REG_ONEMAIL' => array(
                        'type'   => 'radio',
                        'min'    => 1,
                        'option' => array(
                            'true'    => array(
                                'value'    => 'Allow'
                            ),
                            'false'   => array(
                                'value'    => 'Forbid'
                            ),
                        ),
                        'default' => 'false',
                        'kind'      => 'str',
                    ),
                    'BG_LOGIN_MAIL' => array(
                        'type'   => 'radio',
                        'min'    => 1,
                        'option' => array(
                            'on'    => array(
                                'value'    => 'ON'
                            ),
                            'off'   => array(
                                'value'    => 'OFF'
                            ),
                        ),
                        'default'   => 'off',
                        'kind'      => 'str',
                    ),
                    'BG_REG_CONFIRM' => array(
                        'type'   => 'radio',
                        'min'    => 1,
                        'option' => array(
                            'on'    => array(
                                'value'    => 'ON'
                            ),
                            'off'   => array(
                                'value'    => 'OFF'
                            ),
                        ),
                        'default'   => 'off',
                        'kind'      => 'str',
                    ),
                    'BG_ACC_MAIL' => array(
                        'type'       => 'textarea',
                        'format'     => 'text',
                        'min'        => 0,
                        'default'    => '',
                        'kind'       => 'str',
                    ),
                    'BG_BAD_MAIL' => array(
                        'type'       => 'textarea',
                        'format'     => 'text',
                        'min'        => 0,
                        'default'    => '',
                        'kind'       => 'str',
                    ),
                    'BG_BAD_NAME' => array(
                        'type'       => 'textarea',
                        'format'     => 'text',
                        'min'        => 0,
                        'default'    => '',
                        'kind'       => 'str',
                    ),
                ),
            ),

            'smtp' => array(
                'title' => 'SMTP Settings',
                'list'  => array(
                    'BG_SMTP_HOST' => array(
                        'type'       => 'str',
                        'format'     => 'text',
                        'min'        => 1,
                        'default'    => 'smtp.' . $_SERVER['SERVER_NAME'],
                        'kind'       => 'str',
                    ),
                    'BG_SMTP_TYPE' => array(
                        'type'       => 'select',
                        'format'     => 'text',
                        'min'        => 1,
                        'option' => array(
                            'smtp'      => 'SMTP',
                            'phpmail'   => 'Mail',
                            'sendmail'  => 'Sendmail',
                            'qmail'     => 'Qmail',
                        ),
                        'default'    => 'smtp',
                        'kind'       => 'str',
                    ),
                    'BG_SMTP_SEC' => array(
                        'type'       => 'radio',
                        'format'     => 'text',
                        'min'        => 1,
                        'option' => array(
                            'off'   => array(
                                'value' => 'OFF',
                            ),
                            'tls'   => array(
                                'value' => 'TLS',
                            ),
                            'ssl'   => array(
                                'value' => 'SSL',
                            ),
                        ),
                        'default'    => 'off',
                        'kind'       => 'str',
                    ),
                    'BG_SMTP_PORT' => array(
                        'type'       => 'str',
                        'format'     => 'int',
                        'min'        => 1,
                        'default'    => 25,
                        'kind'       => 'str',
                    ),
                    'BG_SMTP_AUTH' => array(
                        'type'   => 'radio',
                        'min'    => 1,
                        'option' => array(
                            'true'    => array(
                                'value'    => 'Yes'
                            ),
                            'false'   => array(
                                'value'    => 'No'
                            ),
                        ),
                        'default' => 'true',
                        'kind'       => 'str',
                    ),

                    'BG_SMTP_AUTHTYPE' => array(
                        'type'   => 'radio',
                        'min'    => 1,
                        'option' => array(
                            'login'   => array(
                                'value'    => 'LOGIN'
                            ),
                            'plain'   => array(
                                'value'    => 'PLAIN'
                            ),
                            'cram-md5'    => array(
                                'value'    => 'CRAM-MD5'
                            ),
                            'xoauth2'   => array(
                                'value'    => 'XOAUTH2'
                            ),
                        ),
                        'default' => 'login',
                        'kind'    => 'str',
                    ),

                    'BG_SMTP_USER' => array(
                        'type'       => 'str',
                        'format'     => 'text',
                        'min'        => 1,
                        'default'    => 'user@' . $_SERVER['SERVER_NAME'],
                        'kind'       => 'str',
                    ),
                    'BG_SMTP_PASS' => array(
                        'type'       => 'str',
                        'format'     => 'text',
                        'min'        => 1,
                        'default'    => 'password',
                        'kind'       => 'str',
                    ),
                    'BG_SMTP_FROM' => array(
                        'type'       => 'str',
                        'format'     => 'text',
                        'min'        => 1,
                        'default'    => 'noreply@' . $_SERVER['SERVER_NAME'],
                        'kind'       => 'str',
                    ),
                    'BG_SMTP_REPLY' => array(
                        'type'       => 'str',
                        'format'     => 'text',
                        'min'        => 1,
                        'default'    => 'reply@' . $_SERVER['SERVER_NAME'],
                        'kind'       => 'str',
                    ),
                ),
            ),
        );
    }


    /** 生成配置
     * config_gen function.
     *
     * @access public
     * @return void
     */
    function config_gen() {
        $this->file_gen($this->arr_dbconfig, 'opt_dbconfig'); //数据库配置
        $this->file_gen($this->arr_config, 'config'); //全局配置
        $this->file_gen($this->arr_opt['base']['list'], 'opt_base'); //基本配置
        $this->file_gen($this->arr_opt['reg']['list'], 'opt_reg'); //注册配置
        $this->file_gen($this->arr_opt['smtp']['list'], 'opt_smtp'); //注册配置
    }


    /** 生成文件
     * file_gen function.
     *
     * @access private
     * @param mixed $arr_configSrc
     * @param mixed $str_file
     * @return void
     */
    private function file_gen($arr_configSrc, $str_file) {
        $_str_constConfig   = '';
        $_str_config        = '';
        if (file_exists(BG_PATH_CONFIG . $str_file . '.inc.php')) { //如果文件存在
            if (defined('IS_INSTALL')) { //如果是安装状态，一一对比
                $_str_configChk = file_get_contents(BG_PATH_CONFIG . $str_file . '.inc.php'); //将配置文件转换为变量
                $_arr_config    = file(BG_PATH_CONFIG . $str_file . '.inc.php'); //将配置文件转换为数组
                $_arr_config    = array_filter(array_unique($_arr_config));

                foreach ($arr_configSrc as $_key_src=>$_value_src) {
                    if (!stristr($_str_configChk, $_key_src)) { //如不存在则加上
                        if ($_value_src['kind'] == 'str') {
                            $_str_constConfig = 'define(\'' . $_key_src . '\', \'' . $_value_src['default'] . '\');' . PHP_EOL;
                        } else {
                            $_str_constConfig = 'define(\'' . $_key_src . '\', ' . $_value_src['default'] . ');' . PHP_EOL;
                        }

                        array_push($_arr_config, $_str_constConfig);
                    }
                }

                foreach ($_arr_config as $_key_m=>$_value_m) { //拼接
                    $_str_config .= $_value_m;
                }

                $_str_config = preg_replace('/(require_once|include_once|require|include)\(.*\);/i', '', $_str_config);

                $_str_config = str_ireplace('?>', '', $_str_config); //去除旧版本配置文件的 php 结尾

                file_put_contents(BG_PATH_CONFIG . $str_file . '.inc.php', $_str_config);
            }
        } else { //如果文件不存在则生成默认
            $_str_config = '<?php' . PHP_EOL;
            foreach ($arr_configSrc as $_key_src=>$_value_src) {
                if ($_value_src['kind'] == 'str') {
                    $_str_config .= 'define(\'' . $_key_src . '\', \'' . $_value_src['default'] . '\');' . PHP_EOL;
                } else {
                    $_str_config .= 'define(\'' . $_key_src . '\', ' . $_value_src['default'] . ');' . PHP_EOL;
                }
            }

            file_put_contents(BG_PATH_CONFIG . $str_file . '.inc.php', $_str_config);
        }
    }



    /** 随机数
     * rand function.
     *
     * @access private
     * @param int $num_rand (default: 32)
     * @return void
     */
    private function rand($num_rand = 32) {
        $_str_char = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $_str_rnd = '';
        while (strlen($_str_rnd) < $num_rand) {
            $_str_rnd .= substr($_str_char, (rand(0, strlen($_str_char))), 1);
        }
        return $_str_rnd;
    }
}

$GLOBALS['obj_config'] = new CLASS_CONFIG();

$GLOBALS['obj_config']->config_gen();
