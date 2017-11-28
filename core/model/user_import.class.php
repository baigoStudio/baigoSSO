<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------用户模型-------------*/
class MODEL_USER_IMPORT extends MODEL_USER {

    public $obj_db;
    public $charsetKeys = array();
    public $csvRows = array();
    public $userConvert;

    function __construct() { //构造函数
        $this->obj_db = $GLOBALS['obj_db']; //设置数据库对象
    }


    /** 导入预览
     * mdl_import function.
     *
     * @access public
     * @return void
     */
    function mdl_import($str_charset = '') {
        if (file_exists(BG_PATH_CACHE . 'sys' . DS . 'user_import.csv')) {
            $_obj_csv    = fopen(BG_PATH_CACHE . 'sys' . DS . 'user_import.csv', 'r');

            $_str_sample = fread($_obj_csv, 1000);
            rewind($_obj_csv);

            if (fn_isEmpty($str_charset)) {
                $_str_charset = @mb_detect_encoding($_str_sample, $this->charsetKeys, true);
            } else {
                $_str_charset = $str_charset;
            }

            if (!fn_isEmpty($_str_charset) && $_str_charset != 'UTF-8' && $_str_charset != 'ASCII') {
                @stream_filter_append($_obj_csv, 'convert.iconv.' . $_str_charset . '/UTF-8');
            }

            $_num_row    = 0;
            while ($_arr_data = @fgetcsv($_obj_csv)) {
                if (is_array($_arr_data) && !fn_isEmpty($_arr_data[0])) {
                    foreach ($_arr_data as $_key=>$_value) {
                        if (fn_isEmpty($_value)) {
                            $this->csvRows[$_num_row][] = '';
                        } else {
                            $_str_value = $_value;
                            $this->csvRows[$_num_row][] = fn_getSafe($_str_value, 'txt', '');
                        }
                    }
                    $_num_row++;
                }
            }
            fclose($_obj_csv);
        }

        return $this->csvRows;
    }


    /** 转换并导入数据库
     * mdl_convert function.
     *
     * @access public
     * @return void
     */
    function mdl_convert() {
        $_num_errChk      = 0;
        $this->mdl_import($this->userConvert['charset']);
        unset($this->csvRows[0]);

        $_num_userId = 0;

        if (in_array('user_name', $this->userConvert['user_convert']) && in_array('user_pass', $this->userConvert['user_convert'])) {
            foreach ($this->csvRows as $_key_row=>$_value_row) {
                foreach ($this->userConvert['user_convert'] as $_key_cel=>$_value_cel) {
                    switch ($_value_cel) {
                        case 'abort':

                        break;

                        default:
                            $_arr_userData[$_value_cel] = $_value_row[$_key_cel];
                        break;
                    }

                }

                $_arr_userRow = $this->mdl_read($_arr_userData['user_name'], 'user_name');

                if ($_arr_userRow['rcode'] == 'x010102') {
                    $_arr_userData['user_pass'] = fn_baigoCrypt($_arr_userData['user_pass'], $_arr_userData['user_name'], true);

                    $_num_userId = $this->obj_db->insert(BG_DB_TABLE . 'user', $_arr_userData);

                    if ($_num_userId >= 0) { //数据库插入是否成功
                        $_num_errChk++;
                    }
                }
            }
        }

        if ($_num_errChk > 0) {
            $_str_rcode = 'y010402';
        } else {
            $_str_rcode = 'x010402';
        }

        return array(
            'user_id'    => $_num_userId,
            'rcode'      => $_str_rcode,
        );
    }


    /** 转换表单验证
     * input_convert function.
     *
     * @access public
     * @return void
     */
    function input_convert() {
        if (!fn_token('chk')) { //令牌
            return array(
                'rcode' => 'x030206',
            );
        }

        $this->userConvert['user_convert'] = fn_post('user_convert');

        if (!in_array('user_name', $this->userConvert['user_convert'])) {
            return array(
                'rcode' => 'x010220',
            );
        }

        if (!in_array('user_pass', $this->userConvert['user_convert'])) {
            return array(
                'rcode' => 'x010221',
            );
        }

        $_str_charset = fn_getSafe(fn_post('charset'), 'txt', '');

        $this->userConvert['charset']   = fn_htmlcode($_str_charset, 'decode', 'url');

        $this->userConvert['rcode']     = 'ok';

        return $this->userConvert;
    }
}
