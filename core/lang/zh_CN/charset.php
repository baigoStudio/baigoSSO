<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------------------通用-------------------------*/
return array(
    'often' => array(
        'title' => '常用编码',
        'list' => array(
            'EUC-CN' => array(
                'title' => '中文-简体',
                'note'  => '中国大陆',
            ),
            'GB2312' => array(
                'title' => '中文-简体',
                'note'  => '中国大陆',
            ),
            'GBK' => array(
                'title' => '中文-简体',
                'note'  => '中国大陆',
            ),
            'BIG-5' => array(
                'title' => '中文-繁体',
                'note'  => '中国台湾、中国香港、中国澳门',

            ),
            'UTF-8' => array(
                'title' => '国际码',
                'note'  => '',
            ),
            'ASCII' => array(
                'title' => '现代英语',
                'note'  => '',
            ),
            'EUC-JP' => array(
                'title' => '日本语',
                'note'  => '',
            ),
            'ISO-8859-15' => array(
                'title' => '西欧语言',
                'note'  => '阿尔巴尼亚语、巴斯克语、布列塔尼语、加泰罗尼亚语、丹麦语、荷兰语、英语、爱沙尼亚语、法罗语、芬兰语、法语、弗里斯语、加利西亚语、德语、格陵兰语、冰岛语、爱尔兰盖尔语、意大利语、拉丁语、卢森堡语、挪威语、葡萄牙语、里托罗曼斯语、苏格兰盖尔语、西班牙语、瑞典语',
            ),
            'EUC-KR' => array(
                'title' => '朝鲜语',
                'note'  => '韩国、朝鲜',
            ),
        ),
    ),

    'list' => array(
        'title' => '所有编码',
        'list' => array(
            'CP936' => array(
                'title' => '中文-简体',
                'note'  => '中国大陆',
            ),
            'HZ' => array(
                'title' => '中文-简体',
                'note'  => '中国大陆',
            ),
            'EUC-TW' => array(
                'title' => '中文-繁体',
                'note'  => '中国台湾',
            ),
            'CP950' => array(
                'title' => '中文-繁体',
                'note'  => '中国台湾、中国香港、中国澳门',
            ),
            'UTF-32' => array(
                'title' => '国际码',
                'note'  => '',
            ),
            'UTF-32BE' => array(
                'title' => '国际码',
                'note'  => '',
            ),
            'UTF-32LE' => array(
                'title' => '国际码',
                'note'  => '',
            ),
            'UTF-16' => array(
                'title' => '国际码',
                'note'  => '',
            ),
            'UTF-16BE' => array(
                'title' => '国际码',
                'note'  => '',
            ),
            'UTF-16LE' => array(
                'title' => '国际码',
                'note'  => '',
            ),
            'UTF-7' => array(
                'title' => '国际码',
                'note'  => '',
            ),
            'UTF7-IMAP' => array(
                'title' => '国际码',
                'note'  => '',
            ),
            'SJIS' => array(
                'title' => '日文',
                'note'  => '',
            ),
            'eucJP-win' => array(
                'title' => '日文',
                'note'  => '',
            ),
            'SJIS-win' => array(
                'title' => '日文',
                'note'  => '',
            ),
            'ISO-2022-JP' => array(
                'title' => '日文',
                'note'  => '',
            ),
            'ISO-2022-JP-MS' => array(
                'title' => '日文',
                'note'  => '',
            ),
            'CP932' => array(
                'title' => '日文',
                'note'  => '',
            ),
            'CP51932' => array(
                'title' => '日文',
                'note'  => '',
            ),
            'JIS' => array(
                'title' => '日文',
                'note'  => '',
            ),
            'JIS-ms' => array(
                'title' => '日文',
                'note'  => '',
            ),
            'CP50220' => array(
                'title' => '日文',
                'note'  => '',
            ),
            'CP50220raw' => array(
                'title' => '日文',
                'note'  => '',
            ),
            'CP50221' => array(
                'title' => '日文',
                'note'  => '',
            ),
            'CP50222' => array(
                'title' => '日文',
                'note'  => '',
            ),
            'ISO-8859-1' => array(
                'title' => '西欧语言',
                'note'  => '阿尔巴尼亚语、巴斯克语、布列塔尼语、加泰罗尼亚语、丹麦语、荷兰语、法罗语、弗里西语、加利西亚语、德语、格陵兰语、冰岛语、爱尔兰盖尔语、意大利语、拉丁语、卢森堡语、挪威语、葡萄牙语、里托罗曼斯语、苏格兰盖尔语、西班牙语、瑞典语',
            ),
            'ISO-8859-2' => array(
                'title' => '中欧语言',
                'note'  => '克罗地亚语、捷克语、匈牙利语、波兰语、斯洛伐克语、斯洛文尼亚语、上索布语、下索布语',
            ),
            'ISO-8859-3' => array(
                'title' => '南欧语言',
                'note'  => '马耳他语、土耳其语',
            ),
            'ISO-8859-4' => array(
                'title' => '波罗的海周边',
                'note'  => '爱沙尼亚语、格陵兰语、拉脱维亚语、立陶宛语、萨米诸语言',
            ),
            'ISO-8859-5' => array(
                'title' => '西里尔语',
                'note'  => '俄语、白俄罗斯语、保加利亚语、马其顿语、塞尔维亚语、乌克兰语',
            ),
            'ISO-8859-6' => array(
                'title' => '阿拉伯文',
                'note'  => '',
            ),
            'ISO-8859-7' => array(
                'title' => '希腊文',
                'note'  => '',
            ),
            'ISO-8859-8' => array(
                'title' => '希伯来文',
                'note'  => '以色列、犹太人',
            ),
            'ISO-8859-9' => array(
                'title' => '土耳其文',
                'note'  => '马耳他语、库尔德语',
            ),
            'ISO-8859-10' => array(
                'title' => '北欧语言',
                'note'  => '俄语、白俄罗斯语、保加利亚语、马其顿语、塞尔维亚语、乌克兰语',
            ),
            'ISO-8859-13' => array(
                'title' => '波罗的海周边',
                'note'  => '爱沙尼亚语、芬兰语、拉脱维亚语、立陶宛语',
            ),
            'ISO-8859-14' => array(
                'title' => '凯尔特语',
                'note'  => '布列塔尼语、加利西亚语、爱尔兰盖尔语、曼岛语、威尔士语',
            ),
            'UHC' => array(
                'title' => '朝鲜语',
                'note'  => '韩国、朝鲜',
            ),
            'ISO-2022-KR' => array(
                'title' => '朝鲜语',
                'note'  => '韩国、朝鲜',
            ),
            'Windows-1251' => array(
                'title' => '西里尔语',
                'note'  => '',
            ),
            'Windows-1252' => array(
                'title' => '西欧语言',
                'note'  => '',
            ),
            'CP866' => array(
                'title' => '西里尔语',
                'note'  => '',
            ),
            'KOI8-R' => array(
                'title' => '西里尔语',
                'note'  => '俄语、保加利亚语使用',
            ),
        ),
    ),
);
