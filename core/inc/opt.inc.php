<?php
return array(
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
                'type'      => 'select',
                'min'       => 1,
                'default'   => 'Y-m-d',
                'option'    => array(
                    'Y-m-d'     => date('Y-m-d'),
                    'y-m-d'     => date('y-m-d'),
                    'M. d, Y'   => date('M. d, Y'),
                ),
                'kind' => 'str',
            ),
            'BG_SITE_DATESHORT' => array(
                'type'      => 'select',
                'min'       => 1,
                'default'   => 'm-d',
                'option'    => array(
                    'm-d'   => date('m-d'),
                    'M. d'  => date('M. d'),
                ),
                'kind' => 'str',
            ),
            'BG_SITE_TIME' => array(
                'type'      => 'select',
                'min'       => 1,
                'default'   => 'H:i:s',
                'option'    => array(
                    'H:i:s'     => date('H:i:s'),
                    'h:i:s A'   => date('h:i:s A'),
                ),
                'kind' => 'str',
            ),
            'BG_SITE_TIMESHORT' => array(
                'type'      => 'select',
                'min'       => 1,
                'default'   => 'H:i',
                'option'    => array(
                    'H:i'   => date('H:i'),
                    'h:i A' => date('h:i A'),
                ),
                'kind' => 'str',
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
                'kind'      => 'num',
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