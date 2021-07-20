<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------------------通用-------------------------*/
return array(
    'often' => array(
        'title' => 'Common charset encoding',
        'lists' => array(
            'EUC-CN' => array(
                'title' => 'Simplified Chinese',
                'note'  => 'China Mainland',
            ),
            'GB2312' => array(
                'title' => 'Simplified Chinese',
                'note'  => 'China Mainland',
            ),
            'GBK' => array(
                'title' => 'Simplified Chinese',
                'note'  => 'China Mainland',
            ),
            'BIG-5' => array(
                'title' => 'Traditional Chinese',
                'note'  => 'China Taiwan, China Hong Kong, China Macao',

            ),
            'UTF-8' => array(
                'title' => 'Unicode',
                'note'  => '',
            ),
            'ASCII' => array(
                'title' => 'Englishi',
                'note'  => '',
            ),
            'EUC-JP' => array(
                'title' => 'Japanese',
                'note'  => '',
            ),
            'ISO-8859-15' => array(
                'title' => 'Western European',
                'note'  => 'Albanian, Basque, Breton, Catalan, Danish, Dutch, Englishi, Estonian, Faroese, Finnish, French, Frisian, Galician, German, Greenlandic, Icelandic, Irish Gaelic, Italian, Latin, Luxembourgish, Norwegian, Portuguese, Rito Romans, Scottish Gaelic, Spanish, Swedish',
            ),
            'EUC-KR' => array(
                'title' => 'Korean',
                'note'  => 'South Korea, North Korea',
            ),
        ),
    ),

    'lists' => array(
        'title' => 'All charset encoding',
        'lists' => array(
            'CP936' => array(
                'title' => 'Simplified Chinese',
                'note'  => 'China Mainland',
            ),
            'HZ' => array(
                'title' => 'Simplified Chinese',
                'note'  => 'China Mainland',
            ),
            'EUC-TW' => array(
                'title' => 'Traditional Chinese',
                'note'  => 'China Taiwan',
            ),
            'CP950' => array(
                'title' => 'Traditional Chinese',
                'note'  => 'China Taiwan, China Hong Kong, China Macao',
            ),
            'UTF-32' => array(
                'title' => 'Unicode',
                'note'  => '',
            ),
            'UTF-32BE' => array(
                'title' => 'Unicode',
                'note'  => '',
            ),
            'UTF-32LE' => array(
                'title' => 'Unicode',
                'note'  => '',
            ),
            'UTF-16' => array(
                'title' => 'Unicode',
                'note'  => '',
            ),
            'UTF-16BE' => array(
                'title' => 'Unicode',
                'note'  => '',
            ),
            'UTF-16LE' => array(
                'title' => 'Unicode',
                'note'  => '',
            ),
            'UTF-7' => array(
                'title' => 'Unicode',
                'note'  => '',
            ),
            'UTF7-IMAP' => array(
                'title' => 'Unicode',
                'note'  => '',
            ),
            'SJIS' => array(
                'title' => 'Japanese',
                'note'  => '',
            ),
            'eucJP-win' => array(
                'title' => 'Japanese',
                'note'  => '',
            ),
            'SJIS-win' => array(
                'title' => 'Japanese',
                'note'  => '',
            ),
            'ISO-2022-JP' => array(
                'title' => 'Japanese',
                'note'  => '',
            ),
            'ISO-2022-JP-MS' => array(
                'title' => 'Japanese',
                'note'  => '',
            ),
            'CP932' => array(
                'title' => 'Japanese',
                'note'  => '',
            ),
            'CP51932' => array(
                'title' => 'Japanese',
                'note'  => '',
            ),
            'JIS' => array(
                'title' => 'Japanese',
                'note'  => '',
            ),
            'JIS-ms' => array(
                'title' => 'Japanese',
                'note'  => '',
            ),
            'CP50220' => array(
                'title' => 'Japanese',
                'note'  => '',
            ),
            'CP50220raw' => array(
                'title' => 'Japanese',
                'note'  => '',
            ),
            'CP50221' => array(
                'title' => 'Japanese',
                'note'  => '',
            ),
            'CP50222' => array(
                'title' => 'Japanese',
                'note'  => '',
            ),
            'ISO-8859-1' => array(
                'title' => 'Western European',
                'note'  => 'Albanian, Basque, Breton, Catalan, Danish, Dutch, Faroese, Frisian, Galician, German, Greenlandic, Icelandic, Irish Gaelic, Italian, Latin, Luxembourgish, Norwegian, Portuguese, Rito Romans, Scottish Gaelic, Spanish, Swedish',
            ),
            'ISO-8859-2' => array(
                'title' => 'Central European',
                'note'  => 'Croatian, Czech, Hungarian, Polish, Slovak, Slovenian, Upper Sorbian, Lower Sorbian',
            ),
            'ISO-8859-3' => array(
                'title' => 'Southern European',
                'note'  => 'Maltese, Turkish',
            ),
            'ISO-8859-4' => array(
                'title' => 'Baltic sea',
                'note'  => 'Estonian, Greenlandic, Latvian, Lithuanian, Sami',
            ),
            'ISO-8859-5' => array(
                'title' => 'Cyrillic',
                'note'  => 'Russian, Belarusian, Bulgarian, Macedonian, Serbian, Ukrainian',
            ),
            'ISO-8859-6' => array(
                'title' => 'Arabic',
                'note'  => '',
            ),
            'ISO-8859-7' => array(
                'title' => 'Greek',
                'note'  => '',
            ),
            'ISO-8859-8' => array(
                'title' => 'Hebrew',
                'note'  => 'Israel, Jewish',
            ),
            'ISO-8859-9' => array(
                'title' => 'Turkish',
                'note'  => 'Maltese, Kurdish',
            ),
            'ISO-8859-10' => array(
                'title' => 'Nordic',
                'note'  => 'Russian, Belarusian, Bulgarian, Macedonian, Serbian, Ukrainian',
            ),
            'ISO-8859-13' => array(
                'title' => 'Baltic sea',
                'note'  => 'Estonian, Finnish, Latvian, Lithuanian',
            ),
            'ISO-8859-14' => array(
                'title' => 'Celtic',
                'note'  => 'Breton, Galician, Irish Gaelic, Isle of Man, Welsh',
            ),
            'UHC' => array(
                'title' => 'Korean',
                'note'  => 'South Korea, North Korea',
            ),
            'ISO-2022-KR' => array(
                'title' => 'Korean',
                'note'  => 'South Korea, North Korea',
            ),
            'Windows-1251' => array(
                'title' => 'Cyrillic',
                'note'  => '',
            ),
            'Windows-1252' => array(
                'title' => 'Western European',
                'note'  => '',
            ),
            'CP866' => array(
                'title' => 'Cyrillic',
                'note'  => '',
            ),
            'KOI8-R' => array(
                'title' => 'Cyrillic',
                'note'  => 'Russian, Bulgarian',
            ),
        ),
    ),
);
